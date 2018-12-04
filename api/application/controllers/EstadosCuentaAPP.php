<?php

/*
  error_reporting(E_ALL);
 */
ini_set('display_errors', '0');

class EstadosCuentaAPP extends CI_Controller {

    public $path_file;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('MY_estado_cuenta'));
        $this->load->model('M_estadoscuenta');
    }

    public function _remap($method, $params = array()) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        show_404();
    }

    function getPdf(){
        try {
			$respuesta=generatePDF(8813, '2018-06-30');
			print_r($respuesta);
        }
		catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
