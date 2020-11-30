@extends("admin.main")
@section("content")

    <div class="page-content">


        <div class="page-header">
            <h1>
                {{isset($info->id)?$info->order_sn.'详情':'添加'}}
                <button class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'index'">
                    返回列表
            </h1>
        </div>
        <div class="row">

            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span class="red">*</span> 名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{$info->name ?? ''}}" class="col-xs-10 col-sm-8" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span class="red">*</span> 代码 </label>
                        <div class="col-sm-9">
                            <input type="text" name="code" value="{{$info->code ?? ''}}" placeholder="app/Kernel.php 中的 case 值" class="col-xs-10 col-sm-8" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span class="red">*</span> 时间 </label>
                        <div class="col-sm-9">
                            <input type="text" name="crontab" value="{{$info->crontab ?? ''}}" placeholder="* * * * *" class="col-xs-10 col-sm-8" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span class="red">*</span> 备注 </label>
                        <div class="col-sm-9">
                            <input type="text" name="description" value="{{$info->description ?? ''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"><span class="red">*</span> 状态 </label>
                        <div class="col-sm-9">
                            {!! From::radio(M('Crontab')->status_arr,!empty($info->status)?$info->status:1,' name="status" ',50,'status') !!}
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
                        <div class="col-md-offset-4 col-md-9">
                            <button class="btn btn-info" type="submit" id="sub_btn">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                提交
                            </button>

                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

