<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Client extends REST_Controller {

  function __construct() {
      parent::__construct();
      $this->load->model('Client_model');
  }

  public function validate_client_get() {
    $identity_card_number = $this->uri->segment(3);
    $club_pycca_card_number = $this->uri->segment(4);
    if (!isset($identity_card_number)) {
      $response = array(
        'error'   => TRUE,
        'message' => 'Es necesario el número de identificación del cliente.'
      );
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    if (!isset($club_pycca_card_number)) {
      $response = array(
        'error'   => TRUE,
        'message' => 'Es necesario el número de la tarjeta club pycca del cliente.'
      );
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $data = $this->Client_model->validate_client2('C', $identity_card_number, $club_pycca_card_number);
    if (isset($data)) {
      $response = array(
        'error'   => FALSE,
        'message' => 'Servicio ejecutado correctamente.',
        'data'    => $data
      );
      $this->response($response);
    }
    else {
      $response = array(
        'error'   => TRUE,
        'message' => 'Servicio ejecutado incorrectamente.',
        'data'    => $data
      );
      $this->response($response);
    }
  }

}
