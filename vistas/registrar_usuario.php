<?php 
	// // session_start();
	// if($_SESSION['id_rol'] != 1)
	// {
	// 	header("location: ./");
	// }

  require "../config/conexion.php";
  require "conexion.php";
  

	if(!empty($_POST))
	{
    $alert='';

		// Validacion para que el campo no quede vacio en el formulario 

    if(empty($_POST['codigo']) || empty($_POST['usuario']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['rol']) )
		{
      $alert = '<div class = "pagination justify-content-center">
                     <div class="col-6">
                         <div class="alert alert-warning alert-dismissible fade show" role="alert">
                           <p3 class="font-italic">Todos los campos son obligatorios...</p3>
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                          </div>
                        </div>
                    </div>';
      } else {

      $codigo    = $_POST['codigo'];
			$usuario   = $_POST['usuario'];
      $email     = $_POST['email'];
      $password= $_POST['password'];
      $password  = password_hash($password, PASSWORD_DEFAULT);
      $rol       = $_POST['rol'];
			$parametro_vigencia="ADMIN_VIGENCIA";
			$sqlvig='SELECT * FROM tbl_parametro WHERE parametro=?';
			$sql_vigencia=$pdo->prepare($sqlvig);
			$sql_vigencia->execute(array($parametro_vigencia));
			$vigencia = $sql_vigencia->fetch();
			$vigencia_usuario=(int)$vigencia['valor'];
	
			$fecha_actual = date("Y-m-d H:i:s");
      $fecha_vencimiento = date("Y-m-d H:i:s",strtotime($fecha_actual."+ ".$vigencia_usuario." days"));
      



			// Definir la variable recogida del formulario del id del campo

			$query = mysqli_query($mysqli,"SELECT * FROM tbl_usuario WHERE usuario = '$usuario' OR correo_electronico = '$email' ");
			$result = mysqli_fetch_array($query);

			if($result > 0){
        $alert = '<div class = "pagination justify-content-center">
                     <div class="col-6">
                         <div class="alert alert-success alert-dismissible fade show" role="alert">
                           <p3 class="font-italic">El correo o usuario ya existe...</p3>
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                          </div>
                        </div>
                    </div>';
			}else{

          //-------------------------------------------------------------------------------------------------------------------------------------------------//
          ///////////////////////////// modificar de acuerdo a los registros que se van a insertar en la tabla////////////////////////////////////////////////
          //-------------------------------------------------------------------------------------------------------------------------------------------------//
              
				$query_insert = 'INSERT INTO tbl_usuario (cod_empleado, usuario, contrasena, id_rol, estado, 
                                                          preguntas_contestadas, primer_ingreso, correo_electronico, intentos, tipo_creacion, fecha_creacion, fecha_vencimiento)                  
                                                          VALUES(?,?,?,2,"NUEVO",0,0 ,?,0, 2, now(), ?)';
        $query=$pdo->prepare($query_insert);

        //-------------------------------------------------------------------------------------------------------------------------------------------------//
        //-------------------------------------------------------------------------------------------------------------------------------------------------//
        
				if($query->execute(array($codigo,$usuario,$password,$email,$fecha_vencimiento))){
        $alert = '<div class = "pagination justify-content-center">
                     <div class="col-6">
                         <div class="alert alert-primary alert-dismissible fade show" role="alert">
                           <p3 class="font-italic">Usuario creado correctamente...</p3>
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                          </div>
                        </div>
                    </div>';
          
				}else{
          $alert = '<div class = "pagination justify-content-center">
                     <div class="col-6">
                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                           <p3 class="font-italic">Error al crear usuario...</p3>
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                          </div>
                        </div>
                    </div>';
				}

			}


		}

	}



 ?>

<!DOCTYPE html>
  <title>SistemaRRHH_Registro_De_Usuario</title>
  <html lang="en">
  <?php require 'master/head.php'; ?>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
    <?php require 'master/header.php'; ?>
    <?php require 'master/sidebar.php'; ?>
          <!-- Content Wrapper. Contains page content -->
         <div class= "content-wrapper">
        <!-- content-wrapper> -->
        <div class="container">
           </br>
               <div class="form_register">
                  <div class="text-center">
                  <h3>REGISTRO DE USUARIO</h3>
               </div>
	<hr>
            <div class = "text-center">
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
            </div>
  <form action = "" method = "POST">
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend3"><i class = "fa fa-hashtag"></i></span>
        </div>
        <input type="number" class="form-control is-invalid" id="validationServerUsername" name = "codigo"  placeholder="CODIGO USUARRIO" aria-describedby="inputGroupPrepend3" 
        required pattern ="[0-9]+" minlength = "1" maxlength = "15">
        <div class="invalid-feedback">
          Ingrese código de usuario
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend3"><i class = "fa fa-user"></i></span>
        </div>
        <input type="text" class="form-control is-invalid" id="validationServerUsername" name = "usuario" placeholder="USUARIO" 
        aria-describedby="inputGroupPrepend3" required pattern ="[A-Za-z0-9]+" minlength = "4" maxlength = "25">
        <div class="invalid-feedback">
          Ingrese usuario
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend3"><i class = "fa fa-key"></i></span>
        </div>
        <input type="text" class="form-control is-invalid" id="validationServerUsername" name = "password" placeholder="CONTRASEÑA" aria-describedby="inputGroupPrepend3"
        required pattern = "[A-Za-z0-9@.!#$%&’*+/=?^_`{|}~-]+"  minlength = "8">
        <div class="invalid-feedback">
          Ingrese contraseña
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend3"><i class = "fa fa-envelope"></i></span>
        </div>
        <input type="email" class="form-control is-invalid" id="validationServerUsername" name = "email"  placeholder="CORREO ELECTRÓNICO" aria-describedby="inputGroupPrepend3" 
        required pattern ="^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$">
        <div class="invalid-feedback">
          Ingrese un correo electrónico
        </div>
      </div>
    </div>
    
    <div class="col-md-12 mb-3">
    <label>ROL DEL USUARIO</label>
                        <select name = "rol" class="form-control">
                       <?php require "../config/conexion.php";
                         $consulta = "SELECT * FROM tbl_roles";
                         $ejecutar = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));?>
                        <?php foreach ($ejecutar as $opciones ): ?>
                        <option value ="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
					 
                        <?php endforeach ?>
                        </select>

    </div>
  </div>
  </br>
  </br>
  <button class="btn btn-success btn-block rounded-pill" type="submit">REGISTRAR</button>
</form>
</div>
</div>


        <!-- /.content -->
     </div>
     <!-- /.content-wrapper -->
    
     <?php require 'master/footer.php'; ?>
     <!-- Control Sidebar -->
     <!-- /.control-sidebar -->
     </div>
     <!-- ./wrapper -->
    <?php require 'master/script.php'; ?>
  </body>
  </html>