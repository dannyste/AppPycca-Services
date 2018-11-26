<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

  function __construct()
  {
      parent::__construct();
      $this->load->helper('util');
  }

  public function account_status($club_pycca_card_number) {
    try {
      $sql = "EXEC NTS_TARJCRED.dbo.sp_webtcp_ws_APP '$club_pycca_card_number'";
      $query = $this->db->query($sql);
      $error_db = $this->db->error();
      if ((int)$error_db['code'] <> 0) {
        throw new Exception($error_db['message'], $error_db['code']);
      }
      $data = array();
      $resultset = 0;
      do {
        $row = $query->row_array();
        if ($resultset == 0) {
          $data['status_error'] = array(
            'co_error' => (int)$row['co_error'],
            'tx_error' => $row['tx_error']
          );
        }
        else {
          $data['result'] = array(
            'cupo'                => (float)$row['cupo'],
            'minimo_pagar'        => (float)$row['minimo_pagar'],
            'fechaTopePago'       => $row['fechaTopePago'],
            'disponible_cuenta'   => (float)$row['disponible_cuenta']
            //'deuda_total'         => (float)$row['deuda_total'],
          );
        }
        $resultset++;
      } while ($query->_next_resultset());
      $query->free_result();
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }

  public function card_blocking($club_pycca_card_number, $account_number, $reason_code, $reason_description) {
    try {
      $sql = "DECLARE @p7 VARCHAR(8000)
              EXEC NTS_TARJCRED.dbo.sp_tactestadostarj 'sa', 'PYCCA_APP', 0, 1000, 0, 27, @p7 OUTPUT, 't119', '$club_pycca_card_number', $account_number, '0000000000', ' ', '1', $reason_code, NULL, 'N', '$reason_description', '840', NULL
              SELECT @p7 AS result";
      $query = $this->db->query($sql);
      $error_db = $this->db->error();
      if ((int)$error_db['code'] <> 0) {
        throw new Exception($error_db['message'], $error_db['code']);
      }
      $data = array();
      $row = $query->row_array();
      $data['status_error'] = array(
        'co_error' => '',
        'tx_error' => '');
      $query->free_result();
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }

  public function quota_calculator($amount) {
    try {
      $sql = "EXEC NTS_TARJCRED.dbo._get_plan_pago $amount, NULL";
      $query = $this->db->query($sql);
      $error_db = $this->db->error();
      if ((int)$error_db['code'] <> 0) {
        throw new Exception($error_db['message'], $error_db['code']);
      }
      $data = array();
      $resultset = 0;
      do {
        if ($resultset == 0) {
          $row = $query->row_array();
          $data['status_error'] = array(
            'co_error' => (int)$row['co_error'],
            'tx_error' => $row['tx_error']
          );
        }
        else {
          foreach($query->result_array() as $row) {
            $data['result'][] = array(
              'NPlazo'      => (int)$row['NPlazo'],
              'ValorCuota'  => (float)$row['ValorCuota'],
              'TotalAPagar' => (float)$row['TotalAPagar']
            );
          }
        }
        $resultset++;
      } while ($query->_next_resultset());
      $query->free_result();
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }

  public function validate_client($document_type, $document_number, $club_pycca_card_number) {
    try {
      $sql = "EXEC IVDBINVENTAR.vtex._validaClienteConsultas '$document_type', '$document_number', '$club_pycca_card_number'";
      $query = $this->db->query($sql);
      $error_db = $this->db->error();
      if ((int)$error_db['code'] <> 0) {
        throw new Exception($error_db['message'], $error_db['code']);
      }
      $data = array();
      $resultset = 0;
      do {
        $row = $query->row_array();
        if ($resultset == 0) {
          $data['status_error'] = array(
            'co_error' => (int)$row['co_error'],
            'tx_error' => $row['tx_error']
          );
        }
        else {
          $data['result'] = array(
            'ma_cuenta'    => (int)$row['ma_cuenta'],
            'cl_nombres'   => utf8_encode($row['cl_nombres']),
            'cl_apellidos' => utf8_encode($row['cl_apellidos']),
            'no_estados'   => $row['no_estados'],
            'ma_fapertura' => $row['ma_fapertura']
          );
        }
        $resultset++;
      } while ($query->_next_resultset());
      $query->free_result();
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }

  public function club_pycca_partner($name, $last_name, $born_date, $identification,
                                     $email, $phone, $cell_phone, $address) {
    try {
      $sql = "EXEC NTS_TARJCRED.dbo._enviar_solicitud_credito '$name', '$last_name', '$born_date', '$identification', '$email', '$phone', '$cell_phone', '$address'";
      $query = $this->db->query($sql);
      $error_db = $this->db->error();
      if ((int)$error_db['code'] <> 0) {
        throw new Exception($error_db['message'], $error_db['code']);
      }
      $data = array();
      $row = $query->row_array();
      $data['status_error'] = array(
        'co_error' => (int)$row['co_error'],
        'tx_error' => $row['tx_error']
      );
      $query->free_result();
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }

  public function club_pycca_cards($account_number, $club_pycca_card_number) {
    try {
      $sql = "EXEC NTS_TARJCRED.dbo.sp_ttarjetasactivas '$account_number', '$club_pycca_card_number'";
      $query = $this->db->query($sql);
      $error_db = $this->db->error();
      if ((int)$error_db['code'] <> 0) {
        throw new Exception($error_db['message'], $error_db['code']);
      }
      $data = array();
      $resultset = 0;
      do {
        $row = $query->row_array();
        if ($resultset == 0) {
          $data['status_error'] = array(
            'co_error' => (int)$row['co_error'],
            'tx_error' => $row['tx_error']
          );
        }
        else {
          $data['result'] = array(
            'tarjeta'    => (int)$row['tarjeta'],
            'ta_plnombre1'   => utf8_encode($row['ta_plnombre1']),
            'ta_princiadicio' => $row['ta_princiadicio']
          );
        }
        $resultset++;
      } while ($query->_next_resultset());
      $query->free_result();
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }

  public function quota_increase($increase_type, $account_number, $identification, $email, $name, $last_name, $quota) {
    try {
      $sql = "EXEC NTS_TARJCRED.dbo._enviar_solicitud_aumento_cupo '$increase_type', '$account_number', '$identification', '$email', '$name', '$last_name', '$quota'";
      $query = $this->db->query($sql);
      $error_db = $this->db->error();
      if ((int)$error_db['code'] <> 0) {
        throw new Exception($error_db['message'], $error_db['code']);
      }
      $data = array();
      $row = $query->row_array();
      $data['status_error'] = array(
        'co_error' => (int)$row['co_error'],
        'tx_error' => utf8_encode($row['tx_error'])
      );
      $query->free_result();
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }
}
