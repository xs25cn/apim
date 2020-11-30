@extends("admin.main")
@section("content")

    <div class="page-content">

        <div class="page-header">
            <h1>
                {{isset($info->id)?'详情':'添加'}}
                <button class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'index?api_domain_id={{request('api_domain_id')}}'">
                    返回列表
                    <i class="icon-reply icon-only"></i>
                </button>
            </h1>
        </div><!-- /.page-header -->


        <div class="row" id="follow-up">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="GET"  action="/{{$_m}}/{{$_c}}/bachApiUrl">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> Url前缀 </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="如:/module/controller/" name="prefix"  class="col-xs-5 col-sm-5" required>
                            <span class="help-inline col-xs-7 col-sm-7" style="padding-top: 8px">
                                 <span class="red">例：/ucl/team,会帮你抓取/ucl/team/*** 常用访问地址</span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 开始时间 </label>
                        <div class="col-sm-9">
                            <input type="text" class="col-xs-3 col-sm-3 layui-input" id="start_time" placeholder="" name="start_time"  value="{{date('Y-m-d 00:00:00')}}">
                            <span class="help-inline col-xs-4 col-sm-4" style="padding-top: 8px">
                                 <span class="red">请求接口的开始时间</span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 结束时间 </label>
                        <div class="col-sm-9">
                            <input type="text" class="col-xs-3 col-sm-3 layui-input" id="end_time" placeholder="" name="end_time"  value="{{date('Y-m-d H:i:s')}}">
                            <span class="help-inline col-xs-4 col-sm-4" style="padding-top: 8px">
                                 <span class="red">请求接口的结束时间</span>
                            </span>
                        </div>
                    </div>

                    <input type="hidden" name="api_domain_id" value="{{$info->api_domain_id?:request('api_domain_id')}}">


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
                        </div>
                    </div>
                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>


@endsection


