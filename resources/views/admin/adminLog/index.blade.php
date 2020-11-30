@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}}
            </h1>
        </div>

        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform" method="GET" class="form-inline">
                    <div class="form-group select-input">
                        <div class="input-group">
                            <div class="input-group-addon">时间</div>
                            <input type="text" class="layui-input" id="start_time"  name="start_time"  value="{{request('start_time')}}">
                        </div>

                        <div class="input-group" style="margin-left: 0;">
                            <div class="input-group-addon"> 至</div>
                            <input type="text" class="layui-input" id="end_time"  name="end_time" value="{{request('end_time')}}">
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">菜单名称</div>
                            <input class="form-control" name="name" type="text" value="{{request('name')}}">
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">管理员</div>
                            <input class="form-control" name="admin_name" type="text" value="{{request('admin_name')}}">
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">ip地址</div>
                            <input class="form-control" name="ip" type="text" value="{{request('ip')}}">
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
                                <th>日期</th>
                                <th>菜单名称</th>
                                <th>请求地址</th>
                                <th>ip地址</th>
                                <th>操作人</th>
                                <th>查看</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{$info->created_at}}</td>
                                    <td>{{$info->menu_name}}</td>
                                    <td>{{$info->c}}/{{$info->a}}{{!empty($info->querystring)?'?'.$info->querystring:''}}</td>
                                    <td>{{$info->ip}}</td>
                                    <td>{{$info->admin_name}}</td>
                                    <td><a href="info?id={{$info->id}}">查看</a></td>
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