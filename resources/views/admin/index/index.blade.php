<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title>{{$info['admin_title']}}</title>
    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    <!-- ace styles -->
    <link rel="stylesheet" href="/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <!-- inline scripts related to this page -->
    <script src="/assets/js/jquery-2.1.4.min.js"></script>

</head>
<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center ">
                        <h1>
                            <span class="red">{{$info['admin_title']}}</span>
                            <span class="white" id="id-text2"></span>
                        </h1>
                        <h4 class="blue" id="id-company-text"></h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="ace-icon fa fa-coffee green"></i>
                                        Please Enter Your Information
                                    </h4>

                                    <div class="space-6"></div>

                                    <form method="POST" id="myform" name="myform" action="/admin/index/login">
                                        {{csrf_field()}}
                                        <input type="hidden" name="login_pub_key" value="{{config('common.login_pub_key')}}">
                                        <fieldset>
                                            <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" class="form-control" name="name" placeholder="用户名"/>
                                                <i class="ace-icon fa fa-user"></i>
                                            </span>
                                             </label>

                                            <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="password" class="form-control" name="password" placeholder="密码"/>
                                                <i class="ace-icon fa fa-lock"></i>
                                            </span>
                                            </label>

                                            <div class="space"></div>

                                            <div class="clearfix">
                                                <label class="inline">
                                                    <input type="checkbox" class="ace" name="xxx" value="0"/>
                                                    <span class="lbl"> Remember Me</span>
                                                </label>

                                                <input type="button" name="dosubmit" value="Login" class="width-35 pull-right btn btn-sm btn-primary">

                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>


                                </div><!-- /.widget-main -->

                                <div class="toolbar clearfix">


                                    <div>
                                        <a href="#" class="user-signup-link">
                                            不要在公众场合记住密码

                                        </a>
                                    </div>
                                </div>
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div><!-- /.widget-body -->
                        </div><!-- /.login-box -->

                    </div><!-- /.position-relative -->

                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->
<script src="/js/rsa.js"></script>
<script>

    $("input[name='dosubmit']").on('click',function(){
        var pub_key = $("input[name='login_pub_key']").val();
        var password = $("input[name='password']").val();
        $("input[name='login_pub_key']").remove();

        var rsa = new JSEncrypt();
        rsa.setPublicKey(pub_key);
        $("input[name='password']").val(rsa.encrypt(password));
        $("#myform").submit();
    });
</script>
</body>
</html>
