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

class EventsController extends AbstractController
{
    /**
     * 转发用户动态
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function forward()
    {
        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'uid' => 'required',
            'forwards' => 'required',
            'evId' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $param['forwards'] = $data['forwards'];
        $param['id'] = $data['evId'];
        $param['eventUserId'] = $data['uid'];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/event/forward',
            $param,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 删除用户动态
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function del()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'evId' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $param['id'] = $data['evId'];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/eapi/event/delete',
            $param,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
