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

class RecommendsController extends AbstractController
{
    /**
     * 获取每日推荐歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResource()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/discovery/recommend/resource',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取每日推荐歌曲.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSongs()
    {
        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);
        $cookie['os'] = 'ios';
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v3/discovery/recommend/songs',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
