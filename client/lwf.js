/**
 * Created by Administrator on 2017/3/30.
 */

var lwf = function (options) {
    this.init(options).run().registerRouters(this.options.routers).registerControllers(this.options.controllers);
    return this;
};

/**初始化
 */
lwf.prototype.init = function (options) {
    var _this = this;
    this.options = options;
    this.is_ssl = this.options.is_ssl || false;
    if (this.is_ssl)
        this.url = 'wss://';
    else
        this.url = 'ws://';
    this.url += this.options.host;
    if (this.options.port) {
        this.url += ':' + this.options.port;
    }

    if (typeof (this.options.params) != 'undefined')
        this.url += '?' + this.options.params;
    this.ws = null;
    this.last_connect_time = new Date().getTime();
    this.is_connect = false; //是否已连接

    /**系统每隔{check_interval_time}进行一次检测
     * 如果 现在时间-上次连接时间 > 连接超时时长({expire_time}) ,则断开连接
     * 如果 现在时间-上次连接时间  > 上次连接超时时长({last_connect_expire_time}) ,则发送心跳
     * **/
    this.check_interval = false;
    this.check_interval_time = 1000;      //每隔多久检测一次是否需要发送心跳 ms
    this.last_connect_expire_time = 5000;     //上次连接超时时长,当上一次连接超过这个时间时就自动发送心跳 ms
    this.expire_time = 60000;              //连接超时时长,超过此时长，表示连接丢失，关闭链接 ms

    /**路由配置
     * 自己定义了ping,pong方法
     */
    this.routers = {
        'ping': 'ping',
        'pong': 'pong'
        //test : function(data){}
    };

    this.controllers = {
        'ping': function () {
            //发送心跳
            _this.send('system/heart', {});
        },
        'pong': function () {
            //接收心跳
            //this.tagConnect(true);
        }
    };

    return this;
};


/**连接开启
 * @param e
 */
lwf.prototype.onOpen = function (e) {
    this.tagConnect(true);
    this.startCheck();
    console.log('open success!');
};

/**连接关闭
 * @param e
 */
lwf.prototype.onClose = function (e) {
    this.stopCheck();
    //标记连接
    this.tagConnect(false);
    console.log('close success!');
};

lwf.prototype.onMessage = function (e) {
    this.tagConnect(true);
    var _res = JSON.parse(e.data);
    if (typeof(_res.uri) == 'undefined')
        return;
    var uri = _res.uri;
    delete _res.uri;
    if (typeof(this.routers[uri]) == 'function') {
        this.routers[uri](_res);
    } else if (typeof(this.routers[uri]) == 'string') {
        this.controllers[this.routers[uri]](_res);
    } else {
        throw new EventException('URI IS NOT EXISTS!');
    }
};


lwf.prototype.run = function () {
    var _this = this;
    this.ws = new WebSocket(this.url);
    this.ws.onopen = function (e) {
        _this.onOpen(e);
    };
    this.ws.onmessage = function (e) {
        _this.onMessage(e);
    };
    this.ws.onclose = function (e) {
        _this.onClose(e);
    };
    this.ws.onerror = function (e) {
        console.log(e.data);
    };
    return this;
};

/**发送消息
 * @param uri
 * @param data
 */
lwf.prototype.send = function (uri, data) {
    var _send = {'uri': uri, 'data': data};
    this.ws.send(JSON.stringify(_send));
};

/**是否已超时
 * @returns {boolean}
 */
lwf.prototype.isExpired = function () {
    var now = new Date().getTime();
    //连接超时，则断开连接
    if ((now - this.last_connect_time) > this.expire_time)
        return true;
    return false;
};


/**系统检测
 * 系统每隔{check_interval_time}进行一次检测
 * 如果 现在时间-上次连接时间  > 连接超时时长({expire_time}) ,则断开连接
 * 如果 现在时间-上次连接时间  > 上次连接超时时长({last_connect_expire_time}) ,则发送心跳
 * **/
lwf.prototype.startCheck = function () {
    var _this = this;
    this.check_interval = setInterval(function () {
        var now = new Date().getTime();
        //连接超时，则断开连接
        if ((now - this.last_connect_time) > this.expire_time) {
            this.ws.close();
        } else if ((now - this.last_connect_time) > this.last_connect_expire_time) {
            this.controllers.ping();
        } else {
            this.tagConnect(false);
        }
    }.bind(_this), this.check_interval_time);
};

/**停止心跳
 */
lwf.prototype.stopCheck = function () {
    clearInterval(this.check_interval);
};

//routers注册
lwf.prototype.registerRouters = function (routers) {
    if (typeof (routers) == 'object') {
        for (var v in (routers)) {
            this.routers[v] = routers[v];
        }
    }
    return this;
};
//controllers注册
lwf.prototype.registerControllers = function (controllers) {
    if (typeof (controllers) == 'object') {
        for (var v in (controllers)) {
            this.controllers[v] = controllers[v];
        }
    }
    return this;
};
//标记连接
lwf.prototype.tagConnect = function (is_connect) {
    if (is_connect) {
        this.last_connect_time = new Date().getTime();
    }
    this.is_connect = is_connect;
};
