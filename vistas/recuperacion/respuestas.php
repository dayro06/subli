<?php

	//session_start();
        include_once "../conexion.php";
        require_once"scripts.php"; 

        $pregunta1=utf8_decode($_POST["enviar_mensaje"]);
        $respuesta1=$_POST["respuesta1"];
        $contra=$_POST['contrasena'];
        $contra2=$_POST['contrasena2'];
      
      


        //USUARIO Y ID DEL USUARIO QUE QUIERE RECUPERAR SU CONTRASENA 
        $usuarioo=$_POST["user"];
        $usuario_login= strtoupper($usuarioo);
        $id=$_POST["id"];
       

       
        unset($POST);
        date_default_timezone_set('America/Tegucigalpa');
        $fecha =date("Y-m-d H:i:s");


        //OBTENEMOS EL ID DE LA PREGUNTA INGRESADA POR EL USUARIO
        $sentencia_p1 = 'SELECT * FROM tbl_pregunta WHERE pregunta=?' ;
        $sql_p1 = $pdo ->prepare($sentencia_p1);
        $sql_p1 -> execute(array($pregunta1));
        $resultado_p1 = $sql_p1->fetch();

        $id_p1= $resultado_p1['id_pregunta'];
       
       

        //quiero obtener el valor NOMBRE del usuario
        $sql= 'SELECT * FROM tbl_usuario WHERE usuario=?';
        $sentencia= $pdo->prepare($sql);
        $sentencia->execute(array($usuario_login));
        //el fetch nos devuelve un verdadero si encuentra un valor en el nombre
        $resultado= $sentencia->fetch();


        //quiero obtener el valor del id_usuario
        $sqla= 'SELECT * FROM tbl_usuario WHERE id_usuario=?';
        $sentenciaa= $pdo->prepare($sqla);
        $sentenciaa->execute(array($id));
        $resultadoa= $sentenciaa->fetch();
           
        $id_usuario=$resultadoa['id_usuario']; 
        


        $sentenciah = "SELECT respuesta FROM tbl_preguntas_x_usuario WHERE id_usuario=$id" ;
        $sqlh = $pdo ->prepare($sentenciah);
        $sqlh -> execute(array());
        $resultadoh = $sqlh->fetchAll();


        $sentenciae = "SELECT id_pregunta FROM tbl_preguntas_x_usuario WHERE id_usuario=$id" ;
        $sqle = $pdo ->prepare($sentenciae);
        $sqle -> execute(array());
        $resultadoe = $sqle->fetchAll();



        foreach ($resultadoh  as  $valor) {
            foreach ($valor as $valor1) {
               

                if($respuesta1==$valor1) {
                    $y=1;
                 
                }

            }
        }


       foreach ($resultadoe as $valorp) {
            foreach ($valorp as $valorp2) {
                //echo "<br>".$valorp2;

                if($id_p1==$valorp2) {
                    $x=1;
                 
                }

            }
        }
       
   


        $sentenciac = "SELECT contrasena FROM tbl_usuario WHERE id_usuario=$id" ;
        $sqlc = $pdo ->prepare($sentenciac);
        $sqlc -> execute(array());
        $resultadoc = $sqlc->fetch();

        $resul_b= $resultadoc['contrasena'];

  

        $contra = password_hash($contra, PASSWORD_DEFAULT);



        $sqlp= 'SELECT * FROM tbl_usuario';
        $sentenciap= $pdo->prepare($sqlp);
        $sentenciap->execute(array());
        $resultadop= $sentenciap->fetch();



      if (password_verify($contra2, $resul_b)) {

            echo '<script>
                setTimeout(function() {
                swal({
                title: "Error",
                text: "SU CONTRASEÑA NO PUEDE SER IGUAL A LA ANTERIOR",
                type: "warning"
                }, function() {
                window.location = "recuperacion_contrasena.php";
                });
                }, 1);
            </script>';
            die();


        }else{
            
            if ($x==1 && $y==1) {

                	$estad="ACTIVO";   
                    $sqlu= 'UPDATE tbl_usuario SET contrasena=?,estado=? WHERE usuario =?';
                    $sentenciau = $pdo->prepare($sqlu);
                    $sentenciau ->execute([$contra,$estad, $usuario_login]);

                    echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "Exito",
                        text: "CONTRASEÑA CREADA CON EXITO",
                        type: "success"
                        }, function() {
                        window.location = "../login/login.php";
                        });
                        }, 1);
                        </script>';
                    die();

                     
            }else{
               
                    $rol=3;
                    $estad="BLOQUEADO";
                    $sqlBloqUs= 'UPDATE tbl_usuario SET id_rol=?, estado=? WHERE usuario =?';
                    $sentenciaBloqUs = $pdo->prepare($sqlBloqUs);
                    $sentenciaBloqUs ->execute([$rol,$estad, $usuario_login]);

                    echo '<script>
                        setTimeout(function() {
                        swal({
                        title: "Error",
                        text: "EL USUARIO HA SIDO BLOQUEADO, FAVOR COMUNIQUESE CON EL ADMINISTRADOR",
                        type: "warning"
                        }, function() {
                        window.location = "../login/login.php";
                        });
                        }, 1);
                        </script>';
                    die();
                    
            }
        }

?>