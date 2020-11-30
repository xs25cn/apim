@extends("admin.main")
@section("content")
    <style>
        .select-input {
            font-size: 12px;
        }
    </style>
    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}}
                <span class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'info'">
            添加
            </h1>
        </div>

        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform" method="GET" class="form-inline">
                    <div class="form-group select-input">


                        <div class="input-group">
                            <div class="input-group-addon">状态</div>
                            {{From::select(M('Crontab')->status_arr,request('status'),'class="form-control" name="status"','--请选择--')}}
                        </div>


                        <div class="input-group">
                            <div class="input-group-addon">创建时间</div>
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
                                <th>id</th>
                                <th>名称</th>
                                <th>代码</th>
                                <th>运行时间</th>
                                <th>状态</th>
                                <th>备注</th>
                                <th>操作人</th>
                                <th>修改时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{$info->id}}</td>
                                    <td>{{$info->name}}</td>
                                    <td>{{$info->code}}</td>
                                    <td>{{$info->crontab}}</td>
                                    <td>{{M('Crontab')->status_arr[$info->status]}}</td>
                                    <td>{{$info->description}}</td>
                                    <td>{{$info->btAdminUser->realname}}</td>
                                    <td>{{$info->updated_at}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="info?id={{$info->id}}">编辑</a>
                                            <a href="status?id={{$info->id}}" onclick="return confirm('确认操作吗？');return false;"> {{$info->status==1?'禁用':'恢复'}} </a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10">
                                        总数：{{$lists->total()}}
                                    </td>
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

    <!-- Modal END -->
@endsection