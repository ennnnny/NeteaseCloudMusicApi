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

class PersonalizedController extends AbstractController
{
    /**
     * 推荐 mv.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function mv()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/personalized/mv',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 推荐歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = true;
        $data['n'] = 1000;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/personalized/playlist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 推荐新音乐.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function newsong()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $data['type'] = 'recommend';
        $data['limit'] = $this->request->input('limit', 10);
        $data['areaId'] = $this->request->input('areaId', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/personalized/newsong',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 推荐电台.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function djprogram()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/personalized/djprogram',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 推荐节目.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function program()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();

        $data['cateId'] = $validator_data['type'];
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/program/recommend/v1',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 独家放送
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function privatecontent()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/personalized/privatecontent',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 独家放送列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function privatecontentList()
    {
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 60);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v2/privatecontent/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
