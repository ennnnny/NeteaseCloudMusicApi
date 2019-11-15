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
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
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
        ]);
    }

    public function createCloudRequest($method, $url, $data, $options)
    {
        $client_opt = [];
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
            $jar = $jar::fromArray($options['cookie']);
        }
        $client_opt['cookies'] = $jar;

        if ($options['crypto'] == 'weapi') {
            $data['csrf_token'] = $options['cookie']['__csrf'] ?? '';
            $data = $this->commonUtils->weApiRequest(json_encode($data));
            $url = str_replace('/\w*api/', 'weapi', $url);
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
