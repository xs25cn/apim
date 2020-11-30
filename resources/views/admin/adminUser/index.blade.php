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
                            <div class="input-group-addon">名称</div>
                            <input class="form-control" name="name" type="text" value="{{request('username')}}" placeholder="">
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">状态</div>
                            {{From::select(M('AdminUser')->status_arr,request('status'),'class="form-control" name="status"','--请选择--')}}
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
                                <th>用户名</th>
                                <th>真实姓名</th>
                                <th class="col-xs-2">权限</th>
                                <th>状态</th>
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
                                    <td>{{$info->realname}}</td>
                                    <td>{{$info->groups}}</td>
                                    <td>{{M('AdminUser')->status_arr[$info->status]}}</td>
                                    <td>{{$info->created_at}}</td>
                                    <td>{{$info->updated_at}}</td>
                                    <td>
                                        <div>
                                            <a href="userApiDomainInfo?id={{$info->id}}">分配项目</a>
                                            <a href="info?id={{$info->id}}">编辑</a>
                                            @if($info->type==1)
                                                <a href="changePwd?id={{$info->id}}">修改密码</a>
                                            @endif
                                            <a href="del?id={{$info->id}}" onclick="return confirm('确认操作吗？');return false;">删除</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="12">总数：{{$lists->total()}}</td>
                            </tr>
                            </tfoot>
                        </table>
                        <div id="page">{{$lists->appends(request()->all())->links()}}</div>
                    </div><!-- /.span -->

                </div><!-- /.row -->
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div>
    </div>


@endsection