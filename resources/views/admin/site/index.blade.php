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
						<div class="input-group-addon">分类</div>
							{{From::select(M('Category')->selectCategory(),request('cat_id'),'class="form-control"  name="cat_id"','--请选择--')}}
					</div>
					<div class="input-group">
						<div class="input-group-addon">标题</div>
						<input class="form-control" name="title" type="text" value="{{request('title')}}" >
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
				<th>id</th>
				<th>分类</th>
				<th>标题</th>
				<th>添加时间</th>
				<th>操作</th>
			    </tr>
			</thead>

			<tbody>
			    @foreach ($lists as $info)
			    <tr>
				<td>{{$info->id}}</td>
				<td>{{M('Category')->where('id',$info->cat_id)->value('name')}}</td>
				<td>{{$info->title}}</td>
				<td>{{$info->created_at}}</td>
					<td>
						<div class="hidden-sm hidden-xs btn-group">
							<a href="info?id={{$info->id}}">编辑</a>
							<a href="del?id={{$info->id}}">删除</a>
						</div>
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