@extends("admin.main")
@section("content")
<div class="page-content">
    <div class="page-header">
	<h1>
	    修改密码
	</h1>

    </div><!-- /.page-header -->

    <div class="row">
	<div class="col-xs-12">
	    <!-- PAGE CONTENT BEGINS -->
	    <form class="form-horizontal" role="form" method="POST" action="">
		{{csrf_field()}}
		@if(isset($info->id))
		<input type="hidden" name="id" value="{{$info->id}}"/>
		@endif

		<div class="form-group">
		    <label class="col-sm-3 control-label no-padding-right"> 登录名称 </label>
		    <div class="col-sm-9">
			<input type="text" name="name" value="{{$info->name ?? ''}}" {{isset($info->id)?'disabled':''}} class="col-xs-10 col-sm-8">
		    </div>
		</div>

		
		<div class="form-group">
		    <label class="col-sm-3 control-label no-padding-right"> 旧密码 </label>
		    <div class="col-sm-9">
			<input type="password" name="passwordOld" value="" class="col-xs-10 col-sm-8">
		    </div>
		</div>
		
		
		<div class="form-group">
		    <label class="col-sm-3 control-label no-padding-right"> 新密码 </label>
		    <div class="col-sm-9">
			<input type="password" name="password" value="" class="col-xs-10 col-sm-8">
		    </div>
		</div>
		<div class="form-group">
		    <label class="col-sm-3 control-label no-padding-right"> 确认新密码 </label>
		    <div class="col-sm-9">
			<input type="password" name="password_confirmation" value="" class="col-xs-10 col-sm-8">
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

