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
    Router::addRoute(['GET', 'POST'], 'cloud', 'App\Controller\UsersController@cloud'); //云盘
    Router::addRoute(['GET', 'POST'], 'cloud/del', 'App\Controller\UsersController@cloudDel'); //云盘歌曲删除
    Router::addRoute(['GET', 'POST'], 'cloud/detail', 'App\Controller\UsersController@cloudDetail'); //云盘数据详情
    Router::addRoute(['GET', 'POST'], 'audio', 'App\Controller\UsersController@audio'); //用户电台
    Router::addRoute(['GET', 'POST'], 'level', 'App\Controller\UsersController@level'); //获取用户等级信息
});
Router::addRoute(['GET', 'POST'], '/follow', 'App\Controller\UsersController@follow'); //关注/取消关注用户
Router::addRoute(['GET', 'POST'], '/personal_fm', 'App\Controller\UsersController@getPersonalFm'); //私人 FM
Router::addRoute(['GET', 'POST'], '/daily_signin', 'App\Controller\UsersController@dailySignin'); //签到
Router::addRoute(['GET', 'POST'], '/like', 'App\Controller\UsersController@likeSong'); //喜欢音乐
Router::addRoute(['GET', 'POST'], '/likelist', 'App\Controller\UsersController@likeList'); //喜欢音乐列表
Router::addRoute(['GET', 'POST'], '/fm_trash', 'App\Controller\UsersController@fmTrash'); //垃圾桶
Router::addRoute(['GET', 'POST'], '/setting', 'App\Controller\UsersController@setting'); //设置
Router::addRoute(['GET', 'POST'], '/avatar/upload', 'App\Controller\UsersController@uploadAvatar'); //更新头像

Router::addGroup('/digitalAlbum/', function () {
    Router::addRoute(['GET', 'POST'], 'purchased', 'App\Controller\DigitalAlbumController@digitalAlbumPurchased'); //我的数字专辑
    Router::addRoute(['GET', 'POST'], 'ordering', 'App\Controller\DigitalAlbumController@ordering'); //购买数字专辑
});

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
    Router::addRoute(['GET', 'POST'], 'order/update', 'App\Controller\PlayListsController@updateOrder'); //调整歌单顺序
    Router::addRoute(['GET', 'POST'], 'cover/update', 'App\Controller\PlayListsController@updateCover'); //歌单封面上传
    Router::addRoute(['GET', 'POST'], 'highquality/tags', 'App\Controller\PlayListsController@highqualityTag'); //精品歌单标签列表
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
    Router::addRoute(['GET', 'POST'], 'floor', 'App\Controller\CommentsController@floor'); //歌曲楼层评论
});

Router::addRoute(['GET', 'POST'], '/hot/topic', 'App\Controller\OthersController@getHotTopic'); //获取热门话题
Router::addRoute(['GET', 'POST'], '/playmode/intelligence/list', 'App\Controller\OthersController@getIntelligenceList'); //智能播放
Router::addRoute(['GET', 'POST'], '/lyric', 'App\Controller\OthersController@getLyric'); //获取歌词
Router::addRoute(['GET', 'POST'], '/banner', 'App\Controller\OthersController@getBanner'); //banner
Router::addRoute(['GET', 'POST'], '/resource/like', 'App\Controller\OthersController@likeResource'); //资源点赞( MV,电台,视频)
Router::addRoute(['GET', 'POST'], '/scrobble', 'App\Controller\OthersController@scrobble'); //听歌打卡
Router::addRoute(['GET', 'POST'], '/batch', 'App\Controller\OthersController@batch'); //batch批量请求接口
Router::addRoute(['GET', 'POST'], '/countries/code/list', 'App\Controller\OthersController@getCountryCodeList'); //国家编码列表

Router::addGroup('/artist/', function () {
    Router::addRoute(['GET', 'POST'], 'list', 'App\Controller\ArtistsController@getList'); //歌手分类
    Router::addRoute(['GET', 'POST'], 'sub', 'App\Controller\ArtistsController@sub'); //收藏/取消收藏歌手
    Router::addRoute(['GET', 'POST'], 'top/song', 'App\Controller\ArtistsController@getTopSong'); //歌手热门50首歌曲
    Router::addRoute(['GET', 'POST'], 'sublist', 'App\Controller\ArtistsController@getSublist'); //收藏的歌手列表
    Router::addRoute(['GET', 'POST'], 'mv', 'App\Controller\ArtistsController@getMv'); //获取歌手 mv
    Router::addRoute(['GET', 'POST'], 'album', 'App\Controller\ArtistsController@getAlbum'); //获取歌手专辑
    Router::addRoute(['GET', 'POST'], 'desc', 'App\Controller\ArtistsController@getDesc'); //获取歌手描述
    Router::addRoute(['GET', 'POST'], 'songs', 'App\Controller\ArtistsController@songs'); //歌手全部歌曲
});
Router::addRoute(['GET', 'POST'], '/artists', 'App\Controller\ArtistsController@getInfo'); //获取歌手单曲

Router::addGroup('/video/', function () {
    Router::addRoute(['GET', 'POST'], 'sub', 'App\Controller\VideosController@sub'); //收藏/取消收藏视频
    Router::addRoute(['GET', 'POST'], 'group/list', 'App\Controller\VideosController@groupList'); //获取视频标签列表
    Router::addRoute(['GET', 'POST'], 'group', 'App\Controller\VideosController@group'); //获取视频标签/分类下的视频
    Router::addRoute(['GET', 'POST'], 'detail', 'App\Controller\VideosController@detail'); //视频详情
    Router::addRoute(['GET', 'POST'], 'url', 'App\Controller\VideosController@url'); //获取视频播放地址
    Router::addRoute(['GET', 'POST'], 'detail/info', 'App\Controller\VideosController@getDetailInfo'); //获取视频点赞转发评论数数据
    Router::addRoute(['GET', 'POST'], 'timeline/recommend', 'App\Controller\VideosController@timelineRecommend'); //获取推荐视频
    Router::addRoute(['GET', 'POST'], 'category/list', 'App\Controller\VideosController@categoryList'); //获取视频分类列表
    Router::addRoute(['GET', 'POST'], 'timeline/all', 'App\Controller\VideosController@timelineAll'); //获取全部视频列表
});

Router::addGroup('/mv/', function () {
    Router::addRoute(['GET', 'POST'], 'sub', 'App\Controller\MvController@sub'); //收藏/取消收藏 MV
    Router::addRoute(['GET', 'POST'], 'sublist', 'App\Controller\MvController@getSubList'); //收藏的 MV 列表
    Router::addRoute(['GET', 'POST'], 'all', 'App\Controller\MvController@all'); //全部 mv
    Router::addRoute(['GET', 'POST'], 'first', 'App\Controller\MvController@first'); //最新 mv
    Router::addRoute(['GET', 'POST'], 'exclusive/rcmd', 'App\Controller\MvController@exclusive'); //网易出品mv
    Router::addRoute(['GET', 'POST'], 'detail', 'App\Controller\MvController@detail'); //获取 mv 数据
    Router::addRoute(['GET', 'POST'], 'url', 'App\Controller\MvController@url'); //mv 地址
    Router::addRoute(['GET', 'POST'], 'detail/info', 'App\Controller\MvController@getDetailInfo'); //获取 mv 点赞转发评论数数据
});

Router::addGroup('/top/', function () {
    Router::addRoute(['GET', 'POST'], 'playlist', 'App\Controller\TopsController@playlist'); //歌单 ( 网友精选碟 )
    Router::addRoute(['GET', 'POST'], 'playlist/highquality', 'App\Controller\TopsController@getHighQuality'); //获取精品歌单
    Router::addRoute(['GET', 'POST'], 'song', 'App\Controller\TopsController@getSong'); //新歌速递
    Router::addRoute(['GET', 'POST'], 'album', 'App\Controller\TopsController@album'); //新碟上架
    Router::addRoute(['GET', 'POST'], 'artists', 'App\Controller\TopsController@getArtists'); //热门歌手
    Router::addRoute(['GET', 'POST'], 'mv', 'App\Controller\TopsController@mv'); //mv 排行
    Router::addRoute(['GET', 'POST'], 'list', 'App\Controller\TopsController@list'); //排行榜
});

Router::addGroup('/toplist', function () {
    Router::addRoute(['GET', 'POST'], '', 'App\Controller\ToplistsController@index'); //所有榜单
    Router::addRoute(['GET', 'POST'], '/detail', 'App\Controller\ToplistsController@detail'); //所有榜单内容摘要
    Router::addRoute(['GET', 'POST'], '/artist', 'App\Controller\ToplistsController@artist'); //歌手榜
});

Router::addGroup('/related/', function () {
    Router::addRoute(['GET', 'POST'], 'playlist', 'App\Controller\RelatesController@playList'); // 相关歌单推荐
    Router::addRoute(['GET', 'POST'], 'allvideo', 'App\Controller\RelatesController@allVideo'); //相关视频
});

Router::addGroup('/song/', function () {
    Router::addRoute(['GET', 'POST'], 'url', 'App\Controller\SongsController@getUrl'); // 获取音乐 url
    Router::addRoute(['GET', 'POST'], 'detail', 'App\Controller\SongsController@getDetail'); //获取歌曲详情
    Router::addRoute(['GET', 'POST'], 'order/update', 'App\Controller\SongsController@updateOrder'); //调整歌曲顺序
});
Router::addRoute(['GET', 'POST'], '/check/music', 'App\Controller\SongsController@checkMusic'); //音乐是否可用

Router::addGroup('/album', function () {
    Router::addRoute(['GET', 'POST'], '', 'App\Controller\AlbumsController@getInfo'); //获取专辑内容
    Router::addRoute(['GET', 'POST'], '/detail/dynamic', 'App\Controller\AlbumsController@getDynamicDetail'); //专辑动态信息
    Router::addRoute(['GET', 'POST'], '/sub', 'App\Controller\AlbumsController@sub'); //收藏/取消收藏专辑
    Router::addRoute(['GET', 'POST'], '/sublist', 'App\Controller\AlbumsController@getSubList'); //获取已收藏专辑列表
    Router::addRoute(['GET', 'POST'], '/newest', 'App\Controller\AlbumsController@getNewest'); //最新专辑
    Router::addRoute(['GET', 'POST'], '/new', 'App\Controller\AlbumsController@getNew'); //全部新碟
    Router::addRoute(['GET', 'POST'], '/list', 'App\Controller\AlbumsController@getList'); //数字专辑-新碟上架
    Router::addRoute(['GET', 'POST'], '/songsaleboard', 'App\Controller\AlbumsController@getSongsaleboard'); //数字专辑&数字单曲-榜单
    Router::addRoute(['GET', 'POST'], '/list/style', 'App\Controller\AlbumsController@getListStyle'); //数字专辑-语种风格馆
    Router::addRoute(['GET', 'POST'], '/detail', 'App\Controller\AlbumsController@getDetail'); //数字专辑详情
});

Router::addGroup('/simi/', function () {
    Router::addRoute(['GET', 'POST'], 'artist', 'App\Controller\SimiController@getArtist'); //获取相似歌手
    Router::addRoute(['GET', 'POST'], 'playlist', 'App\Controller\SimiController@getPlaylist'); //获取相似歌单
    Router::addRoute(['GET', 'POST'], 'mv', 'App\Controller\SimiController@getMv'); //相似 mv
    Router::addRoute(['GET', 'POST'], 'song', 'App\Controller\SimiController@getSong'); //获取相似音乐
    Router::addRoute(['GET', 'POST'], 'user', 'App\Controller\SimiController@getUser'); //获取最近 5 个听了这首歌的用户
});

Router::addGroup('/search', function () {
    Router::addRoute(['GET', 'POST'], '', 'App\Controller\SearchController@index'); // 搜索
    Router::addRoute(['GET', 'POST'], '/default', 'App\Controller\SearchController@getDefault'); //默认搜索关键词
    Router::addRoute(['GET', 'POST'], '/hot', 'App\Controller\SearchController@getHot'); //热搜列表(简略)
    Router::addRoute(['GET', 'POST'], '/hot/detail', 'App\Controller\SearchController@getHotDetail'); //热搜列表(简略)
    Router::addRoute(['GET', 'POST'], '/suggest', 'App\Controller\SearchController@getSuggest'); //搜索建议
    Router::addRoute(['GET', 'POST'], '/multimatch', 'App\Controller\SearchController@multimatch'); //搜索多重匹配
});
Router::addRoute(['GET', 'POST'], '/cloudsearch', 'App\Controller\SearchController@cloud'); //搜索

Router::addGroup('/recommend/', function () {
    Router::addRoute(['GET', 'POST'], 'resource', 'App\Controller\RecommendsController@getResource'); //获取每日推荐歌单
    Router::addRoute(['GET', 'POST'], 'songs', 'App\Controller\RecommendsController@getSongs'); //获取每日推荐歌曲
});

Router::addGroup('/personalized', function () {
    Router::addRoute(['GET', 'POST'], '/mv', 'App\Controller\PersonalizedController@mv'); //推荐 mv
    Router::addRoute(['GET', 'POST'], '', 'App\Controller\PersonalizedController@index'); //推荐歌单
    Router::addRoute(['GET', 'POST'], '/newsong', 'App\Controller\PersonalizedController@newsong'); //推荐新音乐
    Router::addRoute(['GET', 'POST'], '/djprogram', 'App\Controller\PersonalizedController@djprogram'); //推荐电台
    Router::addRoute(['GET', 'POST'], '/privatecontent', 'App\Controller\PersonalizedController@privatecontent'); //独家放送
    Router::addRoute(['GET', 'POST'], '/privatecontent/list', 'App\Controller\PersonalizedController@privatecontentList'); //独家放送列表
});
Router::addRoute(['GET', 'POST'], '/program/recommend', 'App\Controller\PersonalizedController@program'); //推荐节目

Router::addGroup('/dj/', function () {
    Router::addRoute(['GET', 'POST'], 'banner', 'App\Controller\DjController@banner'); //电台banner
    Router::addRoute(['GET', 'POST'], 'hot', 'App\Controller\DjController@hot'); //热门电台
    Router::addRoute(['GET', 'POST'], 'program/toplist', 'App\Controller\DjController@programTopList'); //电台 - 节目榜
    Router::addRoute(['GET', 'POST'], 'toplist/pay', 'App\Controller\DjController@topListPay'); //电台 - 付费精品
    Router::addRoute(['GET', 'POST'], 'program/toplist/hours', 'App\Controller\DjController@programTopListHours'); //电台 - 24小时节目榜
    Router::addRoute(['GET', 'POST'], 'toplist/newcomer', 'App\Controller\DjController@topListNew'); //电台 - 主播新人榜
    Router::addRoute(['GET', 'POST'], 'toplist/popular', 'App\Controller\DjController@topListPopular'); //电台 - 最热主播榜
    Router::addRoute(['GET', 'POST'], 'toplist', 'App\Controller\DjController@topList'); //电台 - 新晋电台榜/热门电台榜
    Router::addRoute(['GET', 'POST'], 'radio/hot', 'App\Controller\DjController@radioHot'); //电台 - 类别热门电台
    Router::addRoute(['GET', 'POST'], 'recommend', 'App\Controller\DjController@recommend'); //电台 - 推荐
    Router::addRoute(['GET', 'POST'], 'catelist', 'App\Controller\DjController@catelist'); //电台 - 分类
    Router::addRoute(['GET', 'POST'], 'recommend/type', 'App\Controller\DjController@recommendType'); //电台 - 分类推荐
    Router::addRoute(['GET', 'POST'], 'sub', 'App\Controller\DjController@sub'); //电台 - 订阅
    Router::addRoute(['GET', 'POST'], 'sublist', 'App\Controller\DjController@sublist'); //电台的订阅列表
    Router::addRoute(['GET', 'POST'], 'paygift', 'App\Controller\DjController@paygift'); //电台 - 付费精选
    Router::addRoute(['GET', 'POST'], 'category/excludehot', 'App\Controller\DjController@categoryExcludeHot'); //电台 - 非热门类型
    Router::addRoute(['GET', 'POST'], 'category/recommend', 'App\Controller\DjController@categoryRecommend'); //电台 - 推荐类型
    Router::addRoute(['GET', 'POST'], 'today/perfered', 'App\Controller\DjController@todayPerfered'); //电台 - 今日优选
    Router::addRoute(['GET', 'POST'], 'detail', 'App\Controller\DjController@detail'); //电台 - 详情
    Router::addRoute(['GET', 'POST'], 'program', 'App\Controller\DjController@program'); //电台 - 节目
    Router::addRoute(['GET', 'POST'], 'program/detail', 'App\Controller\DjController@programDetail'); //电台 - 节目详情
    Router::addRoute(['GET', 'POST'], 'personalize/recommend', 'App\Controller\DjController@personalizeRecommend'); //电台个性推荐
});

Router::addGroup('/msg/', function () {
    Router::addRoute(['GET', 'POST'], 'private', 'App\Controller\MsgController@privateMsg'); //通知 - 私信
    Router::addRoute(['GET', 'POST'], 'private/history', 'App\Controller\MsgController@privateHistory'); //私信内容
    Router::addRoute(['GET', 'POST'], 'comments', 'App\Controller\MsgController@comments'); //通知 - 评论
    Router::addRoute(['GET', 'POST'], 'forwards', 'App\Controller\MsgController@forwards'); //通知 - @我
    Router::addRoute(['GET', 'POST'], 'notices', 'App\Controller\MsgController@notices'); //通知 - 通知
});

Router::addGroup('/send/', function () {
    Router::addRoute(['GET', 'POST'], 'text', 'App\Controller\SendController@text'); //发送私信
    Router::addRoute(['GET', 'POST'], 'playlist', 'App\Controller\SendController@playlist'); //发送私信(带歌单)
});

Router::addGroup('/history/', function () {
    Router::addRoute(['GET', 'POST'], 'recommend/songs', 'App\Controller\HistoryController@recommendSongs'); //获取历史日推可用日期列表
    Router::addRoute(['GET', 'POST'], 'recommend/songs/detail', 'App\Controller\SendController@recommendSongDetail'); //获取历史日推详情数据
});

Router::addGroup('/homepage/', function () {
    Router::addRoute(['GET', 'POST'], 'block/page', 'App\Controller\HomepageController@blockPage'); //首页-发现
    Router::addRoute(['GET', 'POST'], 'dragon/ball', 'App\Controller\HomepageController@dragonBall'); //首页-发现-圆形图标入口列表
});
