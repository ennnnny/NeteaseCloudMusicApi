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

class DjController extends AbstractController
{
    /**
     * 电台banner.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function banner()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/djradio/banner/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 热门电台.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function hot()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/hot/v1',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
