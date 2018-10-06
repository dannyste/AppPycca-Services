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
    if (!isset($document_type) OR !isset($document_number) OR !isset($club_pycca_card_number) ) {
      $response = response_format(FALSE, 'Número de parámetros incorrecto.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->validate_client($document_type, $document_number, $club_pycca_card_number);
    $this->response($response);
  }

  public function balance_get() {
    $document_number = $this->uri->segment(3);
    $detail = $this->uri->segment(4);
    $save_to_log = $this->uri->segment(5);
    $club_pycca_card_number = $this->uri->segment(6);
    $since_website = $this->uri->segment(7);

    if (!isset($document_number) OR !isset($detail) OR !isset($save_to_log) OR !isset($club_pycca_card_number) OR !isset($since_website) ) {
      $response = response_format(FALSE, 'Número de parámetros incorrecto.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }

    $response = $this->Client_model->balance($document_number, $detail, $save_to_log, $club_pycca_card_number, $since_website);
    $this->response($response);
  }

}
