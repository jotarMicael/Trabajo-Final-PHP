<?php session_start(); include('conexion.php'); include('contadorSeguidores.php'); include('verificarSeguido.php'); $link = conectar(); ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity=" sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link href="all.css" rel="stylesheet" type="text/css">
	<link href="Estilos.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="logotipo.jpg">
	<script type="text/javascript" src="scriptInicio.js"></script>
	<title>Perfil</title>
</head>
<body>
	<div class="barraInicio" >
		<div class="divBotones">
          <a class="botonInicio" href="PagInicio.php"> Inicio </a>
	    </div>
	    <div class="divBotones">
          <a class="botonInicio" href="Perfil.php"> Perfil </a>
	    </div>
	    <div class="divBotones">
        <form action="Busqueda.php" method="post">
          <input class="text" type="search" name="busca" autofocus required size="18" maxlength="15" autocomplete="on" >
          <input type="submit" class="botonInicio" value="Buscar"></a>
        </form>
        </div>
	    <div class="divBotones">
            <a class="botonInicio" href="Configuracion.php"> Configuracion </a>
	    </div>  
	</div>
	<div class="fondoPerfil">
	<div class="panelPerfil">
		<div class="imagenPanelPerfil">
			<img src="foto.php?id=<?php echo $_SESSION['visita']['id']?>">
		</div>
		<div class="nombreUsuarioPanelPerfil">
			<label class="labelWhite"><?php echo $_SESSION['visita']['nombreusuario']; ?></label>
		</div>
		<div class="nombrePanelPerfil">
			<label class="labelWhite"><?php echo $_SESSION['visita']['nombre'] ?> </label>
			<label class="labelWhite"><?php echo $_SESSION['visita']['apellido'] ?></label>
		</div>
		<div class="siguiendo">			
			<a class="botonInicio" href="Amigos.php?id=<?php echo $_SESSION['visita']['id']; ?>"> Siguiendo <?php echo contarSeguidores($_SESSION['visita']['id']); ?></a><br><br>
			<?php  $idEnvio = $_SESSION['visita']['id']; if (verificarSeguido($idEnvio)){ ?>
	    				<a class="botonInicio" name="dejarSeguir" href="dejarUsuarioVisita.php?id=<?php echo $idEnvio ; ?>"> Dejar de Seguir</a>
	    			<?php } else {?>
	    				<a class="botonInicio" href="seguirUsuarioVisita.php?id=<?php echo $idEnvio; ?>" name="Seguir"> Seguir </a>
	    	<?php } ?>
		</div>	
	</div>
	<div class="fondoComentariosPerfil">
	 <?php
	 	 $cantidadResultados = 10;

	 	if(empty($_GET['pagina'])){
	 		$pagina = 1;
	 	} else {
	 		$pagina = $_GET['pagina'];
		 }

		$empezarDesde = ($pagina-1) * $cantidadResultados;

		$consulta = "SELECT m.id, texto, imagen_contenido, imagen_tipo, fechayhora
												FROM mensaje m
												WHERE m.usuarios_id = '" . $_SESSION['visita']['id'] ."'
												ORDER BY m.fechayhora DESC";
	    $resultM = mysqli_query($link, $consulta);

	    $totalResultados = mysqli_num_rows($resultM);

	    $totalDePaginas = ceil($totalResultados / $cantidadResultados);

	    $resultMensaje = mysqli_query($link, "$consulta LIMIT $empezarDesde, $cantidadResultados");

	 while($filaM = mysqli_fetch_array($resultMensaje,  MYSQLI_ASSOC)) { ?>
	  <div class="comentario">
	  	<div class="imagenPerfil">
	  		<img src="foto.php?id=<?php echo $_SESSION['visita']['id'] ?>" alt="">
	    </div>	    
	    <div class="cuerpoComentario">
	    	<div class="barraTop">	
	    		<h5 class="textoBarraTop"><?php echo $_SESSION['visita']['nombreusuario']?></h5>
	    		<div class="contenedorIconoYFecha">
	    			<h5 class="textoBarraTop"><?php echo $filaM['fechayhora']; $_SESSION['idOperacion'] = $filaM['id']; ?></h5>  
	    		</div>     		
	    	</div>
	    	<div class="contenido">
	    		<?php echo $filaM['texto'];?> <br>
	    		<?php if($filaM['imagen_contenido']){?>
	    			<img src="fotoM.php?id=<?php echo $filaM['id']; ?>" class="imagenPublicacion">
	    		<?php } ?>
	    	</div>
	    	<div class="barraBot">
	    		<div>
	    		    <h4 class="textoBarraBot"></h4> 
	    	    </div>
	    		<div class="contenedorIcono">	
	    				<?php 
	    				$meGusta = mysqli_query($link, "SELECT * FROM me_gusta WHERE mensaje_id = '" . $_SESSION['idOperacion'] . "' ");
	    				if($meGusta!=NULL) {
	    					$cantLikes = mysqli_num_rows($meGusta);?>
	    					<p> <?php printf($cantLikes); ?> </p>
	    				<?php } else {?>
	    					<p> <?php echo 0; ?> </p>
	    				<?php } ?>
	    			<?php 
	    			 $diLike = mysqli_query($link, "SELECT id FROM me_gusta WHERE '" . $_SESSION ['usuario']['id'] . "' = me_gusta.usuarios_id AND me_gusta.mensaje_id = '" . $filaM['id'] . "' ");
	    			 $idOperacion = $filaM['id']; 
	    			 if(mysqli_num_rows($diLike) == 1){ ?>    			
	    						<a class="iconoCorazon fas fa-heart" style="color: red;" href="eliminarLikeSiguiendo.php?id=<?php echo $idOperacion ?>"></a>
	    			<?php } else {?>
	    						<a class="iconoCorazon fas fa-heart" href="darLikeSiguiendo.php?id=<?php echo $idOperacion ?>"></a>
	    			<?php } ?>
	    		</div> 		
	    	</div>
	    </div> 
	    </div>
	    <?php } 
	    	for ($i=1; $i<=$totalDePaginas;$i++)
				echo "<a href='?pagina=".$i."' style='color: white;'>".$i."</a> - ";
	    ?>  
	</div>
    </div>
	  <div class="barraFin">
		<p class="textoBarra">Gutierrez Matias 15257/5 - Jotar Micael 15388/6</p>
    </div>
</body>
</html>