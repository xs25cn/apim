@extends("admin.main")
@section("content")

    <div class="page-content">

        <div class="page-header">
            <h1>
                {{isset($info->id)?'详情':'添加'}}
                <button class="btn btn-sm btn-primary pull-right"
                        onclick="javascript:window.location.href = 'index?api_domain_id={{request('api_domain_id')}}'">
                    返回列表
                    <i class="icon-reply icon-only"></i>
                </button>
            </h1>
        </div><!-- /.page-header -->


        <div class="row" id="follow-up">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST"
                      action="/{{$_m}}/{{$_c}}/{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="title" value="{{$info->title ?? ''}}" class="col-xs-10 col-sm-8" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> Url </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="如:/module/controller/function" name="url"
                                   value="{{$info->url ?? ''}}" class="col-xs-8 col-sm-8" required>
                            <span class="help-inline col-xs-4 col-sm-4" style="padding-top: 8px">
                                 <span class="red">如: /m/c/f?car_id=100</span>
                            </span>
                        </div>
                    </div>


                    <input type="hidden" name="api_domain_id"
                           value="{{$info->api_domain_id?:request('api_domain_id')}}">

                    <div class="form-group" id="module_id">
                        <label class="col-sm-3 control-label no-padding-right"> 所属模块 </label>
                        <div class="col-sm-9">
                            {{From::select(S('ApiModule')->getApiModel(request('api_domain_id')),$info->api_module_id,'class="col-xs-10 col-sm-8" name="api_module_id"  id="info_module_id" ','--请选择--')}}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 报警(时间)</label>
                        <div class="col-sm-9">
                            <div class="col-sm-2">
                                <input type="text" name="response_time_alert" size="5" value="{{$info->response_time_alert ?? ''}}"  pattern="^[0-9]+$" required>
                                <span>秒</span>
                            </div>
                            <div class="col-sm-10">
                                <label class="col-sm-2 control-label no-padding-right"> 数量/百分比 </label>
                                <input type="text" name="time_alert_total" value="{{$info->time_alert_total ?? ''}}" class="col-sm-1" pattern="^[0-9]+$" required style="margin-left: 5px">
                                &nbsp;&nbsp;
                                {{From::radio(M('ApiUrl')->time_alert_type_arr, $info->time_alert_type, 'name="time_alert_type"', $width = 10, $field = '')}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 报警(状态码)</label>
                        <div class="col-sm-9">
                            {{From::checkbox(M('ApiUrl')->code_alert_arr,$info->code_alert,' name="code_alert[]"')}}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 备注 </label>
                        <div class="col-sm-9">
                            <textarea rows="5" cols="20" name="description" class="col-sm-8">{{$info->description ?? ''}}</textarea>
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
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>


@endsection


