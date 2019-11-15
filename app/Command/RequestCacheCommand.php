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

namespace App\Command;

use App\Utils\CommonUtils;
use GuzzleHttp\Exception\RequestException;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Guzzle\ClientFactory;
use Psr\Container\ContainerInterface;
use QL\QueryList;

/**
 * @Command
 */
class RequestCacheCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('cache:pull');
    }

    public function configure()
    {
        $this->setDescription('拉取UserAgent以及中国Ip');
    }

    public function handle()
    {
        $this->info('开始拉取UserAgent！');
        $base_url = 'https://developers.whatismybrowser.com/useragents/explore/operating_system_name/';
        $ql = QueryList::getInstance()->rules([
            'text' => ['.useragent > a', 'text'],
        ]);
        $types = [
            'android',
            'ios',
            'windows',
            'macos',
        ];
        foreach ($types as $type) {
            $this->warn('执行拉取' . $type . '中。。。。。。');
            $temp = $ql->get($base_url . $type)->query()->getData();
            if (count($temp) > 0) {
                $content = '';
                foreach ($temp as $item) {
                    $content .= $item['text'] . PHP_EOL;
                }
                file_put_contents(BASE_PATH . '/request_cache/' . $type . '.txt', $content);
            }
            // 释放资源，销毁内存占用
            $ql->destruct();
        }
        $this->info('拉取UserAgent执行结束！');

        $this->info('开始拉取中国Ip！');
        $clientFactory = $this->container->get(ClientFactory::class);
        $client = $clientFactory->create([
            'timeout' => 5.0,
            'verify' => false,
        ]);
        try {
            $response = $client->get('https://github.com/17mon/china_ip_list/raw/master/china_ip_list.txt');
            $china_ip_file = $response->getBody()->getContents();
        } catch (RequestException $e) {
            dump($e);
            $china_ip_file = '';
            $this->error('拉取China_IP文件异常');
        }

        if (! empty($china_ip_file)) {
            $temp_list = explode(PHP_EOL, $china_ip_file);
            $rand_keys = array_rand($temp_list, 11); //随机取11个网段
            $content = '';
            $common_utils = new CommonUtils();
            foreach ($rand_keys as $item) {
                [$ip, $mark, $ip_start, $ip_end] = $common_utils->ip_parse($temp_list[$item]); //IP地址解析
                if (! empty($ip_start) && ! empty($ip_end)) {
                    $start = explode('.', long2ip($ip_start));
                    $end = explode('.', long2ip($ip_end));
                    $ip = explode('.', long2ip($ip));
                    //网段展开来
                    for ($i = $start[3]; $i <= $end[3]; ++$i) {
                        $ip[3] = $i;
                        $content .= implode('.', $ip) . PHP_EOL;
                    }
                }
            }
            if (! empty($content)) {
                file_put_contents(BASE_PATH . '/request_cache/china_ip_list.txt', $content);
            }
        }
        $this->info('拉取中国Ip结束！');
    }
}
