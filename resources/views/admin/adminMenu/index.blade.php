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
        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform-select" method="GET" class="form-inline">
                    <div class="form-group select-input">


                        <div class="input-group">
                            <div class="input-group-addon">菜单名称</div>
                            <input class="form-control" name="name" type="text" value="{{request('name')}}"   placeholder="菜单名称">
                        </div>


                        <div class="input-group">
                            <div class="input-group-addon">菜单显示</div>
                            {{From::select(M('AdminMenu')->status_arr,request('status'),'class="form-control" name="status"','--请选择--')}}
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">状态</div>
                            {{From::select(M('AdminMenu')->write_log_arr,request('write_log'),'class="form-control" name="write_log"','--请选择--')}}
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
                                <th class="detail-col">Id</th>
                                <th class="detail-col" width="10">排序</th>
                                <th>名称</th>
                                <th>c/a</th>
                                <th class="hidden-480">data</th>
                                <th>管理员操作</th>
                                <th class="hidden-480">菜单显示</th>
                                <th class="hidden-480">日志记录</th>
                            </tr>
                            </thead>

                            <tbody>

                            <form action="" method="post" name="myform">
                            {{csrf_field()}}
                            @foreach($lists as $info)
                            <tr>
                                <td>{{$info['id']}}</td>
                                <td><input type="text" name="listorder[{{$info['id']}}]" value="{{$info['listorder']}}" width="10"></td>
                                <td><span class="fa {{$info['icon']}}"></span> {{$info['name']}}</td>
                                <td>{{$info['c']}}/{{$info['a']}}</td>
                                <td class="hidden-480">
                                    <span class="label label-sm label-warning">{{$info['data']}}</span>
                                </td>
                                <td>{{M('AdminMenu')->status_arr[$info['status']]}}</td>
                                <td>{{M('AdminMenu')->write_log_arr[$info['write_log']]}}</td>
                                <td>
                                    <div class="hidden-sm hidden-xs btn-group">
                                        <a href="info?parentid={{$info['id']}}">
                                            <span class="btn btn-xs btn-success">
                                        <i class="ace-icon fa fa-plus-square-o bigger-120"></i>
                                                </span>
                                        </a>

                                        <a href="info?id={{$info['id']}}">
                                            <span class="btn btn-xs btn-info">
                                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                                            </span>
                                        </a>

                                        <a href="del?id={{$info['id']}}">
                                            <span class="btn btn-xs btn-danger">
                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                            </span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </form>
                            </tbody>
                        </table>
                        <span class="btn btn-info"  onclick="myform.action='setListorder';myform.submit();">排序</span>
                    </div><!-- /.span -->
                </div><!-- /.row -->


                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div>
    </div>
    </div>


@endsection