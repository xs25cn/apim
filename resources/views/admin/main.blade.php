<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$site->admin_title}}-{{$menu_info->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/font-awesome/4.5.0/css/font-awesome.min.css"/>

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css"/>
    <link rel="stylesheet" href="/assets/css/bootstrap-multiselect.min.css"/>
    <link rel="stylesheet" href="/assets/css/select2.min.css"/>
    <link rel="stylesheet" href="/assets/bt-select/css/bootstrap-select.min.css"/>
    <!-- text fonts -->
    <link rel="stylesheet" href="/assets/css/fonts.googleapis.com.css"/>
    <!-- ace styles -->
    <link rel="stylesheet" href="/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <link rel="stylesheet" href="/assets/js/daterangepicker/daterangepicker.css"/>

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/css/ace-part2.min.css" class="ace-main-stylesheet"/>
    <![endif]-->
    <link rel="stylesheet" href="/assets/css/ace-skins.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-rtl.min.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/css/ace-ie.min.css"/>
    <![endif]-->
    <!-- inline styles related to this page -->
    <!-- ace settings handler -->
    <script src="/assets/js/ace-extra.min.js"></script>

    <!--[if lte IE 8]>
    <script src="/assets/js/html5shiv.min.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->
    <!-- basic scripts -->
    <!--[if !IE]> -->
    <script src="/assets/js/jquery-2.1.4.min.js"></script>
    <!-- <![endif]-->
    <!--[if IE]>
    <script src="/assets/js/jquery-1.11.3.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement)
            document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
    </script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <!-- page specific plugin scripts -->
    <script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
    <script src="/assets/js/jquery.raty.min.js"></script>
    <script src="/assets/js/bootstrap-multiselect.min.js"></script>
    <script src="/assets/js/select2.min.js"></script>
    <script src="/assets/js/jquery-typeahead.js"></script>
    <script src="/assets/js/laydate/laydate.js"></script>
    <script src="/assets/js/tree.min.js"></script>
    <script src="/assets/js/dropzone.min.js"></script>

    <script src="/assets/bt-select/js/bootstrap-select.min.js"></script>

    <!-- ace scripts -->
    <script src="/assets/js/ace-elements.min.js"></script>
    <script src="/assets/js/ace.min.js"></script>

    <script src="/assets/js/daterangepicker/moment.min.js"></script>
    <script src="/assets/js/daterangepicker/daterangepicker.js"></script>
    <script src="/js/common.js"></script>


    <style>
        .select-input {
            font-size: 0;
        }

        .select-input .input-group {
            margin-top: 10px;
            margin-left: 10px;
        }

        .full-watermark {
            opacity: 0.15;
            color: #C0C0C0;
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            font-size: 18px;
            transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
            -o-transform: rotate(7deg);
        }
    </style>
</head>
<body class="no-skin">

<div id="navbar" class="navbar navbar-default  ace-save-state">
    <div class="navbar-container ace-save-state" id="navbar-container">

        <div class="navbar-header pull-left">
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                <span class="sr-only">Toggle sidebar</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a href="/" target="_blank" class="navbar-brand">
                <small>
                    <i class="fa  fa-eye"></i>
                    {{$site->admin_title}}
                    {{--[{{M('City')->where('id',session('city_id'))->value('name')}}]--}}
                </small>
            </a>
        </div>
        <div class="navbar-buttons navbar-header pull-right hidden-sm hidden-xs" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="/assets/images/avatars/user.jpg" alt="Jason's Photo"/>
                        <span class="user-info">
				                <small>Welcome,</small>
                            {{$login_user->realname}}
				        </span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>
                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="/admin/adminHome/publicInfo">
                                <i class="ace-icon fa fa-user"></i>
                                更新资料
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/admin/adminHome/publicChangePwd">
                                <i class="ace-icon fa fa-cog"></i>
                                更新密码
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/admin/index/logout">
                                <i class="ace-icon fa fa-power-off"></i>
                                退出系统
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>

<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.loadState('main-container')
        } catch (e) {
        }
    </script>

    <div id="sidebar" class="sidebar  responsive   ace-save-state">
        <script type="text/javascript">
            try {
                ace.settings.loadState('sidebar')
            } catch (e) {
            }
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">


                <button class="btn btn-info" onclick="javascript:window.location.href = '/admin/workInfo/index'">
                    <i class="ace-icon fa fa-pencil"></i>
                </button>

                <button class="btn btn-warning" onclick="javascript:window.location.href = '/admin/adminUser/index'">
                    <i class="ace-icon fa fa-users"></i>
                </button>

                <button class="btn btn-success" onclick="javascript:window.location.href = '/admin/apiDomain/index'">
                    <i class="ace-icon fa fa-globe"></i>
                </button>

                <button class="btn btn-danger" onclick="javascript:window.location.href = '/admin/apiModule/index'">
                    <i class="ace-icon fa fa-folder-o"></i>
                </button>

            </div>

            <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>

                <span class="btn btn-info"></span>

                <span class="btn btn-warning"></span>

                <span class="btn btn-danger"></span>
            </div>
        </div>


        <!-- /.nav-list -->
        <ul class="nav nav-list">

            {!!S('AdminMenu')->myMenuHtml()!!}

        </ul>

        <!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
               data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>

    <div class="main-content">
        <div class="main-content-inner">


            <!-- /.page-content -->
        @yield("content")
        <!-- /.page-content -->


        </div>

    </div><!-- /.main-content -->


    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div>
<!-- /.main-container -->

<script>
    var u = $(".active").parent('ul');
    var uc = u.attr("class");
    if (uc == 'submenu') {
        u.parent().attr("class", "open active");
        if (u.parent().parent().attr('class') == 'submenu') {
            u.parent().parent().parent().attr("class", "open active");
        }
    }

    function show(id) {
        $("#" + id).siblings().attr('class', 'hide');
        $("#" + id).attr('class', 'open');
    }


    $(function () {
        laydate.render({
            elem: '#start_time',
            calendar: true,
            type: 'datetime'
        });
        laydate.render({
            elem: '#end_time',
            calendar: true,
            type: 'datetime'

        });
        laydate.render({
            elem: '#date',
            calendar: true,
            type: 'date'
        });
    });

    var fullname= "{{ !empty($login_user['realname'])?$login_user['realname']:'' }}";
    var mastername = "{{ !empty($login_user['name'])?$login_user['name']:'' }}";
    watermark('<p style="text-align:center">' + fullname + '</p><p style="text-align:center">' + mastername + '</p>');</script>
</body>
</html>
