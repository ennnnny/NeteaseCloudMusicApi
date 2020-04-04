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
}
