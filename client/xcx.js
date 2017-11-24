/**
 * Created by Administrator on 2017/11/16.
 */
var lwf = function (options) {
    this.init(options).run();
    return this;
};

lwf.prototype.init = function (options) {
    var _this = this;
    this.options = options;
    this.url = 'wss://';
    this.url += this.options.host;
    this.ws = null;
    this.last_connect_time = new Date().getTime();
    this.is_connect = false; //是否已连接
    this.msgQueue = []; //要发送的消息队列

    /**
     * 路由配置
     * 自己定义了ping,pong方法
     */
    this.routers = {
        'ping': 'ping',
        'pong': 'pong'
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
    this.registerRouters(this.options.routers).registerControllers(this.options.controllers);
    return this;
};

lwf.prototype.run = function () {
    this.connect();
    var _this = this
    wx.onSocketOpen(function (e) {
        _this.onOpen(e)
    });
    wx.onSocketMessage(function (e) {
        _this.onMessage(e)
    });
    wx.onSocketClose(function (e) {
        _this.onClose(e)
    });
    wx.onSocketError(function (e) {
        console.log(e.data);
    });
    return this;
};

lwf.prototype.onOpen = function (e) {
    this.tagConnect(true);
    //发送没法送出去的消息
    for (var i = 0; i < this.msgQueue.length; i++) {
        wx.sendSocketMessage({data: this.msgQueue[i]})
    }
    console.log('open success!');
};
lwf.prototype.onClose = function (e) {
    //标记连接
    this.tagConnect(false);
    console.log('close success!');
};

lwf.prototype.onMessage = function (e) {
    this.tagConnect(true);
    var _res = JSON.parse(e.data);
    if (typeof (_res.uri) == 'undefined')
        return;
    var uri = _res.uri;
    if (typeof (this.routers[uri]) == 'function') {
        this.routers[uri](_res);
    } else if (typeof (this.routers[uri]) == 'string') {
        this.controllers[this.routers[uri]](_res);
    } else {
        throw new EventException('URI IS NOT EXISTS!');
    }
};
//标记连接
lwf.prototype.tagConnect = function (is_connect) {
    if (is_connect) {
        this.last_connect_time = new Date().getTime();
    }
    this.is_connect = is_connect;
};
/**
 * 发送消息
 * @param uri
 * @param data
 */
lwf.prototype.send = function (uri, data) {
    var msg = {uri: uri, data: data};
    if (this.is_connect) {
        wx.sendSocketMessage({data: JSON.stringify(msg)});
    } else {
        this.msgQueue.push(JSON.stringify(msg))
    }

};
lwf.prototype.connect = function () {
    wx.connectSocket({
        url: this.url,
        header: {token: this.options.token},
        fail: function () {
            console.log('链接失败')
        }
    });
};
lwf.prototype.close = function () {
    wx.closeSocket()
};

lwf.prototype.reconnect = function () {
    this.close();
    this.connect();
};

//routers注册
lwf.prototype.registerRouters = function (routers) {
    for (var v in (routers)) {
        this.routers[v] = routers[v];
    }
    return this;
};
//controllers注册
lwf.prototype.registerControllers = function (controllers) {
    for (var v in (controllers)) {
        this.controllers[v] = controllers[v];
    }
    return this;
};
module.exports = {

    lwf: lwf

}
