<?php
//上传文件路由
Route::post('uploadfile/{type}', ['uses' => 'UploadController@updateFile','as' => 'upload.upload.uploadfile']);
