@extends("admin.main")
@section("content")

    <div class="page-content">


        <div class="page-header">
            <h1>
                {{isset($info->id)?'详情':'添加'}}
                <button class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'index'">
                    返回列表
            </h1>

        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                <div class="col-xs-12 col-sm-6 widget-container-col ui-sortable" style="min-height: 263px;">
                    <div class="widget-box widget-color-blue ui-sortable-handle" style="opacity: 1;">
                        @if(isset($info->id))
                            <div class="widget-header">
                                <h5 class="widget-title bigger lighter">
                                    <i class="ace-icon fa fa-table"></i>
                                    本次操作记录({{$info->id ?? ''}})
                                </h5>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tbody>
                                        <tr>
                                            <td class="">菜单名</td>
                                            <td>
                                                {{$info->name ?? ''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">用户</td>
                                            <td>
                                                <a href="#">{{M('AdminUser')->where('id',$info->admin_id)->value('realname')}}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">时间</td>
                                            <td>
                                                {{$info->created_at ?? ''}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="">地点</td>
                                            <td>
                                                {{$info->ip ?? ''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">请求地址</td>
                                            <td>
                                                {{$info->c ?? ''}}/{{$info->a ?? ''}}{{!empty($info->querystring)?'?'.$info->querystring:''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">POST数据</td>
                                            <td>
                                                @if(isset($info->data))
                                                    {!! arr2str($info->data) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                <div class="col-xs-12 col-sm-6 widget-container-col ui-sortable" style="min-height: 263px;">
                    <div class="widget-box widget-color-grey ui-sortable-handle" style="opacity: 1;">
                        <div class="widget-header">
                            <h5 class="widget-title bigger lighter">
                                <i class="ace-icon fa fa-table"></i>
                                原始记录
                            </h5>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>

                                    @if(isset($last_info->id))
                                        <tr>
                                            <td class="">菜单名</td>
                                            <td>
                                                {{$last_info->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">用户</td>
                                            <td>
                                                <a href="#">{{M('AdminUser')->where('id',$last_info->admin_id)->value('realname')}}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">时间</td>
                                            <td>
                                                {{$last_info->created_at}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="">地点</td>
                                            <td>
                                                {{$last_info->ip}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">请求地址</td>
                                            <td>
                                                {{$last_info->c}}/{{$last_info->a}}{{!empty($last_info->querystring)?'?'.$last_info->querystring:''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">POST数据</td>
                                            <td>
                                                @if($last_info->data)
                                                    {!! arr2str($last_info->data) !!}
                                                @endif
                                            </td>
                                        </tr>

                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>



            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@endsection

