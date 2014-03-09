<?php

require_once("../PHPMailer_v5.1/class.phpmailer.php");

class DSMailer extends PHPMailer
{
	var $SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	var $SMTPAuth = true;  // authentication enabled
	var $SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	var $Mailer   = "smtp";
	var $Port = 465;
	var $Username = "fortier.jc@gmail.com";  
	var $Password = "jean7c7f";
	
    var $From     = "admin@dungeonstory.com";
    var $FromName = "DSMailer";
    var $Host     = "smtp.gmail.com";
    
    var $WordWrap = 75;
}
?>