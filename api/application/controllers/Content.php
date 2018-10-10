<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Content extends REST_Controller {

  function __construct() {
      parent::__construct();
      $this->load->model('Content_model');
      $this->load->helper('util');
  }

  public function list_image_get() {
    $directory = $this->uri->segment(3);

    if ( !isset($directory) ) {
      $response = response_format(FALSE, 'Número de parámetros incorrecto.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }

    $response = $this->Content_model->list_image($directory);
    $this->response($response);
  }

  public function image_get() {
    $file_name = $this->uri->segment(3);

    if ( !isset($file_name) ) {
      $response = response_format(FALSE, 'Número de parámetros incorrecto.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }

    $this->load->helper('download');
    $data = file_get_contents('./AppContent/Promotion/' . $file_name);
    //or perhpas $data = fopen(......);
    force_download($file_name, $data);
  }

}
