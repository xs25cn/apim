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


                    @if(!isset($info->id))
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">
                            <span class="red">*</span>
                            账号类型
                        </label>
                        <div class="col-sm-9">
                            {{From::select(M('AdminUser')->type_arr,$info->type,' name="type" class="col-xs-10 col-sm-8" id="type" ','--请选择--')}}
                        </div>

                    </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 用户名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{$info->name ?? ''}}" {{isset($info->id)?'disabled':''}} class="col-xs-7">
                            <span class="help-inline col-xs-3" id="find"  style="display: none">
                                <span class="btn btn-xs btn-success">
							    <i class="ace-icon fa bigger-120">查找</i>
						        </span>
                        </span>
                        </div>
                    </div>

                    @if(!isset($info->id))
                        <div class="form-group" id="password" style="display: none">
                            <label class="col-sm-3 control-label no-padding-right"> 密码 </label>
                            <div class="col-sm-9">
                                <input type="password" name="password" value=""
                                       placeholder="{{isset($info->id)?'不修改密码请保持空':''}}" class="col-xs-10 col-sm-8">
                            </div>
                        </div>
                    @endif


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 真实姓名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="realname" value="{{$info->realname ?? ''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 手机号 </label>
                        <div class="col-sm-9">
                            <input type="text" name="mobile" value="{{$info->mobile ?? ''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 邮箱 </label>
                        <div class="col-sm-9">
                            <input type="text" name="email" value="{{$info->email ?? ''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 角色 </label>
                        <div class="col-sm-9">
                            {{From::checkbox(M('AdminGroup')->pluck('name','id')->toArray(),isset($info->group_ids)?$info->group_ids:'',' name="group_id[]"')}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 状态 </label>
                        <div class="col-sm-3">
                            {{From::select(M('AdminUser')->status_arr,isset($info->status)?$info->status:1,'class="form-control" name="status" class="col-xs-10 col-sm-8"','--请选择--')}}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 响应超时报警 </label>
                        <div class="col-sm-9">
                            {{From::checkbox(M('Site')->alert_type_arr,$info['setting']['alert_overtime'],' name="setting[alert_overtime][]"','')}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 状态码异常报警 </label>
                        <div class="col-sm-6">
                            {{From::checkbox(M('Site')->alert_type_arr,$info['setting']['alert_error_code'],' name="setting[alert_error_code][]"','')}}
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


    <script>
        $(function(){
            //类型选择
            $("select[name='type']").bind('change',function(){
                $(this).val()==1?$('#password').show():$('#password').hide();
                $(this).val()==2?$('#find').show():$('#find').hide();
            });

            $("#find").bind('click',function(){
                var username = $("input[name='name']").val();
                $.ajax({
                    type:"get",
                    url:"domainAccount?username="+username,
                    dataType:'json',
                    success:function(data){
                           if(data.code==1){
                               console.log(data.data);
                               $("input[name='realname']").val(data.data.fullname);
                               $("input[name='mobile']").val(data.data.mobile);
                               $("input[name='email']").val(data.data.email);
                           }else if(data.code==-1){
                               alert(data.msg);
                        }
                    },

                });
            });
        });
    </script>


@endsection
