
<?php

	session_start();
	include_once '../conexion.php';
	require_once "scripts.php";

	$usuario=$_POST["usuario"];
	$usuario_login= strtoupper($usuario);
	
	//verificamos si existe el usuario
	$sql= 'SELECT * FROM tbl_usuario WHERE usuario=?';
	$sentencia= $pdo->prepare($sql);
	$sentencia->execute(array($usuario_login));
	//el fetch nos devuelve un verdadero si encuentra un valor en el nombre
	$resultado= $sentencia->fetch();


	if (!$resultado) {

		 echo '<script>
                setTimeout(function() {
                swal({
                title: "Error",
                text: "EL USUARIO NO ESTA REGISTRADO",
                type: "warning"
                }, function() {
                window.location = "recuperacion_contrasena.php";
                });
                }, 1);
            </script>';
            die();

	}

	


?>

<!DOCTYPE html>
<html>
<head>
	<title>Recuperacion por Pregunta</title>
	<?php include_once '../conexion.php';
	require_once "scripts.php"; ?>
</head>
<body>

	<?php

	$usuario= $resultado['usuario'];
	
	$id=$resultado['id_usuario'];

	//$tabla= "preguntas_usuario";
	$sqla= "SELECT * FROM tbl_pregunta";
	$sentenciaa= $pdo->prepare($sqla);
	$sentenciaa->execute();
	//el fetch nos devuelve un verdadero si encuentra un valor en el nombre
	$resultadoa= $sentenciaa->fetchAll(); 

	?>


	<div class="container" style="opacity: 0.85; margin-top: 5%;">
			<div class="row">
				<div class="col-sm-3"></div>
					<div class="col-sm-6">
						<div class="panel panel-primary">
							<div style="text-align: center" class="panel panel-heading">PREGUNTA SECRETA</div>
								<div class="panel panel-body">
								
									<form id="logear" action="respuestas.php" method="POST" style="text-align: center;">
										
										<input style="text-align: center;" id="user" type="text" name="user" value="<?php echo  $usuario_login= strtoupper($usuario); ?>" >

										<input style="text-align: center;" id="id" type="text" name="id" value="<?php echo  $usuario_login= strtoupper($id); ?>">

										
										<br>
										<label for="p1">Selecione la Pregunta</label>

								      	<select name="enviar_mensaje" class="form-control" id="p1">
								        <?php 
								            foreach($resultadoa as $valor){ 
								        ?>
								        	<option>
								        <?php
								            echo utf8_encode($valor['pregunta']);
								        ?>
								            </option>
								        <?php
								          }?>
								      	</select>
								  		</br>
								      	<br>
										<label>Respuesta:</label>

									<div id="pulsaciones">
								      <input type="text" class="form-control" id="respuesta" name= "respuesta1" placeholder="Respuesta"  onkeypress="habilitar()" ></br></br>
								    </div>

								   


										<label>Contraseña Nueva</label>
										<br>

										<div class="input-group" >
												<input class=" form-control" id="password" type="password" minlenght="8" name="contrasena" placeholder="Ingresa contraseña"required>

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

    									<br>
										<label>Confirmar Contraseña</label>
										<br>

										<div class="input-group">
											   <input type="password" id="password2" name="contrasena2" placeholder="confirmar contraseña" class="form-control"  disabled="" >
											   <span class="input-group-addon glyphicon glyphicon-eye-open"style="cursor: pointer;"
											   id="show-hide-pass2"action="hide" onkeypress="habilitar()" ></span>
											</div>

										<br>
										<button style="width: 150px" class="btn btn-primary" disabled="" onclick="funcion()" type="submit" id="aceptar" >Aceptar</button>
									
										<button style="width: 150px" class="btn btn-danger" 	 type="" id="siguiente" disabled="" >siguiente</button>
			
									</form>			
								</div>
						</div>
					</div>
				<div class="col-sm-4"></div>
			</div>
		</div>

</body>
</html>



<script>

	
		$(document).ready(function(){
			$("#user").hide();
			$("#id").hide();

			$('#aceptar').click(function(){
				if ($('#respuesta').val()=="") {
					alertify.error("debe ingresar su Respuesta");
					return false;
				}else if($('#password').val()==""){
					alertify.error("debe ingresar la contraseña");
					return false;
				}else if($('#password2').val()==""){
					alertify.error("debe confirmar la contraseña");
					return false;
				}else if ($('#password').val()!=$('#password2').val()) {
				alertify.error("Las Contraseñas no Coinciden");
				return false;
				}
			});
		});


		$(document).ready(function(){
			$('#siguiente').click(function(){


				if ($('#respuesta').val()=="") {
					alertify.error("debe ingresar su Respuesta");
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
		$('#aceptar').click(function(){


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
			

		});	
	});	





	$(document).ready(function() {
	    $("#password").keypress(function(e) {
	        if (e.which == 32) {
	        	alert("No se Permiten Espacios en Blanco");
	           location.reload();
	        }
	    });
	});



	$(document).ready(function(){
		//$('#aceptar').hide();

		$("#password2").keyup(function(){	

			if ($('#password').val() != $('#password2').val()){
				$('#aceptar').attr("disabled", true);
			}else{
				$('#aceptar').attr("disabled", false);
			}
		});
	});

 

function habilitar(){
	        var respuesta= document.getElementById("respuesta");
	        var password=document.getElementById("password");
	        var pass2=document.getElementById("password2");
	        var p1=document.getElementById("p1");
	        var siguiente=document.getElementById("siguiente");
	        var aceptar=document.getElementById("aceptar");

	        //var pass= document.getElementById("p2").removeAttribute("disabled");
	        //var pass2=document.getElementById("r2").removeAttribute("disabled"); 
	      
	        if (respuesta.value =="") {
	        	password.disabled=true;
	        	pass2.disabled=true;
	        	siguiente.disabled=true;
	        	
	        }else{
	        	password.disabled=false;
	        	pass2.disabled=false;
	        	
	        	//siguiente.disabled=false;
	        }

	        if (password.value=="") {
	        	respuesta.disabled=false;
	        	p1.disabled=false;
	        }else{
	        	respuesta.disabled=true;
	        	p1.disabled=true;
	        }

	       

	      
   }

   function funcion(){
	   	var respuesta= document.getElementById("respuesta");
		var p1=document.getElementById("p1");

		p1.disabled=false;
		respuesta.disabled=false;
	}


   $(document).ready(function(){
		$("#respuesta").click(function() {
			var contador=0;
			document.body.onkeypress=function(e){
				var tecla = (document.all) ? e.keyCode : e.which;
				if(tecla==32){

					

					document.getElementById("respuesta").innerHTML = contador + 1
					contador++;

					if (contador>1){
						alert("Solo se permite un espacio entre cada palabra");
						location.reload();
					}
				}else{
					habilitar();
					contador=0;
				}
			}
		});

	});
	


</script>

