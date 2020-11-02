<?php 
	// session_start();
	// if($_SESSION['rol'] != 1)
	// {
	// 	header("location: ./");
	// }
	require "../config/conexion.php";

	if(!empty($_POST))
	{
		if($_POST['id_usuario'] == 1){
			header("location: tbl_usuarios.php");
			mysqli_close($mysqli);
			exit;
		}
		$id_usuario = $_POST['id_usuario'];

		$query_delete = mysqli_query($mysqli,"DELETE FROM tbl_usuario WHERE id_usuario =$id_usuario ");
		// $query_delete = mysqli_query($mysqli,"UPDATE tbl_usuario SET estatus = 0 WHERE idusuario = $idusuario ");
		mysqli_close($conection);
		if($query_delete){
			header("location: tbl_usuarios.php");
		}else{
			echo "Error al eliminar";
		}

	}




	if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1 )
	{
		header("location: tbl_usuarios.php");
		mysqli_close($mysqli);
	}else{

		$id_usuario = $_REQUEST['id'];

		$query = mysqli_query($mysqli,"SELECT u.cod_empleado, u.usuario, r.rol, u.estado, u.correo_electronico
		                               FROM tbl_usuario u
	                                   INNER JOIN tbl_roles r
		                               ON u.id_rol = r.id_rol
		                               WHERE u.id_usuario =  $id_usuario");
												
		
		mysqli_close($mysqli);
		$result = mysqli_num_rows($query);

		if($result > 0){
			while ($data = mysqli_fetch_array($query)) {
				# code...
				$cod_empleado          = $data['cod_empleado'];
				$usuario               = $data['usuario'];
				$rol                   = $data['rol'];
				$estado                =$data['estado'];
				$email                 =$data['correo_electronico'];
			}
		}else{
			header("location: tbl_usuarios.php");
		}


	}


 ?>

<!DOCTYPE html>
  <title>SistemaRRHH</title>
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
	<div class="pagination justify-content-center">
	<div class="col-10">
		<br>
		<br>
               <div class="jumbotron text-center">
				   <h3>¿ESTÁS SEGURO QUE DESEAS ELIMINAR A ESTE USUARIO?</h3>
					  <hr class="my-3">
					      <p class="lead">Codigo_Empleado===><span><?php echo $cod_empleado; ?></span></p>
			              <p class="lead">Usuario===><span><?php echo $usuario; ?></span></p>
		                  <p class="lead">Rol===><span><?php echo $rol; ?></span></p>
			              <p class="lead">Estado===><span><?php echo $estado; ?></span></p>
			              <p class="lead">Correo===><span><?php echo $email; ?></span></p>
			<hr class="my-3">		
            <form method="post" action="">
				<input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
				<a href="tbl_usuarios.php" class="btn btn-outline-info  btn-lg d-inline-block rounded-pill">Cancelar</a>
				<input type="submit" value="Aceptar"class="btn btn-outline-danger btn-lg  d-inline-block rounded-pill"></input>
			</form>
          </div>
        </div>

	</div>
			

</div>
	<!-- /.content -->
	</div>
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
