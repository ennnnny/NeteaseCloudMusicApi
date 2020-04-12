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

class DjController extends AbstractController
{
    /**
     * 电台banner.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function banner()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'pc';

        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/djradio/banner/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }

    /**
     * 热门电台.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function hot()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/hot/v1',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 节目榜.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function programTopList()
    {
        $data['limit'] = $this->request->input('limit', 100);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/program/toplist/v1',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 付费精品
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function topListPay()
    {
        $data['limit'] = $this->request->input('limit', 100);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/djradio/toplist/pay',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 24小时节目榜.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function programTopListHours()
    {
        $data['limit'] = $this->request->input('limit', 100);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/djprogram/toplist/hours',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 主播新人榜.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function topListNew()
    {
        $data['limit'] = $this->request->input('limit', 100);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/dj/toplist/newcomer',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 最热主播榜.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function topListPopular()
    {
        $data['limit'] = $this->request->input('limit', 100);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/dj/toplist/popular',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 新晋电台榜/热门电台榜.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function topList()
    {
        $data['limit'] = $this->request->input('limit', 100);
        $data['offset'] = $this->request->input('offset', 0);
        $type = $this->request->input('type', 'new');
        $typeMap = [
            'new' => 0,
            'hot' => 1,
        ];
        $data['type'] = $typeMap[$type] ?? 0;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/djradio/toplist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 类别热门电台.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function radioHot()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'cateId' => 'required',
            'limit' => '',
            'offset' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['limit'] = $data['limit'] ?? 30;
        $data['offset'] = $data['offset'] ?? 0;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/djradio/hot',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 推荐.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function recommend()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/recommend/v1',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 分类.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function catelist()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/category/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 分类推荐.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function recommendType()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['cateId'] = $validator_data['type'];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/recommend',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 订阅.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sub()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'rid' => 'required',
            't' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        if (isset($validator_data['t']) && $validator_data['t'] == 1) {
            $t = 'sub';
        } else {
            $t = 'unsub';
        }
        $data['id'] = $validator_data['rid'];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/' . $t,
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台的订阅列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sublist()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/get/subed',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 付费精选.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function paygift()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/home/paygift/list?_nmclfl=1',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 非热门类型.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function categoryExcludeHot()
    {
        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/djradio/category/excludehot',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 推荐类型.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function categoryRecommend()
    {
        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/djradio/home/category/recommend',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 今日优选.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function todayPerfered()
    {
        $data['page'] = $this->request->input('page', 0);

        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/weapi/djradio/home/today/perfered',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'rid' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['id'] = $validator_data['rid'];

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/djradio/get',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 节目.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function program()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'rid' => 'required',
            'limit' => '',
            'offset' => '',
            'asc' => '',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();
        $data['limit'] = $data['limit'] ?? 30;
        $data['offset'] = $data['offset'] ?? 0;
        $data['radioId'] = $validator_data['rid'];
        $data['asc'] = false;
        if (isset($validator_data['asc'])) {
            if ($validator_data['asc'] === '') {
                $data['asc'] = false;
            } else {
                if ($validator_data['asc'] === true || $validator_data['asc'] == '1') {
                    $data['asc'] = true;
                }
            }
        }

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/dj/program/byradio',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 电台 - 节目详情.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function programDetail()
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
            'https://music.163.com/weapi/dj/program/detail',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
