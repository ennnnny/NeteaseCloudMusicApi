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

class ArtistsController extends AbstractController
{
    /**
     * 歌手分类
     * categoryCode 取值
     * 入驻歌手 5001
     * 华语男歌手 1001
     * 华语女歌手 1002
     * 华语组合/乐队 1003
     * 欧美男歌手 2001
     * 欧美女歌手 2002
     * 欧美组合/乐队 2003
     * 日本男歌手 6001
     * 日本女歌手 6002
     * 日本组合/乐队 6003
     * 韩国男歌手 7001
     * 韩国女歌手 7002
     * 韩国组合/乐队 7003
     * 其他男歌手 4001
     * 其他女歌手 4002
     * 其他组合/乐队 4003.
     *
     * initial 取值 a-z/A-Z
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getList()
    {
        $data['categoryCode'] = $this->request->input('cat', '1001');
        $initial = $this->request->input('initial', '');
        if (empty($initial) || ! preg_match('/^[a-zA-Z]$/', $initial)) {
            $data['initial'] = 0;
        } else {
            $data['initial'] = ord(strtoupper($initial));
        }
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = true;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v1/artist/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 收藏/取消收藏歌手.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sub()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            't' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['artistId'] = $validator_data['id'];
        $data['artistIds'] = '[' . $validator_data['id'] . ']';
        if ($validator_data['t'] == 1) {
            $url = 'https://music.163.com/weapi/artist/sub';
        } else {
            $url = 'https://music.163.com/weapi/artist/unsub';
        }
        return $this->createCloudRequest(
            'POST',
            $url,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 歌手热门50首歌曲.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getTopSong()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/artist/top/song',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 收藏的歌手列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSublist()
    {
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = true;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/artist/sublist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取歌手单曲.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getInfo()
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

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/artist/' . $validator_data['id'],
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取歌手 mv.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getMv()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['artistId'] = $validator_data['id'];
        $data['limit'] = $validator_data['limit'] ?? 30;
        $data['offset'] = $validator_data['offset'] ?? 0;
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/artist/mvs',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取歌手专辑.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAlbum()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $id = $validator_data['id'];
        $data['limit'] = $validator_data['limit'] ?? 30;
        $data['offset'] = $validator_data['offset'] ?? 0;
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/artist/albums/' . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取歌手描述.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDesc()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/artist/introduction',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
