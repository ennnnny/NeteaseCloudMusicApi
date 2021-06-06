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

class CloudController extends AbstractController
{
    public function cloud()
    {
        if ($this->request->file('songFile')->isValid()) {
            $file = $this->request->file('songFile');
        //TODO:缺少读取音乐文件信息的轮子
        } else {
            return $this->response->json([
                'msg' => '请上传音乐文件',
                'code' => 500,
            ])->withStatus(500);
        }
    }

    /**
     * 云盘歌曲信息匹配纠正.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function match()
    {
        $cookie = $this->request->getCookieParams();
        $cookie['os'] = 'ios';
        $cookie['appver'] = '8.1.20';

        $data['userId'] = $this->request->input('uid');
        $data['songId'] = $this->request->input('sid');
        $data['adjustSongId'] = $this->request->input('asid');

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/cloud/user/song/match',
            $data,
            ['crypto' => 'weapi', 'cookie' => $cookie]
        );
    }
}
