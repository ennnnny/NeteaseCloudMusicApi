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
use GuzzleHttp\Exception\GuzzleException;
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
            //            'proxy' => [
            //                'http'  => 'http://192.168.18.177:8888', // Use this proxy with "http"
            //                'https' => 'http://192.168.18.177:8888', // Use this proxy with "https",
            //            ]
        ];

        $request_ua = $options['ua'] ?? '';
        if (isset($options['cookie']['p_' . $request_ua . '_ua']) && ! empty($options['cookie']['p_' . $request_ua . '_ua'])) {
            $ua = $options['cookie']['p_' . $request_ua . '_ua'];
        } else {
            $ua = $this->chooseUserAgent($request_ua);
        }
        unset($options['cookie']['p__ua'], $options['cookie']['p_mobile_ua'], $options['cookie']['p_pc_ua']);

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

        if (isset($options['cookie']['p_ip']) && ! empty($options['cookie']['p_ip'])) {
            $ip = $options['cookie']['p_ip'];
            unset($options['cookie']['p_ip']);
        } else {
            $ip = $this->chooseChinaIp();
        }
        if (! empty($ip)) {
            $headers['X-Real-IP'] = $ip;
        }

        $jar = new \GuzzleHttp\Cookie\CookieJar();
        if (isset($options['cookie']) && count($options['cookie']) > 0) {
            $jar = $jar::fromArray($options['cookie'], '.music.163.com');
        }
        $client_opt['cookies'] = $jar;

        if (isset($options['crypto'])) {
            if ($options['crypto'] == 'weapi') {
                $data['csrf_token'] = $options['cookie']['__csrf'] ?? '';

                $data = $this->commonUtils->weApiRequest(json_encode($data));
                $url = preg_replace('/\w*api/', 'weapi', $url);
            } elseif ($options['crypto'] == 'linuxapi') {
                $headers['User-Agent'] = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36';

                $url_temp = preg_replace('/\w*api/', 'api', $url);
                $data = $this->commonUtils->linuxApi([
                    'method' => $method,
                    'url' => $url_temp,
                    'params' => $data,
                ]);
                $url = 'https://music.163.com/api/linux/forward';
            } elseif ($options['crypto'] == 'eapi') {
                $cookie = $options['cookie'] ?? [];
                $csrfToken = $cookie['__csrf'] ?? '';
                $now_time = Carbon::now('PRC');
                $requestId = $now_time->timestamp . $now_time->millisecond . '_' . sprintf('%04d', mt_rand(1, 100));
                $header = [
                    'osver' => $cookie['osver'] ?? '', //系统版本
                    'deviceId' => $cookie['deviceId'] ?? '', //base64_encode(imei + '\t02:00:00:00:00:00\t5106025eb79a5247\t70ffbaac7')
                    'appver' => $cookie['appver'] ?? '6.1.1', //app版本
                    'versioncode' => $cookie['versioncode'] ?? '140', //版本号
                    'mobilename' => $cookie['mobilename'] ?? '', //设备model
                    'buildver' => $cookie['buildver'] ?? Carbon::now('PRC')->timestamp,
                    'resolution' => $cookie['resolution'] ?? '1920x1080', //设备分辨率
                    '__csrf' => $csrfToken,
                    'os' => $cookie['os'] ?? 'android',
                    'channel' => $cookie['channel'] ?? '',
                    'requestId' => $requestId,
                ];
                if (isset($cookie['MUSIC_U']) && ! empty($cookie['MUSIC_U'])) {
                    $header['MUSIC_U'] = $cookie['MUSIC_U'];
                }
                if (isset($cookie['MUSIC_A']) && ! empty($cookie['MUSIC_A'])) {
                    $header['MUSIC_A'] = $cookie['MUSIC_A'];
                }
                $client_opt['cookies'] = $jar::fromArray($header, '.music.163.com');
                $data['header'] = $header;
                $data = $this->commonUtils->eApi($options['url'], $data);
                $url = preg_replace('/\w*api/', 'eapi', $url);
            }
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
                $already_login = false;
                foreach ($cookies as $cookie) {
                    if ($cookie['Name'] == 'os') {
                        continue;
                    }
                    $expires = $cookie['Expires'] ?? 0;
                    if (empty($cookie['Value'])) {
                        $expires = Carbon::now()->timestamp;
                    }
                    $temp = new Cookie($cookie['Name'], $cookie['Value'], $expires, $cookie['Path'], '', $cookie['Secure'], false);
                    $res = $res->withCookie($temp);
                    if (in_array($cookie['Name'], ['MUSIC_U', '__csrf', 'MUSIC_A'])) {
                        if (! empty($cookie['Value'])) {
                            $already_login = true;
                        }
                    }
                }

                if ($already_login) {//保留此次的参数
                    $expires_time = Carbon::now()->endOfDay()->timestamp;
                    $ip_cookie = new Cookie('p_ip', $ip, $expires_time);
                    $ua_cookie = new Cookie('p_' . $request_ua . '_ua', $ua, $expires_time);
                } else {
                    $expires_time = Carbon::now()->timestamp;
                    $ip_cookie = new Cookie('p_ip', '', $expires_time);
                    $ua_cookie = new Cookie('p_' . $request_ua . '_ua', '', $expires_time);
                }
                $res = $res->withCookie($ip_cookie);
                $res = $res->withCookie($ua_cookie);
            }

            $code = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            if (100 < $code && $code < 600) {
                $answer['status'] = $code;
            } else {
                $answer['status'] = 400;
            }
            if ($options['crypto'] == 'eapi') {
                if (json_decode($body, true)) {
                    $answer['body'] = json_decode($body, true);
                } else {
                    $res_temp = $this->commonUtils->decrypt($body);
                    if ($res_temp !== false) {
                        $res_temp = json_decode($res_temp, true);
                    } else {
                        $res_temp = [];
                    }
                    $answer['body'] = $res_temp;
                }
                return $res->json($answer['body'])->withStatus($answer['status']);
            }
            if (json_decode($body, true)) {
                $answer['body'] = json_decode($body, true);
                return $res->json($answer['body'])->withStatus($answer['status']);
            }
            return $res->raw($body)->withStatus($answer['status']);
        } catch (RequestException $e) {
            $this->logger->make()->error($e);
            $code = $e->getResponse()->getStatusCode() ?? 502;
            return $this->response->json([
                'code' => $code,
                'msg' => '请求异常，失败!',
            ])->withStatus($code);
        }
    }

    /**
     * 处理图片上传.
     * @throws GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return array|false
     */
    public function dealUpload()
    {
        $file = $this->request->file('imgFile');
        $data = [
            'bucket' => 'yyimgs',
            'ext' => 'jpg',
            'filename' => $file->getClientFilename(),
            'local' => false,
            'nos_product' => 0,
            'return_body' => '{"code":200,"size":"$(ObjectSize)"}',
            'type' => 'other',
        ];
        $res = $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/nos/token/alloc',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
        $body = $res->getBody()->getContents();
        $body = json_decode($body, true);
        if (isset($body['result'])) {
            $client = $this->clientFactory->create(['verify' => false]);
            try {
                $res2 = $client->request('POST', 'https://nosup-hz1.127.net/yyimgs/' . $body['result']['objectKey'] . '?offset=0&complete=true&version=1.0', [
                    'body' => $file->getStream(),
                    'headers' => [
                        'x-nos-token' => $body['result']['token'],
                        'Content-Type' => 'image/jpeg',
                    ],
                ]);
                $body2 = $res2->getBody()->getContents();
                $body2 = json_decode($body2, true);

                if (isset($body2['result'])) {
                    $imgSize = $this->request->input('imgSize', 300);
                    $imgX = $this->request->input('imgX', 0);
                    $imgY = $this->request->input('imgY', 0);
                    $res3 = $this->createCloudRequest(
                        'POST',
                        'https://music.163.com/upload/img/op?id=' . $body2['result']['docId'] . '&op=' . $imgX . 'y' . $imgY . 'y' . $imgSize . 'y' . $imgSize,
                        [],
                        ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
                    );
                    if ($res3->getStatusCode() == 200) {
                        $body3 = $res3->getBody()->getContents();
                        $body3 = json_decode($body3, true);
                        return [
                            'url_pre' => 'https://p1.music.126.net/' . $body['result']['objectKey'],
                            'url' => $body3['url'],
                            'imgId' => $body3['id'],
                        ];
                    }
                }
            } catch (RequestException $exception) {
            }
        }

        return false;
    }

    /**
     * 随机UserAgent.
     * @param string $type
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return mixed
     */
    public function chooseUserAgent($type = '')
    {
        $android = $this->cache->get('android_ua', []);
        $ios = $this->cache->get('ios_ua', []);
        $macOs = $this->cache->get('macos_ua', []);
        $windows = $this->cache->get('windows_ua', []);
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
    public function chooseChinaIp(): string
    {
        $ip_list = $this->cache->get('china_ip', []);
        return $ip_list[array_rand($ip_list)] ?? '';
    }

    /**
     * 获取匿名token.
     */
    public function getAnonymousToken(): string
    {
        return '8aae43f148f990410b9a2af38324af24e87ab9227c9265627ddd10145db744295fcd8701dc45b1ab8985e142f491516295dd965bae848761274a577a62b0fdc54a50284d1e434dcc04ca6d1a52333c9a';
    }
}
