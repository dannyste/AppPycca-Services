<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

  public function validate_card($identity_card_number, $card_number) {
    $sql = "EXEC IVDBINVENTAR.vtex._validaClienteConsultas 'C', '" . $identity_card_number . "', '" . $card_number . "';";
    #$data = array('name' => $name, 'phone' => $identity_card_number, 'address' => $address);
    return $this->db->query($sql);
  }

}
