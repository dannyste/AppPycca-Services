<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Client extends REST_Controller {

  function __construct() {
      parent::__construct();
      $this->load->model('Client_model');
      $this->load->helper('util');
      $this->load->helper('MY_estado_cuenta');
      $this->load->model('M_estadoscuenta');
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

  public function account_status_get() {
    $club_pycca_card_number = $this->uri->segment(3);
    if (!isset($club_pycca_card_number)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->account_status($club_pycca_card_number);
    $this->response($response);
  }

  public function quota_increase_post() {
    $increase_type = $this->post('increase_type');
    $account_number = $this->post('account_number');
    $identification = $this->post('identification');
    $email = $this->post('email');
    $name = $this->post('name');
    $last_name = $this->post('last_name');
    $quota = $this->post('quota');
    if (!isset($increase_type) OR !isset($account_number) OR !isset($identification)
        OR !isset($email) OR !isset($name) OR !isset($last_name)
        OR !isset($quota)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->quota_increase($increase_type, $account_number, $identification, $email, $name, $last_name, $quota);
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

  public function cards_get() {
    $account_number = $this->uri->segment(3);
    $club_pycca_card_number = $this->uri->segment(4);
    if (!isset($account_number) OR !isset($club_pycca_card_number)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->cards($account_number, $club_pycca_card_number);
    $this->response($response);
  }

  public function card_blocking_post() {
    $club_pycca_card_number = $this->post('club_pycca_card_number');
    $account_number = $this->post('account_number');
    $reason_code = $this->post('reason_code');
    $reason_description = $this->post('reason_description');
    if (!isset($club_pycca_card_number) OR !isset($account_number) OR !isset($reason_code) OR !isset($reason_description)) {
      $response = response_format(FALSE, 'Número de parámetros incorrectos.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $response = $this->Client_model->card_blocking($club_pycca_card_number, $account_number, $reason_code, $reason_description);
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

  public function pdf_account_status_get() {
    $account_number = $this->uri->segment(3);
    $cut_date = $this->uri->segment(4);

    if ( !isset($account_number) OR !isset($cut_date)) {
      $response = response_format(FALSE, 'Número de parámetros incorrecto.');
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return;
    }
    $file_name = '';

    try {
        $respuesta = generatePDF((int)$account_number, $cut_date);
        $file_name = $respuesta['archivo'];
    } catch (Exception $e) {
    }
    print_r($respuesta);

    $this->load->helper('download');
    $data = file_get_contents('./' . $file_name);
    // //or perhpas $data = fopen(......);
    force_download($file_name, $data);
  }

}
