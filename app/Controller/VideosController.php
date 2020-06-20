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

class VideosController extends AbstractController
{
    /**
     * 收藏/取消收藏视频.
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
        $data['id'] = $validator_data['id'];
        if ($validator_data['t'] == 1) {
            $url = 'https://music.163.com/weapi/cloudvideo/video/sub';
        } else {
            $url = 'https://music.163.com/weapi/cloudvideo/video/unsub';
        }
        return $this->createCloudRequest(
            'POST',
            $url,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取视频标签列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function groupList()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/cloudvideo/group/list',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 视频详情.
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
        $data = $validator->validated();

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/cloudvideo/v1/video/detail',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取视频播放地址
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
        $validator_data = $validator->validated();
        $data['ids'] = '["' . $validator_data['id'] . '"]';
        $data['resolution'] = $validator_data['res'] ?? 1080;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/cloudvideo/playurl',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取视频点赞转发评论数数据.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDetailInfo()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'vid' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();

        $data['threadid'] = 'R_VI_62_' . $validator_data['vid'];
        $data['composeliked'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/comment/commentthread/info',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取视频标签/分类下的视频.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function group()
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

        $data['groupId'] = $validator['id'];
        $data['offset'] = $this->request->input('offset', 0);
        $data['need_preview_url'] = 'true';
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/videotimeline/videogroup/otherclient/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取推荐视频.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function timelineRecommend()
    {
        $data['offset'] = $this->request->input('offset', 0);
        $data['filterLives'] = '[]';
        $data['withProgramInfo'] = 'true';
        $data['needUrl'] = '1';
        $data['resolution'] = '480';

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/videotimeline/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取视频分类列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function categoryList()
    {
        $data['limit'] = $this->request->input('limit', 99);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = 'true';

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/cloudvideo/category/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取全部视频列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function timelineAll()
    {
        $data['groupId'] = 0;
        $data['offset'] = $this->request->input('offset', 0);
        $data['need_preview_url'] = 'true';
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/videotimeline/otherclient/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
