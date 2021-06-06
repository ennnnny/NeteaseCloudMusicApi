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

class MvController extends AbstractController
{
    /**
     * 收藏/取消收藏 MV.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sub()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'mvid' => 'required',
            't' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['mvId'] = $validator_data['mvid'];
        $data['mvIds'] = '[' . $validator_data['mvid'] . ']';
        if ($validator_data['t'] == 1) {
            $url = 'https://music.163.com/weapi/mv/sub';
        } else {
            $url = 'https://music.163.com/weapi/mv/unsub';
        }
        return $this->createCloudRequest(
            'POST',
            $url,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 收藏的 MV 列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSubList()
    {
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = true;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/cloudvideo/allvideo/sublist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 全部 mv.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function all()
    {
        $tags[] = [
            '地区' => $this->request->input('area', '全部'),
            '类型' => $this->request->input('type', '全部'),
            '排序' => $this->request->input('order', '上升最快'),
        ];
        $data['tags'] = json_encode($tags);
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = 'true';

        return $this->createCloudRequest(
            'POST',
            'https://interface.music.163.com/api/mv/all',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 最新 mv.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function first()
    {
        $data['area'] = $this->request->input('area', '');
        $data['limit'] = $this->request->input('limit', 30);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://interface.music.163.com/weapi/mv/first',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 网易出品mv.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function exclusive()
    {
        $data['offset'] = $this->request->input('offset', 0);
        $data['limit'] = $this->request->input('limit', 30);

        return $this->createCloudRequest(
            'POST',
            'https://interface.music.163.com/api/mv/exclusive/rcmd',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取 mv 数据.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'mvid' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['id'] = $validator_data['mvid'];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v1/mv/detail',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * mv 地址
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function url()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'res' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['r'] = $data['r'] ?? 1080;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/song/enhance/play/mv/url',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取 mv 点赞转发评论数数据.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDetailInfo()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'mvid' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();

        $data['threadid'] = 'R_MV_5_' . $validator_data['mvid'];
        $data['composeliked'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/comment/commentthread/info',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取mlog播放地址
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function mlogUrl()
    {
        $data = [
            'id' => $this->request->input('id'),
            'resolution' => $this->request->input('res', 1080),
            'type' => 1,
        ];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/mlog/detail/v1',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 将mlog id转为视频id.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function mlogToVideo()
    {
        $data['mlogId'] = $this->request->input('id');

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/mlog/video/convert/id',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
