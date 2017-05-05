# 简介
* lwf 是用[swoole](http://www.swoole.com/)开发的一款websocket框架
* 类似mvc模式开发,让人更容易理解和使用
# 安装
1. composer安装
~~~
composer create-project ljw/lwf lwf --prefer-dist
~~~
2. git安装
~~~
git clone https://github.com/lejianwen/lwf.git
~~~
3. 下载解压使用

# 目录结构
~~~
lwf                     项目部署目录（或者子目录）
├─app                   应用目录
│  ├─controllers        控制器
│  ├─models             模型
│  └─tasks              任务  
├─bootstrap             
│  └─bootstrap.php      应用启动文件
│
├─common                配置文件目录
│  └─functions.php      通用方法
│
├─client                客户端目录
│  └─lwf.js             客户端js
│
├─config                配置文件目录
│  ├─app.php            项目配置
│  ├─route.php          路由配置文件
│  ├─redis.php          redis配置
│  ├─database.php       数据库配置文件
│  └─swoole.php         swoole_websocket配置文件
├─demo                  示例
│
├─lib                   框架系统目录
│  ├─store              redis存储fd方式
│  ├─traits             trait文件
│  ├─controller.php     控制器基础类
│  ├─task.php           任务基础类
│  └─ ...               更多系统模块
│
├─runtime               系统运行目录
│  └─logs               日志文件目录
│
├─server                系统运行目录
│  └─websocket.php      websocket系统文件
│
├─vendor                第三方类库目录（Composer依赖库）
├─index.php             入口文件
├─composer.json         composer 定义文件
├─README.md             README 文件
└─webServer             系统启动文件
~~~

# 系统使用
-  系统配置 `config/`
1. websocket配置 
`swoole.php`中,具体参考[swoole的配置](https://wiki.swoole.com/wiki/page/274.html)
2. app配置  `app.php`
3. 数据库配置   `database.php`
4. redis配置  `redis.php`
- 系统使用
1. `php webServer start     //系统启动`
2. `php webServer stop      //系统停止`
3. `php webServer restart   //系统重启`
4. 也可以在websocket连接上以后发送消息到system/{:cmd}中，具体可以查看route.php中的配置和app\controllers\system.php中的实现，觉得不安全也可以去掉此功能，把route中的相关路由注释掉即可
```
|-------|------>|-------| 
|server |       |client |
|-------|       |-------| 
```
-----

