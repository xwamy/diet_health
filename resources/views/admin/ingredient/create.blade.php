@extends('admin.layouts.admin')

@section('admin-css')
    <link href="{{ asset('asset_admin/assets/plugins/parsley/src/parsley.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" />
@endsection

@section('admin-content')
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="javascript:;">主页</a></li>
            <li><a href="javascript:;">食材列表</a></li>
            <li class="active">新增</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">新增食材</h1>
        <!-- end page-header -->

        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                        <h4 class="panel-title">表单</h4>
                    </div>
                    @if(count($errors)>0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body panel-form">
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="{{ url('admin/ingredient') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">食材名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="name" placeholder="食材名称" data-parsley-required="true" data-parsley-required-message="请输入食材名称" value="{{ old('name') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="url">排序（升序） * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="sort" placeholder="排序" data-parsley-required="true" data-parsley-type="integer" data-parsley-required-message="请输入排序" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="role">营养类型 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#role_error"
                                            data-parsley-required-message="营养类型"
                                            name="nutritive_type">
                                        <option value="">-- 请选择 --</option>
                                        @foreach($nutritive_type as $key=>$value)
                                            <option value="{{ $value['id'] }}">{{ $value['display_name'] }}</option>
                                        @endforeach
                                    </select>
                                    <p id="role_error"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="description">营养名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <p>
                                        <a href="javascript:checkAll();" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-check"></i> 全选</a>
                                        <a href="javascript:checkReverse();" class="btn btn-sm btn-inverse m-r-5"><i class="fa fa-magic"></i> 反选</a>
                                    </p>
                                    <hr>
                                    <div class="col-md-10 col-sm-10" id="nutritive_lists">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-6 col-sm-6">
                                    <button type="submit" class="btn btn-primary">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-6 -->
        </div>
        <!-- end row -->
    </div>
@endsection

@section('admin-js')
    <script src="{{ asset('asset_admin/assets/plugins/parsley/dist/parsley.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/switchery/switchery.min.js') }}"></script>
    <script>

        $('.selectpicker').selectpicker('render');

        $("select[name='nutritive_type']").change(function () {
            $.ajax({
                url:'{{ url('admin/nutritive/ajaxIndex') }}',
                type:'GET', //GET
                data:{'_token':'{{csrf_token()}}',type:$("select[name='nutritive_type']").val()},
                dataType:'json',
                success:function(data){
                    var nutritive_lists = data.data;
                    var html ="";
                    $.each(nutritive_lists, function(key, val) {
                        html +='<input type="checkbox" name="nutritive[]" data-render="switchery" data-theme="purple" value="'+val.id+'"/> '+val.name;
                    });
                    $("#nutritive_lists").html(html);
                    renderSwitcher();
                }
            });
        });

        function renderSwitcher(){
            if ($('[data-render=switchery]').length !== 0) {
                $('[data-render=switchery]').each(function() {
                    var themeColor = '#00acac';
                    if ($(this).attr('data-theme')) {
                        switch ($(this).attr('data-theme')) {
                            case 'red': themeColor = '#ff5b57'; break;
                            case 'blue': themeColor = '#348fe2'; break;
                            case 'purple': themeColor = '#727cb6'; break;
                            case 'orange': themeColor = '#f59c1a'; break;
                            case 'black': themeColor = '#2d353c'; break;
                        }
                    }
                    var option = {};
                    option.color = themeColor;
                    option.secondaryColor = ($(this).attr('data-secondary-color')) ? $(this).attr('data-secondary-color') : '#dfdfdf';
                    option.className = ($(this).attr('data-classname')) ? $(this).attr('data-classname') : 'switchery';
                    option.disabled = ($(this).attr('data-disabled')) ? true : false;
                    option.disabledOpacity = ($(this).attr('data-disabled-opacity')) ? parseFloat($(this).attr('data-disabled-opacity')) : 0.5;
                    option.speed = ($(this).attr('data-speed')) ? $(this).attr('data-speed') : '0.3s';
                    var switchery = new Switchery(this, option);
                });
            }
        }
        //全选
        function checkAll(){
            $("input[type=checkbox]").each(function() {
                if(!this.checked) this.click();
            });
        }
        //反选
        function checkReverse() {
            $("input[type=checkbox]").each(function() {
                this.click();
            });
        }
    </script>
@endsection