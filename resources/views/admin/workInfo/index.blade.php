@extends("admin.main")
@section("content")

<div class="page-content">
    <div class="page-header">
        <h1>
			{{$menu_info->name ?? ''}}
	    <span class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'info'">
            添加
        </span>
        </h1>
    </div>
	<div class="operate panel panel-default">
		<div class="panel-body ">
			<form name="myform" method="GET" class="form-inline">


				<div class="form-group select-input">
					<div class="input-group">
						<div class="input-group-addon">时间</div>
						<input type="text" class="layui-input" id="start_time" placeholder="" name="start_time"  value="{{request('start_time')}}">
					</div>

					<div class="input-group" style="margin-left: 0;">
						<div class="input-group-addon"> 至</div>
						<input type="text" class="layui-input" id="end_time" placeholder="" name="end_time" value="{{request('end_time')}}">
					</div>


					<div class="input-group">
						<input type="submit" value="搜索" class="btn btn-danger btn-sm">
						<span class="btn btn-info btn-sm" onclick="window.location.href = '?'">重置</span>
					</div>
				</div>

			</form>
		</div>
	</div>

    <div class="row">
	<div class="col-xs-12">
	    <!-- PAGE CONTENT BEGINS -->
	    <div class="row">
		<div class="col-xs-12">
		    <table id="simple-table" class="table  table-bordered table-hover">
			<thead>
			    <tr>
				<th>提醒时间</th>
				<th>是否需提醒</th>
				<th>用户</th>
				<th>内容</th>
				<th>是否已提醒</th>
				<th>操作</th>
			    </tr>
			</thead>

			<tbody>
			    @foreach ($lists as $info)
			    <tr>
				<td>{{date('Y-m-d H:i:s',$info->reminder_at)}}</td>
				<td>{{M('WorkInfo')->reminder_status_arr[$info->reminder_status]}}</td>
				<td>{{M('AdminUser')->where('id',$info->admin_id)->value('realname')}}</td>
				<td>{{str_limit($info->content,100)}}</td>
				<td>{{M('WorkInfo')->is_reminder_arr[$info->is_reminder]}}</td>
				<td>
					<a href="info?id={{$info->id}}">编辑</a>
					<a href="del?id={{$info->id}}">删除</a>
				</td>
			    </tr>

			    @endforeach
			</tbody>
		    </table>
		</div><!-- /.span -->

	    </div><!-- /.row -->
	    <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
    </div>

 </div>

    @endsection