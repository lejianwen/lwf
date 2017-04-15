var routers = {
    'test': function (res) {
        console.log(res);
    },
    'test_c': 'test_c'
};
var rand = Math.random() * 1000;
var app = new lwf({
    'ip': 'wss.menyaer.com',
    'is_ssl': true,
    'port': '9501',
    'params': 'a=' + rand,
    'routers': routers,
    'controllers': {
        'test_c': function (res) {
            console.log('test_c');
            console.log(res);
        }
    }
});