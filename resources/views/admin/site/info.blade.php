@extends("admin.main")
@section("content")
    <div class="page-content">

        <div class="page-header">
            <h1>
                站点设置
            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#web-title" aria-controls="web-title" role="tab" data-toggle="tab">基本信息</a>
                    </li>

                    <li role="presentation">
                        <a href="#queue" aria-controls="queue" role="tab" data-toggle="tab">队列设置</a>
                    </li>

                    <li role="presentation">
                        <a href="#other" aria-controls="other" role="tab" data-toggle="tab">其它设置</a>
                    </li>
                </ul>

                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="web-title">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 前台名称 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="info[title]" value="{{$info->title ?? ''}}"
                                           class="col-xs-10 col-sm-8">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 后台名称 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="info[admin_title]" value="{{$info->admin_title ?? ''}}"
                                           class="col-xs-10 col-sm-8">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 关键词 </label>
                                <div class="col-sm-9">
                                    <textarea name="info[keywords]" class="col-xs-10 col-sm-8" rows="2" cols="10"
                                              style="height:100px;">{{$info['keywords'] ?? ''}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 网站描述 </label>
                                <div class="col-sm-9">
                                    <textarea name="info[description]" class="col-xs-10 col-sm-8" rows="2" cols="10"
                                              style="height:100px;">{{$info['description'] ?? ''}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="queue">

                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 抓取api接口地址
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::radio(config('queue.name'),$info['setting']['get_api_url'],' name="info[setting][get_api_url]" ','80','')}}
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 抓取api响应数据
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::radio(config('queue.name'),$info['setting']['get_api_response_time'],' name="info[setting][get_api_response_time]" ','80','')}}
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 保存api响应数据
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::radio(config('queue.name'),$info['setting']['save_api_response_time'],' name="info[setting][save_api_response_time]" ','80','')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 报警通知
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::radio(config('queue.name'),$info['setting']['queue_api_alert'],' name="info[setting][queue_api_alert]" ','80','')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 发送微信
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::radio(config('queue.name'),$info['setting']['queue_weixin'],' name="info[setting][queue_weixin]" ','80','')}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 发送邮件
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::radio(config('queue.name'),$info['setting']['queue_email'],' name="info[setting][queue_email]" ','80','')}}
                                </div>

                            </div>

                        </div>

                        <div role="tabpanel" class="tab-pane" id="other">
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right" for="form-field-2">
                                    <span class="red">*</span> 系统通知
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::checkbox(M('Site')->alert_type_arr,$info['setting']['alert_type']??'',' name="info[setting][alert_type][]"','')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right" for="form-field-2">
                                    <span class="red"></span> 发送日志
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {{From::checkbox(config('common.send_log'),$info['setting']['send_log']??'',' name="info[setting][send_log][]"','')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> API响应抓取时间分组 </label>
                                <div class="col-sm-4">
                                    {{From::select(config('elastic.request_time_hist'),$info['setting']['es_request_time_hist'],'class="form-control" name="info[setting][es_request_time_hist]"','--请选择--')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> API响应抓取第一次时间 </label>
                                <div class="col-sm-4">
                                    {{From::select(config('elastic.first_start_time'),$info['setting']['es_first_start_time'],'class="form-control" name="info[setting][es_first_start_time]"','--请选择--')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> API响应抓取结束时间 </label>
                                <div class="col-sm-4">
                                    {{From::select(config('elastic.end_time'),$info['setting']['es_end_time'],'class="form-control" name="info[setting][es_end_time]"','--请选择--')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 最大并发数 </label>
                                <div class="col-sm-4">
                                    {{From::select(config('elastic.process_max'),$info['setting']['process_max'],'class="form-control" name="info[setting][process_max]"','--请选择--')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 系统报警邮件 </label>
                                <div class="col-sm-9">
                                    <textarea name="info[setting][mail]" class="col-xs-10 col-sm-8" rows="2" cols="10" style="height:100px;">{{$info['setting']['mail'] ?? ''}}</textarea>
                                </div>
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
                    </div>

                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@endsection


