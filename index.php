
<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<?php require_once"scripts.php"; ?>
</head>
<body>

	<div style="background-color: white; opacity: 0.85; margin-top:10%;">
		<?php
	session_start();
	require_once 'vistas/conexion.php';

	if (isset($_SESSION['usuario_admin']) ) {
		
		header('Location: vistas/principal.php');
		
	}
	else if(isset($_SESSION['usuario_rrhh'])){
		$x=strtoupper( $_SESSION['usuario_rrhh']);
		$sentencia = 'SELECT * FROM tbl_usuario WHERE usuario=?';
		$sql = $pdo ->prepare($sentencia);
		$sql -> execute(array($_SESSION['usuario_rrhh']));
		$resultado = $sql->fetch();
		if($resultado["estado"]=="NUEVO" /*ESTADO NUEVO*/){
			header('Location: vistas/preguntas_secretas/preguntas.php');
		}else if($resultado["id_rol"]==3){
			echo '<script>
				setTimeout(function() {
				swal({
				title: "Cuenta inactivada",
				text: "Recuerde que debe comunicarse con el administrador para activar su cuenta",
				type: "warning"
				}, function() {
				window.location = "vistas/cerrar.php";
				});
				}, 1);
			</script>';	
		}else{
			echo "<h1>Bienvenido al Sistema</h1>";
			echo "<h1>Bienvenido: </h1>";
			echo "<h1>$x</h1>";
			echo '<br><a href="vistas/cerrar.php">Cerrar Sesion</a>';
		}
			
		}
		
	else{
		header('Location:vistas/login/login.php');
	}	
	?>
	</div>



</body>
</html>