<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once"scripts.php"; ?>
    <title>Document</title>
</head>

</html>
<?php
	//esto siempre se coloca para iniciar sesion
	session_start();

	include_once '../conexion.php';
	

	$usuario=$_POST["usuario"];
	$usuario_login= strtoupper($usuario);
	$contrasena_login=$_POST["contrasena"];
	unset($POST);
	//OBTENIENDO ID DEL OBJETO
	$pagina="login";
	$objeto = 'SELECT * FROM tbl_objetos WHERE objeto=?';
	$objeto_s=$pdo->prepare($objeto);
	$objeto_s->execute(array($pagina));
	$objeto_r=$objeto_s->fetch();
	$id_objeto=$objeto_r['id_objeto'];
	//verificamos si existe el usuario
	$sql= 'SELECT * FROM tbl_usuario WHERE usuario=?';
	$sentencia= $pdo->prepare($sql);
	$sentencia->execute(array($usuario_login));
	//el fetch nos devuelve un verdadero si encuentra un valor en el nombre
	$resultado= $sentencia->fetch();

	//OBTENIENDO ADMIN LA TABLA PARAMETROS	
	$parametro_admin="ADMIN_CUSER";
	$sql_admin= 'SELECT * FROM tbl_parametro WHERE parametro=?';
	$sentencia_usuario= $pdo->prepare($sql_admin);
	$sentencia_usuario->execute(array($parametro_admin));
	$admin_user=$sentencia_usuario->fetch();

	//OBTENIENDO CONTRASEÑA ADMIN LA TABLA PARAMETROS	
	$parametro_contrA="ADMIN_CPASS";
	$sql_adminC= 'SELECT * FROM tbl_parametro WHERE parametro=?';
	$sentencia_contra= $pdo->prepare($sql_admin);
	$sentencia_contra->execute(array($parametro_contrA));
	$admin_contra=$sentencia_contra->fetch();

	date_default_timezone_set('America/Tegucigalpa');
	$fecha_actual = date("Y-m-d H:i:s");

	if($usuario_login == $admin_user['valor']){
		if($contrasena_login==$admin_contra['valor']){
			//INSERTANDO BITACORA
			$fecha_actual = date("Y-m-d H:i:s");
			$accion="SESION INICIADA";
			$descripcion="Admin inicio sesion en el sistema";
			$sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
			$bitacora=$pdo->prepare($sql_bitacora);
			$bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
			$_SESSION["usuario_admin"]=$usuario_login;
			echo '<script>
				swal({
					title: "Bienvenido",
					text: "Admin",
					type: "success",
					timer: 2000,
					showConfirmButton: false
				}, function(){
						window.location.href = "../principal.php";
				});
				</script>';
				die();		
		}else{
			//INSERTANDO BITACORA
			$fecha_actual = date("Y-m-d H:i:s");
			$accion="ERROR DE ADMIN";
			$descripcion="Intento fallido del administrador para entrar";
			$sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
			$bitacora=$pdo->prepare($sql_bitacora);
			$bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
			echo '<script>
				setTimeout(function() {
				swal({
				title: "Error",
				text: "Datos ingresados inválidos",
				type: "warning"
				}, function() {
				window.location = "../../index.php";
				});
				}, 1);
			</script>';
			die();
		}
		
	}else{
		if (!$resultado) {
			$mensaje = "EL USUARIO NO ESTA REGISTRADO";
			echo '<script>
				setTimeout(function() {
				swal({
				title: "Error",
				text: "El usuario no esta registrado",
				type: "warning"
				}, function() {
				window.location = "../registro/registro.php";
				});
				}, 1);
			</script>';	
			die();
		}
		else {
			if($resultado["fecha_vencimiento"]!=NULL){
				if (password_verify($contrasena_login, $resultado["contrasena"])) {
					$usuariobitacora= $resultado["usuario"];
					/*SE DEBE AGREGAR UN CAMPO PARA TIPO DE USUARIO EN LA TABLA DE USUARAIOS
					/*PARA SABER SI QUIEN SE LOGEA ES UNA CUENTA DE ADMINISTRADOR O UNA CUENTA CON MENOS 
					/*PERMISOS*/
					switch($resultado["id_rol"]){	
						case 2:
							switch($resultado["estado"]){
								case "ACTIVO":
									$_SESSION['usuario_rrhh']=$usuario_login;
									insertar_bitacora("INGRESO AL SISTEMA", "Usuario ".$usuariobitacora ." ingreso al sistema", $id_objeto);
									$id_usuario=$resultado["id_usuario"];
									//Actualizando Campo de veces logueado
									$sql_get_ingresos='SELECT * FROM tbl_usuario WHERE id_usuario =?';
									$get_ingresos=$pdo->prepare($sql_get_ingresos);
									$get_ingresos->execute(array($id_usuario));
									$ingresos= $get_ingresos->fetch();
									$ingreso=$ingresos['primer_ingreso'];
									$ingreso++;
									$sql_upd_pusuarios='UPDATE tbl_usuario SET primer_ingreso=? WHERE id_usuario =?';
									$update_usuario=$pdo->prepare($sql_upd_pusuarios);
									$update_usuario->execute(array($ingreso,$id_usuario));
									//Reset de intentos 
									$intentos=0;
									$sqlUpdLog = 'UPDATE tbl_usuario SET ultima_conexion=?, intentos=? WHERE id_usuario =?';
									$sentenciaUpdLog = $pdo->prepare($sqlUpdLog);
									$sentenciaUpdLog ->execute([date("Y-m-d H:i:s"), $intentos, $id_usuario]);
									
									echo '<script>
										swal({
											title: "Bienvenido",
											text: "",
											type: "success",
											timer: 2000,
											showConfirmButton: false
										}, function(){
												window.location.href = "../../index.php";
										});
										</script>';
										break;
								case "NUEVO":
									$_SESSION['usuario_rrhh']=$usuario_login;
									$intentos=0;
									$id_usuario=$resultado["id_usuario"];
									$sqlUpdLog = 'UPDATE tbl_usuario SET ultima_conexion=?, intentos=? WHERE id_usuario =?';
									$sentenciaUpdLog = $pdo->prepare($sqlUpdLog);
									$sentenciaUpdLog ->execute([date("Y-m-d H:i:s"), $intentos, $id_usuario]);
									insertar_bitacora("INGRESO USUARIO", "Usuario nuevo: ".$usuariobitacora. " entro al sistema", $id_objeto);
									
									//Actualizando Campo de veces logueado
									$sql_get_ingresos='SELECT * FROM tbl_usuario WHERE id_usuario =?';
									$get_ingresos=$pdo->prepare($sql_get_ingresos);
									$get_ingresos->execute(array($id_usuario));
									$ingresos= $get_ingresos->fetch();
									$ingreso=$ingresos['primer_ingreso'];
									$ingreso++;
									$sql_upd_pusuarios='UPDATE tbl_usuario SET primer_ingreso=? WHERE id_usuario =?';
									$update_usuario=$pdo->prepare($sql_upd_pusuarios);
									$update_usuario->execute(array($ingreso,$id_usuario));
									
									echo '<script>
											swal({
												title: "Bienvenido",
												text: "Como usuario nuevo debes llenar primero las preguntas de recuperación",
												type: "success"
											}, function(){
													window.location.href = "../preguntas_secretas/preguntas.php";
											});
											</script>';
								break;
								case "INACTIVO":
									$id_usuario=$resultado["id_usuario"];
									$sqlUpdLog = 'UPDATE tbl_usuario SET ultima_conexion=? WHERE id_usuario =?';
									$sentenciaUpdLog = $pdo->prepare($sqlUpdLog);
									$sentenciaUpdLog ->execute([date("Y-m-d H:i:s"), $id_usuario]);
									insertar_bitacora("INTENTO DE INGRESO", "Usuario inactivo: ".$usuariobitacora. " intento entrar al sistema",  $id_objeto);
									//Actualizando Campo de veces logueado
									$sql_get_ingresos='SELECT * FROM tbl_usuario WHERE id_usuario =?';
									$get_ingresos=$pdo->prepare($sql_get_ingresos);
									$get_ingresos->execute(array($id_usuario));
									$ingresos= $get_ingresos->fetch();
									$ingreso=$ingresos['primer_ingreso'];
									$ingreso++;
									$sql_upd_pusuarios='UPDATE tbl_usuario SET primer_ingreso=? WHERE id_usuario =?';
									$update_usuario=$pdo->prepare($sql_upd_pusuarios);
									$update_usuario->execute(array($ingreso,$id_usuario));
									
									echo '<script>
											setTimeout(function() {
											swal({
											title: "Cuenta inactivada",
											text: "Favor comuníquese con el administrador",
											type: "warning"
											}, function() {
											window.location = "../login/login.php";
											});
											}, 1);
										</script>';		
									die();
								break;
								case "BLOQUEADO":
									$intentos=$resultado["intentos"]+1;
									$id_usuario=$resultado["id_usuario"];
									$sqlUpdLog = 'UPDATE tbl_usuario SET fecha_ultimo_intento=?, intentos=? WHERE id_usuario =?';
									$sentenciaUpdLog = $pdo->prepare($sqlUpdLog);
									$sentenciaUpdLog ->execute([date("Y-m-d H:i:s"), $intentos, $id_usuario]);
									insertar_bitacora("INTENTO DE INGRESO", "Usuario bloqueado: ".$usuariobitacora. " intento entrar al sistema", $id_objeto);
									//Actualizando Campo de veces logueado
									$sql_get_ingresos='SELECT * FROM tbl_usuario WHERE id_usuario =?';
									$get_ingresos=$pdo->prepare($sql_get_ingresos);
									$get_ingresos->execute(array($id_usuario));
									$ingresos= $get_ingresos->fetch();
									$ingreso=$ingresos['primer_ingreso'];
									
									$ingreso++;
									$sql_upd_pusuarios='UPDATE tbl_usuario SET primer_ingreso=? WHERE id_usuario =?';
									$update_usuario=$pdo->prepare($sql_upd_pusuarios);
									$update_usuario->execute(array($ingreso,$id_usuario));
									
									echo '<script>
											setTimeout(function() {
											swal({
											title: "Usuario Bloqueado",
											text: "Ha sobrepasado el límite de intentos para entrar al sistema",
											type: "warning"
											}, function() {
											window.location = "../login/login.php";
											});
											}, 1);
										</script>';	
									die();
								break;
								default:
									$mensaje = "HAY UN ERROR CON SU CUENTA INTENTE DE NUEVO O COMUNIQUESE CON EL ADMINISTRADOR";
									echo '<script>
											setTimeout(function() {
											swal({
											title: "Error",
											text: "Intente de nuevo o comuníquese con el administrado",
											type: "warning"
											}, function() {
											window.location = "../login/login.php";
											});
											}, 1);
										</script>';		
									die();
							}
						break;
	
						case 3:
							$id_usuario=$resultado["id_usuario"];
							$sqlUpdLog = 'UPDATE tbl_usuario SET fecha_ultimo_intento=? WHERE id_usuario =?';
							$sentenciaUpdLog = $pdo->prepare($sqlUpdLog);
							$sentenciaUpdLog ->execute([date("Y-m-d H:i:s"), $id_usuario]);
							$mensaje="Usuario no verificado: ".$usuariobitacora. " intento entrar al sistema";
							insertar_bitacora("INTENTO DE INGRESO", $mensaje, $id_objeto);
							
							echo '<script>
									setTimeout(function() {
										swal({
										title: "Error",
										text: "Su cuenta requiere ser verififcada, comuníquese con el administrador",
										type: "warning"
										}, function() {
										window.location = "../login/login.php";
										});
										}, 1);
								</script>';	
							die();
						
						default:
								echo '<script>
										setTimeout(function() {
										swal({
										title: "Error",
										text: "Intente de nuevo o comuníquese con el administrado",
										type: "warning"
										}, function() {
										window.location = "../login/login.php";
										});
										}, 1);
									</script>';		
							die();
	
					}
	
				}else{
					$usuariobitacora= $resultado["usuario"];
					$intentos=$resultado["intentos"]+1;
					$id_usuario=$resultado["id_usuario"];
					$sqlUpdLog = 'UPDATE tbl_usuario SET fecha_ultimo_intento=?, intentos=? WHERE id_usuario =?';
					$sentenciaUpdLog = $pdo->prepare($sqlUpdLog);
					$sentenciaUpdLog ->execute([date("Y-m-d H:i:s"), $intentos, $id_usuario]);
					
					//CONSULTANDO EL PARAMETRO ADMIN_INTENTOS
					$parametro_intentos="ADMIN_INTENTOS";
					$sql_intentos= 'SELECT * FROM tbl_parametro WHERE parametro=?';
					$sentencia_intentos= $pdo->prepare($sql_intentos);
					$sentencia_intentos->execute(array($parametro_intentos));
					$intentos_r=$sentencia_intentos->fetch();
					$intentos_parametro= (int)$intentos_r['valor']-1;

					//BLOQUEANDO USUARIO SI EXCEDE LOS INTENTOS DE PARAMETRO DADO
					if($intentos>$intentos_parametro+1){
						$estado="BLOQUEADO";
						$sqlBloqUs= 'UPDATE tbl_usuario SET estado=? WHERE id_usuario =?';
						$sentenciaBloqUs = $pdo->prepare($sqlBloqUs);
						$sentenciaBloqUs ->execute([$estado, $id_usuario]);
						//INSERTANDO BITACORA
						$fecha_actual = date("Y-m-d H:i:s");
						$accion="BLOQUEO";
						$descripcion="Se bloqueo al usuario ".$usuariobitacora." por intentos fallidos";
						$sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
						$bitacora=$pdo->prepare($sql_bitacora);
						$bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
						echo '<script>
								setTimeout(function() {
								swal({
								title: "Usuario Bloqueado",
								text: "Ha sobrepasado el límite de intentos para entrar al sistema",
								type: "warning"
								}, function() {
								window.location = "../login/login.php";
								});
								}, 1);
							</script>';	 	
						die();
					}else{
						echo '<script>
							setTimeout(function() {
								swal({
								title: "Error",
								text: "Usuario o Contraseña incorrectos",
								type: "warning"
								}, function() {
								window.location = "../login/login.php";
								});
								}, 1);
							</script>';
						die();
					}
				}
			}else{
				
				
			}
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
