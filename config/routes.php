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

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::addRoute(['GET', 'POST'], '/login/cellphone', 'App\Controller\LoginController@cellPhone'); //手机登录
Router::addRoute(['GET', 'POST'], '/login', 'App\Controller\LoginController@login'); //邮箱登录
Router::addRoute(['GET', 'POST'], '/login/refresh', 'App\Controller\LoginController@refresh'); //刷新登录
Router::addRoute(['GET', 'POST'], '/login/status', 'App\Controller\LoginController@status'); //登录状态
Router::addRoute(['GET', 'POST'], '/logout', 'App\Controller\LoginController@logout'); //退出登录

Router::addRoute(['GET', 'POST'], '/captcha/sent', 'App\Controller\RegisterController@sentCaptcha'); //发送验证码
Router::addRoute(['GET', 'POST'], '/captcha/verify', 'App\Controller\RegisterController@verifyCaptcha'); //校验验证码
Router::addRoute(['GET', 'POST'], '/cellphone/existence/check', 'App\Controller\RegisterController@checkPhone'); //检测手机号码是否已注册
Router::addRoute(['GET', 'POST'], '/register/cellphone', 'App\Controller\RegisterController@register'); //注册(修改密码)
Router::addRoute(['GET', 'POST'], '/activate/init/profile', 'App\Controller\RegisterController@initProfile'); //初始化昵称
Router::addRoute(['GET', 'POST'], '/rebind', 'App\Controller\RegisterController@rebind'); //更换绑定手机

Router::addGroup('/user/', function () {
    Router::addRoute(['GET', 'POST'], 'detail', 'App\Controller\UsersController@getDetail'); //获取用户详情
    Router::addRoute(['GET', 'POST'], 'subcount', 'App\Controller\UsersController@subCount'); //获取用户信息 , 歌单，收藏，mv, dj 数量
    Router::addRoute(['GET', 'POST'], 'update', 'App\Controller\UsersController@update'); //更新用户信息
    Router::addRoute(['GET', 'POST'], 'playlist', 'App\Controller\UsersController@playList'); //获取用户歌单
    Router::addRoute(['GET', 'POST'], 'dj', 'App\Controller\UsersController@dj'); //获取用户电台
    Router::addRoute(['GET', 'POST'], 'follows', 'App\Controller\UsersController@getFollows'); //获取用户关注列表
    Router::addRoute(['GET', 'POST'], 'followeds', 'App\Controller\UsersController@getFolloweds'); //获取用户粉丝列表
    Router::addRoute(['GET', 'POST'], 'event', 'App\Controller\UsersController@getEvent'); //获取用户动态
    Router::addRoute(['GET', 'POST'], 'record', 'App\Controller\UsersController@getRecord'); //获取用户播放记录
});
Router::addRoute(['GET', 'POST'], '/follow', 'App\Controller\UsersController@follow'); //关注/取消关注用户

Router::addGroup('/playlist/', function () {
    Router::addRoute(['GET', 'POST'], 'update', 'App\Controller\PlayListsController@update'); //更新歌单
    Router::addRoute(['GET', 'POST'], 'desc/update', 'App\Controller\PlayListsController@updateDesc'); //更新歌单描述
    Router::addRoute(['GET', 'POST'], 'name/update', 'App\Controller\PlayListsController@updateName'); //更新歌单名
    Router::addRoute(['GET', 'POST'], 'tags/update', 'App\Controller\PlayListsController@updateTags'); //更新歌单标签
    Router::addRoute(['GET', 'POST'], 'catlist', 'App\Controller\PlayListsController@getCatList'); //歌单分类
    Router::addRoute(['GET', 'POST'], 'hot', 'App\Controller\PlayListsController@getHotList'); //热门歌单分类
    Router::addRoute(['GET', 'POST'], 'detail', 'App\Controller\PlayListsController@detail'); //获取歌单详情
    Router::addRoute(['GET', 'POST'], 'create', 'App\Controller\PlayListsController@create'); //新建歌单
    Router::addRoute(['GET', 'POST'], 'delete', 'App\Controller\PlayListsController@delete'); //删除歌单
    Router::addRoute(['GET', 'POST'], 'subscribe', 'App\Controller\PlayListsController@subscribe'); //收藏/取消收藏歌单
    Router::addRoute(['GET', 'POST'], 'subscribers', 'App\Controller\PlayListsController@subscribers'); //歌单收藏者
    Router::addRoute(['GET', 'POST'], 'tracks', 'App\Controller\PlayListsController@tracks'); //对歌单添加或删除歌曲
});

Router::addGroup('/event', function () {
    Router::addRoute(['GET', 'POST'], '/forward', 'App\Controller\EventsController@forward'); //转发用户动态
    Router::addRoute(['GET', 'POST'], '/del', 'App\Controller\EventsController@del'); //删除用户动态
    Router::addRoute(['GET', 'POST'], '', 'App\Controller\EventsController@index'); //获取动态消息
});

Router::addGroup('/share/', function () {
    Router::addRoute(['GET', 'POST'], 'resource', 'App\Controller\SharesController@resource'); //分享歌曲、歌单、mv、电台、电台节目到动态
});

Router::addGroup('/comment', function () {
    Router::addRoute(['GET', 'POST'], '/event', 'App\Controller\CommentsController@event'); //获取动态评论
    Router::addRoute(['GET', 'POST'], '/hotwall/list', 'App\Controller\CommentsController@getHotwallList'); //云村热评
    Router::addRoute(['GET', 'POST'], '/music', 'App\Controller\CommentsController@music'); //歌曲评论
    Router::addRoute(['GET', 'POST'], '/album', 'App\Controller\CommentsController@album'); //专辑评论
    Router::addRoute(['GET', 'POST'], '/playlist', 'App\Controller\CommentsController@playlist'); //歌单评论
    Router::addRoute(['GET', 'POST'], '/mv', 'App\Controller\CommentsController@mv'); //mv 评论
    Router::addRoute(['GET', 'POST'], '/dj', 'App\Controller\CommentsController@dj'); //电台节目评论
    Router::addRoute(['GET', 'POST'], '/video', 'App\Controller\CommentsController@video'); //视频评论
    Router::addRoute(['GET', 'POST'], '/hot', 'App\Controller\CommentsController@hot'); //热门评论
    Router::addRoute(['GET', 'POST'], '/like', 'App\Controller\CommentsController@like'); //给评论点赞
    Router::addRoute(['GET', 'POST'], '', 'App\Controller\CommentsController@operate'); //发送/删除评论
});

Router::addRoute(['GET', 'POST'], '/hot/topic', 'App\Controller\OthersController@getHotTopic'); //获取热门话题
Router::addRoute(['GET', 'POST'], '/playmode/intelligence/list', 'App\Controller\OthersController@getIntelligenceList'); //智能播放
Router::addRoute(['GET', 'POST'], '/lyric', 'App\Controller\OthersController@getLyric'); //获取歌词
Router::addRoute(['GET', 'POST'], '/banner', 'App\Controller\OthersController@getBanner'); //banner
Router::addRoute(['GET', 'POST'], '/resource/like', 'App\Controller\OthersController@likeResource'); //资源点赞( MV,电台,视频)

Router::addGroup('/artist/', function () {
    Router::addRoute(['GET', 'POST'], 'list', 'App\Controller\ArtistsController@getList'); //歌手分类
    Router::addRoute(['GET', 'POST'], 'sub', 'App\Controller\ArtistsController@sub'); //收藏/取消收藏歌手
    Router::addRoute(['GET', 'POST'], 'top/song', 'App\Controller\ArtistsController@getTopSong'); //歌手热门50首歌曲
    Router::addRoute(['GET', 'POST'], 'sublist', 'App\Controller\ArtistsController@getSublist'); //收藏的歌手列表
});

Router::addGroup('/video/', function () {
    Router::addRoute(['GET', 'POST'], 'sub', 'App\Controller\VideosController@sub'); //收藏/取消收藏视频
});

Router::addGroup('/mv/', function () {
    Router::addRoute(['GET', 'POST'], 'sub', 'App\Controller\MvController@sub'); //收藏/取消收藏 MV
    Router::addRoute(['GET', 'POST'], 'sublist', 'App\Controller\MvController@getSubList'); //收藏的 MV 列表
});

Router::addGroup('/top/', function () {
    Router::addRoute(['GET', 'POST'], 'playlist', 'App\Controller\TopsController@playlist'); //歌单 ( 网友精选碟 )
    Router::addRoute(['GET', 'POST'], 'playlist/highquality', 'App\Controller\TopsController@getHighQuality'); //获取精品歌单
    Router::addRoute(['GET', 'POST'], 'song', 'App\Controller\TopsController@getSong'); //新歌速递
});

Router::addGroup('/related/', function () {
    Router::addRoute(['GET', 'POST'], 'playlist', 'App\Controller\RelatesController@playlist'); // 相关歌单推荐
});

Router::addGroup('/song/', function () {
    Router::addRoute(['GET', 'POST'], 'url', 'App\Controller\SongsController@getUrl'); // 获取音乐 url
});
Router::addRoute(['GET', 'POST'], '/check/music', 'App\Controller\SongsController@checkMusic'); //音乐是否可用

Router::addGroup('/search', function () {
    Router::addRoute(['GET', 'POST'], '', 'App\Controller\SearchController@index'); // 搜索
    Router::addRoute(['GET', 'POST'], '/default', 'App\Controller\SearchController@getDefault'); //默认搜索关键词
    Router::addRoute(['GET', 'POST'], '/hot', 'App\Controller\SearchController@getHot'); //热搜列表(简略)
    Router::addRoute(['GET', 'POST'], '/hot/detail', 'App\Controller\SearchController@getHotDetail'); //热搜列表(简略)
    Router::addRoute(['GET', 'POST'], '/suggest', 'App\Controller\SearchController@getSuggest'); //搜索建议
    Router::addRoute(['GET', 'POST'], '/multimatch', 'App\Controller\SearchController@multimatch'); //搜索多重匹配
});
