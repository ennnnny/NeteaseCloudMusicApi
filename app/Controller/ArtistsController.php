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

use Carbon\Carbon;

class ArtistsController extends AbstractController
{
    /**
     * type 取值
     * 1:男歌手
     * 2:女歌手
     * 3:乐队
     *
     * area 取值
     * -1:全部
     * 7华语
     * 96欧美
     * 8:日本
     * 16韩国
     * 0:其他
     *
     * initial 取值 a-z/A-Z
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getList()
    {
//        $data['categoryCode'] = $this->request->input('cat', '1001');
        $initial = $this->request->input('initial', '');
        if (empty($initial) || ! preg_match('/^[a-zA-Z]$/', $initial)) {
            $data['initial'] = 0;
        } else {
            $data['initial'] = ord(strtoupper($initial));
        }
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = true;
        $data['type'] = $this->request->input('type', '1');
        $data['area'] = $this->request->input('area');
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

    /**
     * 歌手全部歌曲.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function songs()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $data['id'] = $this->request->input('id');
        $data['private_cloud'] = 'true';
        $data['work_type'] = 1;
        $data['order'] = $this->request->input('order', 'hot');
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 100);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v1/artist/songs',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 关注歌手新MV.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function newMv()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'ios';
        $cookie['appver'] = '8.0.00';

        $data['limit'] = $this->request->input('limit', 20);
        $data['startTimestamp'] = $this->request->input('before', Carbon::now()->timestamp . '000');

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/sub/artist/new/works/mv/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 关注歌手新歌.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function newSong()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'ios';
        $cookie['appver'] = '8.0.00';

        $data['limit'] = $this->request->input('limit', 20);
        $data['startTimestamp'] = $this->request->input('before', Carbon::now()->timestamp . '000');

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/sub/artist/new/works/song/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 获取歌手详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/artist/head/info/get',
            ['id' => $this->request->input('id')],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
