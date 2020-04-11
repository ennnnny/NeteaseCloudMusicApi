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

class RegisterController extends AbstractController
{
    /**
     * 发送验证码
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sentCaptcha()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/i',
        ], [
            'phone.required' => '手机号必填',
            'phone.regex' => '手机号格式错误',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $params = $validator->validated();
        $data['cellphone'] = $params['phone'];
        $data['ctcode'] = $this->request->input('ctcode', '86');

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/sms/captcha/sent',
            $data,
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 校验验证码
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function verifyCaptcha()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/i',
            'captcha' => 'required',
        ], [
            'phone.required' => '手机号必填',
            'phone.regex' => '手机号格式错误',
            'captcha.required' => '验证码不能为空',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $params = $validator->validated();
        $data = [
            'ctcode' => $this->request->input('ctcode', '86'),
            'cellphone' => $params['phone'],
            'captcha' => $params['captcha'],
        ];
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/sms/captcha/verify',
            $data,
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 检测手机号码是否已注册.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function checkPhone()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/i',
        ], [
            'phone.required' => '手机号必填',
            'phone.regex' => '手机号格式错误',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $params = $validator->validated();
        $data = [
            'countrycode' => $this->request->input('countrycode', ''),
            'cellphone' => $params['phone'],
        ];
        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/eapi/cellphone/existence/check',
            $data,
            ['crypto' => 'eapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/cellphone/existence/check']
        );
    }

    /**
     * 注册(修改密码).
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/i',
            'captcha' => 'required',
            'password' => 'required',
            'nickname' => 'required',
        ], [
            'phone.required' => '手机号必填',
            'phone.regex' => '手机号格式错误',
            'captcha.required' => '验证码必填',
            'password.required' => '密码必填',
            'nickname.required' => '昵称必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['password'] = md5($data['password']);
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/register/cellphone',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 初始化昵称.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function initProfile()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'nickname' => 'required',
        ], [
            'nickname.required' => '昵称必填',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        return $this->createCloudRequest(
            'POST',
            'http://music.163.com/eapi/activate/initProfile',
            $data,
            ['crypto' => 'eapi', 'cookie' => $this->request->getCookieParams(), 'url' => '/api/activate/initProfile']
        );
    }

    /**
     * 更换绑定手机.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function rebind()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'phone' => 'required|regex:/^1[3456789]\d{9}$/i',
            'captcha' => 'required',
            'oldcaptcha' => 'required',
        ], [
            'phone.required' => '手机号必填',
            'phone.regex' => '手机号格式错误',
            'captcha.required' => '新手机验证码不能为空',
            'oldcaptcha.required' => '原手机验证码不能为空',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $data = $validator->validated();
        $data['ctcode'] = $this->request->input('ctcode', '86');
        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/user/replaceCellphone',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
