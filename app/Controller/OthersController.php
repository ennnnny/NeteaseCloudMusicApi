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
        $data['limit'] = $this->request->input('limit', 20);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/act/hot',
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
            'http://music.163.com/weapi/playmode/intelligence/list',
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
            'https://music.163.com/weapi/song/lyric?lv=-1&kv=-1&tv=-1',
            $data,
            ['crypto' => 'linuxapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
