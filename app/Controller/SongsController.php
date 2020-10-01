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

class SongsController extends AbstractController
{
    /**
     * 获取音乐 url.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getUrl()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'br' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();

        $cookie = $this->request->getCookieParams();
        if (! isset($cookie['MUSIC_U'])) {
            $cookie['_ntes_nuid'] = bin2hex($this->commonUtils->randString(16));
        }
        $cookie['os'] = 'pc';

        $data['ids'] = '[' . $validated_data['id'] . ']';
        $data['br'] = (int) ($validated_data['br'] ?? 999000);
        return $this->createCloudRequest(
            'POST',
            'https://interface3.music.163.com/eapi/song/enhance/player/url',
            $data,
            ['crypto' => 'eapi', 'cookie' => $cookie, 'url' => '/api/song/enhance/player/url']
        );
    }

    /**
     * 音乐是否可用.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function checkMusic()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'br' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();

        $data['ids'] = '[' . (int) $validated_data['id'] . ']';
        $data['br'] = (int) ($validated_data['br'] ?? 999000);
        $res = $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/song/enhance/player/url',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
        try {
            $playable = false;
            $body = $res->getBody()->getContents();
            $body = json_decode($body, true);
            if ($res->getStatusCode() == 200) {
                if ($body['data'][0]['code'] == 200) {
                    $playable = true;
                }
            }
            if ($playable) {
                return $this->response->json([
                    'success' => true,
                    'message' => 'ok',
                ])->withStatus(200);
            }
            return $this->response->json([
                'success' => false,
                'message' => '亲爱的,暂无版权',
            ])->withStatus(404);
        } catch (\Exception $e) {
            return $this->response->json([
                'code' => 500,
                'msg' => $e,
            ])->withStatus(500);
        }
    }

    /**
     * 获取歌曲详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDetail()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'ids' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();

        $ids = explode(',', $validated_data['ids']);
        $temp_lists = [];
        foreach ($ids as $id) {
            $temp_lists[] = '{"id":' . $id . '}';
        }
        $data = [
            'c' => '[' . implode(',', $temp_lists) . ']',
            'ids' => '[' . $validated_data['ids'] . ']',
        ];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v3/song/detail',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 调整歌曲顺序.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateOrder()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'pid' => 'required',
            'ids' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();

        $data['pid'] = $validated_data['pid'];
        $data['trackIds'] = $validated_data['ids'];
        $data['op'] = 'update';

        return $this->createCloudRequest(
            'POST',
            'http://interface.music.163.com/api/playlist/manipulate/tracks',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/playlist/desc/update']
        );
    }
}
