@extends("admin.main")
@section("content")

<div class="page-content">
    <div class="page-header">
        <h1>
            {{$menu_info->name ?? ''}}
             <span class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'info'">
            添加
        </h1>
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
				<th>角色名称</th>
				<th>描述</th>
				<th>创建时间</th>
				<th>修改时间</th>
				<th>操作</th>
			    </tr>
			</thead>

			<tbody>
			    @foreach ($lists as $info)
			    <tr>
				<td>{{$info->id}}</td>
				<td>{{$info->name}}</td>
				<td>{{$info->description}}</td>
				<td>{{$info->created_at}}</td>
				<td>{{$info->updated_at}}</td>
				<td><a href="info?id={{$info->id}}">编辑</a></td>
			    </tr>
			    @endforeach

			</tbody>
		    </table>
		    <div id="page">{{$lists->appends(request()->all())->links()}}</div>
		</div><!-- /.span -->

	    </div><!-- /.row -->
	    <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
    </div>
 </div>


    @endsection