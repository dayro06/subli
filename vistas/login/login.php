<!DOCTYPE html>
<html lang="en">
<head>
	<title>Inicio de Sesión</title>
	<?php
		require_once "scripts.php"; ?>
</head>
<body>
	<div class="container" style=" opacity: 0.90; margin-top: 10%;">
			<div class="row">
				<div class="col-sm-3"></div>
					<div class="col-sm-6">
						<div class="panel panel-primary">
							<div class="panel panel-heading">Inicio de Sesión</div>
								<div class="panel panel-body">
								
									<form id="logear" action="logear.php" method="POST" autocomplete="off" >
										<label>Ingresa Nombre de Usuario</label>
										<div class="input-group">
											<input id="nombre" type="text" style="text-transform:uppercase;" name="usuario" placeholder="Ingresa usuario" class="form-control">
											<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										</div>

										<br>
										<br>
										<label>Ingresa Contraseña</label>
										<br>

										<!--div class="input-group">
											<input  id="password" type="password" name="contrasena" placeholder="ingresa contraseña" class="form-control">
											<span style="cursor: pointer" id="show-hide-pass" action="hide" class="input-group-addon glyphicon glyphicon-eye-open" ></span>

										</div-->
										<div class="input-group" id="show_hide_password">
      											<input class="form-control" type="password" name= "contrasena" placeholder= "INGRESA TU CONTRASEÑA">
      											<div class="input-group-addon">
        											<a href=""><i class="glyphicon glyphicon-eye-open" aria-hidden="true"></i></a>
      											</div>
    									</div>
											<!--<a href="recuperacion.php">Olvidaste tu contraseña</a>-->

										<br>
										<br>
										<button style="width: 300px" class="btn btn-primary" type="submit" id="guardar">Entrar</button>
									
												<a href="../registro/registro.php" class="btn btn-danger">Registrarse</a>
												<br>
												<br>
												<a href="../recuperacion/recuperacion_contrasena.php">Olvido su usuario y/o contraseña?</a>
											
									</form>			
								</div>
						</div>
					</div>
				<div class="col-sm-4"></div>
			</div>
		</div>
		

</body>
</html>

<script type="text/javascript">
	
	$(function(){
		$('#guardar').click(function(){
			if ($('#nombre').val()=="") {
				alertify.error("debe ingresar el nombre");
				return false;
			}else if($('#password').val()==""){
				alertify.error("debe ingresar la contraseña");
				return false;
			}else if($('#nombre').val().match(/[0-9]/)){
				alertify.error("Favor no incluya numeros en el nombre de usuario");
				return false;
			}else if($('#nombre').val().match(/[¿='?,\.\/\\~!\|,@#\$%\^&\*""\(\)_\+]/)){
				alertify.error("Favor no incluya caracteres especiales en el usuario");
				return false;

			}
		});
	});

</script>
<script>
	$(document).ready(function() {
		$("#show_hide_password a").on('click', function(event) {
			event.preventDefault();
			if($('#show_hide_password input').attr("type") == "text"){
				$('#show_hide_password input').attr('type', 'password');
				$('#show_hide_password i').addClass( "glyphicon-eye-close" );
				$('#show_hide_password i').removeClass( "glyphicon-eye-open" );
			}else if($('#show_hide_password input').attr("type") == "password"){
				$('#show_hide_password input').attr('type', 'text');
				$('#show_hide_password i').removeClass( "glyphicon-eye-close" );
				$('#show_hide_password i').addClass( "glyphicon-eye-open" );
			}
		});
	});
</script>





	

	
