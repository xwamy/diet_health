<?php

Route::group(['middleware' => ['auth:admin']], function ($router) {
    $router->get('/', ['uses' => 'AdminController@index','as' => 'admin.index']);

    $router->resource('index', 'IndexController');

    //目录
    $router->resource('menus', 'MenuController');

    //后台用户
    $router->get('adminuser/ajaxIndex',['uses'=>'AdminUserController@ajaxIndex','as'=>'admin.adminuser.ajaxIndex']);
    $router->resource('adminuser', 'AdminUserController');

    //权限管理
    $router->get('permission/ajaxIndex',['uses'=>'PermissionController@ajaxIndex','as'=>'admin.permission.ajaxIndex']);
    $router->resource('permission', 'PermissionController');

    //角色管理
    $router->get('role/ajaxIndex',['uses'=>'RoleController@ajaxIndex','as'=>'admin.role.ajaxIndex']);
    $router->resource('role', 'RoleController');

    //用户管理
    $router->get('users/ajaxIndex',['uses'=>'UsersController@ajaxIndex','as'=>'admin.users.ajaxIndex']);
    $router->resource('users', 'UsersController');

    //烹饪方式管理
    $router->get('cookingway/ajaxIndex',['uses'=>'CookingWayController@ajaxIndex','as'=>'admin.cookingway.ajaxIndex']);
    $router->resource('cookingway', 'CookingWayController');

    //食材管理
    $router->get('ingredient/ajaxIndex',['uses'=>'IngredientController@ajaxIndex','as'=>'admin.ingredient.ajaxIndex']);
    $router->resource('ingredient', 'IngredientController');

    //营养价值管理
    $router->get('nutritive/ajaxIndex',['uses'=>'NutritiveController@ajaxIndex','as'=>'admin.nutritive.ajaxIndex']);
    $router->resource('nutritive', 'NutritiveController');

    //菜品口味管理
    $router->get('taste/ajaxIndex',['uses'=>'TasteController@ajaxIndex','as'=>'admin.taste.ajaxIndex']);
    $router->resource('taste', 'TasteController');

    //菜谱教程管理
    $router->get('cookbook/ajaxIndex',['uses'=>'CookbookController@ajaxIndex','as'=>'admin.cookbook.ajaxIndex']);
    $router->resource('cookbook', 'CookbookController');
});

Route::get('login', ['uses' => 'AuthController@index','as' => 'admin.auth.index']);
Route::post('login', ['uses' => 'AuthController@login','as' => 'admin.auth.login']);

Route::get('logout', ['uses' => 'AuthController@logout','as' => 'admin.auth.logout']);

Route::get('register', ['uses' => 'AuthController@getRegister','as' => 'admin.auth.register']);
Route::post('register', ['uses' => 'AuthController@postRegister','as' => 'admin.auth.register']);

Route::get('password/reset/{token?}', ['uses' => 'PasswordController@showResetForm','as' => 'admin.password.reset']);
Route::post('password/reset', ['uses' => 'PasswordController@reset','as' => 'admin.password.reset']);
Route::post('password/email', ['uses' => 'PasswordController@sendResetLinkEmail','as' => 'admin.password.email']);
