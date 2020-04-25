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
namespace App\Aspect;

use App\Controller\AlbumsController;
use App\Controller\ArtistsController;
use App\Controller\CommentsController;
use App\Controller\DigitalAlbumController;
use App\Controller\DjController;
use App\Controller\EventsController;
use App\Controller\IndexController;
use App\Controller\LoginController;
use App\Controller\MsgController;
use App\Controller\MvController;
use App\Controller\OthersController;
use App\Controller\PersonalizedController;
use App\Controller\PlayListsController;
use App\Controller\RecommendsController;
use App\Controller\RegisterController;
use App\Controller\RelatesController;
use App\Controller\SearchController;
use App\Controller\SendController;
use App\Controller\SharesController;
use App\Controller\SimiController;
use App\Controller\SongsController;
use App\Controller\ToplistsController;
use App\Controller\TopsController;
use App\Controller\UsersController;
use App\Controller\VideosController;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * @Aspect
 */
class ApiAspect extends AbstractAspect
{
    public $classes = [
        AlbumsController::class,
        ArtistsController::class,
        CommentsController::class,
        DigitalAlbumController::class,
        DjController::class,
        EventsController::class,
        //        IndexController::class,
        //        LoginController::class,
        MsgController::class,
        MvController::class,
        OthersController::class,
        PersonalizedController::class,
        PlayListsController::class,
        RecommendsController::class,
        //        RegisterController::class,
        RelatesController::class,
        SearchController::class,
        SendController::class,
        SharesController::class,
        SimiController::class,
        SongsController::class,
        ToplistsController::class,
        TopsController::class,
        UsersController::class,
        VideosController::class,
    ];

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject
     * @var CacheInterface
     */
    protected $cache;

    /**
     * {@inheritdoc}
     * @throws \Hyperf\Di\Exception\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        //获取请求标识
        $url = $this->request->url();
        $params = $this->request->all();
        $keyStr = $url . '::';
        foreach ($params as $key => $val) {
            $keyStr = $keyStr . $key . ':' . $val . '::';
        }
        $key = md5($keyStr);
        //判断是否走缓存
        if ($this->cache->has($key)) {
            $content = $this->cache->get($key);
            return $this->response->json($content);
        }
        $result = $proceedingJoinPoint->process();
        $content = json_decode($result->getBody()->getContents(), true);
        $this->cache->set($key, $content, config('api_cache', 120));
        return $result;
    }
}
