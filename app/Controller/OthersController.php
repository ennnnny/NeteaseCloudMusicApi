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
        $data['limit'] = $this->request->input('limit', 20);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/act/hot',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 智能播放.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIntelligenceList()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'pid' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['songId'] = $validator_data['id'];
        $data['type'] = 'fromPlayOne';
        $data['playlistId'] = $validator_data['pid'];
        $data['startMusicId'] = $this->request->input('sid', $validator_data['id']);
        $data['count'] = $this->request->input('count', 1);
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playmode/intelligence/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取歌词.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getLyric()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['lv'] = -1;
        $data['kv'] = -1;
        $data['tv'] = -1;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/song/lyric',
            $data,
            ['crypto' => 'linuxapi', 'cookie' => $cookie]
        );
    }

    /**
     * banner.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getBanner()
    {
        $type = $this->request->input('type', 0);
        switch ($type) {
            case 1:
                $type = 'android';
                break;
            case 2:
                $type = 'iphone';
                break;
            case 3:
                $type = 'ipad';
                break;
            default:
                $type = 'pc';
        }
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v2/banner/get',
            ['clientType' => $type],
            ['crypto' => 'linuxapi']
        );
    }

    /**
     * 资源点赞( MV,电台,视频).
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function likeResource()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required_unless:type,6',
            'type' => 'required',
            't' => 'required',
            'threadId' => 'required_if:type,6',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $query_data = $this->request->all();
        $t = $query_data['t'] == 1 ? 'like' : 'unlike';
        $type = '';
        switch ($query_data['type']) {
            case 1:
                $type = 'R_MV_5_'; //MV
                break;
            case 4:
                $type = 'A_DJ_1_'; //电台
                break;
            case 5:
                $type = 'R_VI_62_'; //视频
                break;
            case 6:
                $type = 'A_EV_2_'; //动态
                break;
        }
        $data = [
            'threadId' => $type . $query_data['id'],
        ];
        if ($type == 'A_EV_2_') {
            $data['threadId'] = $query_data['threadId'];
        }
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/resource/' . $t,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 听歌打卡
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function scrobble()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'sourceid' => 'required',
            'time' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $log = [
            'action' => 'play',
            'json' => [
                'download' => 0,
                'end' => 'playend',
                'id' => $validated_data['id'],
                'sourceId' => $validated_data['sourceid'],
                'time' => $validated_data['time'],
                'type' => 'song',
                'wifi' => 0,
            ],
        ];
        $data = ['logs' => '[' . json_encode($log) . ']'];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/feedback/weblog',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * batch批量请求接口.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function batch()
    {
        $data['e_r'] = true;
        $request_data = $this->request->all();
        foreach ($request_data as $k => $request_datum) {
            if (preg_match('/^\\/api\\//', $k)) {
                $data[$k] = $request_datum;
//                $data[$k] = json_decode($request_datum, true);
            }
        }

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/eapi/batch',
            $data,
            ['crypto' => 'eapi', 'url' => '/api/batch', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 国家编码列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getCountryCodeList()
    {
        $data = [];
        return $this->createCloudRequest(
            'POST',
            'https://interface3.music.163.com/eapi/lbs/countries/v1',
            $data,
            ['crypto' => 'eapi', 'url' => '/api/lbs/countries/v1', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 音乐日历.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function calendar()
    {
        $data['startTime'] = $this->request->input('startTime', Carbon::now()->timestamp . '000');
        $data['endTime'] = $this->request->input('endTime', Carbon::now()->timestamp . '000');
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/mcalendar/detail',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
