
<?php
	include_once '../conexion.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Registro</title>
	<?php require_once "scripts.php"; ?>
	
	<script type="text/javascript">
		
	</script>

</head>
	<body>

		<div class="container" style="opacity: 0.90;padding-top: 30px;">
			<div class="row">
				<div class="col-sm-3"></div>
					<div class="col-sm-6">
						<div class="panel panel-primary">
							<div class="panel panel-heading">REGISTRO DE USUARIOS</div>
								<div class="panel panel-body">
									
									<form id="frmlogin" action="registro_usuario.php" method="GET" autocomplete="off" >
									

											 <label>Ingresa tu codigo de empleado</label>
											<br>
											<div class="input-group">
											    <input id="empleado" autofocus style="text-transform:uppercase;" type="text" name="empleado" placeholder="Ingresa tu codigo de empleado" class="form-control" >
											    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											 </div>

											<button style="width: 300px" class="btn btn-primary" type="submit" id="guardar">Registrarse</button>
											<a style="text-align: right;" href="../login/login.php" class="btn btn-danger">Login</a>
											
											
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


// validar el input del correo
	$(document).ready(function() {
		 	$("#correo").hide();
			$("#correo2").hide();
			
				$("#e").keyup(function(){	
					var correo=false;
					var pswd = $("#e").val();

					 if (!pswd.match(/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/)) {
				      $("#correo2").css({"color":"red"});
				      $("#correo").hide();
				      $("#correo2").show();
				      $('#guardar').attr("disabled", true);
				      Correo = false;
				    } else {
				       $("#correo").css({"color":"green"});
				       $("#correo2").hide();
				       $("#correo").show();
				       $('#guardar').attr("disabled", false);
				      Correo = true;
				}	
		});
	});


	//no permitir espacios en blanco en el usuario
	$(document).ready(function() {
		var verificar=true;
	    $("#empleado").keypress(function(e) {
	        if (e.which == 32) {
	        	alertify.alert("No se Permiten Espacios en Blanco");
	            return false;
	        }
		});

	});



	//validad que los campos no esten vacios y que las contrase;as coincidan
	$(document).ready(function(){
		$('#guardar').click(function(){

			if ($('#empleado').val()=="") {
				alertify.error("debe ingresar el nombre");
				return false;
			}
		});	
	});	


</script>

