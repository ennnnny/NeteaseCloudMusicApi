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
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/login/cellphone',
            $data,
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $cookie]
        );
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
            'GET',
            'https://music.163.com',
            [],
            ['cookie' => $this->request->getCookieParams()]
        );
        try {
            $body = $res->getBody()->getContents();
            preg_match('/GUser\s*=\s*([^;]+);/', $body, $profile);
            preg_match('/GBinds\s*=\s*([^;]+);/', $body, $bindings);
            return $this->response->json([
                'code' => 200,
                'profile' => json_decode($profile[1], true),
                'bindings' => json_decode($bindings[1], true),
            ]);
        } catch (\Exception $e) {
            return $this->response->json([
                'code' => 301,
            ])->withStatus(301);
        }
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
}
