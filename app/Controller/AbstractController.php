<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Utils\CommonUtils;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
//use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @Inject
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @Inject
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * @Inject
     * @var LoggerFactory
     */
    protected $logger;

    /**
     * @Inject
     * @var CommonUtils
     */
    protected $commonUtils;

    /**
     * 格式化返回响应.
     * @param int $code 状态码
     * @param string $msg 消息说明
     * @param array $data 数据
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function returnMsg($code = 200, $msg = '成功', $data = [])
    {
        return $this->response->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ])->withStatus($code);
    }

    /**
     * 发起请求
     * @param $method
     * @param $url
     * @param $data
     * @param $options
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createCloudRequest($method, $url, $data, $options)
    {
        $client_opt = [
            'verify' => false,
            'timeout' => 3.141,
        ];
        $ua = $this->chooseUserAgent($options['ua'] ?? '');
        if (! empty($ua)) {
            $headers['User-Agent'] = $ua;
        }
        $method = strtoupper($method);
        if ($method == 'POST') {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }
        if (strpos($url, 'music.163.com') !== false) {
            $headers['Referer'] = 'https://music.163.com';
        }

        $ip = $this->chooseChinaIp();
        if (! empty($ip)) {
            $headers['X-Real-IP'] = $ip;
        }

        $jar = new \GuzzleHttp\Cookie\CookieJar();
        if (isset($options['cookie']) && count($options['cookie']) > 0) {
            $jar = $jar::fromArray($options['cookie'], '');
        }
        $client_opt['cookies'] = $jar;

        if ($options['crypto'] == 'weapi') {
            $data['csrf_token'] = $options['cookie']['__csrf'] ?? '';

            $data = $this->commonUtils->weApiRequest(json_encode($data));
            $url = str_replace('/\w*api/', 'weapi', $url);
        } elseif ($options['crypto'] == 'linuxapi') {
            $headers['User-Agent'] = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36';

            $data = $this->commonUtils->linuxApi([
                'method' => $method,
                'url' => str_replace('/\w*api/', 'api', $url),
                'params' => $data,
            ]);
            $url = 'https://music.163.com/api/linux/forward';
        } elseif ($options['crypto'] == 'eapi') {
            $cookie = $options['cookie'] ?? [];
            $csrfToken = $cookie['__csrf'] ?? '';
            $now_time = Carbon::now('PRC');
            $requestId = $now_time->timestamp . $now_time->millisecond . '_' . sprintf('%04d', mt_rand(1, 100));
            $header = [
                'osver' => $cookie['osver'], //系统版本
                'deviceId' => $cookie['deviceId'], //base64_encode(imei + '\t02:00:00:00:00:00\t5106025eb79a5247\t70ffbaac7')
                'appver' => $cookie['appver'] ?? '6.1.1', //app版本
                'versioncode' => $cookie['versioncode'] ?? '140', //版本号
                'mobilename' => $cookie['mobilename'], //设备model
                'buildver' => $cookie['buildver'] ?? Carbon::now('PRC')->timestamp,
                'resolution' => $cookie['resolution'] ?? '1920x1080', //设备分辨率
                '__csrf' => $csrfToken,
                'os' => $cookie['os'] ?? 'android',
                'channel' => $cookie['channel'],
                'requestId' => $requestId,
            ];
            if (isset($cookie['MUSIC_U']) && ! empty($cookie['MUSIC_U'])) {
                $header['MUSIC_U'] = $cookie['MUSIC_U'];
            }
            if (isset($cookie['MUSIC_A']) && ! empty($cookie['MUSIC_A'])) {
                $header['MUSIC_A'] = $cookie['MUSIC_A'];
            }
            $client_opt['cookies'] = $jar::fromArray($header, '');
            $data['header'] = $header;
            $data = $this->commonUtils->eApi($options['url'], $data);
            $url = str_replace('/\w*api/', 'eapi', $url);
        }

        try {
            $client = $this->clientFactory->create($client_opt);
            $client_params['headers'] = $headers ?? [];
            if ($method == 'GET') {
                $client_params['query'] = $data;
            } elseif ($method == 'POST') {
                $client_params['form_params'] = $data;
            }
            $response = $client->request($method, $url, $client_params);
            //cookie处理
            $res = $this->response;
            $cookies = $jar->toArray();
            if (count($cookies) > 0) {
                foreach ($cookies as $cookie) {
                    $temp = new Cookie($cookie['Name'], $cookie['Value'], $cookie['Expires'], $cookie['Path'], '', $cookie['Secure'], false);
                    $res = $res->withCookie($temp);
                }
            }

            $code = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            if (100 < $code && $code < 600) {
                $answer['status'] = $code;
            } else {
                $answer['status'] = 400;
            }
            $answer['body'] = json_decode($body, true) ?? $body;
            return $res->json($answer['body'])->withStatus($answer['status']);
        } catch (RequestException $e) {
            $this->logger->make()->error($e);
            return $this->response->json([
                'code' => 502,
                'msg' => '请求异常，失败!',
            ])->withStatus(502);
        }
    }

    /**
     * 随机UserAgent.
     * @param string $type
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return mixed
     */
    public function chooseUserAgent($type = '')
    {
        $android = $this->cache->get('android_ua');
        $ios = $this->cache->get('ios_ua');
        $macOs = $this->cache->get('macos_ua');
        $windows = $this->cache->get('windows_ua');
        switch ($type) {
            case 'mobile':
                $mobile_ua_list = array_merge($android, $ios);
                return $mobile_ua_list[array_rand($mobile_ua_list)] ?? '';
                break;
            case 'pc':
                $pc_ua_list = array_merge($macOs, $windows);
                return $pc_ua_list[array_rand($pc_ua_list)] ?? '';
                break;
            default:
                $all_ua_list = array_merge($android, $ios, $macOs, $windows);
                return $all_ua_list[array_rand($all_ua_list)] ?? '';
        }
    }

    /**
     * 随机中国IP地址
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return mixed
     */
    public function chooseChinaIp()
    {
        $ip_list = $this->cache->get('china_ip');
        return $ip_list[array_rand($ip_list)] ?? '';
    }
}
