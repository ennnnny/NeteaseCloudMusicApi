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

namespace App\Middleware;

use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestCacheMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->container->get(Response::class);
        //将ua存到redis中
        $cache = $this->container->get(\Psr\SimpleCache\CacheInterface::class);
        $android = $ios = $macos = $windows = [];
        if ($cache->has('android_ua')) {
            $android = $cache->get('android_ua');
        } else {
            $android_data = file_get_contents(BASE_PATH . '/request_cache/android.txt');
            if (! empty($android_data)) {
                $android = array_values(array_filter(explode(PHP_EOL, $android_data)));
                $cache->set('android_ua', $android, 604800);
            }
        }
        if ($cache->has('ios_ua')) {
            $ios = $cache->get('ios_ua');
        } else {
            $ios_data = file_get_contents(BASE_PATH . '/request_cache/ios.txt');
            if (! empty($ios_data)) {
                $ios = array_values(array_filter(explode(PHP_EOL, $ios_data)));
                $cache->set('ios_ua', $ios, 604800);
            }
        }
        if ($cache->has('macos_ua')) {
            $macos = $cache->get('macos_ua');
        } else {
            $macos_data = file_get_contents(BASE_PATH . '/request_cache/macos.txt');
            if (! empty($macos_data)) {
                $macos = array_values(array_filter(explode(PHP_EOL, $macos_data)));
                $cache->set('macos_ua', $macos, 604800);
            }
        }
        if ($cache->has('windows_ua')) {
            $windows = $cache->get('windows_ua');
        } else {
            $windows_data = file_get_contents(BASE_PATH . '/request_cache/windows.txt');
            if (! empty($windows_data)) {
                $windows = array_values(array_filter(explode(PHP_EOL, $windows_data)));
                $cache->set('windows_ua', $windows, 604800);
            }
        }
        if ((empty($android) && empty($ios)) || (empty($macos) && empty($windows))) {
            return $response->json(['error' => 1000, 'msg' => '请先执行php bin/hyperf.php cache:pull']);
        }
        //拉取china_ip，并存到Redis
        $china_ip_list = [];
        if (! $cache->has('china_ip')) {
            $china_ip_file = file_get_contents(BASE_PATH . '/request_cache/china_ip_list.txt');
            if (! empty($china_ip_file)) {
                $china_ip_list = array_values(array_filter(explode(PHP_EOL, $china_ip_file)));
                $cache->set('china_ip', $china_ip_list, 604800);
            }
        } else {
            $china_ip_list = $cache->get('china_ip');
        }
        if (empty($china_ip_list)) {
            return $response->json(['error' => 1000, 'msg' => '请先执行php bin/hyperf.php cache:pull']);
        }

        return $handler->handle($request);
    }
}
