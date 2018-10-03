<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

  function __construct() {
      parent::__construct();
      $this->load->model('Client_model');
  }

  public function _remap($method, $params = array()) {
    if (method_exists($this, $method)) {
      return call_user_func_array(array($this, $method), $params);
    }
    show_404();
  }

  public function index() {
    $query = $this->Client_model->validate_card('0927436824', '9218101008274008');
    echo json_encode($query->row());
  }

}
