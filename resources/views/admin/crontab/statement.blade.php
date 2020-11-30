@extends("admin.main")
@section("content")

    <div class="page-content">

        <div class="row">

            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span class="red">*</span> 名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="str" value="" class="col-xs-10 col-sm-8" required>
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
                        <div class="col-md-offset-4 col-md-9">
                            <button class="btn btn-info" type="submit" id="sub_btn">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                提交
                            </button>

                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

