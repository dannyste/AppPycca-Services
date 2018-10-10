<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content_model extends CI_Model {

  function __construct()
  {
      parent::__construct();
      $this->load->helper('util');
  }

  public function list_image($directory) {
    try {
      $links = array();
      $full_directory = './AppContent/' . $directory;
      if ($handle = opendir($full_directory)) {
          while (false !== ($entry = readdir($handle))) {
              if ($entry != "." && $entry != "..") {
                  $created_date = "";
                  $filename = $full_directory . '/' . $entry;
                  if (file_exists($filename)) {
                    $created_date = date("d/m/Y H:i:s", filectime($filename));
                  }
                  $file_info = array('name' => $entry, 'date' => $created_date);
                  array_push($links, $file_info);
              }
          }
          closedir($handle);
      }
      else {
        return response_format(FALSE, 'Ocurrió un problema al acceder al directorio.', $data);
      }
      $data = array(
        'files' => $links
      );
      return response_format(TRUE, 'Servicio ejecutado correctamente.', $data);
    }
    catch (Exception $e) {
      return response_format(FALSE, $e->getMessage());
    }
  }

}
