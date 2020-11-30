@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}} {{$api_info->title}} [ {{$api_info->btApiDomain->domain.$api_info->url}} ]
                <span class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.reload()">
            刷新
        </span>
            </h1>
        </div>

        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform" method="GET" class="form-inline">

                    <div class="form-group select-input">
                        <input type="hidden" name="api_url_id" value="{{request('api_url_id')}}">

                        <div class="input-group" id="id_search_date">
                            <span>按时间查询：</span>
                            <span class="add-on input-group-addon">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar" style="font-size: 18px"></i>
                            </span>
                            <input type="text" readonly style="width:220px" name="reportrange" id="reportrange"
                                   class="form-control" value=""/>
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">分时日</div>
                            {{From::select(M('ApiResponseTime')->view_type_arr,request('view_type'),'class="form-control"  name="view_type"','--请选择--')}}
                        </div>

                        <div class="input-group">
                            <input type="submit" value="搜索" class="btn btn-danger btn-sm">
                            <span class="btn btn-info btn-sm"   onclick="window.location.href = '?api_url_id={{request('api_url_id')}}'">重置</span>
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
                    &nbsp;注：默认按10分钟分组,查询最近8小时数据！平均响应时长=总请求时长/请求总数！
                </div>
            </div>
            <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
            <div class="col-xs-12" id="main1" style="height: 250px;padding-bottom:30px;border-bottom: #fff3cd 1px solid"></div>
            <div class="col-xs-12" id="main2" style="height:250px;padding-top:30px;"></div>
            <div class="col-xs-12" id="main3" style="height:250px;padding-top:30px;"></div>
        </div>
        <!-- PAGE CONTENT BEGINS -->
        <div class="row" style="margin-top: 50px;">
            <div class="col-xs-12">
                <table id="simple-table" class="table  table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>时间</th>
                        <th>平均(秒)</th>
                        <th>最大(秒)</th>
                        <th>总数</th>
                        <th>1s以下</th>
                        <th>1-5s</th>
                        <th>5-10s</th>
                        <th>10s以上</th>
                        <th>超阀值{{$api_info->response_time_alert}}s</th>
                        <th>状态499</th>
                        <th>状态500</th>
                        <th>状态502</th>
                        <th>状态504</th>
                        <th>状态5xx</th>
                        @if(request('view_type')==1|| empty(request('view_type')))
                            <th>详情</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach (sort2array($lists->toArray(),'times','desc') as $info)
                        <tr @if($info['time_alert_total'])class="warning"@endif>
                            <td>{{$info['times']}}</td>
                            <td>{{$info['avg']}}</td>
                            <td>{{$info['max']}}</td>
                            <td>{{$info['total']}}</td>
                            <td>{{$info['total_1']}}</td>
                            <td>{{$info['total_2']}}</td>
                            <td @if($info['total_3']>0) class="red" @endif>{{$info['total_3']}}</td>
                            <td  @if($info['total_4']>0) class="red" @endif>{{$info['total_4']}}</td>
                            <td @if($info['time_alert_total']>0) class="red" @endif>{{$info['time_alert_total']}}</td>
                            <td @if($info['code_499']>0) class="red" @endif>{{$info['code_499']}}</td>
                            <td @if($info['code_500']>0) class="red" @endif>{{$info['code_500']}}</td>
                            <td @if($info['code_502']>0) class="red" @endif>{{$info['code_502']}}</td>
                            <td @if($info['code_504']>0) class="red" @endif>{{$info['code_504']}}</td>
                            <td @if($info['code_5xx']>0) class="red" @endif>{{$info['code_5xx']}}</td>

                            @if(request('view_type')==1 || empty(request('view_type')))
                                <td>
                                    <a href="javascript:void(0)" data-toggle="modal"
                                       onclick="$('#modal-body').load('info?domain={{$api_info->btApiDomain->domain}}&url={{$api_info->url}}&start_time={{strtotime(date('Y').'-'.$info['times'].':00')}} #follow-up');$('.modal-title').html('正在抓取10分钟请求记录，请稍后... (按响应时间大小排序，只取前20条)')"
                                       data-target="#myModal">请求记录</a>
                                </td>@endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div><!-- /.span -->

        </div><!-- /.row -->
        <!-- PAGE CONTENT ENDS -->
    </div>

    <!-- Modal START -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        <!--modal,弹出层父级,fade使弹出层有一个运动过程-->
        <div class="modal-dialog" style="width:1200px">
            <!--modal-dialog,弹出层-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span  aria-hidden="true">&times;</span>
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
    <!-- ECharts单文件引入 -->
    <script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
    <script type="text/javascript">
        // 路径配置
        require.config({
            paths: {
                echarts: 'http://echarts.baidu.com/build/dist'
            }
        });
        // 使用柱状图就加载bar模块，按需加载
        require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line'
            ],
            drawEcharts
        );

        //绘制图形
        function drawEcharts(echarts) {
            // 基于准备好的dom，初始化echarts图表
            var myChart1 = echarts.init(document.getElementById('main1'));
            var myChart2 = echarts.init(document.getElementById('main2'));
            var myChart3 = echarts.init(document.getElementById('main3'));
            var option1 = {
                title: {
                    y: 3,
                    text: '响应时长统计',
                    subtext: '',
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['平均时长','最大时长','最小时长'],
                    selected: {
                        '平均时长': true,
                        '最大时长': true,
                        '最小时长': true
                    },

                },

                toolbox: {
                    show: true,
                    feature: {
                        dataZoom: {
                            show: true,
                            title: {
                                dataZoom: '区域缩放',
                                dataZoomReset: '区域缩放后退'
                            }
                        },
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                    },
                    width:50,

                },
                dataZoom: [
                    {
                        type: 'inside'
                    }
                ],
                xAxis: [
                    {
                        type: 'category', //x轴为类目类型
                        axisLabel: {
                            show: true,
                            interval: 0,
                            rotate: 45
                        },
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'times')) !!} @else[]@endif,

                    }],
                yAxis: [
                    {
                        type: 'value'  //y轴为值类型
                    }
                ],
                series: [
                    {
                        name: '平均时长',
                        type: 'line',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'avg')) !!} @else[]@endif,
                        markPoint: {
                            data: [
                                {type: 'max', name: '最高值'},
                                {type: 'min', name: '最低值'}
                            ]
                        }
                    },
                    {
                        name: '最大时长',
                        type: 'line',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'max')) !!} @else[]@endif,
                        markPoint: {
                            data: [
                                {type: 'max', name: '最高值'},
                                {type: 'min', name: '最低值'}
                            ]
                        }
                    },
                    {
                        name: '最小时长',
                        type: 'line',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'min')) !!} @else[]@endif,
                        markPoint: {
                            data: [
                                {type: 'max', name: '最高值'},
                                {type: 'min', name: '最低值'}
                            ]
                        }
                    },

                ]
            }
            var option2 = {
                title: {
                    y: 3,
                    text: '请求量统计',
                    subtext: '响应时长内的请求量，总量默认不显示',
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    y: 30,
                    data: ['总量', '1秒以下', '1-5秒', '5-10秒', '10秒以上'],
                    selected: {
                        '总量': true,
                        '1秒以下': true,
                    }
                },

                toolbox: {
                    show: true,
                    feature: {
                        dataZoom: {
                            show: true,
                            title: {
                                dataZoom: '区域缩放',
                                dataZoomReset: '区域缩放后退'
                            }
                        },
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                    },
                },
                axisPointer: {
                    link: {xAxisIndex: 'all'}
                },
                dataZoom: [
                    {
                        type: 'slider',
                        xAxisIndex: 0,
                        filterMode: 'empty'
                    },
                    {
                        type: 'slider',
                        yAxisIndex: 0,
                        filterMode: 'empty'
                    },
                    {
                        type: 'inside',
                        xAxisIndex: 0,
                        filterMode: 'empty'
                    },
                    {
                        type: 'inside',
                        yAxisIndex: 0,
                        filterMode: 'empty'
                    }
                ],

                xAxis: [
                    {
                        type: 'category', //x轴为类目类型
                        axisLabel: {
                            show: true,
                            interval: 0,
                            rotate: 45
                        },
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'times')) !!} @else[]@endif,

                    }],
                yAxis: [
                    {
                        type: 'value'  //y轴为值类型
                    }
                ],
                series: [
                    {
                        name: '总量',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'total')) !!} @else[]@endif,
                    },
                    {
                        name: '1秒以下',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'total_1')) !!} @else[]@endif,
                    },
                    {
                        name: '1-5秒',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'total_2')) !!} @else[]@endif,
                    },
                    {
                        name: '5-10秒',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'total_3')) !!} @else[]@endif,
                    },
                    {
                        name: '10秒以上',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'total_4')) !!} @else[]@endif,
                    }
                ]
            };

            var option3 = {
                title: {
                    y:30,
                    text: '状态码',
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    y:30,
                    data: ['200','499', '500','502','504','5xx'],
                    selected: {
                        '200': true,
                    }
                },

                toolbox: {
                    y:30,
                    show: true,
                    feature: {
                        dataZoom: {
                            show: true,
                            title: {
                                dataZoom: '区域缩放',
                                dataZoomReset: '区域缩放后退'
                            }
                        },
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                    },

                },
                axisPointer: {
                    link: {xAxisIndex: 'all'}
                },
                dataZoom: [
                    {
                        type: 'slider',
                        xAxisIndex: 0,
                        filterMode: 'empty'
                    },
                    {
                        type: 'slider',
                        yAxisIndex: 0,
                        filterMode: 'empty'
                    },
                    {
                        type: 'inside',
                        xAxisIndex: 0,
                        filterMode: 'empty'
                    },
                    {
                        type: 'inside',
                        yAxisIndex: 0,
                        filterMode: 'empty'
                    }
                ],

                xAxis: [
                    {
                        type: 'category', //x轴为类目类型
                        axisLabel: {
                            show: true,
                            interval: 0,
                            rotate: 45
                        },
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'times')) !!} @else[]@endif,

                    }],
                yAxis: [
                    {
                        type: 'value'  //y轴为值类型
                    }
                ],
                series: [
                    {
                        name: '200',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'code_200')) !!} @else[]@endif,
                    },

                    {
                        name: '499',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'code_499')) !!} @else[]@endif,
                    },
                    {
                        name: '500',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'code_500')) !!} @else[]@endif,
                    },
                    {
                        name: '502',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'code_502')) !!} @else[]@endif,
                    },
                    {
                        name: '504',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'code_504')) !!} @else[]@endif,
                    },
                    {
                        name: '5xx',
                        type: 'bar',
                        smooth: true,
                        data: @if(isset($lists)){!! json_encode(array_column($lists->toArray(),'code_5xx')) !!} @else[]@endif,
                    }
                ]
            };

            myChart1.setTheme("macarons");
            myChart2.setTheme("macarons");
            myChart3.setTheme("macarons");

            // 为echarts对象加载数据
            myChart1.setOption(option1);
            myChart2.setOption(option2);
            myChart3.setOption(option3);

            //联动配置
            myChart1.connect([myChart2,myChart3]);
            myChart2.connect([myChart1,myChart3]);
            myChart3.connect([myChart1,myChart2]);

            var zoomSize = 6;
            myChart1.on('click', function (params) {
                console.log(params);
                /*myChart.dispatchAction({
                    type: 'dataZoom',
                    startValue: dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)],
                    endValue: dataAxis[Math.min(params.dataIndex + zoomSize / 2, data.length - 1)]
                });*/
            });
        }

        // 初始化 日期范围选择器
        var start = '{!! $start_time !!}';
        var end = '{!! $end_time !!}';
        initDateRangePicker(start, end, 'reportrange');
    </script>
@endsection