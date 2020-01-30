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

class MvController extends AbstractController
{
    /**
     * 收藏/取消收藏 MV.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sub()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'mvid' => 'required',
            't' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['mvId'] = $validator_data['mvid'];
        $data['mvIds'] = '[' . $validator_data['mvid'] . ']';
        if ($validator_data['t'] == 1) {
            $url = 'https://music.163.com/weapi/mv/sub';
        } else {
            $url = 'https://music.163.com/weapi/mv/unsub';
        }
        return $this->createCloudRequest(
            'POST',
            $url,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 收藏的 MV 列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSubList()
    {
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = true;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/cloudvideo/allvideo/sublist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
