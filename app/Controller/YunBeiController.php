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

class YunBeiController extends AbstractController
{
    /**
     * 云贝.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/point/signed/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝支出.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function expense()
    {
        $data['limit'] = $this->request->input('limit', 10);
        $data['offset'] = $this->request->input('offset', 0);
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/store/api/point/expense',
            $data,
            ['crypto' => 'api', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝账户信息.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function info()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v1/user/info',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝收入.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function receipt()
    {
        $data['limit'] = $this->request->input('limit', 10);
        $data['offset'] = $this->request->input('offset', 0);
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/store/api/point/receipt',
            $data,
            ['crypto' => 'api', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝签到.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sign()
    {
        $data['type'] = '0';
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/point/dailyTask',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝完成任务
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function taskFinish()
    {
        $data['userTaskId'] = $this->request->input('userTaskId');
        $data['depositCode'] = $this->request->input('depositCode', '0');
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/usertool/task/point/receive',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝所有任务
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function task()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/usertool/task/list/all',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝todo任务
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function taskTodo()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/usertool/task/todo/query',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝今日签到信息.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function today()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/point/today/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝推歌.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function rcmdSong()
    {
        $data['songId'] = $this->request->input('id');
        $data['reason'] = $this->request->input('reason', '好歌献给你');
        $data['scene'] = '';
        $data['fromUserId'] = -1;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/yunbei/rcmd/song/submit',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云贝推歌历史记录.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function rcmdSongHistory()
    {
        $page['size'] = $this->request->input('size', 20);
        $page['cursor'] = $this->request->input('cursor', '');
        $data['page'] = json_encode($page);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/yunbei/rcmd/song/history/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
