<?php

class M_estadoscuenta extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->db->trans_strict(FALSE);
        $this->db->trans_off();
        $this->db->save_queries = FALSE;
    }

    function retEstadoCuenta($cuenta, $fe_corte) {
        try {
            if (empty($fe_corte) || empty($cuenta)) {
                throw new Exception('PARAMETROS INCOMPLETOS');
            }

			$Afinidad=0;
            $Sql = "select ma_afinidad from nts_tarjcred..tc_maestro with (nolock) where ma_cuenta=" . (int) $cuenta;
            $query = $this->db->query($Sql);
            $errDB = $this->db->error();
            if ($errDB['code'] <> 0) {
                throw new Exception($errDB['message'], (int) $errDB['code']);
            }
			$result=$query->result_array();
            foreach ($result as $row) {
                $Afinidad=(int)$row['ma_afinidad'];
			}
            $Sql = "EXEC Nts_tarjcred.dbo.sp_tcr1000_local 27,'" . date('Y-m-d', strtotime($fe_corte)) . "'," . (int) $cuenta . ",'" . trim($Afinidad) . "'";
            $query = $this->db->query($Sql);
            $errDB = $this->db->error();
            if ($errDB['code'] <> 0) {
                throw new Exception($errDB['message'], (int) $errDB['code']);
            }
            return $query->result_array();
        } catch (Exception $e) {
            return array('err' => $e->getMessage());
        }
    }


}
