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

    /**
     * 关注/取消关注用户.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function follow()
    {
        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);

        $cookie['os'] = 'pc';
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            't' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['t'] = $data['t'] == 1 ? 'follow' : 'delfollow';
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/user/' . $data['t'] . '/' . $data['id'],
            [],
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 获取用户播放记录.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRecord()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
            'type' => '', // 1: 最近一周, 0: 所有时间
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['type'] = $data['type'] ?? 0; // 1: 最近一周, 0: 所有时间
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/play/record',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 私人 FM.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getPersonalFm()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/radio/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 签到.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dailySignin()
    {
        $data['type'] = $this->request->input('type', 0);
        /*
         * 0为安卓端签到 3点经验, 1为网页签到,2点经验
         * 签到成功 {'android': {'point': 3, 'code': 200}, 'web': {'point': 2, 'code': 200}}
         * 重复签到 {'android': {'code': -2, 'msg': '重复签到'}, 'web': {'code': -2, 'msg': '重复签到'}}
         * 未登录 {'android': {'code': 301}, 'web': {'code': 301}}
         */
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/point/dailyTask',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 喜欢音乐.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function likeSong()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'like' => '',
            'alg' => '',
            'time' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $alg = $validator_data['alg'] ?? 'itembased';
        $time = $validator_data['time'] ?? 25;
        $data['trackId'] = $validator_data['id'];
        $data['like'] = $validator_data['like'] == 'false' ? false : true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/radio/like?alg=' . $alg . '&trackId=' . $validator_data['id'] . '&time=' . $time,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 喜欢音乐列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function likeList()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/song/like/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 垃圾桶.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fmTrash()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'time' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $time = $validator_data['time'] ?? 25;
        $data['songId'] = $validator_data['id'];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/radio/trash/add?alg=RT&songId=' . $validator_data['id'] . '&time=' . $time,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云盘.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cloud()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/cloud/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云盘歌曲删除.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cloudDel()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['songIds'] = [$validator_data['id']];

        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/cloud/del',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云盘数据详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cloudDetail()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['songIds'] = explode(',', trim($validator_data['id']));

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/cloud/get/byids',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 用户电台.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function audio()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['userId'] = $validator_data['uid'];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/get/byuser',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 设置.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setting()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/user/setting',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 更新头像.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function uploadAvatar()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'imgFile' => 'required|image',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $uploadInfo = $this->dealUpload();

        if ($uploadInfo !== false) {
            $res = $this->createCloudRequest(
                'POST',
                'https://music.163.com/weapi/user/avatar/upload/v1',
                ['imgid' => $uploadInfo['imgId']],
                ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
            );
            $body = $res->getBody()->getContents();
            $body = json_decode($body, true);

            return $this->response->json([
                'code' => 200,
                'data' => array_merge($uploadInfo, $body),
            ])->withStatus(200);
        }
        return $this->response->json([
            'code' => 500,
            'msg' => '请求异常，失败!',
        ])->withStatus(500);
    }
}
