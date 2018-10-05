<?php

function response_format($status, $message, $data = null) {
  $response = array(
    'status'   => $status,
    'message' => $message,
    'data'    => $data
  );
  return $response;
}

?>
