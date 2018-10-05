<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Home extends CI_Controller {

  function __construct() {
      parent::__construct();
  }



  public function index() {
    echo "llego";
  }

}
