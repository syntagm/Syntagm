<?php
	include("sendConfig.php");
	$_POST['type'] = (empty($_POST['type']) ? $_GET['a'] : $_POST['type']);

	if(!preg_match('@('.join('|',array_keys($formTypes)).')@',$_POST['type']))
	{
		die('Undefined Form Type');
	}
	
	if(empty($_POST['send']))
	{
		$temp 			= $_POST;			unset($_POST);
		$_POST['type']	= $temp['type']; 	unset($temp['type']);
		$_POST['send'] 	= $temp;			unset($temp);
	}
	
	array_filter($_POST['send'],"clean");
	
	//Info Send
	require_once('classes/class.phpmailer.php');
	$mail             	= new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug  	= true;
	$mail->SMTPAuth   	= true;
	$mail->Host       	= 'smtp.mandrillapp.com';
	$mail->Port       	= 587;
	$mail->Username   	= 'mandrill@username.com';
	$mail->Password   	= 'mandrillApiKey';
	$mail->Subject    	= 'New '.$formTypes[$_POST['type']]. ' Message from Website Name';
	$mail->CharSet		= 'utf-8';
	$mail->WordWrap 	= 50;
	
	$mail->SetFrom('no-reply@website.com', 'Website Name');

	$mail->AddAddress('yourmail@address.com');
	
	extract($_POST['send']);

	ob_start();

	include(MAIL_TEMPLATE_PATH.$_POST['type'].'.php');
	
	$mail->MsgHTML(ob_get_contents());print_r($mail);
	$mail->Send();
	echo $mail->ErrorInfo;
	ob_end_clean();
	unset($mail);
	header('content-type:text/html; charset=utf8');
?>