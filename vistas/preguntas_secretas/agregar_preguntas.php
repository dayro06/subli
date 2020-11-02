<!DOCTYPE html>
<html lang=en >
<head>
  <meta name="viewport" charset="UTF-8" content="width=device-width, initial-scale=1">
  <?php 
  require_once "scripts.php"; 
  ?>

</head>
<body>
<?php
	//esto siempre se coloca para iniciar sesion
    session_start();
    include_once '../conexion.php';
    date_default_timezone_set('America/Tegucigalpa');

   

	if (isset($_SESSION['usuario_rrhh']) ) {
        //Consultando parametro preguntas
        $parametro_admin="ADMIN_PREGUNTAS";
        $sql_preguntas= 'SELECT * FROM tbl_parametro WHERE parametro=?';
        $sentencia_preguntas= $pdo->prepare($sql_preguntas);
        $sentencia_preguntas->execute(array($parametro_admin));
        $preguntas=$sentencia_preguntas->fetch();
        $cantidad_preguntas=$preguntas['valor'];
        $preguntas=array();
        $respuestas=array();
        //OBTENIENDO ID DEL OBJETO
        $pagina="preguntas";
        $objeto = 'SELECT * FROM tbl_objetos WHERE objeto=?';
        $objeto_s=$pdo->prepare($objeto);
        $objeto_s->execute(array($pagina));
        $objeto_r=$objeto_s->fetch();
        $id_objeto=$objeto_r['id_objeto'];
        

        //Llenado de preguntas
        for($i=0; $i<$cantidad_preguntas; $i++){
                $preguntas[$i]=utf8_decode($_POST["enviar_mensaje".$i]);
            }
            $contador=0;
            $repetidos=false;
            for ($i=0; $i < $cantidad_preguntas; $i++) { 
                for ($j=0; $j <$cantidad_preguntas ; $j++) { 
                    if($preguntas[$i]==$preguntas[$j]){
                        $contador++;
                        if($contador>1){
                            $repetidos=true;
                        }
                    }
                    
                }
                $contador=0;
            }
        if($repetidos){
            echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "Error",
                        text: "Debe elegir preguntas diferentes",
                        type: "warning"
                        }, function() {
                        window.history.back();
                        });
                        }, 1);
                    </script>';

        }else{
                $x=strtoupper( $_SESSION['usuario_rrhh']);
                $sentencia = 'SELECT * FROM tbl_usuario WHERE usuario=?';
                $sql = $pdo ->prepare($sentencia);
                $sql -> execute(array($x));
                $resultado = $sql->fetch();
                $id_usuario = $resultado['id_usuario'];
                $usuario1=$resultado['usuario'];
               
                $resultado_insert=true;
                for ($k=0; $k <$cantidad_preguntas ; $k++) { 
                    $pregunta= $preguntas[$k];
                    //Obteniendo id pregunta
                    $sentencia_p = 'SELECT * FROM tbl_pregunta WHERE pregunta=?';
                    $sql_p = $pdo ->prepare($sentencia_p);
                    $sql_p -> execute(array($pregunta));
                    $resultado_p = $sql_p->fetch();
                    $id_p = $resultado_p['id_pregunta'];
                   
                    //respuesta dada por el usuario
                    $respuesta= utf8_decode($_POST["respuesta".$k]);
                    $fecha =date("Y-m-d H:i:s");
                    //insert de preguntas
                    $sentencia_insert_pregunta = 'INSERT INTO tbl_preguntas_x_usuario (id_pregunta, id_usuario, respuesta, fecha_creacion) VALUES (?, ?, ? , ? )';
                    $sql_insert = $pdo -> prepare($sentencia_insert_pregunta);
                        if(!$sql_insert-> execute([$id_p, $id_usuario , $respuesta , $fecha ])){
                            $resultado_insert=false;
                        }
                }

                if($resultado_insert){
                    insertar_bitacora("INSERCION","Se guardaron preguntas secretas de usuario ".$usuario1,$id_objeto);
                    //Actualizando campo preguntas contestadas y estado del usuario
                    $estado = "ACTIVO";
                    $sql_upd_pusuarios='UPDATE tbl_usuario SET preguntas_contestadas=?, estado=? WHERE id_usuario =?';
                    $update_usuario=$pdo->prepare($sql_upd_pusuarios);
                    $update_usuario->execute(array($cantidad_preguntas,$estado,$id_usuario));

                    //Consultando si el usuario fue autoregistrado o no para mandar a cambio
                    //de contraseña
                    $sql_creacion = 'SELECT * FROM tbl_usuario WHERE id_usuario=?';
                    $sentencia_creacion = $pdo ->prepare($sql_creacion);
                    $sentencia_creacion -> execute(array($id_usuario));
                    $usuario = $sentencia_creacion->fetch();
                    $creacion_usuario = $usuario['tipo_creacion'];
                    insertar_bitacora("ACTUALIZACION","Se cambio el el estado del usuario ".$usuario1,$id_objeto);

                    if($creacion_usuario==1){
                        echo '<script>
                            swal({
                                title: "Completado",
                                text: "Sus respuestas fueron alamacenadas exitosamente, recuerde hablar con el administrador para activar su cuenta",
                                type: "success",
                                showConfirmButton: true
                            }, function(){
                                    window.location.href = "../cerrar.php";
                            });
                            </script>';
                        die();	
                    }else{
                        echo '<script>
                            swal({
                                title: "Completado",
                                text: "Sus respuestas fueron alamacenadas exitosamente, a continuación deberá cambiar su contraseña",
                                type: "success",
                            }, function(){
                                    window.location.href = "../cambio_contrasena/index.php";
                            });
                            </script>';
                        die();

                    }

                    

                }else{
                    //Si falla algun insert de las preguntas borramos las preguntas
                    //Que ya hayan sido almacenadas, si es que las hay
                    try {
                        $sql_back= 'DELETE FROM tbl_preguntas_x_usuario WHERE id_usuario=?';
                        $sql_back_sent = $pdo->prepare($sql_back);
                        $sql_back_sent->execute(array($id_usuario));
                        insertar_bitacora("BORRADO","Se borraron datos de ".$usuario1." en tbl_preguntas_x_usuario",$id_objeto);
                    } catch (\Throwable $th) {
                        
                    }
                    echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "Error de sistema",
                        text: "Sus datos no fueron guardados, favor intente de nuevo",
                        type: "warning"
                        }, function() {
                        window.history.back();
                        });
                        }, 1);
                    </script>';
                    die();
                }
                    
            }

	}else{
		header('Location:../login/login.php');
    }	
    
    function insertar_bitacora($accion, $descripcion, $id_objeto){
        include '../conexion.php';
        $fecha_actual = date("Y-m-d H:i:s");
        $sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
        $bitacora=$pdo->prepare($sql_bitacora);
        $bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
    }
    ?>
    </body>
</html>