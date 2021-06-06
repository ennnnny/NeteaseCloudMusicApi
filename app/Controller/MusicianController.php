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

class MusicianController extends AbstractController
{
    /**
     * 音乐人数据概况.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dataOverview()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/creator/musician/statistic/data/overview/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 音乐人播放趋势
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function playTrend()
    {
        $data = [
            'startTime' => $this->request->input('startTime'),
            'endTime' => $this->request->input('endTime'),
        ];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/creator/musician/play/count/statistic/data/trend/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 音乐人任务
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tasks()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/nmusician/workbench/mission/cycle/list',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 账号云豆数.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cloudbean()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/cloudbean/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 领取云豆.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function obtainCloudbean()
    {
        $data = [
            'userMissionId' => $this->request->input('id'),
            'period' => $this->request->input('period'),
        ];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/nmusician/workbench/mission/reward/obtain/new',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
