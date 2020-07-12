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

class HistoryController extends AbstractController
{
    /**
     * 获取历史日推可用日期列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function recommendSongs()
    {
        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);
        $cookie['os'] = 'ios';

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/discovery/recommend/songs/history/recent',
            [],
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 获取历史日推详情数据.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function recommendSongDetail()
    {
        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);
        $cookie['os'] = 'ios';

        $data['date'] = $this->request->input('date', '');
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/discovery/recommend/songs/history/detail',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }
}
