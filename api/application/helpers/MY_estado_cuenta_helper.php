<?php
ini_set('display_errors', '0');

if (!function_exists('generatePDF')) {

    function generatePDF($co_cuenta, $fe_corte) {
        try {
			$respuesta = Array();
			
			$respuesta[co_error]=0;
			$respuesta[tx_error]="";
			$respuesta[archivo]="";
			
            $controller = &get_instance();
            $path_file = $_SERVER['DOCUMENT_ROOT'] . '/EC';
            if (empty($co_cuenta)) {
                throw new Exception('CUENTA ERRONEA');
            }

            $filename = $co_cuenta . '_' . date('dmY', strtotime($fe_corte));
            $filenameReturn = "EC/" . $filename . '.pdf';
            $fileLocation = $path_file . '/' . $filename . '.pdf';

            if (file_exists($fileLocation)) {
				$respuesta[archivo]=$filenameReturn;
                return $respuesta;
            }

            $data = $controller->M_estadoscuenta->retEstadoCuenta($co_cuenta, $fe_corte);
            if (isset($data['err'])) {
                throw new Exception($data['err']);
            }

            if (empty($data)) {
                throw new Exception('SIN DATOS EN LA BUSQUEDA REALIZADA');
            }

            $controller->load->library('Pdf_eecc');
            $pdf = new Pdf_eecc();

            //$pdf->fpdf('P', 'mm', 'A5');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFillColor(255, 255, 255);
            //$pdf->SetAlpha(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetFont('helvetica', 'B', 7);


            $pdf->SetMargins(6, 0, 9);
            //aqui modifico
            $pdf->ln(5);
            $pdf->Cell(13, 4, 'NOMBRE:', 0, 0, 'L');
            $pdf->Cell(20, 4, $data[0]['Nombre'], 0, 0, 'L');
            $pdf->ln(3);
            $pdf->Cell(18, 5, 'DIRECCION:', 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 6);
            $pdf->Cell(120, 5, substr(utf8_decode(($data[0]['Direccion'])), 0, 95), 0, 0, 'L');
            $pdf->ln(3);
            //$pdf->Cell(16, 5, '', 0, 0, 'L');
            $pdf->SetFont('helvetica', 'B', 6);
            //$pdf->Cell(16, 5, substr($data[0]['Ciudad'],0,10), 0, 0, 'J');
            $pdf->Cell(18, 6, substr(utf8_decode($data[0]['Ciudad']), 0, 11), 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 6);
            $pdf->Cell(120, 5, trim(substr((utf8_decode($data[0]['Direccion'])), 95, 92)), 0, 0, 'L');
            $pdf->ln(3);
            $pdf->SetFont('helvetica', 'B', 6);
            $pdf->Cell(18, 6, substr($data[0]['Ciudad'], 12, 11), 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 6);
            $pdf->Cell(120, 5, trim(substr(($data[0]['Direccion']), 187, 100)), 0, 0, 'L');


            /*
              $pdf->Text(12, 20, 'NOMBRE:');
              $pdf->Text(25, 20, $data[0]['Nombre']);
              $pdf->Text(12, 23, 'DIRECCION:');
              $pdf->SetFont('helvetica', '', 6);
              $pdf->Text(28, 23, substr($data[0]['Direccion'], 0, 85));
              $pdf->Text(28, 26, substr($data[0]['Direccion'], 86, 170));
              $pdf->Text(28, 29, substr($data[0]['Direccion'], 171, 255)); */



            //$pdf->SetFont('helvetica', '', 7);
            //$pdf->Text(12, 26, $data[0]['Ciudad']);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(12, 32, 'CUENTA #: ');
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Text(26, 32, $data[0]['Cuenta'], '');

            $pdf->Text(42, 32, 'SECTOR:');
            $pdf->Text(54, 32, $data[0]['zonacourier']);

            $pdf->Text(100, 32, 'SEC:');
            $pdf->Text(108, 32, $data[0]['secuencial']);

            $pdf->SetFont('helvetica', 'B', 7); //Normal
            //$pdf->SetFont('helvetica', 'B', 10); //Formato temporal
            $pdf->ln(10);
            //$pdf->ln(11);//Formato temporal
            $pdf->Cell(130, 8, utf8_decode($data[0]['MsgEstadoss1Linea1']), 0, 0, 'C');
            //$pdf->Cell(130, 8, utf8_decode($data[0]['MsgEstadoss1Linea1']), 0, 0, 'C');
            $pdf->ln(3);
            $pdf->Cell(130, 8, utf8_decode($data[0]['MsgEstadoss1Linea2']), 0, 0, 'C');
            $pdf->ln(3);
            $pdf->Cell(130, 8, utf8_decode($data[0]['MsgEstadoss1Linea3']), 0, 0, 'C');
            $pdf->ln(3); //Normal
            //$pdf->ln(1);//Formato temporal
            $pdf->Cell(130, 8, utf8_decode($data[0]['MsgEstadoss1Linea4']), 0, 0, 'C');
            //$pdf->ln(2); //Formato temporal
            $conLinea = 0;
            $saltoLinea = 2;
            $lineaFooter = 22;
            $saltoTarjeta = '';

            $pdf->SetFont('helvetica', '', 6);
            $pdf->SetMargins(7, 0, 7);
            $pdf->setAutoPageBreak(true, 0);
            $pdf->ln(13);

            foreach ($data as $row) {
                if ($saltoTarjeta != $row['Tarjeta']) {
                    if ($row['OrdenDetalle'] != '01') {
                        if ($row['OrdenDetalle'] == '98') {
                            $pdf->SetFont('helvetica', 'B', 6);
                            $lineaFooter--;
                            $pdf->Cell(5);
                            $pdf->Cell(70, 4, 'SALDOS VENCIDOS', 0, 0, 'L');
                            $pdf->Cell(19, 4, $row['PagosVencidos'], 0, 0, 'R');
                            $pdf->ln(4);
                            $pdf->SetFont('helvetica', '', 6);
                        } else {
                            $pdf->SetFont('helvetica', 'B', 6);
                            $lineaFooter--;
                            $pdf->Cell(5);
                            $pdf->Cell(25, 4, $row['Tarjeta'], 0, 0, 'L');
                            $pdf->Cell(70, 4, $row['NombreAd'], 0, 0, 'L');
                            $pdf->ln(4);
                            $pdf->SetFont('helvetica', '', 6);
                        }
                    }
                } else {
                    
                }
                $lineaFooter--;
                //$pdf->Cell(15, 4, date('Y.m.d', strtotime($row['ma_fchcorteanterior'])), 0, 0, 'L');

                $fechatrx = str_replace('.', '-', $row['FchTrx']);
                // echo ($fechatrx);
                // echo  ("<br />");

                $pdf->Cell(15, 4, date('Y-m-d', strtotime($fechatrx)), 0, 0, 'L');
                $pdf->Cell(27, 4, $row['Referencia'], 0, 0, 'L');
                $pdf->Cell(36, 4, $row['Descripcion'], 0, 0, 'L');
                if ($row['Descripcion'] == 'INTERES COMPRA DIFERIDA') {
                    $pdf->Cell(16, 4, '', 0, 0, 'R');
                } else {
                    $pdf->Cell(16, 4, number_format($row['Valor'], 2, '.', ','), 0, 0, 'R');
                }
                if ($row['OrdenDetalle'] == '05') {
                    $pdf->Cell(14, 4, $row['DifNumCuota'], 0, 0, 'R');
                } else {
                    $pdf->Cell(14, 4, '', 0, 0, 'R');
                }

                if ($row['OrdenDetalle'] == '05') {
                    $pdf->Cell(14, 4, number_format($row['DifSaldo'], 2, '.', ','), 0, 0, 'R');
                } else {
                    $pdf->Cell(16, 4, '', 0, 0, 'R');
                }
                $pdf->ln(4);
                if ($row['OrdenDetalle'] != '01') {
                    $saltoTarjeta = $row['Tarjeta'];
                }
            }

            $pdf->ln($lineaFooter * 4);
            $pdf->ln(3);
            $pdf->SetFont('helvetica', 'B', 5);

//        $pdf->Cell(25, $saltoLinea, date('Y/m/d', strtotime($row['fsigcuota1'])), $conLinea, 0, 'L');
            //        $pdf->Cell(10, $saltoLinea, '$' . $data[0]['sigcuota1'], $conLinea, 0, 'R');
            //

            $pdf->ln($saltoLinea);
            $pdf->Cell(35, $saltoLinea, 'PROXIMAS 6 CUOTAS', 0, 0, 'C');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(35, $saltoLinea, 'CREDITO APROBADO', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['Cupo'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, 'DEUDA ANTERIOR', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['SdoAnterior'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->ln($saltoLinea);
            $pdf->Cell(25, $saltoLinea, date('Y/m/d', strtotime($row['fsigcuota1'])), $conLinea, 0, 'L');
            $pdf->Cell(10, $saltoLinea, '$' . number_format($data[0]['sigcuota1'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(35, $saltoLinea, 'SUPERCREDITO APROB.', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['cupo_supercredito'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, 'PAGOS', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['Pagos'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->ln($saltoLinea);
            $pdf->Cell(25, $saltoLinea, date('Y/m/d', strtotime($row['fsigcuota2'])), $conLinea, 0, 'L');
            $pdf->Cell(10, $saltoLinea, '$' . number_format($data[0]['sigcuota2'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(35, $saltoLinea, 'CREDITO UTILIZADO', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['SdoActual'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, 'CONSUMOS', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['OtrasTrxs'] + $data[0]['diferidos11'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->ln($saltoLinea);
            $pdf->Cell(25, $saltoLinea, date('Y/m/d', strtotime($row['fsigcuota3'])), $conLinea, 0, 'L');
            $pdf->Cell(10, $saltoLinea, '$' . number_format($data[0]['sigcuota3'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(35, $saltoLinea, 'FECHA DE EMISION', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, $data[0]['FCorte'], $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, 'CARGOS', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['Cargos'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->ln($saltoLinea);
            $pdf->Cell(25, $saltoLinea, date('Y/m/d', strtotime($row['fsigcuota4'])), $conLinea, 0, 'L');
            $pdf->Cell(10, $saltoLinea, '$' . number_format($data[0]['sigcuota4'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(35, $saltoLinea, '', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, 'DEUDA ACTUAL', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['SdoActual'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->ln($saltoLinea);
            $pdf->Cell(25, $saltoLinea, date('Y/m/d', strtotime($row['fsigcuota5'])), $conLinea, 0, 'L');
            $pdf->Cell(10, $saltoLinea, '$' . number_format($data[0]['sigcuota5'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(46, $saltoLinea, 'PROGRAMA DE BENEFICIOS PYCCA', $conLinea, 0, 'C');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, utf8_decode('CUOTA DE CRÉDITO'), $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['PagoMinimo'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->ln($saltoLinea);
            $pdf->Cell(25, $saltoLinea, date('Y/m/d', strtotime($row['fsigcuota6'])), $conLinea, 0, 'L');
            $pdf->Cell(10, $saltoLinea, '$' . number_format($data[0]['sigcuota6'], 2, '.', ','), $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(35, $saltoLinea, 'KMS. LATAM Pass Generados:', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, $data[0]['Km_generados'], $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, utf8_decode('RECAUDACIÓN ANTERIOR'), $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['recaudaciones_ant'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->ln($saltoLinea);
            $pdf->Cell(35, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(35, $saltoLinea, 'KMS. LATAM Pass Acreditados:', $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, $data[0]['km_acreditados'], $conLinea, 0, 'R');
            $pdf->Cell(8, $saltoLinea, '', $conLinea, 0, 'R');
            $pdf->Cell(25, $saltoLinea, utf8_decode('RECAUDACIÓN'), $conLinea, 0, 'L');
            $pdf->Cell(11, $saltoLinea, number_format($data[0]['recaudaciones'], 2, '.', ','), $conLinea, 0, 'R');

            $pdf->SetFillColor(0, 0, 0);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->ln(3);
            $pdf->Cell(98);
            $pdf->Cell(17, 2.5, 'Cuota apagar', 0, 0, 'C', 'true');
            $pdf->Cell(2, 2.5, '', 0, 0, 'C');
            $pdf->Cell(17, 2.5, 'Pagar hasta', 0, 0, 'C', 'true');
            $pdf->ln(2);
            $pdf->Cell(98);
            $pdf->Cell(17, 2.5, number_format($data[0]['PagominimoRecaudacion'], 2, '.', ','), 0, 0, 'C', 'true');
            $pdf->Cell(2, 2.5, '', 0, 0, 'C');
            $pdf->Cell(17, 2.5, $data[0]['FPago'], 0, 0, 'C', 'true');

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->ln(10);
            $pdf->SetMargins(7, 0, 9);
            $pdf->Cell(136, 10, utf8_decode($data[0]['MsgComercialLinea1']), 0, 0, 'C');
            $pdf->SetMargins(7, 0, 9);
            $pdf->ln(5);
            $pdf->Cell(136, 7, utf8_decode($data[0]['MsgComercialLinea2']), 0, 0, 'C');
            $pdf->ln(5);
            $pdf->Cell(136, 5, utf8_decode($data[0]['MsgComercialLinea3']), 0, 0, 'C');
            //$filename = $this->path_file . '/' . date('dmY', strtotime($fe_corte)) . $co_cuenta . date('dmY', strtotime($fe_corte)) . '.pdf';

            $pdf->Output($fileLocation, 'F');
            //$pdf->Output();
			$respuesta[archivo]=$filenameReturn;
			
        } catch (Exception $e) {
			$respuesta[co_error]=1;
			$respuesta[tx_error]=$e->getMessage();
        }
		return $respuesta;		
    }

}
?>

