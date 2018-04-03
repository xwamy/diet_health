@extends('admin.layouts.admin')

@section('admin-css')
    <link href="{{ asset('asset_admin/assets/xin/upload.css') }}" rel="stylesheet" />

    <link href="{{ asset('asset_admin/assets/plugins/parsley/src/parsley.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/ionRangeSlider/css/ion.rangeSlider.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/ionRangeSlider/css/ion.rangeSlider.skinNice.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/jquery-file-upload/css/jquery.fileupload.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css') }}" rel="stylesheet" />
@endsection

@section('admin-content')
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="javascript:;">主页</a></li>
            <li><a href="javascript:;">食谱列表</a></li>
            <li class="active">编辑</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">编辑食谱</h1>
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
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="{{ url('admin/cookbook/'.$data['id']) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">食谱名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="name" placeholder="食谱名称" data-parsley-required="true" data-parsley-required-message="请输入食谱名称" value="{{ $data['name'] }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">排序（升序） * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="sort" placeholder="排序" data-parsley-required="true" data-parsley-type="integer" data-parsley-required-message="请输入排序" value="{{ $data['sort'] }}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">缩略图 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <div class="">
                                        <input id="uploadImg" type="file" name="thumb_upload" data-url="{{url('upload/uploadfile/jqueryfile')}}?_token={{csrf_token()}}" multiple style="display: none" />
                                        <div id="chooseFile">选择文件</div>
                                        <div id="uploadFile">开始上传</div>
                                        <div id="rechooseFile">重新选择</div>
                                        <div style="clear: both;"></div>
                                    </div>
                                    <div>
                                        <img id="image" src="{{ $data['thumb'] }}" style="display: block;">
                                    </div>
                                    <div id="progress">
                                        <div class="bar" style="width: 0%;"></div>
                                    </div>
                                    <input id="uploadImg_path" type="hidden" name="thumb" value="{{ $data['thumb'] }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">简介 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea class="form-control" placeholder="请输入简介..." data-parsley-required="true"  data-parsley-required-message="请输入简介"  name="description" rows="5">{{ $data['description'] }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">烹饪时长 * :</label>
                                <div class="col-md-2 col-sm-2">
                                    <input class="form-control" type="text" name="timer" placeholder="烹饪时长" data-parsley-required="true" data-parsley-type="integer" data-parsley-required-message="请输入烹饪时长" value="{{ $data['timer'] }}" />单位：分钟
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">可食用人数 * :</label>
                                <div class="col-md-2 col-sm-2">
                                    <input class="form-control" type="number" min="1" name="people_num" placeholder="可食用人数" data-parsley-required="true" data-parsley-type="number" data-parsley-required-message="请输入可食用人数" value="{{ $data['people_num'] }}"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="role">烹饪方式 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#cooking_way_error"
                                            data-parsley-required-message="请选择烹饪方式"
                                            name="cooking_way_id">
                                        <option value="">-- 请选择 --</option>

                                    </select>
                                    <p id="cooking_way_error"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="role">口味 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#taste_error"
                                            data-parsley-required-message="请选择口味"
                                            name="taste_id">
                                        <option value="">-- 请选择 --</option>

                                    </select>
                                    <p id="taste_error"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="role">营养类型 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#nutritive_error"
                                            data-parsley-required-message="请选择营养类型"
                                            name="nutritive_type">
                                        <option value="">-- 请选择 --</option>
                                        @foreach($nutritive_type as $key=>$value)
                                            <option value="{{ $value['id'] }}" @if($value['id'] == $nutritive->type) selected="selected" @endif>{{ $value['display_name'] }}</option>
                                        @endforeach
                                    </select>
                                    <p id="nutritive_error"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="description">营养名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <div class="col-md-12 col-sm-12" id="nutritive_lists">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">难度系数 * ：</label>
                                <div class="col-md-3">
                                    <input type="text" id="difficulty" name="difficulty" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">选择食材 * ：</label>
                                <div class="col-md-8">
                                    <div id="add_ingredient" class="btn add_ingredient">新增</div>
                                    <table class="table table-striped table-hover" id="reportTable">

                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">菜谱做法 * ：</label>
                                <div class="col-md-8">
                                    <textarea class="ckeditor" id="editor1" name="practice" rows="20">{{ $data['practice'] }}</textarea> <!--data-parsley-required="true"  data-parsley-required-message="请输入菜谱做法"-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">制作技巧 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea class="form-control" name="skill" placeholder="请输入制作技巧..."  data-parsley-required="true"  data-parsley-required-message="请输入制作技巧" rows="5">{{ $data['skill'] }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="role">食物类型 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#food_type_error"
                                            data-parsley-required-message="请选择食物类型"
                                            name="food_type">
                                        <option value="">-- 请选择 --</option>
                                        @foreach($food_type as $key=>$value)
                                            <option value="{{ $value['id'] }}" @if($value['id'] == $data['food_type']) selected="selected" @endif>{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <p id="food_type_error"></p>
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
    <script src="{{ asset('asset_admin/assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/jquery-file-upload/js/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/jquery-file-upload/js/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/js/jQuery.CascadingSelect.js') }}"></script>


    <script>
        $(function(){
            //点击新增一行食材
            $('#add_ingredient').click(function(){
                var num = $('#reportTable tr').length;
                var html_class = "ingredienttype_td_"+num;
                var html = '<tr class="add_list">'
                    +'<td class="'+ html_class +'">'
                    +'</td><td width="20%">'
                    +'<select class="form-control" name="main[]"><option value="1">主料</option><option value="2">辅料</option></select>'
                    +'</td><td width="10%">'
                    +'<div class="btn del_ingredient">删除</div></td></tr>';
                $('#reportTable').append(html);

                $("."+html_class).CascadingSelect({data:"0"}); //无限极下拉框
                $(".del_ingredient").click(function (){      //删除一行
                    $(this).parents('tr').remove();
                });
            });

            //上传图片
            $("#chooseFile").on("click", function() {
                $("#uploadImg").click();
            });
            $('#uploadImg').fileupload({
                Type : 'POST',//请求方式 ，可以选择POST，PUT或者PATCH,默认POST
                dataType : 'json',//服务器返回的数据类型
                autoUpload : false,
                acceptFileTypes : /(gif|jpe?g|png)$/i,//验证图片格式
                maxNumberOfFiles : 1,//最大上传文件数目
                maxFileSize : 2000000, // 文件上限1MB
                minFileSize : 100,//文件下限  100b
                messages : {//文件错误信息
                    acceptFileTypes : '文件类型不匹配',
                    maxFileSize : '文件过大',
                    minFileSize : '文件过小'
                }
            }).on("fileuploadadd", function(e, data) {
                //图片添加完成后触发的事件
                validate(data.files[0])//这里也可以手动来验证文件格式和大小
                //隐藏或显示页面元素
                $('#progress .bar').css(
                    'width', '0%'
                );
                $('#progress').hide();
                $("#chooseFile").hide();
                $("#image").show();
                $("#uploadFile").show();
                $("#rechooseFile").show();

                //获取图片路径并显示
                var url = getUrl(data.files[0]);
                $("#image").attr("src", url);

                //绑定开始上传事件
                $('#uploadFile').click(function() {
                    $("#uploadFile").hide();
                    jqXHR = data.submit();
                    //解绑，防止重复执行
                    $("#uploadFile").off("click");
                })

                //绑定点击重选事件
                $("#rechooseFile").click(function(){
                    $("#uploadImg").click();
                    //解绑，防止重复执行
                    $("#rechooseFile").off("click");
                })
            })
            //当一个单独的文件处理队列结束触发(验证文件格式和大小)
                .on("fileuploadprocessalways", function(e, data) {
                    //获取文件
                    file = data.files[0];
                    //获取错误信息
                    if (file.error) {
                        console.log(file.error);
                        $("#uploadFile").hide();
                    }
                })
                //显示上传进度条
                .on("fileuploadprogressall", function(e, data) {
                    $('#progress').show();
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress').css(
                        'width','40%'
                    );
                    $('#progress .bar').css(
                        'width',progress + '%'
                    );
                })
                //上传请求失败时触发的回调函数
                .on("fileuploadfail", function(e, data) {
                    if (data.errorThrown=='abort') {
                        NUI.showMsg('上传取消！', 'success',3);
                    }else{
                        NUI.showMsg('上传失败，请稍后重试！', 'error',3);
                    }
                })
                //上传请求成功时触发的回调函数
                .on("fileuploaddone", function(e,data) {
                    //上传成功并将地址写入到input框
                    $("#progress").css('display',"none");
                    alert(data.result.message);
                    $("#uploadImg_path").val(data.result.file_path);
                })
                //上传请求结束后，不管成功，错误或者中止都会被触发
                .on("fileuploadalways", function(e, data) {
                    console.log(data);
                })
            //手动验证
            function validate(file) {
                //获取文件名称
                var fileName = file.name;
                //验证图片格式
                if (!/.(gif|jpg|jpeg|png)$/.test(fileName)) {
                    console.log("文件格式不正确");
                    return true;
                }
                //验证excell表格式
                /*  if(!/.(xls|xlsx)$/.test(fileName)){
                    alert("文件格式不正确");
                    return true;
                 } */

                //获取文件大小
                var fileSize = file.size;
                if (fileSize > 1024 * 1024 *2) {
                    alert("文件不得大于2M")
                    return true;
                }
                return false;
            }

            //获取图片地址
            function getUrl(file) {
                var url = null;
                if (window.createObjectURL != undefined) {
                    url = window.createObjectURL(file);
                } else if (window.URL != undefined) {
                    url = window.URL.createObjectURL(file);
                } else if (window.webkitURL != undefined) {
                    url = window.webkitURL.createObjectURL(file);
                }
                return url;
            }

            //获取烹饪方式
            $.ajax({
                url:'{{ url('admin/cookingway/ajaxIndex') }}',
                type:'GET', //GET
                data:{'_token':'{{csrf_token()}}'},
                dataType:'json',
                success:function(data){
                    var cooking_way_id = {{$data['cooking_way_id']}};
                    var cookingway_lists = data.data;
                    var html ="";
                    $.each(cookingway_lists, function(key, val) {
                        html +='<option value="'+val.id+'"';
                        if(cooking_way_id ==val.id){
                            html += 'selected="selected"';
                        }
                        html += '>'+val.name+'</option>';
                    });

                    $("select[name='cooking_way_id']").append(html);
                }
            });
            //获取口味
            $.ajax({
                url:'{{ url('admin/taste/ajaxIndex') }}',
                type:'GET', //GET
                data:{'_token':'{{csrf_token()}}'},
                dataType:'json',
                success:function(data){
                    var taste_id = {{$data['taste_id']}};
                    var taste_lists = data.data;
                    var html ="";
                    $.each(taste_lists, function(key, val) {
                        html +='<option value="'+val.id+'"';
                        if(taste_id ==val.id){
                            html += 'selected="selected"';
                        }
                        html += '>'+val.name+'</option>';
                    });
                    $("select[name='taste_id']").append(html);
                }
            });
            //等待2秒后ajax获取完数据再将select更改为可搜索形式
            setTimeout(function(){
                $('.selectpicker').selectpicker('render');
            },2000);
            //难度控制
            $("#difficulty").ionRangeSlider({
                min: 1,
                max: 10,
                from:{{$data['difficulty']}}
            });
            //富文本编辑框
            CKEDITOR.replace('editor1', {
                language: 'zh-cn',
                height: 450,
                imageUpload: true,
                //filebrowserBrowseUrl: '{{url('upload/uploadfile')}}', //查看文件
                filebrowserUploadUrl: '{{url('upload/uploadfile')}}?_token={{csrf_token()}}'//上传文件
            });
        });
        //营养价值类型发生改变后重新获取该类型下的营养价值
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
                        html +='<label class="radio-inline" style="line-height: 21px;"> <input type="radio" name="nutritive_id" value="'+val.id+'"';

                        var nutritive_id = {{$data['nutritive_id']}};
                        if(nutritive_id ==val.id){
                            html += 'checked="checked"';
                        }
                        html += '/>'+val.name+'</label>';
                    });
                    $("#nutritive_lists").html(html);
                }
            });
        });
        //模拟选中事件
        $("select[name='nutritive_type']").trigger("change");
    </script>
@endsection