<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pycca_model extends CI_Model {

  function __construct()
  {
      parent::__construct();
      $this->load->helper('util');
  }

  public function shop() {
    try {
      $sql = "EXEC IVDBINVENTAR.dbo._get_direccion_tiendas";
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
              'ciudad'           => utf8_encode($row['ciudad']),
              'descripcion'      => utf8_encode($row['descripcion']),
              'telefono1'        => $row['telefono1'],
              'telefono2'        => $row['telefono2'],
              'direccion'        => utf8_encode($row['direccion']),
              'latitud'          => (float)$row['latitud'],
              'longitud'         => (float)$row['longitud'],
              'horario_atencion' => utf8_encode($row['horario_atencion'])
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

}
