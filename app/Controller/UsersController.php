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

class UsersController extends AbstractController
{
    /**
     * 获取用户详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDetail()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
        ], [
            'uid.required' => '用户ID必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/user/detail/' . $data['uid'],
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取用户信息 , 歌单，收藏，mv, dj 数量.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function subCount()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/subcount',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 更新用户信息.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'birthday' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'nickname' => 'required',
            'province' => 'required',
            'signature' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['avatarImgId'] = '0';
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/user/profile/update',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取用户歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function playList()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
            'limit' => '',
            'offset' => '',
        ], [
            'uid.required' => '用户ID必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['limit'] = $data['limit'] ?? 30;
        $data['offset'] = $data['offset'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/user/playlist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取用户电台.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dj()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
        ], [
            'uid.required' => '用户ID必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $user_data = $validator->validated();
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/dj/program/' . $user_data['uid'],
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取用户关注列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getFollows()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
        ], [
            'uid.required' => '用户ID必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $user_data = $validator->validated();
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/user/getfollows/' . $user_data['uid'],
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取用户粉丝列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getFolloweds()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
            'limit' => '',
            'lasttime' => '',
        ], [
            'uid.required' => '用户ID必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['limit'] = $data['limit'] ?? 30;
        $data['time'] = $data['lasttime'] ?? -1;
        $data['userId'] = $data['uid'];
        unset($data['uid'], $data['lasttime']);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/eapi/user/getfolloweds/' . $data['userId'],
            $data,
            ['crypto' => 'eapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/user/getfolloweds']
        );
    }

    /**
     * 获取用户动态
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getEvent()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
            'limit' => '',
            'lasttime' => '',
        ], [
            'uid.required' => '用户ID必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $uid = $data['uid'];
        $data['getcounts'] = true;
        $data['total'] = false;
        $data['limit'] = $data['limit'] ?? 30;
        $data['time'] = $data['lasttime'] ?? -1;
        unset($data['lasttime'], $data['uid']);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/event/get/' . $uid,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
