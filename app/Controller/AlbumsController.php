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

class AlbumsController extends AbstractController
{
    /**
     * 获取专辑内容.
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
        $validated_data = $validator->validated();

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/album/' . $validated_data['id'],
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 专辑动态信息.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDynamicDetail()
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

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/album/detail/dynamic',
            $validated_data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 收藏/取消收藏专辑.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sub()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            't' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();
        $validated_data['t'] = $validated_data['t'] ?? 0;
        if ($validated_data['t'] == 1) {
            $t = 'sub';
        } else {
            $t = 'unsub';
        }

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/album/' . $t,
            ['id' => $validated_data['id']],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取已收藏专辑列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSubList()
    {
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 25);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/album/sublist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 最新专辑.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getNewest()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/discovery/newAlbum',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 全部新碟
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getNew()
    {
        $data['limit'] = $this->request->input('limit', 25);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;
        $data['area'] = $this->request->input('area', 'ALL'); //ALL:全部,ZH:华语,EA:欧美,KR:韩国,JP:日本

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/album/new',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 数字专辑-新碟上架.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getList()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;
        $data['area'] = $this->request->input('area', 'ALL'); //ALL:全部,ZH:华语,EA:欧美,KR:韩国,JP:日本
        $data['type'] = $this->request->input('type', 'daily');

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/vipmall/albumproduct/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 数字专辑&数字单曲-榜单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSongsaleboard()
    {
        $data['albumType'] = $this->request->input('albumType', 0);
        $type = $this->request->input('type', 'daily'); // daily,week,year,total
        if ($type == 'year') {
            $data['year'] = $this->request->input('year');
        }

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/feealbum/songsaleboard/' . $type . '/type',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 数字专辑-语种风格馆.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getListStyle()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;
        $data['area'] = $this->request->input('area', 'Z_H'); //Z_H:华语,E_A:欧美,KR:韩国,JP:日本

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/vipmall/appalbum/album/style',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 数字专辑详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDetail()
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
            'https://music.163.com/weapi/vipmall/albumproduct/detail',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
