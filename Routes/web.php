<?php
use App\Models\Menu;


Route::prefix('panel')->middleware('auth')->group(function () {

    $menu = new Menu;
    $menu_list = $menu
    ->join('modules', 'menus.module_id', '=', 'modules.id')
    ->where('modules.controller', 'CategoriesController')
    ->where('menus.type','module')
    ->get();
    foreach($menu_list as $menu_item){
        $actions_array = explode(',',$menu_item->actions);
        foreach($actions_array as $actions_item){
            $actions_item_array = explode(';',$actions_item);
            $verb = $actions_item_array[0];
            $method = $actions_item_array[1];
            $url = isset($actions_item_array[2]) ? '/'.$actions_item_array[2] : '' ;
            Route::$verb($menu_item->slug.$url, "CategoriesController@$method");
        }        
    }
});