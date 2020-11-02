
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
    <?php
	include_once '../conexion.php';
    $codigo_empleado= $_GET['empleado'];
   

	$empleado_usuario='SELECT * FROM tbl_usuario WHERE cod_empleado=?';
    $sentencia_empleado_usuario= $pdo->prepare($empleado_usuario);
	$sentencia_empleado_usuario->execute(array($codigo_empleado));
	$empleadoUsuario=$sentencia_empleado_usuario->fetch();

	if($empleadoUsuario){
		echo '<script>
						setTimeout(function() {
						swal({
						title: "Error",
						text: "El codigo de empleado ya esta registrado",
						type: "warning"
						}, function() {
						window.history.back();
						});
						}, 1);
					</script>';
					die();
	}

	$empleado_sql='SELECT * FROM tbl_empleados WHERE cod_empleado=?';
    $sentencia_empleado= $pdo->prepare($empleado_sql);
	$sentencia_empleado->execute(array($codigo_empleado));
    if($empleado=$sentencia_empleado->fetch()){
        $apelllido1= $empleado['primer_apellido'];
        $apelllido2= $empleado['segundo_apellido'];
        $nombre= $empleado['primer_nombre'];
        $nombre2=$empleado['segundo_nombre'];
        $nombre .= " ".$nombre2." ".$apelllido1." ".$apelllido2;
        ?>
        <div class="container" style="opacity: 0.90;padding-top: 30px;">
			<div class="row">
				<div class="col-sm-3"></div>
					<div class="col-sm-6">
						<div class="panel panel-primary">
							<div class="panel panel-heading">REGISTRO DE USUARIOS</div>
								<div class="panel panel-body">
									
									<form id="frmlogin" action="agregar_usuario.php/?<?php echo "empleado=".$codigo_empleado?>" method="POST" autocomplete="off" >
									
                                            <label>Codigo de Empleado</label>
											<br>
											<div class="input-group">
											    <input id="em" type="text" name="empleado" placeholder='<?php echo $codigo_empleado?>' class="form-control" disabled>
											    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											 </div>
											 <br>
											 <br>
											

                                             <label>Nombre del Empleado</label>
											<br>
											<div class="input-group">
											    <input type="text" style="text-transform:uppercase;" placeholder='<?php echo $nombre;?>'   class="form-control" disabled>
											    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											 </div>
											 <br>
											 <br>

                                             <label>Numero de Identidad</label>
											<br>
											<div class="input-group">
											    <input type="text" style="text-transform:uppercase;" placeholder=<?php echo $empleado['identidad'];?> class="form-control" disabled>
											    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											 </div>
											 <br>
											 <br>

											<label>Ingresa Nombre de Usuario</label>
											<br>
											<div class="input-group">
											    <input id="usuario" type="text" style="text-transform:uppercase;" onfocus="empleado()" name="nombre_usuario" placeholder="Ingresa usuario" class="form-control" >
											    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											 </div>
											 <br>
											 <br>

											<label  >Ingresa la Contraseña</label>
											<br>

											<div class="input-group" >
												<input class=" form-control" id="password" type="password" minlenght="8" name="contrasena" placeholder="INGRESA TU CONTRASEÑA"required>

												<span class=" input-group-addon glyphicon glyphicon-eye-open" style="cursor: pointer;"      id="show-hide-pass"action="hide" >
												</span>
											 </div>

												<ul style="text-align:left;">
													<li class="invalid" id="mayus">Mayusculas</li>
													<li class="invalid" id="especial">caracter especial</li>						
													<li class="invalid" id="number">numeros</li>
													<li class="invalid" id="lower">minusculas</li>
													<li class="invalid" id="len">minimo 8 caracteres</li>	
												</ul>
										

											<label>Confirmar Contraseña</label>
											<br>
											<div class="input-group">
											   <input type="password" id="password2" name="contrasena2" placeholder="CONFIRMA CONTRASEÑA" class="form-control" >
											   <span class="input-group-addon glyphicon glyphicon-eye-open"style="cursor: pointer;"
											   id="show-hide-pass2"action="hide" ></span>
											</div>
											

											<br>
											<label>Ingresar Correo</label>
											<br>
											<div class="input-group">  
											<input type="email" name="correo"  id="e" placeholder="Ingresar Correo" class="form-control" >
											<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
											</div>
																						

											<ul style="text-align: left;"><li class="invalid" id="correo">Correo Correcto</li></ul>
											<ul style="text-align: left;"><li class="invalid" id="correo2">Correo Incorrecto</li></ul>
										
											<button style="width: 300px" class="btn btn-primary" type="submit" id="guardar">Registrarse</button>
											<a style="text-align: right;" href="../login/login.php" class="btn btn-danger">Login</a>									
											
									</form>
								</div>
						</div>
					</div>
				<div class="col-sm-4"></div>
			</div>
		</div>


    <?php }else{
        echo '<script>
        setTimeout(function() {
        swal({
        title: "Error",
        text: "No existe el usuario",
        type: "warning"
        }, function() {
        window.location = "registro.php";
        });
        }, 1);
    </script>';	
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


	//no permitir espacios en blanco en el usuario
	$(document).ready(function() {
		var verificar=true;
	    $("#usuario").keypress(function(e) {
	        if (e.which == 32) {
	        	alertify.error("No se Permiten Espacios en Blanco");
	            return false;
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
			if ($('#usuario').val()=="") {
				alertify.error("debe ingresar el nombre");
				return false;
			}else if($('#password').val()==""){
				alertify.error("debe ingresar la contraseña");
				return false;
			}else if($('#password2').val()==""){
				alertify.error("debe confirmar la contraseña");
				return false;
			}else if ($('#e').val()=="") {
				alertify.error("El Correo No puede estar Vacio");
				return false;
			}else if ($('#password').val()!=$('#password2').val()) {
				alertify.error("Las Contraseñas no Coinciden");
				return false;
			}else if($('#usuario').val().match(/[0-9]/)){
				alertify.error("No se permiten numero en el nombre de usuario");
				return false;
			}else if($('#usuario').val().match(/[¿='?,\.\/\\~!\|,@#\$%\^&\*""\(\)_\+]/)){
				alertify.error("Favor no incluya caracteres especiales en el usuario");
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
	

</script>

