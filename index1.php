<?php 
	
$alert = '';
session_start();
if(!empty($_SESSION['active']))
{
	header('location: index1.php');
}else{

	if(!empty($_POST))
	{
		if(empty($_POST['usuario']) || empty($_POST['clave']))
		{
			$alert = 'Ingrese su usuario y su calve';
		}else{

           require  'config/conexion.php';

			$user = mysqli_real_escape_string($mysqli,$_POST['usuario']);
			// $pass = md5(mysqli_real_escape_string($mysqli,$_POST['clave']));
			$pass = (mysqli_real_escape_string($mysqli,$_POST['clave']));

			$query = mysqli_query($mysqli,"SELECT * FROM tbl_usuario WHERE usuario= '$user' AND contrasena = '$pass'");
			mysqli_close($mysqli);
			$result = mysqli_num_rows($query);

			if($result > 0)
			{
				$data = mysqli_fetch_array($query);
				$_SESSION['active']    = true;
				$_SESSION['idUser']    = $data['id_usuario'];
				$_SESSION['email']     = $data['contrasena'];
				$_SESSION['user']      = $data['usuario'];
				$_SESSION['id_rol']    = $data['id_rol'];

				header('location: vistas/principal.php');
			}else{
				$alert = 'El usuario o la clave son incorrectos';
				session_destroy();
			}


		}

	}
}
 ?>

	<section id="container">
		
		<form action="" method="post">
			
			<h3>Iniciar Sesión</h3>
			<img src="img/login.png" alt="Login">

			<input type="text" name="usuario" placeholder="Usuario">
			<input type="password" name="clave" placeholder="Contraseña">
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<input type="submit" value="INGRESAR">

		</form>

	</section>