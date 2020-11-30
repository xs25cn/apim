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


        <div class="row"   id="follow-up">
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
                            <input type="text" name="title" value="{{$info->title ?? ''}}" class="col-xs-10 col-sm-8" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 所属项目 </label>
                        <div class="col-sm-9">
                            {{From::select(array_column(S('ApiDomain')->userDomain($login_user->id),'title','id'),$info->api_domain_id,'class="col-xs-10 col-sm-8" name="api_domain_id" required','--请选择--')}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 备注 </label>
                        <div class="col-sm-9">
                            <textarea  rows="5" cols="20" name="description" class="col-xs-9 col-sm-9" >{{$info->description ?? ''}}</textarea>
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


