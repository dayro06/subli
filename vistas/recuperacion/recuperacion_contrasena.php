<?php require_once"scripts.php"; ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body style="text-align: center;background-image:url('sublilogo.jpg');">

	<div class="container" style="opacity: 0.85; margin-top: 5%;">
			<div class="row">
				<div class="col-sm-3"></div>
					<div class="col-sm-6">
						<div class="panel panel-primary">
							<div class="panel panel-heading">Recuperar Contraseña</div>
								<div class="panel panel-body">

									<form id="logear" action="recuperar_contrasena.php" method="POST" >
										<label>Ingresa Nombre de Usuario</label>

										<button	 href="../recuperacion/enviar-correo-re.php">porcorreo?</button>

										<div class="input-group">
											<input style=" text-transform: uppercase;" id="nombre" type="text" name="usuario" placeholder="ingresa usuario" class="form-control" required >
											<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										</div>
										<br>


										<div style="text-align: center">


											<br>
											<br>
											<button style="width: 300px;" class="btn btn-danger" type="submit" id="enviar_pregunta" href="login.php">
											Recuperar via preguntas secretas</button>
										</div>

										<br>

										<a style=" position: absolute; left: 5%;" href="../login/login.php" class="btn btn-primary">Login</a>

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
		$('#enviar_pregunta').click(function(){
			if ($('#nombre').val()=="") {
				alertify.error("debe ingresar el nombre");
				return false;
			}
		});
	});

</script>
