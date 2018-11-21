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

  public function account_status_get() {
    $enterprise_id = $this->uri->segment(3);
    $date = $this->uri->segment(4);
    $account_number = $this->uri->segment(5);
    $web_service = $this->uri->segment(6);
    if (!isset($enterprise_id) OR !isset($date) OR !isset($account_number) OR !isset($web_service)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->account_status($enterprise_id, $date, $account_number, $web_service);
    $this->response($response);
  }

  public function balance_get() {
    $club_pycca_card_number = $this->uri->segment(3);
    if (!isset($club_pycca_card_number)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->balance($club_pycca_card_number);
    $this->response($response);
  }

  public function card_blocking_get() {
    $club_pycca_card_number = $this->uri->segment(3);
    $account_number = $this->uri->segment(4);
    $reason_code = $this->uri->segment(5);
    $reason_description = $this->uri->segment(6);
    if (!isset($club_pycca_card_number) OR !isset($account_number) OR !isset($reason_code) OR !isset($reason_description)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->card_blocking($club_pycca_card_number, $account_number, $reason_code, $reason_description);
    $this->response($response);
  }

  public function quota_calculator_get() {
    $amount = $this->uri->segment(3);
    if (!isset($amount)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->quota_calculator($amount);
    $this->response($response);
  }

  public function validate_client_get() {
    $document_type = $this->uri->segment(3);
    $document_number = $this->uri->segment(4);
    $club_pycca_card_number = $this->uri->segment(5);
    if (!isset($document_type) OR !isset($document_number) OR !isset($club_pycca_card_number)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->validate_client($document_type, $document_number, $club_pycca_card_number);
    $this->response($response);
  }

  public function club_pycca_partner_post() {
    $name = $this->post('name');
    $last_name = $this->post('last_name');
    $born_date = $this->post('born_date');
    $identification = $this->post('identification');
    $email = $this->post('email');
    $phone = $this->post('phone');
    $cell_phone = $this->post('cell_phone');
    $address = $this->post('address');

    if ($name == '' OR $last_name == '' OR $born_date == '' OR $identification == '' OR
        $email == '' OR $phone == '' OR $cell_phone == '' OR $address == '') {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->club_pycca_partner($name, $last_name, $born_date, $identification,
                                                        $email, $phone, $cell_phone, $address);
    $this->response($response);
  }

}
