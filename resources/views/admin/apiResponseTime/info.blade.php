@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}} {{M('ApiDomain')->where('id',request('api_domain_id'))->value('title')}}&nbsp;&nbsp;
            </h1>
        </div>
        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform" method="GET" class="form-inline">
                    <input type="hidden" name="api_domain_id" value="{{request('api_domain_id')}}">
                    <div class="form-group select-input">
                        <div class="input-group">
                            <div class="input-group-addon">开始时间</div>
                            <input class="form-control layui-input" id="start_time" name="start_time" type="text" value="{{$start_time}}">
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">域名</div>
                            <input class="form-control" name="domain" type="text" value="{{request('domain')}}">
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">地址</div>
                            <input class="form-control" size="50px" name="url" type="text" value="{{request('url')}}">
                        </div>

                        <div class="input-group">
                            <input type="submit" value="搜索" class="btn btn-danger btn-sm">
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="row"  id="follow-up">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="alert alert-block alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    <i class="ace-icon fa fa-check red"></i>
                    <strong class="red">
                        打开浏览器console，点击序号可查看所有参数~,这里只帮你查询10分内数据记录，按响应时间从大到小排序展示20条。
                    </strong>
                </div>
            </div>

            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="row">
                    <div class="col-sm-12">
                        <table id="simple-table" class="table  table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>时长</th>
                                <th>方式</th>
                                <th>地址</th>
                                <th>状态码</th>
                                <th>请求时间</th>
                                <th>ip地址</th>
                                <th class="col-sm-4">参数(GET/POST)</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($lists as $k=>$info)
                                <tr>
                                    <td onclick="javascript:console.log({{json_encode($info)}});" style="cursor:pointer;color:blue">{{$k+1}}</td>
                                    <td >{{$info['request_time']}}</td>
                                    <td>{{$info['method']}}</td>
                                    <td>{{$info['request']}}</td>
                                    <td>{{$info['status']}}</td>
                                    <td>{{$info['timestamp']}}</td>
                                    <td>{{$info['remote_addr']}}<br>{{$info['ip_address']}}</td>
                                    <td>
                                        @if($info['request_body'])<?php dump($info['request_body']);?>@endif
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

