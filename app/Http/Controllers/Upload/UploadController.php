<?php

namespace App\Http\Controllers\Upload;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
class UploadController extends Controller
{
    public $file_servers = "http://img.diet_health.cc/";
    public function updateFile($type='editor1',Request $request)
    {
        if ($request->isMethod('post')) {
            if($type=='editor1'){
                $file = $request->file("upload");
            }elseif($type=='jqueryfile'){
                $file = $request->file("thumb");
            }

            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                //过滤文件格式
                $img_exts= ['jpg','jpeg','png','gif','bmp'];
                $file_exts= ['doc','docx','xls'];
                $video_exts= ['mp4','avi','ogg'];
                if (in_array($ext, $img_exts)) {
                    $filename = '/upload/imgs/';
                }elseif(in_array($ext, $file_exts)){
                    $filename = '/upload/files/';
                }elseif(in_array($ext, $video_exts)){
                    $filename = '/upload/videos/';
                }else{
                    abort(500, '文件不是允许上传的类型！');
                }
                //拼接今日文件夹 如果不存在就创建
                $filename .= date('Y-m-d').'/';
                if(!is_dir(base_path().$filename)){
                    Storage::makeDirectory($filename);
                }
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件
                $filename .= date('His') . '_' . rand(0,999) . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('upload')->put($filename, file_get_contents($realPath));

                if($type=='editor1'){
                    $callback = $_REQUEST["CKEditorFuncNum"];
                    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'".$this->file_servers.$filename."','');</script>";
                    die;
                }else if($type=='jqueryfile'){
                    //获取文件名
                    $filearr = pathinfo($filename) ;
                    $arr = [
                        'total' =>'1',
                        'success' =>'1',
                        'message' =>'上传成功！',
                        'files' =>[
                            "srcName"=> $filearr['basename'],
                            "success"=> true,
                            "path"=> $this->file_servers.$filename
                        ]
                    ];
                    echo json_encode($arr);
                }
            }

        }
    }

}
