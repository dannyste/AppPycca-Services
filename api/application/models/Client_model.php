<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

  function __construct()
  {
      parent::__construct();
      $this->load->helper('util');
  }

  public function account_status($enterprise_id, $date, $account_number, $web_service) {
    try {
      $sql = "DECLARE @afinidad VARCHAR(4)
              EXEC NTS_TARJCRED.dbo.sp_get_afinidad 8813, @afinidad OUTPUT;
              EXEC NTS_TARJCRED.dbo.sp_tcr1000_ws $enterprise_id, '$date', $account_number, @afinidad, $web_service";
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
            'Cuenta'             => (int)$row['Cuenta'],
            'MoviMes'            => (int)$row['MoviMes'],
            'NombreAd'           => utf8_encode($row['NombreAd']),
            'Nombre'             => utf8_encode($row['Nombre']),
            'Direccion'          => utf8_encode($row['Direccion']),
            'Telefono'           => utf8_encode($row['Telefono']),
            'Ciudad'             => utf8_encode($row['Ciudad']),
            'Zona'               => $row['Zona'],
            'FCorte'             => $row['FCorte'],
            'FPago'              => $row['FPago'],
            'Cupo'               => (float)$row['Cupo'],
            'SdoActual'          => (float)$row['SdoActual'],
            'SdoAnterior'        => (float)$row['SdoAnterior'],
            'PagoMinimo'         => (float)$row['PagoMinimo'],
            'PagosVencidos'      => (float)$row['PagosVencidos'],
            'OtrasTrxs'          => (float)$row['OtrasTrxs'],
            'Cargos'             => (float)$row['Cargos'],
            'Pagos'              => (float)$row['Pagos'],
            'FchTrx'             => $row['FchTrx'],
            'Referencia'         => $row['Referencia'],
            'Descripcion'        => utf8_encode($row['Descripcion']),
            'Valor'              => (float)$row['Valor'],
            'DifNumCuota'        => $row['DifNumCuota'],
            'DifCuota'           => (float)$row['DifCuota'],
            'DifSaldo'           => (float)$row['DifSaldo'],
            'MsgComercialLinea1' => utf8_encode($row['MsgComercialLinea1']),
            'MsgComercialLinea2' => utf8_encode($row['MsgComercialLinea2']),
            'MsgComercialLinea3' => utf8_encode($row['MsgComercialLinea3']),
            'TarjetaNormal'      => $row['TarjetaNormal'],
            'OrdenDetalle'       => $row['OrdenDetalle'],
            'empresa'            => utf8_encode($row['empresa']),
            'direccionempresa'   => utf8_encode($row['direccionempresa']),
            'ciudadpais'         => utf8_encode($row['ciudadpais']),
            'ruc'                => $row['ruc'],
            'resolucion'         => utf8_encode($row['resolucion']),
            'autorizacion'       => utf8_encode($row['autorizacion']),
            'telefonoempresa'    => $row['telefonoempresa'],
            'iva'                => (float)$row['iva'],
            'financieros'        => (float)$row['financieros'],
            'nofinancieros'      => (float)$row['nofinancieros'],
            'valoriva'           => (float)$row['valoriva'],
            'valoriva0'          => (float)$row['valoriva0'],
            'fact'               => $row['fact'],
            'mp_disponible'      => (float)$row['mp_disponible'],
            'mp_xvencer'         => (float)$row['mp_xvencer'],
            'Km_generados'       => (int)$row['Km_generados'],
            'km_acreditados'     => (int)$row['km_acreditados']
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
            'nombre'              => utf8_encode($row['nombre']),
            'cupo'                => (float)$row['cupo'],
            'saldoTotal'          => (float)$row['saldoTotal'],
            'minimo_pagar'        => (float)$row['minimo_pagar'],
            'fechaTopePago'       => $row['fechaTopePago'],
            'ultimo_pago'         => $row['ultimo_pago'],
            'saldo_diferido'      => (float)$row['saldo_diferido'],
            'saldo_rotativo'      => (float)$row['saldo_rotativo'],
            'dolpycca_disponible' => (float)$row['dolpycca_disponible'],
            'dolpycca_xvencer'    => (float)$row['dolpycca_xvencer'],
            'cupo_tarjeta'        => (float)$row['cupo_tarjeta'],
            'tarjeta_no'          => $row['tarjeta_no'],
            'identificacion'      => $row['identificacion'],
            'disponible_cuenta'   => (float)$row['disponible_cuenta'],
            'disponible_tarjeta'  => (float)$row['disponible_tarjeta'],
            'deuda_total'         => (float)$row['deuda_total'],
            'fecha_act'           => $row['fecha_act'],
            'estado'              => $row['estado'],
            'maestado'            => $row['maestado'],
            'fecha_activacion'    => $row['fecha_activacion']
          );
        }
        else {
          $data['dataset'] = array(
            'fecha'       => $row['fecha'],
            'descripcion' => $row['descripcion'],
            'total'       => $row['tn_total'],
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
      $resultset = 0;
      do {
        $row = $query->row_array();
        if ($resultset == 0) {
          $data['result'] = array(
            'ta_tarjeta'      => $row['ta_tarjeta'],
            'ta_princiadicio' => $row['ta_princiadicio'],
            'tr_anterior'     => $row['tr_anterior'],
            'tr_actual'       => $row['tr_actual']
          );
        }
        else {
          array_push($data['result']['output'], $row['result']);
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

}
