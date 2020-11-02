<?php 
	
	include_once "../conexion.php";
    require_once"scripts.php"; 

      
       $pregunta1=utf8_decode($_POST["pre"]);
       $respuesta1=$_POST["resp"];
       $usuario= strtoupper($_POST['usuario']);
       $usuario_login=$usuario;
        $id=$_POST["id"];
      

        //USUARIO Y ID DEL USUARIO QUE QUIERE RECUPERAR SU CONTRASENA 
        $usuarioo=$_POST["usuario"];
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
        


        $sentenciah = "SELECT respuesta FROM tbl_preguntas_x_usuario WHERE id_usuario=?" ;
        $sqlh = $pdo ->prepare($sentenciah);
        $sqlh -> execute(array($id));
        $resultadoh = $sqlh->fetch();

        $respuesta=$resultadoh['respuesta'];



        $sentenciae = "SELECT id_pregunta FROM tbl_preguntas_x_usuario WHERE id_usuario=?" ;
        $sqle = $pdo ->prepare($sentenciae);
        $sqle -> execute(array($id));
        $resultadoe = $sqle->fetchAll();


        $sentenciapr = "SELECT respuesta FROM tbl_preguntas_x_usuario WHERE id_pregunta=?" ;
        $sqlpr = $pdo ->prepare($sentenciapr);
        $sqlpr -> execute(array($id_p1));
        $resultadopr = $sqlpr->fetchAll();

        //var_dump($resultadopr);

       $sentenciar = "SELECT * FROM tbl_preguntas_x_usuario where id_pregunta=$id_p1 AND respuesta='$respuesta1'";
        $sqlr = $pdo ->prepare($sentenciar);
        $sqlr -> execute(array());
        $resultador = $sqlr->fetchAll();


        if ($resultador) {
           $mensaje='<script>setTimeout(function() {
                swal({
                title: "Bien",
                text: "PREGUNTA Y RESPUESTA CORRECTAS",
                type: "success"
                }, function() {
                });
                }, 1);</script>';
            echo $mensaje;
            echo '<script>respuesta.value="";</script>';
            echo '<script>p1.value="";</script>';
        }else{
            $mensaje2="<script>alert('PREGUNTA O RESPUESTA INCORRECTAS'); </script>";
            echo $mensaje2;
            echo "<script> location.reload(); </script>";
        }



/*
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

        
                
       if ($x==1 && $y==1){

        

           $mensaje='<script>setTimeout(function() {
                swal({
                title: "Bien",
                text: "PREGUNTA Y RESPUESTA CORRECTAS",
                type: "success"
                }, function() {
                });
                }, 1);</script>';
            echo $mensaje;
            echo '<script>respuesta.value="";</script>';
            echo '<script>p1.value="";</script>';

            

       }else{
            $mensaje2="<script>alert('PREGUNTA O RESPUESTA INCORRECTAS'); </script>";
            echo $mensaje2;
            echo "<script> location.reload(); </script>";
       }
       
       */
       
 ?>