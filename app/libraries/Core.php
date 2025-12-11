<?php
  /*
   * App Core Class
   * Creates URL & loads core controller
   * URL FORMAT - /controller/method/params
   */
  class Core {
    protected $currentController = 'Home';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        // Get parsed URL segments (if any)
        $url = $this->getUrl();

        // Ensure $url is an array before accessing indices
        if(!is_array($url)){
          $url = [];
        }

        // Look in controllers for first value
        if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
          // If exists, set as controller
          $this->currentController = ucwords($url[0]);
          // Unset 0 Index
          unset($url[0]);
        }

      // Require the controller
      require_once '../app/controllers/'. $this->currentController . '.php';

      // Instantiate controller class
      $this->currentController = new $this->currentController;

      // Check for second part of url (method)
      if(isset($url[1]) && method_exists($this->currentController, $url[1])){
        $this->currentMethod = $url[1];
        // Unset 1 index
        unset($url[1]);
      }

      // Check if the method is an API call
      if (substr($this->currentMethod, -4) === '_api') {
        define('IS_API', true);
      }

      // Get params
      $this->params = $url ? array_values($url) : [];

      // Call a callback with array of params
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
      if(isset($_GET['url'])){
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
      }

      // Always return an array to simplify callers
      return [];
    }
  }
