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

class VipController extends AbstractController
{
    /**
     * vip成长值
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function growthPoint()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/vipnewcenter/app/level/growhpoint/basic',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 会员成长值领取记录.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function growthPointDetails()
    {
        $data = [
            'limit' => $this->request->input('limit', 20),
            'offset' => $this->request->input('offset', 0),
        ];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/vipnewcenter/app/level/growth/details',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * vip任务
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tasks()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/vipnewcenter/app/level/task/list',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 领取vip成长值
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getGrowthPoint()
    {
        $data['taskIds'] = $this->request->input('ids');

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/vipnewcenter/app/level/task/reward/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
