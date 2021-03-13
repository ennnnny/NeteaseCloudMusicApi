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

class CommentsController extends AbstractController
{
    public $type_list = [
        0 => 'R_SO_4_', //歌曲
        1 => 'R_MV_5_', //MV
        2 => 'A_PL_0_', //歌单
        3 => 'R_AL_3_', //专辑
        4 => 'A_DJ_1_', //电台
        5 => 'R_VI_62_', //视频
        6 => 'A_EV_2_', //动态
    ];

    /**
     * 获取动态评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function event()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'threadId' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $threadId = $data['threadId'];
        $params = [
            'limit' => $data['limit'] ?? 20,
            'offset' => $data['offset'] ?? 0,
            'beforeTime' => $data['beforeTime'] ?? 0,
        ];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/comments/' . $threadId,
            $params,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 歌曲评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function music()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $id = $data['rid'] = $data['id'];
        unset($data['id']);
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        $data['beforeTime'] = $data['before'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v1/resource/comments/R_SO_4_' . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 专辑评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function album()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $id = $data['rid'] = $data['id'];
        unset($data['id']);
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        $data['beforeTime'] = $data['before'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/comments/R_AL_3_' . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 歌单评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function playlist()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $id = $data['rid'] = $data['id'];
        unset($data['id']);
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        $data['beforeTime'] = $data['before'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/comments/A_PL_0_' . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * mv 评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function mv()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $id = $data['rid'] = $data['id'];
        unset($data['id']);
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        $data['beforeTime'] = $data['before'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/comments/R_MV_5_' . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 电台节目评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dj()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $id = $data['rid'] = $data['id'];
        unset($data['id']);
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        $data['beforeTime'] = $data['before'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/comments/A_DJ_1_' . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 视频评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function video()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $id = $data['rid'] = $data['id'];
        unset($data['id']);
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        $data['beforeTime'] = $data['before'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/comments/R_VI_62_' . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 热门评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function hot()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'type' => 'required',
            'limit' => '',
            'offset' => '',
            'before' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $id = $data['rid'] = $data['id'];
        unset($data['id']);
        $type = $this->type_list[$data['type']];
        unset($data['type']);
        $data['limit'] = $data['limit'] ?? 20;
        $data['offset'] = $data['offset'] ?? 0;
        $data['beforeTime'] = $data['before'] ?? 0;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/resource/hotcomments/' . $type . $id,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 给评论点赞.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function like()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required_unless:type,6',
            'type' => 'required',
            'cid' => 'required',
            'threadId' => 'required_if:type,6',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $query_data = $this->request->all();
        $t = $query_data['t'] == 1 ? 'like' : 'unlike';
        $type = $this->type_list[$query_data['type']];
        $data = [
            'threadId' => $type . $query_data['id'],
            'commentId' => $query_data['cid'],
        ];
        if ($query_data['type'] == 6) {
            $data['threadId'] = $query_data['threadId'];
        }
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/comment/' . $t,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 发送/删除评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function operate()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required_unless:type,6',
            'type' => 'required',
            't' => 'required',
            'threadId' => 'required_if:type,6',
            'content' => 'required_unless:t,0',
            'commentId' => 'required_unless:t,1',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $query_data = $this->request->all();
        $t = '';
        $type = $this->type_list[$query_data['type']];
        $data = [
            'threadId' => $type . $query_data['id'],
        ];
        if ($query_data['type'] == 6) {
            $data['threadId'] = $query_data['threadId'];
        }
        switch ($query_data['t']) {
            case 1:
                $t = 'add';
                $data['content'] = $query_data['content'];
                break;
            case 0:
                $t = 'delete';
                $data['commentId'] = $query_data['commentId'];
                break;
            case 2:
                $t = 'reply';
                $data['content'] = $query_data['content'];
                $data['commentId'] = $query_data['commentId'];
                break;
        }
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/resource/comments/' . $t,
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 歌曲楼层评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function floor()
    {
        $type_list = $this->type_list;
        $data['parentCommentId'] = $this->request->input('parentCommentId');
        $data['threadId'] = $type_list[$this->request->input('type')] . $this->request->input('id');
        $data['time'] = $this->request->input('time', -1);
        $data['limit'] = $this->request->input('limit', 20);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/resource/comment/floor/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 新版评论接口.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function new()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $query_data = $this->request->all();
        $type = $this->type_list[$query_data['type']];
        $threadId = $type . $query_data['id'];
        $pageSize = $query_data['pageSize'] ?? 20;
        $pageNo = $query_data['pageNo'] ?? 1;
        $query_data['sortType'] = $query_data['sortType'] ?? 1;
        $data = [
            'threadId' => $threadId,
            'pageNo' => $pageNo,
            'showInner' => $query_data['showInner'] ?? true,
            'pageSize' => $pageSize,
            'cursor' => $query_data['sortType'] == 3 ? ($query_data['cursor'] ?? '0') : (($pageNo - 1) * $pageSize),
            'sortType' => $query_data['sortType'],
        ];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v2/resource/comments',
            $data,
            ['crypto' => 'eapi', 'url' => '/api/v2/resource/comments', 'cookie' => $cookie]
        );
    }

    /**
     * 评论抱一抱列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function hugList()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'ios';
        $cookie['appver'] = '8.1.20';

        $query_data = $this->request->all();
        $type = $this->type_list[($query_data['type'] ?? 0)];
        $threadId = $type . $query_data['sid'];
        $data['targetUserId'] = $query_data['uid'];
        $data['commentId'] = $query_data['cid'];
        $data['cursor'] = $query_data['cursor'] ?? '-1';
        $data['threadId'] = $threadId;
        $data['pageNo'] = $query_data['page'] ?? 1;
        $data['idCursor'] = $query_data['idCursor'] ?? -1;
        $data['pageSize'] = $query_data['pageSize'] ?? 100;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v2/resource/comments/hug/list',
            $data,
            ['crypto' => 'api', 'cookie' => $cookie]
        );
    }

    /**
     * 抱一抱评论.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function hug()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'ios';
        $cookie['appver'] = '8.1.20';

        $query_data = $this->request->all();
        $type = $this->type_list[($query_data['type'] ?? 0)];
        $threadId = $type . $query_data['sid'];
        $data = [
            'targetUserId' => $query_data['uid'],
            'commentId' => $query_data['cid'],
            'threadId' => $threadId,
        ];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/v2/resource/comments/hug/listener',
            $data,
            ['crypto' => 'api', 'cookie' => $cookie]
        );
    }
}
