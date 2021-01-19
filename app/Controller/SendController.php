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

class SendController extends AbstractController
{
    /**
     * 发送私信
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function text()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'playlist' => 'required',
            'msg' => 'required',
            'user_ids' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $data['id'] = $validated_data['playlist'];
        $data['type'] = 'text';
        $data['msg'] = $validated_data['msg'];
        $data['userIds'] = '[' . $validated_data['user_ids'] . ']';

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/msg/private/send',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 发送私信(带歌单).
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function playlist()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'playlist' => 'required',
            'msg' => 'required',
            'user_ids' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $data['id'] = $validated_data['playlist'];
        $data['type'] = 'playlist';
        $data['msg'] = $validated_data['msg'];
        $data['userIds'] = '[' . $validated_data['user_ids'] . ']';

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/msg/private/send',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 发送私信音乐.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function song()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'ios';
        $cookie['appver'] = '8.0.0';
        $data['id'] = $this->request->input('id');
        $data['msg'] = $this->request->input('msg', '');
        $data['type'] = 'song';
        $data['userIds'] = '[' . $this->request->input('user_ids') . ']';

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/msg/private/send',
            $data,
            ['crypto' => 'api', 'cookie' => $cookie]
        );
    }
}
