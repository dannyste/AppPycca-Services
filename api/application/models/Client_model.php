<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

  public $co_error;
  public $tx_error;

  public function validate_client($identity_card_number, $club_pycca_card_number) {

	// OJO PORFAVOR VALIDA SI HAY ERROR EN LA CONSULTA A LA BASE DE DATOS
	// CON LA FUNCION $errDB = $this->db->error(); PODRAS OBTENER LOS ERRORES QUE SE PRESENTAN AL EJECUTAR EL QUERY
	// ADEMAS EN CADA FUNCION DEL MODELO PODRIAS USAR try / catch PARA CONTROLAR LOS ERRORES
	// GRACIAS

    $sql = "EXEC IVDBINVENTAR.vtex._validaClienteConsultas 'C', '" . $identity_card_number . "', '" . $club_pycca_card_number . "';";
    $query = $this->db->query($sql);
    $data = $query->result_array();
    $query->free_result();
    return $data;
  }

	function validate_client2($type,$identity_card_number, $club_pycca_card_number) {
        try {
            //$Sql = sp_query($this->db, 'IVDBINVENTAR', '_validaClienteConsultas', array(
				//$this->db->escape($type),
				//$this->db->escape($identity_card_number),
				//$this->db->escape($club_pycca_card_number)
			//),'vtex');
$Sql = "EXEC IVDBINVENTAR.vtex._validaClienteConsultas 'C', '" . $identity_card_number . "', '" . $club_pycca_card_number . "';";

            $query = $this->db->query($Sql);
            $errDB = $this->db->error();
            if ((int) $errDB['code'] <> 0) {
                throw new Exception($errDB['message'], $errDB['code']);
            }
            do {
                $datos = $query->result_array();
                if (!empty($datos)) {
                    print_r($datos);
                }
            } while ($query->_next_resultset());
            $query->free_result();

        } catch (Exception $e) {
            return array('err' => $e->getMessage());
        }
    }

}
