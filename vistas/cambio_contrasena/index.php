
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cambio de Contraseña</title>
	<?php require_once "scripts.php"; ?>
	
	<script type="text/javascript">
		
	</script>

</head>
	<body>
    <?php
	session_start();
	require_once '../conexion.php';

    if (isset($_SESSION['usuario_rrhh']) || isset($_SESSION['usuario_admin']))
    {?>
        <div class="container" style="opacity: 0.90;padding-top: 30px;">
			<div class="row">
				<div class="col-sm-3"></div>
					<div class="col-sm-6">
						<div class="panel panel-primary">
							<div class="panel panel-heading">REGISTRO DE USUARIOS</div>
								<div class="panel panel-body">
									
									<form id="frmlogin" action="cambiar_contrasena" method="POST" autocomplete="off" >
											 <label>Ingresa tu Contraseña Actual</label>
											<br>
											<div class="input-group">
											    <input id="contrasenaV" type="password" name="contrasenaV" placeholder="Ingresa Tu Contraseña Actual" class="form-control" >
											    <span class=" input-group-addon glyphicon glyphicon-eye-open" style="cursor: pointer;"      id="show-hide-pass"action="hide" >
												</span>
											 </div>
											<br>
											<br>
											<label  >Ingresa la Nueva Contraseña</label>
											<br>

											<div class="input-group" >
												<input class=" form-control" id="password" type="password" minlenght="8" name="contrasena" placeholder="Ingresa contraseña" required>

												<span class="input-group-addon glyphicon glyphicon-eye-open"style="cursor: pointer;"
											   id="show-hide-pass2"action="hide" ></span>
											 </div>

												<ul style="text-align:left;">
													<li class="invalid" id="mayus">Mayusculas</li>
													<li class="invalid" id="especial">caracter especial</li>						
													<li class="invalid" id="number">numeros</li>
													<li class="invalid" id="lower">minusculas</li>
													<li class="invalid" id="len">minimo 8 caracteres</li>	
												</ul>
										

											<label>Confirmar Nueva Contraseña</label>
											<br>
											<div class="input-group">
											   <input type="password" id="password2" name="contrasena2" placeholder="Confirmar contraseña" class="form-control" >
											   <span class="input-group-addon glyphicon glyphicon-eye-open"style="cursor: pointer;"
											   id="show-hide-pass3" action="hide" ></span>
											</div>
											

											<br>
										
											<button style="width: 300px" class="btn btn-primary" type="submit" id="guardar">Cambiar Contraseña</button>
									</form>
								</div>
						</div>
					</div>
				<div class="col-sm-4"></div>
			</div>
		</div>
<?php
    }else{
        header('Location:../login/login.php');
    }
    ?>
		

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

	//funcion que valida la contrase;a....8 caracteres....minusculas...mayusculas....caracter especial....numeros
	$(function(){

			var longitud = false,
		    minuscula = false,
		    numero = false,
		    mayuscula = false,
		    special = false;
		    
		$('#password').keyup(function() {
		    var pswd = $("#password").val();

		    if (pswd.length < 8) {
		     $("#len").css({"color":"red"});
		      longitud = false;
		    } else {
		      $("#len").css({"color":"green"});
		      longitud = true;
		    }

		    //validar minusculas
		    if (pswd.match(/[a-z]/)) {
		      $("#lower").css({"color":"green"});
		      minuscula = false;
		    } else {
		      $("#lower").css({"color":"red"});
		      minuscula = true;
		    }

		    //validar mayusculas
		    if (pswd.match(/[A-Z]/)) {
		     $("#mayus").css({"color":"green"});
		      mayuscula = true;
		    } else {
		      $("#mayus").css({"color":"red"});
		      mayuscula = false;
		    }

		    //validar numeros
		    if (pswd.match(/\d/)) {
		       $("#number").css({"color":"green"});
		      numero = true;
		    } else {
		       $("#number").css({"color":"red"});
		      numero = false;
		    }

		    //validar caracter especial
		    if (pswd.match(/[¿='?,\.\/\\~!\|,@#\$%\^&\*""\(\)_\+]/)) {
		       $("#especial").css({"color":"green"});
		      special = true;
		    } else {
		       $("#especial").css({"color":"red"});
		      special = false;
		    }

		});
		
	});



	//validad que los campos no esten vacios y que las contrase;as coincidan
	$(document).ready(function(){
		$('#guardar').click(function(){


			//longitud
			if ( $("#password").val().length < 8) {
				alertify.error("La contraseña debe contener al menos 8 caracteres");
				return false;
			}

			//validar minusculas
			if ( $("#password").val().match(/[a-z]/)) {
			} else {
				alertify.error("Favor incluya al menos una minúscula en la contraseña");
				return false;
			}

			//validar mayusculas
			if ( $("#password").val().match(/[A-Z]/)) {
				
			} else {
				alertify.error("Favor incluya al menos una mayuscula en su contraseña");
				return false;
			}

			//validar numeros
			if ( $("#password").val().match(/\d/)) {

			} else {	
				alertify.error("Favor incluya al menos un dígito númerico en la contraseña");
				return false;
			}

			//validar caracter especial
			if ( $("#password").val().match(/[¿='?,\.\/\\~!\|,@#\$%\^&\*""\(\)_\+]/)) {

			} else {
				alertify.error("Favor incluya al menos un caracter NO alfa-numérico");
				return false;
			}
			if ($('#contrasenaV').val()=="") {
				alertify.error("Debe ingresar su contraseña actual");
				return false;
			}else if($('#password').val()==""){
				alertify.error("Debe Ingresar La Contraseña Nueva");
				return false;
			}else if($('#password2').val()==""){
				alertify.error("Debe Confirmar Su Nueva Contraseña");
				return false;
			}else if ($('#e').val()=="") {
				alertify.error("El Correo No puede estar Vacio");
				return false;
			}else if ($('#password').val()!=$('#password2').val()) {
				alertify.error("Las Contraseñas no Coinciden");
				return false;
			}

		});	
	});	


	//aplicar la funcionalidad de poder ver la contrse;a que se esta escribiendo
	$(document).ready(function(){
		$('#show-hide-pass').on('click',function(e){
			e.preventDefault();

			var current= $(this).attr('action');

			if (current=='hide') {
				$(this).prev().attr('type','text');
				$(this).removeClass('glyphicon-eye-open').addClass(' glyphicon-eye-close').attr('action','show');
			}
			if (current=='show') {
				$(this).prev().attr('type','password');
				$(this).removeClass(' glyphicon-eye-close').addClass('glyphicon-eye-open').attr('action','hide');
			}
		});	
	});

	$(document).ready(function(){
		$('#show-hide-pass2').on('click',function(e){
			e.preventDefault();

			var current= $(this).attr('action');

			if (current=='hide') {
				$(this).prev().attr('type','text');
				$(this).removeClass('glyphicon-eye-open').addClass(' glyphicon-eye-close').attr('action','show');
			}
			if (current=='show') {
				$(this).prev().attr('type','password');
				$(this).removeClass(' glyphicon-eye-close').addClass('glyphicon-eye-open').attr('action','hide');
			}
		});	
	});

    
	$(document).ready(function(){
		$('#show-hide-pass3').on('click',function(e){
			e.preventDefault();

			var current= $(this).attr('action');

			if (current=='hide') {
				$(this).prev().attr('type','text');
				$(this).removeClass('glyphicon-eye-open').addClass(' glyphicon-eye-close').attr('action','show');
			}
			if (current=='show') {
				$(this).prev().attr('type','password');
				$(this).removeClass(' glyphicon-eye-close').addClass('glyphicon-eye-open').attr('action','hide');
			}
		});	
	});

</script>