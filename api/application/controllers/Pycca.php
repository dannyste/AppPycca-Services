<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pycca extends REST_Controller {

  function __construct() {
      parent::__construct();
      $this->load->model('Pycca_model');
      $this->load->helper('util');
  }

  public function our_shops_get() {
    $response = $this->Pycca_model->our_shops();
    $this->response($response);
  }

}
