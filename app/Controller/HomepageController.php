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

class HomepageController extends AbstractController
{
    /**
     * 首页-发现.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function blockPage()
    {
        $data = [
            'refresh' => $this->request->input('refresh', true),
        ];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/homepage/block/page',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 首页-发现-圆形图标入口列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dragonBall()
    {
        $data = [];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/eapi/homepage/dragon/ball/static',
            $data,
            ['crypto' => 'eapi', 'url' => '/api/homepage/dragon/ball/static', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
