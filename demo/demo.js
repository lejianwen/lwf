var routers = {
      'test' : function (res) {
          console.log(res);
      }
};

var app = new lwf({
    'ip' : '192.168.0.233',
    'port' : '9501',
    'params' : 'a=1&b=2',
    'routers' : routers
});