<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_Prueba
 *
 * @author fvera
 */
class M_Prueba extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function consulta() {
        try {
            $Sql = "SELECT top 1 * FROM NTS_TARJCRED.dbo.tc_tarjetas; ";
            $query = $this->db->query($Sql);
            $errDB = $this->db->error();
            if ($errDB['code'] <> 0) {
                throw new Exception($errDB['message'], $errDB['code']);
            }
            $res = $query->result_array();
            $query->free_result();
            return $res;
		} catch (Exception $e) {
			return array('err' => $e->getMessage());
		}
	}
}


?>


