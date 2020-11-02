<?php

function isNull($usuario, $nombre, $password, $email){
	if(strlen(trim($usuario)) < 1 || strlen(trim($nombre)) < 1 || strlen(trim($password)) < 1 || strlen(trim($email)) < 1 ) 
	{
		return true;
		} else {
		return false;
	}		
}

function isEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
			} else {
			return false;
		}
	}


	function usuarioExiste($usuario)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT id_usuario FROM tbl_usuario WHERE usuario = ? LIMIT 1");
		$stmt->bind_param("s", $usuario);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();
		
		if ($num > 0){
			return true;
			} else {
			return false;
		}
	}
	
	function emailExiste($email)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT id_usuario FROM tbl_usuario WHERE email = ? LIMIT 1");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();
		
		if ($num > 0){
			return true;
			} else {
			return false;
		}
	}

	function generateToken()
	{
		$gen = md5(uniqid(mt_rand(), false));	
		return $gen;
	}

	function hashPassword($password) 
	{
		$hash = password_hash($password, PASSWORD_DEFAULT);
		return $hash;
	}
	

function resultBlock($errors){
	if(count($errors) > 0)
	{
		echo "<div id='error' class='alert alert-danger' role='alert'>
		<a href='#' onclick=\"showHide('error');\">[X]</a>
		<ul>";
		foreach($errors as $error)
		{
			echo "<li>".$error."</li>";
		}
		echo "</ul>";
		echo "</div>";
	}
}



  function registraUsuario($nombre,  $usuario, $pass_hash, $rol,  $estado, $email, $token){
		global $mysqli;
		
		$stmt = $mysqli->prepare("INSERT INTO tbl_usuario (nombre, usuario, password, id_rol, id_estado, email, fecha_creacion, token) VALUES(?,?,?,?,?,?,now(),?)");
		$stmt->bind_param('sssiiss', $nombre,  $usuario, $pass_hash, $rol,  $estado, $email, $token);
		
		if ($stmt->execute()){
			return $mysqli->insert_id;
			} else {
			return 0;	
		}		
	}
	
	function enviarEmail($email, $nombre, $asunto, $cuerpo){
		
		require_once 'PHPMailer/PHPMailerAutoload.php';
		
		$mail = new PHPMailer();
	    $mail->isSMTP();
	    $mail->SMTPAuth = true;
	    $mail->SMTPSecure = 'tls';//Modificar
	    $mail->Host = 'smtp.gmail.com';//Modificar
	    $mail->Port = '587';//Modificar
	    $mail->Username = 'henry.funes26@gmail.com'; //Modificar
	    $mail->Password = 'andre9826'; //Modificar
	
	    $mail->setFrom('henry.funes26@gmail.com', 'RRHH');//Modificar
	
	    $mail->addAddress('$email', '$nombre');//Modificar
	
	    $mail->Subject = '$asunto';//Modificar
		$mail->Body = '$cuerpo'; //Modificar
		
		$mail->IsHTML(true);
	
	    if($mail->send()){
		echo 'true';
		} else {
		echo 'false';
	}
}

?>