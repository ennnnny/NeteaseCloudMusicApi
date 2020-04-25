# 网易云音乐 API
![php>=7.2](https://img.shields.io/badge/php->%3D7.2-orange.svg?maxAge=2592000)

# 介绍
使用PHP语言复刻[@Binaryify][1]的网易云音乐接口

框架使用了 基于 `Swoole 4.3+` 实现的高性能、高灵活性的 PHP 持久化框架[Hyperf][2]

*尽可能地保持原汁原味*

# 使用
 * demo存放于`http_client`文件夹，可直接通过新版PhpStorm进行调试；
 * `request_cache`文件夹存放请求UA参数相关文件，分系统各自存放常用及常见UA；
 
   同时存有国内IP段文件`china_ip_list.txt`，请求时会自动加上（解决境外获取问题），登录后会固定IP；
   
   更新方法：
   ```shell script
   php bin/hyperf.php cache:pull
   ```

# TODO
- [x] 接口缓存

# API文档

[https://binaryify.github.io/NeteaseCloudMusicApi](https://binaryify.github.io/NeteaseCloudMusicApi)


  [1]: https://github.com/Binaryify/NeteaseCloudMusicApi
  [2]: https://hyperf.io/
