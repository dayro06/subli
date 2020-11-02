<?php 
	// session_start();
	// if($_SESSION['id_rol'] != 1)
	// {
	// 	header("location: ./");
	// }

	require "../config/conexion.php";

?>

<!DOCTYPE html>
  <title>Búsqueda_Usuario</title>
  <html lang="en">
  <?php require 'master/head.php'; ?>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
    <?php require 'master/header.php'; ?>
    <?php require 'master/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
      <div class= "content-wrapper">
      <!-- content-wrapper> -->
    <?php
      $busqueda =  strtolower($_REQUEST['busqueda']);
      if(empty($busqueda)){
          header('location: tbl_usuarios.php');
      }

    ?>
</br>
<h3 class = "text-center">USUARIOS REGISTRADOS</h3>
<hr>
</br>
<div class='text-center'><a href = "registrar_usuario.php" class='btn btn-success btn-lg'><i class = "fa fa-user-plus"></i>
<span>AÑADIR</span></a></div>
<br>
	<!-- SEARCH FORM -->
	<nav>
        <ul class="pagination justify-content-end">
			<div class="col-5">
				<form action = "buscar_usuario.php" method = "get" class="form ml-3">   <!--form--inline -->
                       <div class="input-group input-group-xl">
                          <input class="form-control form-control-navbar" name = "busqueda" type="search" placeholder="BUSCAR..." aria-label="Search" value = "<?php echo $busqueda; ?>">
                             <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                  <i class="fas fa-search"></i>
                                 </button>
                            </div>
                       </div>
	               </form> 
			   </div>
             </ul>
		</nav>

<br>  
<br> 
<div class="container-fluid">
				<div class="row">
						<div class="col">
							<div class="table-responsive"> 
								<table id="tablaPersonas" class="table table-striped table-bordered table-condensed" style="width:100%">
								<thead class="bg-primary text-center">
    
								<tr>
									<th>Id_Usuario</th>
									<th>Cod_Empleado</th>
									<th>Usuario</th>                                
									<th>Contraseña</th>  
									<th>Rol</th>
									<th>Estado</th>
									<th>Correo_Electrónico</th>
									<th>Fecha_Creación</th>
									<th>F_Cambio_Contraseña</th>
									<th>Acciones</th>
								</tr>
							 </thead>
							

							<?php 

                            //-----------------------------------Inicio_Paginador----------------------------------------------------------------//
                            //------------------------------------------------------------------------------------------------------------------//
                            //Validacion de la tabla roles en la busqueda de la tabla usuario por medio del id_rol
                            $rol ='';
                            if($busqueda == 'ADMINISTRADOR'){
                                $rol = "OR rol LIKE '%1%' ";
                            }else if ($busqueda == 'RRHH'){
                                $rol = "OR rol LIKE '%2%' ";
                            }else if ($busqueda == 'USUARIO_NUEVO'){
                                    $rol = "OR rol LIKE '%3%' ";    
							}
							
							$sql_registro = mysqli_query($mysqli,"SELECT COUNT(*) as total_registro FROM tbl_usuario
                                                                                                    WHERE 
                                                                                                    (id_usuario LIKE '%$busqueda%' OR
                                                                                                     cod_empleado LIKE '%$busqueda%' OR
                                                                                                     usuario LIKE '%$busqueda%'OR
																									 estado LIKE '%$busqueda%'OR
                                                                                                     correo_electronico LIKE '%$busqueda%' OR
                                                                                                     fecha_creacion LIKE '%$busqueda%' OR
                                                                                                     fecha_cambio_contrasena LIKE '%$busqueda%'
                                                                                                     $rol)");
			                  $registros_encontrados = mysqli_fetch_array($sql_registro);
			                     $total_registro = $registros_encontrados['total_registro'];

									// $por_pagina = 5;
									//Este es el valor de registros que se mostraran en cada pagina 
									 $valor_pagina = 8;

			                          if(empty($_GET['pagina'])){

										 $pagina = 1;
										 
		                                 	} else {

				                              $pagina = $_GET['pagina'];
			                                }
											  //$desde es una variable que va tomar el valor de 0 en este caso, y va llegar hasta el valor que hemos declarado en la variable $valor_pagina
											  //la funcion ceil lo que hace es redondear el valo a uno entero

			                                  $desde = ($pagina-1) * $valor_pagina;
												$total_paginas = ceil($total_registro / $valor_pagina);
												

							//-----------------------------------Fin_Paginador----------------------------------------------------------------//
							//------------------------------------------------------------------------------------------------------------------//
							
							
								
				//Esta query sirve para buscar los datos en la barra de busqueda y pueden ir tantos filtros queramos
				//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//											
				//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//				
	            $sql = mysqli_query($mysqli,"SELECT u.id_usuario, u.cod_empleado ,u.usuario , u.contrasena, r.rol, u.estado,  u.correo_electronico, u.fecha_creacion, u.fecha_cambio_contrasena 
				                             FROM tbl_usuario u
				                             JOIN tbl_roles r
				                             ON u.id_rol = r.id_rol
                                             WHERE 
                                            (u.id_usuario LIKE '%$busqueda%' OR
                                            u.cod_empleado LIKE '%$busqueda%' OR
                                            u.usuario LIKE '%$busqueda%'OR
                                            u.correo_electronico LIKE '%$busqueda%' OR
                                            u.fecha_creacion LIKE '%$busqueda%' OR
                                            u.fecha_cambio_contrasena LIKE '%$busqueda%' OR
                                            r.rol LIKE '%$busqueda%' OR
											u.estado LIKE '%$busqueda%')
											ORDER BY u.id_usuario ASC
                                            LIMIT $desde, $valor_pagina");
				//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
				//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//		
											 
		                             mysqli_close($mysqli);
			                               $result = mysqli_num_rows($sql);

			                                 	if($result > 0){

				                                    while($data=mysqli_fetch_array($sql)){
					                                   ?>
		                                                   <tr>
			                                                <td><?php echo  $data['id_usuario'] ?></td>
			                                                <td><?php echo  $data['cod_empleado'] ?></td>
			                                                <td><?php echo  $data['usuario'] ?></td>
			                                                <td><?php echo  $data['contrasena'] ?></td>
			                                                <td><?php echo  $data['rol'] ?></td>
			                                                <td><?php echo  $data['estado'] ?></td>
			                                                <td><?php echo  $data['correo_electronico'] ?></td>
			                                                <td><?php echo  $data['fecha_creacion'] ?></td>
			                                                <td><?php echo  $data['fecha_cambio_contrasena'] ?></td>

											<td><div class='text-center'><div class='btn-group'>
											<?php if($data["id_usuario"] != 1){ ?>
												    <a href="editar_usuario.php?id=<?php echo $data["id_usuario"]; ?>" class='btn btn-primary'><i class = "fa fa-edit"></i></a>
											    	<a href="eliminar_usuario.php?id=<?php echo $data["id_usuario"]; ?>" class='btn btn-danger'><i class = "fa fa-trash"></i></a>
											</div></div>
											<?php } ?>
											</td>
										</tr>
									   <?php 
									 }

									}
	                                
	                                   ?>
						</tbody> 
          
        </div>
						
					 </table>  

					 <!-----------------------------------Diseño_Paginador----------------------------------------->
	                  <!-------------------------------------------------------------------------------------------->

         <?php



			  if($total_registro !=0)
			   {
				   ?>
						 <nav>
                             <ul class="pagination justify-content-end">
							 <?php
							 if($pagina != 1){
							 ?>
							    <li class="page-item"><a href="?pagina=<?php echo 1 ; ?>&busqueda=<?php echo $busqueda; ?>"  class="page-link"><span>|<</span></a></li>
							    <li class="page-item"><a href="?pagina=<?php echo $pagina - 1 ; ?>&busqueda=<?php echo $busqueda; ?>"  class="page-link"><span>Anterior</span></a></li>
						    <?php 
							    }

				                       for ($i=1; $i <= $total_paginas; $i++) { 

										if($i == $pagina){
											echo '<li class="page-item active"><a class = "page-link">'.$i.'</a></li>';  
										}else{
											echo '<li class="page-item"><a href="?pagina='.$i.'&busqueda='.$busqueda.'" class ="page-link">'.$i.'</a></li>';  
										}
									}
										if($pagina != $total_paginas){
							?>	
									    <li class="page-item"><a href="?pagina=<?php echo $pagina + 1 ; ?>&busqueda=<?php echo $busqueda; ?>" class="page-link"><span>Siguiente</span></a></li>
									    <li class="page-item"><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>" class="page-link"><span>>|</span></a></li>
				  
										<?php }	?>
                              </ul>
						  </nav>
				   <?php
				}
				?>		  

                     <!-----------------------------------Diseño_Paginador----------------------------------------->
					 <!--------------------------------------------------------------------------------------------->
				</div>
			</div>
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