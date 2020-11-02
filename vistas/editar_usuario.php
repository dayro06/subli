<?php
// session_start();
// 	if($_SESSION['rol'] != 1)
// 	{
// 		header("location: ./");
// 	}

	require "../config/conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['codigo']) || empty($_POST['usuario']) || empty($_POST['email'])  || empty($_POST['rol']) || empty($_POST['estado']))
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
		}else{

			$id_Usuario   = $_POST['id_Usuario'];
			$codigo       = $_POST['codigo'];
			$usuario      = $_POST['usuario'];
			$password     = md5($_POST['password']);
			$rol          = $_POST['rol'];
			$estado       = strtoupper($_POST['estado']);
			$email        = $_POST['email'];


			$query = mysqli_query($mysqli,"SELECT * FROM tbl_usuario 
													   WHERE (usuario = '$usuario' AND id_usuario != $id_Usuario)
													   OR (correo_electronico = '$email' AND id_usuario != $id_Usuario) ");

      $result = mysqli_fetch_array($query);
      
      

			if($result > 0){
				$alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
			}else{

				if(empty($_POST['password']))
				{

					$sql_update = mysqli_query($mysqli,"UPDATE tbl_usuario
															SET cod_empleado = '$codigo', usuario='$usuario', id_rol='$rol', estado='$estado', correo_electronico ='$email'
															WHERE id_usuario = $id_Usuario ");
				}else{
					$sql_update = mysqli_query($mysqli,"UPDATE tbl_usuario
															SET cod_empleado = '$codigo', usuario='$usuario', id_rol='$rol', estado='$estado', correo_electronico ='$email', contrasena = '$password'
															WHERE id_usuario= $id_Usuario ");

        }
        
        //-------------------------------------------------------------------------------------------------------------------------------------------------------------------//
        //-------------------------------------------------------------------Validacion estado_usuario-----------------------------------------------------------------------//
        if($estado == "ACTIVO"){
          $sql_update = mysqli_query($mysqli,"UPDATE tbl_usuario
															SET cod_empleado = '$codigo', usuario='$usuario', id_rol='$rol', estado='$estado', correo_electronico ='$email', intentos = 0
                              WHERE id_usuario = $id_Usuario ");
        //-------------------------------------------------------------------------------------------------------------------------------------------------------------------//
        //----------------------------------------------Nececsito parametro maximo para bloquear al usuario------------------------------------------------------------------//
        
        }elseif($estado == "BLOQUEADO"){
          $sql_update = mysqli_query($mysqli,"UPDATE tbl_usuario
          SET cod_empleado = '$codigo', usuario='$usuario', id_rol='$rol', estado='$estado', correo_electronico ='$email', intentos = 5
          WHERE id_usuario = $id_Usuario ");
        }
        //-------------------------------------------------------------------------------------------------------------------------------------------------------------------//
        //-------------------------------------------------------------------Validacion estado_usuario-----------------------------------------------------------------------//

				if($sql_update){
          $alert = '<div class = "pagination justify-content-center">
                     <div class="col-6">
                         <div class="alert alert-primary alert-dismissible fade show" role="alert">
                           <p3 class="font-italic">Usuario actualizado correctamente...</p3>
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
                           <p3 class="font-italic">Error al actualizar usuario...</p3>
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

	//Mostrar Datos
	if(empty($_REQUEST['id']))
	{
		header('Location: tbl_usuarios.php');
		mysqli_close($mysqli);
	}
	$id_usuario = $_REQUEST['id'];

	$sql= mysqli_query($mysqli,"SELECT u.id_usuario, u.cod_empleado, u.usuario, 
                  u.contrasena, u.correo_electronico, (u.id_rol) as id_rol, (r.rol) as rol, u.estado
									FROM tbl_usuario u
									INNER JOIN tbl_roles r
									on u.id_rol = r.id_rol
									WHERE u.id_usuario= $id_usuario");
	mysqli_close($mysqli);
	$result_sql = mysqli_num_rows($sql);

	if($result_sql == 0){
		header('Location: tbl_usuarios.php');
	}else{
    $opcion_r = '';
		while ($data = mysqli_fetch_array($sql)) {
			# code...
			$id_usuario         = $data['id_usuario'];
			$codigo             = $data['cod_empleado'];
			$usuario            = $data['usuario'];
      $password           = $data['contrasena'];
      $email              = $data['correo_electronico'];
			$id_rol             = $data['id_rol'];
			$rol                = $data['rol'];
			$estado             = $data['estado'];

      if($id_rol == 1){
        $opcion_r = '<option value="'.$id_rol.'" select>'.$rol.'</option>';
			}else if($id_rol == 2){
        $opcion_r = '<option value="'.$id_rol.'" select>'.$rol.'</option>';	
			}else if($id_rol == 3){
        $opcion_r = '<option value="'.$id_rol.'" select>'.$rol.'</option>';
      }    
    }
	}

 ?>
 <!DOCTYPE html>
  <title>SistemaRRHH_Actualizar_Usuario</title>
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
        <h3>ACTUALIZACIÓN DATOS DEL USUARIO</h3>
        <hr class = "my-3">
    </div>
            <div class = "text-center">
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
            </div>
  <form action = "" method = "POST">

  <input type="hidden" name="id_Usuario" value="<?php echo $id_usuario; ?>">

  <div class="form-row">
    <div class="col-md-6 mb-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend3"><i class = "fa fa-hashtag"></i></span>
        </div>
        <input type="number" class="form-control is-invalid" id="validationServerUsername" name = "codigo" value="<?php echo $codigo; ?>"
		placeholder="CODIGO USUARRIO" aria-describedby="inputGroupPrepend3" 
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
        <input type="text" class="form-control is-invalid" id="validationServerUsername" name = "usuario" placeholder="USUARIO" value="<?php echo $usuario; ?>"
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
        minlength = "8">
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
        <input type="email" class="form-control is-invalid" id="validationServerUsername" name = "email" value="<?php echo $email; ?>"
		placeholder="CORREO ELECTRÓNICO" aria-describedby="inputGroupPrepend3" 
        required pattern ="^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$">
        <div class="invalid-feedback">
          Ingrese un correo electrónico
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
    <label>ESTADO DEL USUARIO</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend3"><i class = "fa fa-retweet"></i></span>
        </div>
        <input type="text" class="form-control is-invalid" id="validationServerUsername" name = "estado" value="<?php echo $estado; ?>"
		placeholder="Estado del usuario" aria-describedby="inputGroupPrepend3" 
        required pattern = "[A-Za-z]+" minlength = "6" maxlength = "25">
        <div class="invalid-feedback">
          Ingrese estado del usuario
        </div>
      </div>
    </div>   
    <div class="col-md-6 mb-3">
       <label>ROL DEL USUARIO</label>

              <select name = "rol" class="form-control">
                       <?php require "../config/conexion.php";
                          $consulta = "SELECT * FROM tbl_roles";
                            echo $opcion_r;
                                $ejecutar = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));?>
                                  <?php foreach ($ejecutar as $opciones ): ?>  
                                    <option value ="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
                                     <?php endforeach ?>
               </select>
       </div>


   
  </div>
  </br>
  </br>
  <button class="btn btn-primary btn-block rounded-pill" type="submit">ACTUALIZAR REGISTROS</button>
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
