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

class OthersController extends AbstractController
{
    /**
     * 获取热门话题.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHotTopic()
    {
        $request_data = $this->request->all();
        $data['limit'] = $request_data['limit'] ?? 20;
        $data['offset'] = $request_data['offset'] ?? 0;

        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/act/hot',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
