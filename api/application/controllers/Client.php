<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Client extends REST_Controller {

  function __construct() {
      parent::__construct();
      $this->load->model('Client_model');
      $this->load->helper('util');
  }

  public function validate_client_get() {
    $document_type = $this->uri->segment(3);
    $document_number = $this->uri->segment(4);
    $club_pycca_card_number = $this->uri->segment(5);
    if (!isset($document_type)) {
      $response = response_format(FALSE, 'Es necesario el tipo de documento del cliente.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    if (!isset($document_number)) {
      $response = response_format(FALSE, 'Es necesario el número de documento del cliente.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    if (!isset($club_pycca_card_number)) {
      $response = response_format(FALSE, 'Es necesario el número de la tarjeta club pycca del cliente.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->validate_client($document_type, $document_number, $club_pycca_card_number);
    $this->response($response);
  }

}
