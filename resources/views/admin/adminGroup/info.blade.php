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
                <form id="myform" name="myform" class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif
                    <input type="hidden" name="menus" value="{{$info->menus ?? ''}}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{$info->name ?? ''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 描述 </label>
                        <div class="col-sm-9">
                            <input type="text" name="description" value="{{$info->description ?? ''}}"   class="col-xs-10 col-sm-8">
                        </div>
                </form>

                <div class="form-group">
                    <label class="col-sm-3  control-label no-padding-right">权限</label>
                    <div class="col-sm-6">

                        <link rel="stylesheet" href="/assets/js/ztree/zTreeStyle.css" type="text/css">
                        <script type="text/javascript" src="/assets/js/ztree/jquery.ztree.core.min.js"></script>
                        <script type="text/javascript" src="/assets/js/ztree/jquery.ztree.excheck.min.js"></script>
                        <script type="text/javascript">
                            /**-
                             * var zNodes = [
                                {id: 11, pId: 1, name: "随意勾选 1-1", open: true},
                                {id: 111, pId: 11, name: "随意勾选 1-1-1"},
                                {id: 2, pId: 0, name: "随意勾选 2", checked: true, open: true},
                                {id: 21, pId: 2, name: "随意勾选 2-1"},
                            ];
                             **/
                            var zNodes={!! json_encode($menus) !!};

                            var setting = {
                                check: {
                                    enable: true,
                                    chkStyle: "checkbox",
                                    chkboxType: {
                                        "Y": "s",
                                        "N": "s"
                                    }
                                },
                                data: {
                                    simpleData: {
                                        enable: true
                                    }
                                }
                            };
                            $(document).ready(function () {
                                $.fn.zTree.init($("#tree"), setting, zNodes);
                                $("#dosubmit").click(function(){
                                    var menu = $.fn.zTree.getZTreeObj("tree").getCheckedNodes(true);
                                    var menus = '';
                                    for (var i = 0; i < menu.length; i++) {
                                        menus += menu[i].id + ',';
                                    }
                                    menus = menus.replace(/(,$)/,"");
                                    console.log(menus);
                                    $("input[name='menus']").val(menus);
                                    myform.submit();
                                })
                            });

                        </script>
                        <style>
                            ul.ztree {margin-top: 10px;border: 1px solid #617775;}


                        </style>
                        <ul id="tree" class="ztree" style="font-size: 20px;"></ul>


                    </div>

                </div>


                @if (count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit" id="dosubmit">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            提交
                        </button>
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Reset
                        </button>
                    </div>
                </div>


            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>






    <!-- page specific plugin scripts -->




@endsection

