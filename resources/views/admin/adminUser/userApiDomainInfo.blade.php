@extends("admin.main")
@section("content")


    <div class="page-content">


        <div class="page-header">
            <h1>
                {{$menu_info->name ?? ''}}
                <button class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'index'">
                    返回列表
            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="userApiDomainEdit" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="admin_user_id" value="{{$user_info->id}}"/>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-top"> 账号</label>
                        <div class="inline">{{$user_info->name}}</div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-top"> 姓名</label>
                        <div class="inline">{{$user_info->realname}}</div>
                    </div>

                    <div class="space-6"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-top">分配项目 </label>

                        <div class="col-sm-8">
                            <h3>注:左边未分配,右边已分配</h3>
                            <select multiple="multiple" size="10" name="api_domain_ids[]">
                                @foreach($userDomain as $v)
                                    <option value="{{$v['id']}}" selected="selected" >【{{$v['domain']}}】{{$v['title']}}</option>
                                @endforeach

                                @foreach($userDomainNo as $v)
                                    <option value="{{$v['id']}}">【{{$v['domain']}}】{{$v['title']}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                提交
                            </button>

                        </div>
                    </div>

                </form>

                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function ($) {
            var demo1 = $('select[name="api_domain_ids[]"]').bootstrapDualListbox({infoTextFiltered: '<span class="label label-purple label-lg">Filtered</span>'});
            var container1 = demo1.bootstrapDualListbox('getContainer');
            container1.find('.btn').addClass('btn-white btn-info btn-bold');

            //select2
            $('.select2').css('width', '200px').select2({allowClear: true})
            $('#select2-multiple-style .btn').on('click', function (e) {
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2)
                    $('.select2').addClass('tag-input-style');
                else
                    $('.select2').removeClass('tag-input-style');
            });
            //in ajax mode, remove remaining elements before leaving page
            $(document).one('ajaxloadstart.page', function (e) {
                $('select[name="api_domain_ids[]"]').bootstrapDualListbox('destroy');

            });

        });
    </script>

@endsection

