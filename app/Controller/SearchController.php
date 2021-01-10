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

class SearchController extends AbstractController
{
    /**
     * 搜索.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $data = $this->dealSearch();

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/search/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 处理搜索参数.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dealSearch()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'keywords' => 'required',
            'type' => '',
            'limit' => '',
            'offset' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $data['s'] = $validated_data['keywords'];
        // 1: 单曲, 10: 专辑, 100: 歌手, 1000: 歌单, 1002: 用户, 1004: MV, 1006: 歌词, 1009: 电台, 1014: 视频
        $data['type'] = $validated_data['type'] ?? 1;
        $data['limit'] = $validated_data['limit'] ?? 30;
        $data['offset'] = $validated_data['offset'] ?? 0;

        return $data;
    }

    /**
     * 搜索.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cloud()
    {
        $data = $this->dealSearch();

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/cloudsearch/get/web',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 默认搜索关键词.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDefault()
    {
        return $this->createCloudRequest(
            'POST',
            'https://interface3.music.163.com/eapi/search/defaultkeyword/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/search/defaultkeyword/get']
        );
    }

    /**
     * 热搜列表(简略).
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHot()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/search/hot',
            ['type' => 1111],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams(), 'ua' => 'mobile']
        );
    }

    /**
     * 热搜列表(详细).
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHotDetail()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/hotsearchlist/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 搜索建议.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSuggest()
    {
        $data['s'] = $this->request->input('keywords', '');
        $type = $this->request->input('type', '');
        $type = $type == 'mobile' ? 'keyword' : 'web';
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/search/suggest/' . $type,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 搜索多重匹配.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function multimatch()
    {
        $data['type'] = $this->request->input('type', 1);
        $data['s'] = $this->request->input('keywords', '');
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/search/suggest/multimatch',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
