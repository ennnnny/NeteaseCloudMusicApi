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

class MsgController extends AbstractController
{
    /**
     * 通知 - 私信
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function privateMsg()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/msg/private/users',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 私信内容.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function privateHistory()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
            'limit' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $data['userId'] = $validated_data['uid'];
        $data['limit'] = $validated_data['limit'] ?? 30;
        $data['time'] = $validated_data['before'] ?? 0;
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/msg/private/history',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 通知 - 评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function comments()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
            'limit' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $data['uid'] = $validated_data['uid'];
        $data['limit'] = $validated_data['limit'] ?? 30;
        $data['time'] = $validated_data['before'] ?? -1;
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v1/user/comments/' . $data['uid'],
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 通知 - @我.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function forwards()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/forwards/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 通知 - 通知.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function notices()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/msg/notices',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
