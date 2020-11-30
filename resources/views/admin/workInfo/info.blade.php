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


        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 提醒 </label>
                        <div class="col-sm-9">
                            {!! From::radio(M('WorkInfo')->reminder_status_arr,$info->reminder_status,' required="required" name="info[reminder_status]" ',60,'info[reminder_status]') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 提醒时间 </label>
                        <div class="col-sm-9">
                            <input type="text" class="col-xs-5 col-sm-5" id="start_time" placeholder="" name="info[reminder_at]" required="required"  value="@if($info->id){{date('Y-m-d H:i:s',$info->reminder_at)}}@endif">

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 内容 </label>
                        <div class="col-sm-9">
                            <textarea  rows="10" cols="20" name="info[content]" class="col-xs-5 col-sm-5" required>{{$info->content ?? ''}}</textarea>
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

                            &nbsp; &nbsp; &nbsp;
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


