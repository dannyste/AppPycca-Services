<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/mailer/class.phpmailer.php'; 

class Mailer extends PHPMailer
{
    
    function __construct()
    {
        parent::__construct();
    }
    
    var $Titulo;
    
    
} 
