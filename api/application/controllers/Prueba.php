<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prueba extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('M_Prueba'));
    }

    public function _remap($method, $params = array()) {
		if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $params);
		}
        show_404();
    }

    public function index() {
        $consulta = $this->M_Prueba->consulta();
		print_r($consulta);
            
    }

}
