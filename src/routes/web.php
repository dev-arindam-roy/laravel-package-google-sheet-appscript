<?php

use Illuminate\Support\Facades\Route;

$isRouteEnabled = true;
$routePrefix = 'onex';
$routeName = 'gsheet';
$routeMiddleware = ['web'];

$publishedConfigFilePath = config_path('gsheet-appscript.php');
if (file_exists($publishedConfigFilePath)) {
    $isRouteEnabled = !empty(config('gsheet-appscript.is_route_enabled')) ? config('gsheet-appscript.is_route_enabled') : $isRouteEnabled;
    $routePrefix = !empty(config('gsheet-appscript.route_prefix')) ? config('gsheet-appscript.route_prefix') : $routePrefix;
    $routeName = !empty(config('gsheet-appscript.route_name')) ? config('gsheet-appscript.route_name') : $routeName; 
}

if ($isRouteEnabled) {
    Route::group(['namespace' => 'Arindam\GsheetAppScript\Http\Controllers', 'prefix' => $routePrefix, 'middleware' => $routeMiddleware], function() use($routeName) {
        Route::get($routeName, 'GsheetAppScriptController@index')->name('gsheet-appscript.index');
        Route::post($routeName.'/delete-all', 'GsheetAppScriptController@deleteAll')->name('gsheet-appscript.deleteAll');
        Route::post($routeName.'/save-row', 'GsheetAppScriptController@saveRow')->name('gsheet-appscript.saveRow');
        Route::post($routeName.'/delete-row', 'GsheetAppScriptController@removeRow')->name('gsheet-appscript.deleteRow');
        Route::post($routeName.'/access-login', 'GsheetAppScriptController@accessLogin')->name('gsheet-appscript.accessLogin');
        Route::get($routeName.'/access-off', 'GsheetAppScriptController@accessOff')->name('gsheet-appscript.accessOff');
    });
}