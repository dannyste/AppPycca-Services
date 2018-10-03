<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

  function __construct() {
      parent::__construct();
  }

  public function _remap($method, $params = array()) {
    if (method_exists($this, $method)) {
      return call_user_func_array(array($this, $method), $params);
    }
    show_404();
  }

}
