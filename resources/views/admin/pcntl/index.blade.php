@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}}
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
                                <th>启动时间</th>
                                <th>运行时长</th>
                                <th>父进程</th>
                                <th>子进程</th>
                                <th>key</th>
                                <th>操作 <a href="del?child_pid=0" onclick="return confirm('确认操作吗？');return false;">暂停所有</a></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{$info['start_time']}}</td>
                                    <td>{{$info['all_time']}}</td>
                                    <td>{{$info['pid']}}</td>
                                    <td>{{$info['child_pid']}}</td>
                                    <td>{{config('common.pid_key')}}:{{$info['child_pid']}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="del?child_pid={{$info['child_pid']}}" onclick="return confirm('确认操作吗？');return false;">
                                                停止进程
                                            </a>
                                        </div>
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