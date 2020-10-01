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

class PlayListsController extends AbstractController
{
    /**
     * 更新歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'name' => 'required',
            'desc' => '',
            'tags' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();

        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);
        $cookie['os'] = 'pc';
        $data['desc'] = $data['desc'] ?? '';
        $data['tags'] = $data['tags'] ?? '';

        $params = [
            '/api/playlist/desc/update' => json_encode([
                'id' => $data['id'],
                'desc' => $data['desc'],
            ]),
            '/api/playlist/tags/update' => json_encode([
                'id' => $data['id'],
                'tags' => $data['tags'],
            ]),
            '/api/playlist/update/name' => json_encode([
                'id' => $data['id'],
                'name' => $data['name'],
            ]),
        ];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/batch',
            $params,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 更新歌单描述.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateDesc()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'desc' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        return $this->createCloudRequest(
            'POST',
            'http://interface3.music.163.com/eapi/playlist/desc/update',
            $data,
            ['crypto' => 'eapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/playlist/desc/update']
        );
    }

    /**
     * 更新歌单名.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateName()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        return $this->createCloudRequest(
            'POST',
            'http://interface3.music.163.com/eapi/playlist/update/name',
            $data,
            ['crypto' => 'eapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/playlist/update/name']
        );
    }

    /**
     * 更新歌单标签.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateTags()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'tags' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        return $this->createCloudRequest(
            'POST',
            'http://interface3.music.163.com/eapi/playlist/tags/update',
            $data,
            ['crypto' => 'eapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/playlist/tags/update']
        );
    }

    /**
     * 歌单分类.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getCatList()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/catalogue',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 热门歌单分类.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHotList()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/hottags',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取歌单详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $data['id'] = $validated_data['id'];
        $data['n'] = 100000;
        $data['s'] = $this->request->input('s', 8);
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v3/playlist/detail',
            $data,
            ['crypto' => 'linuxapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 新建歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'name' => 'required',
            'privacy' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['privacy'] = $data['privacy'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/create',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 删除歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete()
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
        $validator_data = $validator->validated();
        $data['ids'] = '[' . $validator_data['id'] . ']';
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/remove',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 收藏/取消收藏歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function subscribe()
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
        $data['id'] = $validator_data['id'];
        if ($validator_data['t'] == 1) {
            $type = 'subscribe';
        } else {
            $type = 'unsubscribe';
        }
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/' . $type,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 歌单收藏者.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function subscribers()
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
        $data = $validator->validated();
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/subscribers',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 对歌单添加或删除歌曲.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tracks()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'pid' => 'required',
            'op' => 'required',
            'tracks' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $tracks = explode(',', $data['tracks']);
        $data['trackIds'] = json_encode($tracks);
        unset($data['tracks']);
        $data['imme'] = 'true';

        $res = $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/manipulate/tracks',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
        if ($res->getStatusCode() == 512) {
            $data['trackIds'] = json_encode([$tracks, $tracks]);
            return $this->createCloudRequest(
                'POST',
                'http://music.163.com/api/playlist/manipulate/tracks',
                $data,
                ['crypto' => 'weapi', 'cookie' => $cookie]
            );
        }
        return $res;
    }

    /**
     * 调整歌单顺序.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateOrder()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'ids' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/playlist/order/update',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 歌单封面上传.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateCover()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'imgFile' => 'required|image',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $uploadInfo = $this->dealUpload();

        if ($uploadInfo !== false) {
            $res = $this->createCloudRequest(
                'POST',
                'https://music.163.com/weapi/playlist/cover/update',
                [
                    'id' => $this->request->input('id'),
                    'coverImgId' => $uploadInfo['imgId'],
                ],
                ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
            );
            $body = $res->getBody()->getContents();
            $body = json_decode($body, true);

            return $this->response->json([
                'code' => 200,
                'data' => array_merge($uploadInfo, $body),
            ])->withStatus(200);
        }
        return $this->response->json([
            'code' => 500,
            'msg' => '请求异常，失败!',
        ])->withStatus(500);
    }

    /**
     * 精品歌单标签列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function highqualityTag()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/playlist/highquality/tags',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
