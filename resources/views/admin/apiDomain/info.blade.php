@extends("admin.main")
@section("content")

    <div class="page-content">

        <div class="page-header">
            <h1>
                {{isset($info->id)?'详情':'添加'}}
                <button class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'index'">
                    返回列表
                    <i class="icon-reply icon-only"></i>
                </button>
            </h1>
        </div><!-- /.page-header -->


        <div class="row"    id="follow-up">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="title" value="{{$info->title ?? ''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 域名 </label>
                        <div class="col-sm-7">
                            <input type="text" name="domain" placeholder="如:nc.xin.com"  value="{{$info->domain ?? ''}}" class="col-xs-5 col-sm-5">
                            <span class="help-inline col-xs-7 col-sm-7" style="padding-top: 8px">
                                 <span class="red">注:域名不需要添加http://</span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 索引 </label>
                        <div class="col-sm-9">
                            {{From::radio(M('ApiDomain')->es_index_arr,$info->es_index??1,' name="es_index"')}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 环境 </label>
                        <div class="col-sm-9">
                            {{From::radio(M('ApiDomain')->env_type_arr,$info->env_type??1,' name="env_type"')}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 备注 </label>
                        <div class="col-sm-9">
                            <textarea  rows="5" cols="20" name="description" class="col-xs-9 col-sm-9">{{$info->description ?? ''}}</textarea>
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


