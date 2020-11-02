<!DOCTYPE html>
<html lang=en >
<head>
  <meta name="viewport" charset="UTF-8" content="width=device-width, initial-scale=1">
  <?php 
  require_once "scripts.php"; 
  ?>

</head>
<body>

<div class="container" style=" opacity: 0.85; margin-top: 5%;">
			<div class="row">
				<div class="col-sm-2"></div>
					<div class="col-sm-8">
						<div class="panel panel-primary">
							<div class="panel panel-heading">Preguntas Secretas</div>
								<div class="panel panel-body">
                <?php 
                  session_start();
                  include_once '../conexion.php';
                  if (isset($_SESSION['usuario_rrhh']) ) {
                  $tabla= "tbl_pregunta";
                  $sql= 'SELECT * FROM tbl_pregunta';
                  $sentencia= $pdo->prepare($sql);
                  $sentencia->execute();
                  //el fetch nos devuelve un verdadero si encuentra un valor en el nombre
                  $resultado= $sentencia->fetchAll();

                  //Consultando parametro preguntas
                    $parametro_admin="ADMIN_PREGUNTAS";
                    $sql_preguntas= 'SELECT * FROM tbl_parametro WHERE parametro=?';
                    $sentencia_preguntas= $pdo->prepare($sql_preguntas);
                    $sentencia_preguntas->execute(array($parametro_admin));
                    $preguntas=$sentencia_preguntas->fetch();
                    $cantidad_preguntas=$preguntas['valor'];
                  ?>
                    <form action="agregar_preguntas.php" method="POST" autocomplete="off">
                      <div class="form-group">
                          <?php
                          for ($i=0; $i < $cantidad_preguntas; $i++) {?> 
                              <label for="p<?php echo $i;?>">Elija pregunta <?php echo $i+1;?></label>
                                  <select name="enviar_mensaje<?php echo $i;?>" class="form-control" id="p<?php echo $i;?>" required>
                                      <?php 
                                          foreach($resultado as $valor){ 
                                      ?>
                                      <option>
                                          <?php
                                              echo utf8_encode($valor['pregunta']);
                                          ?>
                                      </option>
                                    <?php
                                    }?>
                                </select></br>
                                <input type="text" class="form-control" id="r<?php echo $i;?>" name= "respuesta<?php echo $i;?>" placeholder="Respuesta a pregunta <?php echo $i+1;?>" required></br></br>
                                  <?php
                                  }
                              ?>
                                <button type="submit" id="Select" value="Enviar" class="btn btn-primary btn-lg btn-block">Enviar Respuestas</button>
                              </div>
                            </form>
                            <?php
                            }else{
                              header('Location:../login/login.php');
                            }
                            ?>                            		
                  </div>
              </div>
            </div>
          <div class="col-sm-4"></div>
        </div>
      </div>
</body>
</html>

