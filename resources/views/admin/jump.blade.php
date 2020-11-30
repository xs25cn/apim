<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <title>跳转提示</title>
    <style type="text/css">

        * {
            padding: 0;
            margin: 0;
        }

        body {
            background: #fff;
            font-family: '微软雅黑';
            color: #CCC;
            font-size: 16px;
        }

        .system-message {
            padding: 24px 48px;
            margin: auto;
            border: #CCC 3px solid;
            top: 50%;
            width: 500px;
            border-radius: 10px;
            -moz-border-radius: 10px; /* Old Firefox */
        }

        .system-message h1 {
            font-size: 100px;
            font-weight: normal;
            line-height: 120px;
            margin-bottom: 5px;
        }

        .system-message .jump {
            padding-top: 10px;
            color: #999;
        }

        .system-message .success, .system-message .error {
            line-height: 1.8em;
            color: #999;
            font-size: 36px;
            font-family: '黑体';
        }

        .system-message .detail {
            font-size: 12px;
            line-height: 20px;
            margin-top: 12px;
            display: none
        }

    </style>
    <!-- basic scripts -->
    <!--[if !IE]> -->
    <script src="/assets/js/jquery-2.1.4.min.js"></script>
    <!-- <![endif]-->
    <!--[if IE]>
    <script src="/assets/js/jquery-1.11.3.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        $(function () {
            var height2 = $('.system-message').height();
            var height1 = $(window).height();
            $('.system-message').css('margin-top', ((height1 - height2) / 2) - 30);
        });
    </script>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>

</head>


<body>
<div class="system-message">

    @if ($data['code'] === 1)
        <h1 class="glyphicon glyphicon-ok-circle" style="color:#09F"></h1>
        <p class="success">{{$data['msg']}}</p>
    @else
        <h1 class="glyphicon glyphicon-exclamation-sign" style="color:#F33"></h1>
        <p class="error">{{$data['msg']}}</p>
    @endif

    <p class="detail"></p>
    <p class="jump">
        页面自动 <a id="href" class="text-primary" href="{{$data['url']}}">跳转</a> 等待时间： <b id="wait">{{$data['wait']}}</b>

    </p>
</div>
<script type="text/javascript">
    (function () {
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        var interval = setInterval(function () {
            var time = --wait.innerHTML;
            if (time <= 0) {
                location.href = href;
                clearInterval(interval);
            }
        }, 1000);
    })();
</script>
</body>
</html>



