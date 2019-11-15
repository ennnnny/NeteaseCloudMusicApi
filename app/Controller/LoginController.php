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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cellPhone()
    {
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
        if (isset($data['countrycode']) && empty($data['countrycode'])) {
            unset($data['countrycode']);
        }
        $data['password'] = md5($data['password']);
        $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/login/cellphone',
            $data,
            ['crypto' => 'weapi', 'ua' => 'pc', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
