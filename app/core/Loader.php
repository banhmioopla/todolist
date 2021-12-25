<?php
namespace AppToDo\Core;
class Loader{
    const MODEL_PATH = "../app/models/";
    const VIEW_PATH = "../app/views/";

    /*LOADER*/
    public function load_model($model){
        require_once self::MODEL_PATH.$model. ".php";
        return new $model();
    }

    /*LOADER*/
    public function load_view($view, $data = []){
        if(file_exists(self::VIEW_PATH.$view.".php")){
            require_once self::VIEW_PATH.$view.".php";
        } else {
            die("VIEW NOT EXIST");
        }
    }
}