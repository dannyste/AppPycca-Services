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
        $row = $query->row_array();
        if ($resultset == 0) {
          $data = array(
            'resultado' => $row
          );
        }
        else {
          $data['result'] = array(
            'ma_cuenta'    => $row
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
