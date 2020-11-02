	<?php
		$link='mysql:host=localhost;dbname=rrhh_subli';
		$usuario='root';
		$pass='';

		try {

			//esta variable guarda la conexion a la base 
			$pdo= new PDO($link,$usuario,$pass);
			//echo "conectado";

			//esto nos sirve para hace runa consulta a la base de datos
			/*foreach($pdo->query('SELECT * FROM `colores`') as $fila) {
	        	print_r($fila);
	    	}*/

	    	//echo "CONECTADO <br>";
			
		} catch (PDOException $e) {
		    print "¡Error!: " . $e->getMessage() . "<br/>";
		    die();
		}
	?>



