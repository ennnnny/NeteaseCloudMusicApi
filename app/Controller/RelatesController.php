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

class RelatesController extends AbstractController
{
    /**
     * 相关歌单推荐.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function playList()
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
        $res = $this->createCloudRequest(
            'GET',
            'https://music.163.com/playlist',
            $data,
            ['ua' => 'pc', 'cookie' => $this->request->getCookieParams()]
        );
        try {
            $body = $res->getBody()->getContents();
            preg_match_all('/<div class="cver u-cover u-cover-3">[\s\S]*?<img src="([^"]+)">[\s\S]*?<a class="sname f-fs1 s-fc0" href="([^"]+)"[^>]*>([^<]+?)<\/a>[\s\S]*?<a class="nm nm f-thide s-fc3" href="([^"]+)"[^>]*>([^<]+?)<\/a>/', $body, $result);
            $playlists = [];
            if (count($result[0]) > 0) {
                $num = count($result[0]);
                for ($i = 0; $i < $num; ++$i) {
                    $temp = [];
                    $user_id = str_replace('/user/home?id=', '', $result[4][$i] ?? '');
                    $temp['creator']['userId'] = $user_id;
                    $temp['creator']['nickname'] = $result[5][$i] ?? '';
                    $temp['coverImgUrl'] = str_replace('?param=50y50', '', $result[1][$i] ?? '');
                    $temp['name'] = $result[3][$i] ?? '';
                    $temp['id'] = str_replace('/playlist?id=', '', $result[2][$i] ?? '');
                    $playlists[] = $temp;
                }
            }
            return $this->response->json([
                'code' => 200,
                'playlists' => $playlists,
            ])->withStatus(200);
        } catch (\Exception $e) {
            return $this->response->json([
                'code' => 500,
                'msg' => $e,
            ])->withStatus(500);
        }
    }

    /**
     * 相关视频.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function allVideo()
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
        if (is_numeric($data['id'])) {
            $data['type'] = 0;
        } else {
            $data['type'] = 1;
        }
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/cloudvideo/v1/allvideo/rcmd',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
