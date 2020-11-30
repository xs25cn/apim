@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}}
                <span class="btn btn-sm btn-primary pull-right" onclick="javascript:$('#modal-body').load('info #follow-up');$('.modal-title').html('添加')"  data-toggle="modal" data-target="#myModal">
                    添加
                </span>
            </h1>
        </div>
        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform" method="GET" class="form-inline">


                    <div class="form-group select-input">

                        <div class="input-group">
                            <div class="input-group-addon">项目</div>
                            {{From::select(array_column(S('ApiDomain')->userDomain($login_user->id),'title','id'),request('api_domain_id'),'class="form-control" name="api_domain_id"','--请选择--')}}
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
                                <th>ID</th>
                                <th>所属项目</th>
                                <th>模块名称</th>
                                <th>接口数量</th>
                                <th>简介</th>
                                <th>操作人</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{$info->id}}</td>
                                    <td>{{$info->btApiDomain->title}} [{{$info->btApiDomain->domain}}]</td>
                                    <td>{{$info->title}}</td>
                                    <td>
                                        <a href="/{{$_m}}/apiUrl/index?api_module_id={{$info->id}}"> {{M('ApiUrl')->where('api_module_id',$info->id)->count()}}</a>
                                    </td>
                                    <td>{{str_limit($info->description,100)}}</td>
                                    <td>{{$info->btAdminUser->realname}}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal"
                                           onclick="javascript:$('#modal-body').load('info?id={{$info->id}} #follow-up');$('.modal-title').html('编辑')"
                                           data-target="#myModal">编辑</a>
                                        <a href="del?id={{$info->id}}" onclick="return confirm('确认操作吗？');return false;">删除</a>
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


    <!-- Modal START -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        <!--modal,弹出层父级,fade使弹出层有一个运动过程-->
        <div class="modal-dialog" style="width:1000px">
            <!--modal-dialog,弹出层-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">详情</h4>
                </div>
                <div class="modal-body" id="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" style="line-height: 13px;" data-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
    <script>
        //重加载
        $("#myModal").on("hidden.bs.modal", function () {
            $(this).removeData("bs.modal");
            $(".modal-body").empty();
        });
    </script>
    <!-- Modal END -->
@endsection