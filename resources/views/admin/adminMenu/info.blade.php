@extends("admin.main")
@section("content")

    <div class="page-content">

        <div class="page-header">
            <h1>
                {{isset($info->id)?'详情':'添加'}}
                <button class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'index'">
                    返回列表
            </h1>

        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 上级菜单 </label>
                        <div class="col-sm-9">
                            {{From::select($menus,request('parentid')?request('parentid'):(isset($info->id)?$info->parentid:0),' name="parentid" class="col-xs-10 col-sm-8" ','--作为一级菜单--')}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">图标</label>
                        <div class="col-sm-9">
                        <span class="input-icon input-icon-left">
                            <input type="text" id="form-field-icon-2" name="icon" value="{{$info->icon ?? ''}}">
                            <i class="ace-icon fa fa-leaf green"></i>
                        </span>
                            <!-- <a href="http://fontawesome.io/cheatsheet/" target="_blank">查看</a>-->
                            <a href="#modal-form" role="button" class="blue" data-toggle="modal"> 查看图标 </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{$info->name ?? old('a')}}"
                                   class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 文件名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="c" value="{{$info->c ??  old('a')}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="space-4"></div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 方法名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="a" value="{{$info->a ?? old('a')}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="space-4"></div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 附加参数 </label>
                        <div class="col-sm-9">
                            <input type="text" name="data" value="{{$info->data ?? ''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 排序 </label>
                        <div class="col-sm-9">
                            <input type="text" name="listorder" value="{{$info->listorder ?? '999'}}"
                                   class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="space-4"></div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 菜单显示 </label>
                        <div class="col-sm-9">
                            {!! From::radio(M('AdminMenu')->status_arr,!empty($info->status)?$info->status:1,' name="status" ',70,'status') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 记录日志 </label>
                        <div class="col-sm-9">
                            {!! From::radio(M('AdminMenu')->write_log_arr,!empty($info->write_log)?$info->write_log:2,' name="write_log" ',70,'write_log') !!}
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

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                提交
                            </button>
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Reset
                            </button>
                        </div>
                    </div>


                </form>


                <!--模态框 start-->
                <div id="modal-form" class="modal in" tabindex="-1" style="display: none;">
                    <div class="modal-dialog" style="width:750px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="blue bigger">所有图标</h4>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3 col-sm-3">
                                        <ul class="list-unstyled">
                                            <li>
                                                <i class="ace-icon fa fa-adjust"></i>
                                                fa-adjust
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-asterisk"></i>
                                                fa-asterisk
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-ban"></i>
                                                fa-ban
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-bar-chart-o"></i>
                                                fa-bar-chart-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-barcode"></i>
                                                fa-barcode
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-flask"></i>
                                                fa-flask
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-beer"></i>
                                                fa-beer
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-bell-o"></i>
                                                fa-bell-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-bell"></i>
                                                fa-bell
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-bolt"></i>
                                                fa-bolt
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-book"></i>
                                                fa-book
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-bookmark"></i>
                                                fa-bookmark
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-bookmark-o"></i>
                                                fa-bookmark-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-briefcase"></i>
                                                fa-briefcase
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-bullhorn"></i>
                                                fa-bullhorn
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-calendar"></i>
                                                fa-calendar
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-camera"></i>
                                                fa-camera
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-camera-retro"></i>
                                                fa-camera-retro
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-certificate"></i>
                                                fa-certificate
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-3 col-sm-3">
                                        <ul class="list-unstyled">
                                            <li>
                                                <i class="ace-icon fa fa-check-square-o"></i>
                                                fa-check-square-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-square-o"></i>
                                                fa-square-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-circle"></i>
                                                fa-circle
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-circle-o"></i>
                                                fa-circle-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-cloud"></i>
                                                fa-cloud
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-cloud-download"></i>
                                                fa-cloud-download
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-cloud-upload"></i>
                                                fa-cloud-upload
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-coffee"></i>
                                                fa-coffee
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-cog"></i>
                                                fa-cog
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-cogs"></i>
                                                fa-cogs
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-comment"></i>
                                                fa-comment
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-comment-o"></i>
                                                fa-comment-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-comments"></i>
                                                fa-comments
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-comments-o"></i>
                                                fa-comments-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-credit-card"></i>
                                                fa-credit-card
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-tachometer"></i>
                                                fa-tachometer
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-desktop"></i>
                                                fa-desktop
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-arrow-circle-o-down"></i>
                                                fa-arrow-circle-o-down
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-download"></i>
                                                fa-download
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-3 col-sm-3">
                                        <ul class="list-unstyled">
                                            <li>
                                                <i class="ace-icon fa fa-pencil-square-o"></i>
                                                fa-pencil-square-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-envelope"></i>
                                                fa-envelope
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-envelope-o"></i>
                                                fa-envelope-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-exchange"></i>
                                                fa-exchange
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-exclamation-circle"></i>
                                                fa-exclamation-circle
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-external-link"></i>
                                                fa-external-link
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-eye-slash"></i>
                                                fa-eye-slash
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-eye"></i>
                                                fa-eye
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-video-camera"></i>
                                                fa-video-camera
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-fighter-jet"></i>
                                                fa-fighter-jet
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-film"></i>
                                                fa-film
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-filter"></i>
                                                fa-filter
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-fire"></i>
                                                fa-fire
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-flag"></i>
                                                fa-flag
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-folder"></i>
                                                fa-folder
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-folder-open"></i>
                                                fa-folder-open
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-folder-o"></i>
                                                fa-folder-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-folder-open-o"></i>
                                                fa-folder-open-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-cutlery"></i>
                                                fa-cutlery
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-3 col-sm-3">
                                        <ul class="list-unstyled">
                                            <li>
                                                <i class="ace-icon fa fa-gift"></i>
                                                fa-gift
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-glass"></i>
                                                fa-glass
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-globe"></i>
                                                fa-globe
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-users"></i>
                                                fa-users
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-hdd-o"></i>
                                                fa-hdd-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-headphones"></i>
                                                fa-headphones
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-heart"></i>
                                                fa-heart
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-heart-o"></i>
                                                fa-heart-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-home"></i>
                                                fa-home
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-inbox"></i>
                                                fa-inbox
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-info-circle"></i>
                                                fa-info-circle
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-key"></i>
                                                fa-key
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-leaf"></i>
                                                fa-leaf
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-laptop"></i>
                                                fa-laptop
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-gavel"></i>
                                                fa-gavel
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-lemon-o"></i>
                                                fa-lemon-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-lightbulb-o"></i>
                                                fa-lightbulb-o
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-lock"></i>
                                                fa-lock
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-unlock"></i>
                                                fa-unlock
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
                <!--模态框 end-->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@endsection

