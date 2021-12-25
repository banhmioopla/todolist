<?php
namespace AppToDo\Core;
class Core {
    protected $currentController = "Notfound";
    protected $currentMethod = "index";
    protected $params = [];

    public function __construct() {
        $partial_url = $this->getUrl();

        if(is_array($partial_url)
            && file_exists('../app/controllers/'.ucwords($partial_url[0]).'.php')){
                $this->currentController = ucwords($partial_url[0]);
                unset($partial_url[0]);
        }

        require_once "../app/controllers/".$this->currentController.".php";
        $this->currentController = new $this->currentController;

        if(isset($partial_url[1])){
            if(method_exists($this->currentController, $partial_url[1])){
                $this->currentMethod = $partial_url[1];
                unset($partial_url[1]);
            }
        }

        $this->params = $partial_url ? array_values($partial_url) : [];
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }


    public function getUrl(){
        if(isset($_GET['url'])){
            $url = trim($_GET['url']);
            return explode('/', $url);
        }
    }


}