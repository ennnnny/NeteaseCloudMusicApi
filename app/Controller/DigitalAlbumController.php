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

class DigitalAlbumController extends AbstractController
{
    /**
     * 全部新碟
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function new()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;
        $data['area'] = $this->request->input('area', 'ALL'); //ALL:全部,ZH:华语,EA:欧美,KR:韩国,JP:日本

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/weapi/album/new',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 我的数字专辑.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function digitalAlbumPurchased()
    {
        $data['limit'] = $this->request->input('limit', 30);
        $data['offset'] = $this->request->input('offset', 0);
        $data['total'] = true;

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/digitalAlbum/purchased',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }

    /**
     * 购买数字专辑.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ordering()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'id' => 'required',
            'payment' => 'required', //支付方式， 0 为支付宝 3 为微信
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            // Handle exception
            $errorMessage = $validator->errors()->first();
            return $this->returnMsg(422, $errorMessage);
        }
        $validated_data = $validator->validated();

        $data['business'] = 'Album';
        $data['paymentMethod'] = $validated_data['payment'];
        $digitalResources[] = [
            'business' => 'Album',
            'resourceID' => $validated_data['id'],
            'quantity' => $validated_data['quantity'],
        ];
        $data['digitalResources'] = json_encode($digitalResources);
        $data['from'] = 'web';

        return $this->createCloudRequest(
            'POST',
            'https://music.163.com/api/ordering/web/digital',
            $data,
            ['crypto' => 'weapi', 'cookie' => $this->request->getCookieParams()]
        );
    }
}
