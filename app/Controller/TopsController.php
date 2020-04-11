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

class TopsController extends AbstractController
{
    /**
     * 歌单 ( 网友精选碟 ).
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function playlist()
    {
        //全部,华语,欧美,日语,韩语,粤语,小语种,流行,摇滚,民谣,电子,舞曲,说唱,轻音乐,爵士,乡村,R&B/Soul,古典,民族,英伦,金属,朋克,蓝调,雷鬼,世界音乐,拉丁,另类/独立,New Age,古风,后摇,Bossa Nova,清晨,夜晚,学习,工作,午休,下午茶,地铁,驾车,运动,旅行,散步,酒吧,怀旧,清新,浪漫,性感,伤感,治愈,放松,孤独,感动,兴奋,快乐,安静,思念,影视原声,ACG,儿童,校园,游戏,70后,80后,90后,网络歌曲,KTV,经典,翻唱,吉他,钢琴,器乐,榜单,00后
        $data['cat'] = $this->request->input('cat', '全部');
        //hot,new
        $data['order'] = $this->request->input('order', 'hot');
        $data['limit'] = $this->request->input('limit', 50);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 获取精品歌单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHighQuality()
    {
        //全部,华语,欧美,日语,韩语,粤语,小语种,流行,摇滚,民谣,电子,舞曲,说唱,轻音乐,爵士,乡村,R&B/Soul,古典,民族,英伦,金属,朋克,蓝调,雷鬼,世界音乐,拉丁,另类/独立,New Age,古风,后摇,Bossa Nova,清晨,夜晚,学习,工作,午休,下午茶,地铁,驾车,运动,旅行,散步,酒吧,怀旧,清新,浪漫,性感,伤感,治愈,放松,孤独,感动,兴奋,快乐,安静,思念,影视原声,ACG,儿童,校园,游戏,70后,80后,90后,网络歌曲,KTV,经典,翻唱,吉他,钢琴,器乐,榜单,00后
        $data['cat'] = $this->request->input('cat', '全部');
        $data['limit'] = $this->request->input('limit', 50);
        $data['lasttime'] = $this->request->input('before', 0); //歌单updateTime
        $data['total'] = true;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/playlist/highquality/list',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 新歌速递.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getSong()
    {
        // 全部:0 华语:7 欧美:96 日本:8 韩国:16
        $data['areaId'] = $this->request->input('type', 0);
        $data['total'] = true;
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v1/discovery/new/songs',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 新碟上架.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function album()
    {
        $data['area'] = $this->request->input('type', 'ALL'); // ALL,ZH,EA,KR,JP
        $data['limit'] = $this->request->input('limit', 50);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/album/new',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 热门歌手.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getArtists()
    {
        $data['limit'] = $this->request->input('limit', 50);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/artist/top',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * mv 排行.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function mv()
    {
        $data['area'] = $this->request->input('area', '');
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/mv/toplist',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 排行榜.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'idx' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validator_data = $validator->validated();

        $topList = [
            0 => '3779629', //云音乐新歌榜
            1 => '3778678', //云音乐热歌榜
            2 => '2884035', ///云音乐原创榜
            3 => '19723756', //云音乐飙升榜
            5 => '180106', //UK排行榜周榜
            4 => '10520166', //云音乐电音榜
            6 => '60198', //美国Billboard周榜
            7 => '21845217', //KTV嗨榜
            8 => '11641012', //iTunes榜
            9 => '120001', //Hit FM Top榜
            10 => '60131', //日本Oricon周榜
            11 => '3733003', //韩国Melon排行榜周榜
            12 => '60255', //韩国Mnet排行榜周榜
            13 => '46772709', //韩国Melon原声周榜
            14 => '112504', //中国TOP排行榜(港台榜)
            15 => '64016', //中国TOP排行榜(内地榜)
            16 => '10169002', //香港电台中文歌曲龙虎榜
            17 => '4395559', //华语金曲榜
            18 => '1899724', //中国嘻哈榜
            19 => '27135204', //法国 NRJ EuroHot 30周榜
            20 => '112463', //台湾Hito排行榜
            21 => '3812895', //Beatport全球电子舞曲榜
            22 => '71385702', //云音乐ACG音乐榜
            23 => '991319590', //云音乐说唱榜,
            24 => '71384707', //云音乐古典音乐榜
            25 => '1978921795', //云音乐电音榜
            26 => '2250011882', //抖音排行榜
            27 => '2617766278', //新声榜
            28 => '745956260', //云音乐韩语榜
            29 => '2023401535', //英国Q杂志中文版周榜
            30 => '2006508653', //电竞音乐榜
            31 => '2809513713', //云音乐欧美热歌榜
            32 => '2809577409', //云音乐欧美新歌榜
            33 => '2847251561', //说唱TOP榜
            34 => '3001835560', //云音乐ACG动画榜
            35 => '3001795926', //云音乐ACG游戏榜
            36 => '3001890046', //云音乐ACG VOCALOID榜
        ];

        $data['id'] = $topList[$validator_data['idx']];
        $data['n'] = 10000;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/v3/playlist/detail',
            $data,
            ['crypto' => 'linuxapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
