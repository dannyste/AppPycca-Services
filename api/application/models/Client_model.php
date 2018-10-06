<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

  function __construct()
  {
      parent::__construct();
      $this->load->helper('util');
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
            'no_estados'   => $row['no_estados']
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

  public function balance($document_number, $detail, $save_to_log, $club_pycca_card_number, $since_website) {
    try {
      $sql = "EXEC NTS_TARJCRED.dbo.sp_webtcp_ws '$document_number', $detail, $save_to_log, '$club_pycca_card_number', $since_website";
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
          $data['result'] = array(
            'nombre'   => utf8_encode($row['nombre']),
            'cupo'    => (float)$row['cupo'],
            'saldoTotal'    => (float)$row['saldoTotal'],
            'minimo_pagar'    => (float)$row['minimo_pagar'],
            'fechaTopePago' => ($row['fechaTopePago']),
            'ultimo_pago' => ($row['ultimo_pago']),
            'saldo_diferido'    => (float)$row['saldo_diferido'],
            'saldo_rotativo'    => (float)$row['saldo_rotativo'],
            'dolpycca_disponible'    => (float)$row['dolpycca_disponible'],
            'dolpycca_xvencer'    => (float)$row['dolpycca_xvencer'],
            'cupo_tarjeta'    => (float)$row['cupo_tarjeta'],
            'tarjeta_no'   => $row['tarjeta_no'],
            'identificacion'   => $row['identificacion'],
            'disponible_cuenta'    => (float)$row['disponible_cuenta'],
            'disponible_tarjeta'    => (float)$row['disponible_tarjeta'],
            'deuda_total'    => (float)$row['deuda_total'],
            'fecha_act' => ($row['fecha_act']),
            'estado' => $row['estado'],
            'maestado' => $row['maestado'],
            'fecha_activacion' => ($row['fecha_activacion'])
          );
        }
        else {
          $data['dataset'] = array(
            'fecha' => $row['fecha'],
            'descripcion' => $row['descripcion'],
            'total' => $row['tn_total'],
            'difnumcuota' => $row['tn_difnumcuota']
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

}
