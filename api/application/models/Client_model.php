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

}
