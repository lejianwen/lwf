var routers = {
      'test' : function (res) {
          console.log(res);
      }
};
var rand = Math.random() * 1000;
var app = new lwf({
    'ip' : 'wss.menyaer.com',
    'is_ssl' : true,
    'port' : '9501',
    'params' : 'a='+rand,
    'routers' : routers
});