@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}}
                <span class="btn btn-sm btn-primary pull-right"
                      onclick="javascript:$('#modal-body').load('info #follow-up');$('.modal-title').html('添加')"
                      data-toggle="modal" data-target="#myModal">
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
                            <input class="form-control" name="title" type="text" value="{{request('title')}}" placeholder="">
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">域名</div>
                            <input class="form-control" name="domain" type="text" value="{{request('domain')}}" placeholder="">
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">索引</div>
                            {{From::select(M('ApiDomain')->es_index_arr,request('es_index'),'class="form-control" name="es_index"','--请选择--')}}
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">环境</div>
                            {{From::select(M('ApiDomain')->env_type_arr,request('env_type'),'class="form-control" name="env_type"','--请选择--')}}
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">时间</div>
                            <input type="text" class="layui-input" id="start_time" placeholder="" name="start_time"
                                   value="{{request('start_time')}}">
                        </div>

                        <div class="input-group" style="margin-left: 0;">
                            <div class="input-group-addon"> 至</div>
                            <input type="text" class="layui-input" id="end_time" placeholder="" name="end_time"
                                   value="{{request('end_time')}}">
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
                                <th>名称/域名</th>
                                <th>索引/环境</th>
                                <th>接口数</th>
                                <th>备注</th>
                                <th>操作时间</th>
                                <th>操作人</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{$info->id}}</td>
                                    <td>{{$info->title}}<br>{{$info->domain}}</td>
                                    <td>
                                        {{M('ApiDomain')->es_index_arr[$info->es_index]}}
                                        <br>
                                        {{M('ApiDomain')->env_type_arr[$info->env_type]}}
                                    </td>
                                    <td>
                                        <a href="/{{$_m}}/apiUrl/index?api_domain_id={{$info->id}}"> {{M('ApiUrl')->where('api_domain_id',$info->id)->count()}}</a>
                                    </td>
                                    <td>{{$info->description}}</td>
                                    <td>{{$info->created_at}}</td>
                                    <td>{{$info->btAdminUser->realname}}</td>
                                    <td>
                                        <span class="label @if($info->status==2)  label-danger @else label-success @endif arrowed-in arrowed-in-right">{{m('ApiDomain')->status_arr[$info->status]}}</span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal"
                                           onclick="javascript:$('#modal-body').load('info?id={{$info->id}} #follow-up');$('.modal-title').html('编辑')"
                                           data-target="#myModal">编辑</a>
                                        <a href="status?id={{$info->id}}"
                                           onclick="return confirm('确认操作吗？');return false;"> {{$info->status==1?'禁用':'恢复'}} </a>
                                        {{--
                                                            <a href="asyncApiUrl?id={{$info->id}}" onclick="return confirm('确认要获取 {{$info->title}} 下的url数据吗？');return false;">获取接口地址</a>
                                        --}}
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