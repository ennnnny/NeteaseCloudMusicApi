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

use Endroid\QrCode\QrCode;

class LoginController extends AbstractController
{
    /**
     * 手机登录.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cellPhone()
    {
        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);

        $cookie['os'] = 'pc';
        $validator = $this->validationFactory->make($this->request->all(), [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/i',
            'password' => 'required',
            'countrycode' => '',
        ], [
            'phone.required' => '手机号必填',
            'phone.regex' => '手机号格式错误',
            'password.required' => '密码必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['rememberLogin'] = 'true';
        if (! isset($data['countrycode']) || empty($data['countrycode'])) {
            $data['countrycode'] = '86';
        }
        $data['password'] = md5($data['password']);

        $res = $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/login/cellphone',
            $data,
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $cookie]
        );
        if ($res->getStatusCode() == 200) {
            $new_res = $this->response;

            $body = $res->getBody()->getContents();
            $body = json_decode($body, true);
            $cookies = $res->getCookies();
            $cookie_temp = head(head($cookies));
            $cookie_res = [];
            foreach ($cookie_temp as $item) {
                $cookie_res[] = $item->__toString();
                $new_res = $new_res->withCookie($item);
            }
            $body['cookie'] = implode(';', $cookie_res);
            return $new_res->json($body)->withStatus(200);
        }

        return $res;
    }

    /**
     * 邮箱登录.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login()
    {
        $cookie = $this->request->getCookieParams();
        unset($cookie['p_ip'], $cookie['p_ua']);

        $cookie['os'] = 'pc';
        $validator = $this->validationFactory->make($this->request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => '邮箱地址必填',
            'email.email' => '邮箱地址格式错误',
            'password.required' => '密码必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $param = $validator->validated();
        $data['username'] = $param['email'];
        $data['password'] = md5($param['password']);
        $data['rememberLogin'] = 'true';
        $res = $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/login',
            $data,
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $cookie]
        );
        if ($res->getStatusCode() == 502) {
            return $this->response->json([
                'msg' => '账号或密码错误',
                'code' => 502,
                'message' => '账号或密码错误',
            ])->withStatus(200);
        }
        return $res;
    }

    /**
     * 刷新登录.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function refresh()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/login/token/refresh',
            [],
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 登录状态
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function status()
    {
        $res = $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/w/nuser/account/get',
            [],
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
        $body = $res->getBody()->getContents();
        $body = json_decode($body, true);
        if ($res->getStatusCode() == 200 && $body['code'] == 200) {
//            $cookies = $res->getCookies();
//            $cookie_temp = head(head($cookies));
//            $cookie_res = [];
//            foreach ($cookie_temp as $item) {
//                $cookie_res[] = $item->__toString();
//            }

            return $this->response->json(['data' => $body]);
        }
        return $res;
    }

    /**
     * 退出登录.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout()
    {
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/logout',
            [],
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 二维码key生成接口.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function qrKey()
    {
        $data['type'] = 1;
        $res = $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/login/qrcode/unikey',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
        $body = $res->getBody()->getContents();
        $body = json_decode($body, true);
        return $this->response->json(['data' => $body, 'code' => 200]);
    }

    /**
     * 二维码生成接口.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function qrCreate()
    {
        $key = $this->request->input('key');
        $url = 'https://music.163.com/login?codekey=' . $key;
        if ($this->request->has('qrimg')) {
            $qr = new QrCode($url);
            $qr_img = $qr->writeDataUri();
        } else {
            $qr_img = '';
        }
        return $this->response->json(['data' => [
            'qrurl' => $url,
            'qrimg' => $qr_img,
        ]]);
    }

    /**
     * 二维码检测扫码状态接口.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function qrCheck()
    {
        $data['key'] = $this->request->input('key', '');
        $data['type'] = 1;
        try {
            $res = $this->createCloudRequest(
                'POST',
                'https://music.163.com/weapi/login/qrcode/client/login',
                $data,
                ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
            );
            $body = $res->getBody()->getContents();
            $body = json_decode($body, true);
            $cookies = $res->getCookies();
            $cookie_temp = head(head($cookies));
            $cookie_res = [];
            foreach ($cookie_temp as $item) {
                $cookie_res[] = $item->__toString();
            }
            $body['cookie'] = implode(';', $cookie_res);
            return $this->response->json($body);
        } catch (\Exception $e) {
            return $this->response->json([]);
        }
    }
}
