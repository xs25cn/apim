@extends("admin.main")
@section("content")
    <style>
        .console, .pro {
            height: 500px;
            color: #fff;
        }
        ul li {
            list-style-type: none;
        }
        #scroll{
            margin-bottom:60px;
            color: #fff;
            background-color: #000000;
            font-size: 14px;
            height: 480px;
            overflow:auto;
        }
    </style>
    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}}
                <span class="btn btn-sm btn-warning pull-right" id="stop">暂停</span>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12" style="background-color: #000">
                <!-- PAGE CONTENT BEGINS -->
                <div class="row">
                    <div class="col-xs-7 console " id="scroll">

                    </div>

                    <div class="col-xs-5 pro">
                        <table id="simple-table" class="table">
                            <thead>
                            <tr style="background-color: #000;color: #fff;background-image: none">
                                <td>子进程 ( <span id="proc_total">0</span> ) <span id="kill">kill</span></td>
                                <td>启动时间</td>
                                <td>运行时长 </td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.span -->

                </div><!-- /.row -->
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div>

    </div>
    <script>
        $(function(){
            var log_status = 1;//开始，2暂停
            var t1 = window.setInterval(refreshCount, 1000);
            let t2 = window.setInterval(getLog,500);
            $("#kill").on("click",function(){
                $.ajax({
                    type: "get",
                    url: '/admin/pcntl/del?child_pid=0&'+Math.random(),
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                    }
                });
            });
            $("#stop").on("click", function () {
                if (log_status == 1) {
                    log_status = 2;
                    window.clearInterval(t1);
                    window.clearInterval(t2);
                    $("#stop").html('开始');
                } else {
                    log_status = 1;
                    t1 = window.setInterval(refreshCount, 1000);
                    t2 = window.setInterval(getLog, 500);
                    $("#stop").html('暂停');
                }
            });
        })



        function refreshCount() {
            $.ajax({
                type: "get",
                url: '/admin/pcntl/index?'+Math.random(),
                dataType: "json",
                success: function (data, textStatus, jqXHR) {
                    $("#proc_total").html(data.length);
                    var html = '';
                    $.each(data, function (commentIndex, comment) {
                        html += '<tr><td>' + comment['child_pid'] + '<td>' + comment['start_time'] + '</td><td>' + comment['all_time'] + '</td></tr>';
                    });
                    $("#simple-table tbody").html(html);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log("请求失败！");
                }
            });
        }

        function getLog() {
            $.ajax({
                type: "get",
                url: '?get_log=1'+Math.random(),
                dataType: "json",
                success: function (data) {
                    let str = data.msg;
                    if (str!=null){
                        $("#scroll").append(str+"<br>");
                        $("#scroll").scrollTop($("#scroll")[0].scrollHeight);
                    }
                }
            });
        }

    </script>

@endsection