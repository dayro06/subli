<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "scripts.php"; ?>
    <title>Cambio de Contraseña</title>
</head>
<body>
<?php

session_start();	
include_once '../conexion.php';
date_default_timezone_set('America/Tegucigalpa');

if (isset($_SESSION['usuario_rrhh'])){
    $x=strtoupper( $_SESSION['usuario_rrhh']);
	$sentencia = 'SELECT * FROM tbl_usuario WHERE usuario=?';
	$sql = $pdo ->prepare($sentencia);
	$sql -> execute(array($_SESSION['usuario_rrhh']));
	$resultado = $sql->fetch();
    $id_usuario = $resultado['id_usuario'];
    $contrasenaV= $_POST['contrasenaV'];
    $contrasena = $_POST['contrasena'];
    $contrasena2 = $_POST['contrasena2'];
    //OBTENIENDO ID DEL OBJETO
    $pagina="Cambio_contrasena";
    $objeto = 'SELECT * FROM tbl_objetos WHERE objeto=?';
    $objeto_s=$pdo->prepare($objeto);
    $objeto_s->execute(array($pagina));
    $objeto_r=$objeto_s->fetch();
    $id_objeto=$objeto_r['id_objeto'];
    
    if(password_verify($contrasenaV, $resultado['contrasena'])){

        if(password_verify($contrasena,$resultado['contrasena'])){              
            echo '<script>
            setTimeout(function() {
                swal({
                title: "Error",
                text: "Debe elegir una contraseña diferente",
                type: "warning"
                }, function() {
                window.location = "index.php";
                });
                }, 1);
            </script>';
        }else{
            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
            $fecha_actual = date("Y-m-d H:i:s");
            $sql_update_contra='UPDATE tbl_usuario SET contrasena=?, fecha_cambio_contrasena=? WHERE id_usuario=?';
            $sentencia_contra= $pdo->prepare($sql_update_contra);
            if($sentencia_contra->execute(array($contrasena,$fecha_actual, $id_usuario))){
                $sql_historial ='INSERT INTO tbl_historia_contrasena (id_usuario, contrasena,fecha_creacion) VALUES (?,?,?)';
                $sql_sentencia_historial=$pdo->prepare($sql_historial);
                $sql_sentencia_historial->execute(array($id_usuario,$contrasena,date("Y-m-d H:i:s")));
                //INSERTANDO BITACORA
                $fecha_actual = date("Y-m-d H:i:s");
                $accion="ACTUALIZACION";
                $descripcion="Actualizacion de contrasena de usuario en tbl_usuarios";
                $sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
                $bitacora=$pdo->prepare($sql_bitacora);
                $bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
                $accion="INSERCION";
                $descripcion="Se inserto contraseña en la tabla tbl_historia_contrasena";
                $sql_bitacora='INSERT INTO tbl_bitacora (fecha, id_objeto, accion, descripcion) VALUES (?,?,?,?);';
                $bitacora=$pdo->prepare($sql_bitacora);
                $bitacora->execute(array($fecha_actual, $id_objeto,$accion,$descripcion));
                echo '<script>
                        swal({
                            title: "Completado",
                            text: "Su contraseña ha sido actualizada",
                            type: "success"
                        }, function(){
                                window.location.href = "../../index.php";
                        });
                        </script>';
            }else{
                echo '<script>
                    setTimeout(function() {
                        swal({
                        title: "Error",
                        text: "No pudo actualizarse su contraseña, por favor intente de nuevo",
                        type: "warning"
                        }, function() {
                        window.location = "cambio_contrasena.php";
                        });
                        }, 1);
                    </script>';
            }

        }
    }else{
        echo '<script>
            setTimeout(function() {
                swal({
                title: "Error",
                text: "La contraseña ingresada no coincide con su contraseña actual, favor intente de nuevo",
                type: "warning"
                }, function() {
                window.location = "cambio_contrasena.php";
                });
                }, 1);
            </script>';
    }
}
           
    else{
        header('Location:../login/login.php');
    }

    
    ?>
</body>
</html>
