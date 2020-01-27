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

class CommentsController extends AbstractController
{
    /**
     * 获取动态评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function event()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'threadId' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $threadId = $data['threadId'];
        $params = [
            'limit' => $data['limit'] ?? 20,
            'offset' => $data['offset'] ?? 0,
            'beforeTime' => $data['beforeTime'] ?? 0,
        ];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/comments/' . $threadId,
            $params,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 云村热评.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHotwallList()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/comment/hotwall/list/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
