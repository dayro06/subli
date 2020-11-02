

<!DOCTYPE html>
<html>
<head>
<?php require_once "scripts.php";?>
	<title></title>
</head>
<body style="text-align: center">
<?php

	session_start();	
	include_once '../conexion.php';
	date_default_timezone_set('America/Tegucigalpa');
	

		$usuario_nuevo = $_POST['nombre_usuario'];
		$usuarioNuevo= strtoupper($usuario_nuevo);
		$codigo_empleado= $_GET['empleado'];
		$contrasena = $_POST['contrasena'];
		$contrasena2 = $_POST['contrasena2'];
		$email=$_POST['correo'];

		//OBTENIENDO ID DEL OBJETO
		$pagina="registro";
		$objeto = 'SELECT * FROM tbl_objetos WHERE objeto=?';
		$objeto_s=$pdo->prepare($objeto);
		$objeto_s->execute(array($pagina));
		$objeto_r=$objeto_s->fetch();
		$id_objeto=$objeto_r['id_objeto'];
		$codigo=$_GET['empleado'];
		
		//verificamos si existe el usuario
		$sql= 'SELECT * FROM tbl_usuario WHERE usuario=? OR correo_electronico=? OR codigo_empleado=';
		$sentencia= $pdo->prepare($sql);
		$sentencia->execute(array($usuarioNuevo,$email,$codigo));
		//el fetch nos devuelve un verdadero si encuentra un valor en el nombre
		$resultado= $sentencia->fetchAll();

		//si existe un usuario matamos la operacion
		if ($resultado) {
			
			echo '<script>
					setTimeout(function() {
					swal({
					title: "Error en el registro",
					text: "Ya existe este usuario",
					type: "warning"
					};
					window.location = "../login/login.php";
				</script>';	

			die();

		}else if(!$resultado){
			
		$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
	

		if (password_verify($contrasena2, $contrasena)) {

			//OBTENIENDO LA VIGENCIA DEL USUARIO DE LA TABLA PARAMETROS
			$parametro_vigencia="ADMIN_VIGENCIA";
			$sqlvig='SELECT * FROM tbl_parametro WHERE parametro=?';
			$sql_vigencia=$pdo->prepare($sqlvig);
			$sql_vigencia->execute(array($parametro_vigencia));
			$vigencia = $sql_vigencia->fetch();
			$vigencia_usuario=(int)$vigencia['valor'];
	
			$fecha_actual = date("Y-m-d H:i:s");
			$fecha_vencimiento = date("Y-m-d H:i:s",strtotime($fecha_actual."+ ".$vigencia_usuario." days"));

			//INSERTANDO DATOS DE NUEVO USUARIO
			$sql_agregar='INSERT INTO tbl_usuario
										(usuario,contrasena,correo_electronico,id_rol,cod_empleado,intentos, 
										preguntas_contestadas,fecha_vencimiento,estado,primer_ingreso,tipo_creacion) 
						  Values(?,?,?,?,?,?,?,?,?,?,?);';
			$sentencia_agregar= $pdo->prepare($sql_agregar);
			$estado = "NUEVO";
			$id_rol=3;
			$preguntas_contestadas =0;
			$intentos=0;
			$primer_ingreso=0;
			$creacion =1;

			//AGREGANDO EL USUARIO A TBL_USUARIO
			if ($sentencia_agregar->execute(array($usuarioNuevo,$contrasena,$email,$id_rol,$codigo_empleado,$intentos,$preguntas_contestadas,$fecha_vencimiento,$estado,$primer_ingreso,$creacion))) {
				$sql_usuario ='SELECT * FROM tbl_usuario WHERE usuario=?';
				$sql_sentencia_usuario=$pdo->prepare($sql_usuario);
				$sql_sentencia_usuario->execute(array($usuarioNuevo));
				$resultado_usuario=$sql_sentencia_usuario->fetch();
				$usuario_sesion= $resultado_usuario['usuario'];

				//AGREGANDO CONTRASEÑA A TBL_HISTORIA_CONTRASEÑA
				$sql_historial ='INSERT INTO tbl_historia_contrasena (id_usuario, contrasena,fecha_creacion) VALUES (?,?,?)';
				$sql_sentencia_historial=$pdo->prepare($sql_historial);
				if($sql_sentencia_historial->execute(array($resultado_usuario['id_usuario'],$contrasena,date("Y-m-d H:i:s")))){
					//INSERTANDO BITACORA
					$fecha_actual = date("Y-m-d H:i:s");
					$accion="INSERCION";
					$descripcion="Se registro el usuario ".$usuarioNuevo. " mediante autoregistro";
					$sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
					$bitacora=$pdo->prepare($sql_bitacora);
					$bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
					
					$_SESSION['usuario_rrhh']=$usuario_sesion;
					header('Location:../../preguntas_secretas/preguntas.php ');
						
				}else{
					//INSERTANDO BITACORA
					$fecha_actual = date("Y-m-d H:i:s");
					$accion="INSERCION";
					$descripcion="Se registro el usuario ".$usuarioNuevo ." mediante autoregistro";
					$sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
					$bitacora=$pdo->prepare($sql_bitacora);
					$bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
					$_SESSION['usuario_rrhh']=$usuario_sesion;
					header('Location:../../preguntas_secretas/preguntas.php ');

				}

				
				
			}else{
				echo '<script>
						setTimeout(function() {
						swal({
						title: "Error en el registro",
						text: "Intente de nuevo más tarde o comuníquese con el administrador",
						type: "warning"
						}, function() {
						window.location = "../login/login.php";
						});
						}, 1);
					</script>';
					die();	
			}

			
		}else{
			echo '<script>
						setTimeout(function() {
						swal({
						title: "Error",
						text: "Sus contraseñas no coinciden",
						type: "warning"
						}, function() {
						window.location = "../../login.php";
						});
						}, 1);
					</script>';
					die();	
		
		}
	
	}
	function insertar_bitacora($accion, $descripcion, $id_objeto){
		include '../conexion.php';
        $fecha_actual = date("Y-m-d H:i:s");
        $sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
        $bitacora=$pdo->prepare($sql_bitacora);
		$bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
	}




?>


</body>
</html>







