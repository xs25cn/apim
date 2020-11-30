@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name}} {{$api_domain_info->title}} {{$api_domain_info->domain}}
                <span class="btn btn-sm btn-primary pull-right"
                      onclick="javascript:$('#modal-body').load('info?api_domain_id={{request('api_domain_id')}} #follow-up');$('.modal-title').html('添加')"
                      data-toggle="modal" data-target="#myModal">
                    添加接口
                </span>
                <span class="btn btn-sm btn-primary pull-right" style="margin-right: 10px"
                      onclick="javascript:window.location.href = 'bachApiUrl?api_domain_id={{request('api_domain_id')}}'">
                    批量添加
                </span>
            </h1>
        </div>
        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform" method="GET" class="form-inline">
                    <input type="hidden" name="api_domain_id" value="{{request('api_domain_id')}}">
                    <div class="form-group select-input">
                        <div class="input-group" id="module_id">
                            <div class="input-group-addon">模块</div>
                            {{From::select(S('ApiModule')->getApiModel(request('api_domain_id')),request('api_module_id'),'class="form-control" name="api_module_id"','--请选择--')}}
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">url</div>
                            <input class="form-control" name="url" type="text" value="{{request('url')}}">
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
                <div class="alert alert-block alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    <i class="ace-icon fa fa-bullhorn red"> </i>
                    &nbsp;公告：状态禁用的接口<strong class="red">暂停数据同步</strong>！！！
                </div>
            </div>
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="row">
                    <div class="col-xs-12">
                        <form name="table-form">
                            <table id="simple-table" class="table  table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="center">
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace">
                                            <span class="lbl"></span>
                                        </label>
                                    </th>
                                    <th>模块/接口名称</th>
                                    <th>URL</th>
                                    <th>请求总量</th>
                                    <th>响应5秒+</th>
                                    <th>状态码异常</th>
                                    <th>报警阀值(10分钟)</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($lists as $info)
                                    <tr>
                                        <td class="center">
                                            <label class="pos-rel">
                                                <input type="checkbox" class="ace " value="{{$info->id}}" name="apiId">
                                                <span class="lbl"></span>
                                            </label>
                                        </td>
                                        <td>
                                            {{$info->btApiModule->title}}
                                            <br>
                                            {{$info->title}}
                                        </td>
                                        <td>
                                            {{$info->url}}
                                            <br>
                                            <span style="color:#bbbfc3;">更新时间:{{date('Y-m-d H:i:s',$info->sync_at)}}</span>
                                        </td>
                                        <td>
                                            今 {{$info->today_total ?? '--'}}
                                            <br>
                                            昨 {{$info->yesterday_total ?? '--'}}
                                        </td>
                                        <td>
                                            今 {{$info->today_5s_up>0 ?$info->today_5s_up:'--'}}
                                            <br>
                                            昨 {{$info->yesterday_5s_up>0 ?$info->yesterday_5s_up:'--'}}
                                        </td>
                                        <td>
                                            今 {{$info->today_code>0?$info->today_code:'--'}}
                                            <br>
                                            昨 {{$info->yesterday_code>0?$info->yesterday_code:'--'}}
                                        </td>
                                        <td>
                                            @if(!$info->response_time_alert) @else<span class="label label-warning ">

                                            @if($info->time_alert_type==1)
                                                    {{$info->time_alert_total}}次 超 {{$info->response_time_alert}} 秒
                                                @else
                                                    %{{$info->time_alert_total}} 超 {{$info->response_time_alert}} 秒
                                                @endif
                                        </span>
                                            @endif
                                            <br>
                                            <span class="label label-warning">
                                                 状态码:{{$info->code_alert}}
                                            </span>

                                        </td>
                                        <td>
                                        <span class="label @if($info->status==2)  label-danger @else label-success @endif arrowed-in arrowed-in-right">
                                              <a class="white"
                                                 href="status?id={{$info->id}}&api_domain_id={{$info->api_domain_id}}"
                                                 onclick="return confirm('确认反向操作吗？');return false;"> {{m('ApiDomain')->status_arr[$info->status]}} </a>
                                        </span>
                                        </td>
                                        <td>
                                            <a href="/{{$_m}}/apiResponseTime/index?api_url_id={{$info->id}}"
                                               target="_blank">统计</a>
                                            <a href="asyncResponseTime?id={{$info->id}}&api_domain_id={{$info->api_domain_id}}"
                                               onclick="return confirm('确认要同步{{$info->url}}下的数据吗？');return false;">同步</a>
                                            <br>
                                            <a href="javascript:void(0)" data-toggle="modal"
                                               onclick="javascript:$('#modal-body').load('info?id={{$info->id}}&api_domain_id={{$info->api_domain_id}} #follow-up');$('.modal-title').html('编辑')"
                                               data-target="#myModal">编辑</a>
                                            <a href="del?id={{$info->id}}&api_domain_id={{$info->api_domain_id}}"
                                               onclick="return confirm('确认操作吗？');return false;">删除</a>
                                            <a href="asyncResponseTime?id=0&api_domain_id={{$info->api_domain_id}}"
                                               onclick="return confirm('恭喜你发现一个隐藏的功能~~确认要同步这个项目的所有接口吗？');return false;">&nbsp;</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="1" class="ui-pg-button ui-corner-all" title="删除选中" id="del_grid-table">
                                        <div class="ui-pg-div">
                                            <span class="ui-icon ace-icon fa fa-trash-o red"></span>
                                        </div>
                                    </td>
                                    <td colspan="10">
                                        总数：{{$lists->total()}}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
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


    <script>

        function setMoudelId(api_domain_id) {
            var url = '/admin/apiModule/publicSystemApiModel?api_domain_id=' + api_domain_id;
            $.ajax({
                url: url,
                type: 'get',
                data: '',
                success: function (msg) {
                    var data = msg.data;
                    var str = '';
                    if (data.length == 0) {
                        alert('此域名无模块可选,请先添加');
                    } else {
                        for (var i = 0; i < data.length; i++) {
                            str += '<option value="' + data[i]['id'] + '">' + data[i]['title'] + '</option>';
                        }
                    }
                    $("#info_module_id").html(str);
                }
            });
        }

        //select/deselect all rows according to table header checkbox
        var active_class = 'active';
        $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function () {
            var th_checked = this.checked;//checkbox inside "TH" table header

            $(this).closest('table').find('tbody > tr').each(function () {
                var row = this;
                if (th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
            });
        });

        $(function () {
            $("#del_grid-table").on("click", function () {

                var apiIds = "";
                $("#simple-table input:checkbox[name=apiId]:checked").each(function (i) {

                    if (0 == i) {
                        apiIds = $(this).val();
                    } else {
                        apiIds += ("," + $(this).val());
                    }
                });
                if (apiIds != "") {
                    //询问是否要删除先中
                    var r = confirm("确认将选中的数据 [" + apiIds + "] 全部删除吗??");
                    if (r == true) {
                        var api_domain_id = "{{request('api_domain_id')}}";
                        $.ajax({
                            type: "post",
                            url: "batchDel",
                            data: {"api_domain_id": api_domain_id, "ids": apiIds},
                            dataType: "json",
                            success: function (result) {
                                if (result.code == 1) {
                                    location.reload();
                                } else {
                                    alert(result.msg);
                                }
                            },
                            error: function (result) {
                                console.log(result)
                            }
                        })
                    } else {

                    }
                }
            })
        })

    </script>
@endsection