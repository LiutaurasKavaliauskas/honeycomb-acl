<?php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/permissions', ['as' => 'admin.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@adminView']);

    Route::group(['prefix' => 'api'], function ()
    {
        Route::get('users/permissions', ['as' => 'admin.api.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listData']);
        Route::get('users/permissions/search', ['as' => 'admin.api.acl.permissions.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@search']);
        Route::get('users/permissions/{id}', ['as' => 'admin.api.acl.permissions.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@single']);
        Route::post('users/permissions/{id}/duplicate', ['as' => 'admin.api.acl.permissions.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@duplicate']);
        Route::post('users/permissions/restore', ['as' => 'admin.api.acl.permissions.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@restore']);
        Route::post('users/permissions/merge', ['as' => 'admin.api.acl.permissions.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@merge']);
        Route::post('users/permissions', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_create'], 'uses' => 'acl\\PermissionsController@create']);
        Route::put('users/permissions/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@update']);
        Route::delete('users/permissions/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_delete'], 'uses' => 'acl\\PermissionsController@delete']);
        Route::delete('users/permissions', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_delete'], 'uses' => 'acl\\PermissionsController@delete']);
        Route::delete('users/permissions/{id}/force', ['as' => 'admin.api.acl.permissions.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_force_delete'], 'uses' => 'acl\\PermissionsController@forceDelete']);
        Route::delete('users/permissions/force', ['as' => 'admin.api.acl.permissions.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_force_delete'], 'uses' => 'acl\\PermissionsController@forceDelete']);
    });
});
