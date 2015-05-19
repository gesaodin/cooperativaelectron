<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
	<link rel="stylesheet" href="<?php echo __CSS__ ?>lightbox/screen1.css" type="text/css" media="screen" /> 
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
 	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/verificar.js"></script>		
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>		
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Solicitudes </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
			<center>
			<h1>Solicitudes</h1>
			<div id="tabs" style="width:720px"> 
			    <ul>
			    <li><a href="#tabs-1">Enviar</a></li>
		        <li><a href="#tabs-2">Verificar</a></li>
		        
			    </ul>						    							    	
				<div id='tabs-1' >																		
					<div id='Enviar'>
						<table  border=0>
							<tr><td colspan="2">Ingrese Cedula</td></tr>
							<tr><td><input type="text" id='ced' name='ced' /></td><td><button onclick='enviar();'>Enviar</button></td></tr>
						</table>
					</div>					
				</div>
				<div id='tabs-2'>
					<div id='verificar'>
						<table  border=0>
							<tr><td>Ingrese Cedula</td><td><input type="text" id='cced' name='cced' /></td></tr>
							<tr><td>Ingrese Codigo</td><td><input type="text" id='cod' name='cod' /></td></tr>
							<tr><td>Tipo Cliente</td><td><select id='tipo'>
								<option value='N'>Nuevo</option>
								<option value='A'>Vip</option>
								<option value='B'>Medio,Regular</option>
								<option value='C'>Moroso,Conflictivo</option>
							</select>
								</td><td><button onclick='verificar();'>Verificar</button></td></tr>
						</table>
					</div>											
				</div>
			</div>
			
			</center>
		</div>
		
	</body>
</html>
