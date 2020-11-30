@extends("admin.main")
@section("content")
    <div class="page-content">
        <div class="page-header">
            <h1>
                Dashboard

            </h1>
        </div><!-- /.page-header -->
                <div class="row">
                    <div class="operate panel panel-default">
                        <div class="panel-body ">
                            <form name="myform" method="GET" class="form-inline">
                                <div class="form-group select-input">
                                    <div class="input-group">
                                        <div class="input-group-addon">域名</div>
                                        {{From::select($domains,request('api_domain_id'),'class="form-control" name="api_domain_id"','--请选择--')}}
                                    </div>
                                    <div class="input-group">
                                        <input type="submit" value="搜索" class="btn btn-danger btn-sm">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="alert alert-block alert-success">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            <i class="ace-icon fa fa-check green"></i>
                            <strong class="green">
                                欢迎使用小手API性能监控系统，本系统基于elasticsearch中的NGIN请求日志,分析请求时间,访问量,错误码等。https://github.com/xs25cn/apim
                            </strong>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-xs-12">
                                <table id="simple-table" class="table  table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>排行</th>
                                        <th>今日最大响应时间</th>
                                        <th>所属项目</th>
                                        <th>所属接口</th>
                                        <th>域名地址</th>
                                        <th>Api地址</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($lists as $k=>$info)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{$info['max_time']}}</td>
                                            <td>{{$info['domain_title']}}</td>
                                            <td>{{$info['title']}}</td>
                                            <td>{{$info['domain']}}</td>
                                            <td>{{$info['url']}}</td>
                                            <td><a href="/admin/apiResponseTime/index?api_url_id={{$info['api_url_id']}}&view_type=1">查看今日数据</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /.span -->

                        </div><!-- /.row -->
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div>


    </div><!-- /.page-content -->




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










