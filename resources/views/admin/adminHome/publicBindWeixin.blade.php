<html lang="zh-CN" class="body">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <title></title>
    <script src="/pc/js/jquery.min.js"></script>
</head>
<body>


</body>


<script>
    $(function () {
        var openid = getOpenId();
        //alert(openid);
        //document.write(openid);

    });
    function getOpenId() {
        var openid = getUrlKey('openid');
        if (!openid) {
            //跳转获取openid页面
            var getOpenIdUrl = 'https://gaichaowang.com/weixinOpenid.php';
            window.location.href = getOpenIdUrl;
        } else {
            // localStorage.setItem('openid', openid);
            return openid;
        }
    }

    function getUrlKey(key) {
        var seachUrl = window.location.search.replace("?", "");
        var itemArr = seachUrl.split("&"); //使用&分成数组 name=a1,bbb=b1
        var args = Array(), item = Array();
        for (var i = 0; i < itemArr.length; i++) {
            item = itemArr[i].split("="); //合用=号将其分成数组
            args[item[0]] = item[1];
        }
        //如果传入了KEY返回KEY对应的值，如果没有传入，返回整个数组
        if (key) {
            return args[key];
        } else {
            return args;
        }
    }
</script>
</html>
