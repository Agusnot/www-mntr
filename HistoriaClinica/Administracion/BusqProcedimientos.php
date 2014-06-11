		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
		?>	
		
		
		
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				<script language="javascript">
					function CerrarThis()
					{
						parent.document.getElementById('FrameOpener').style.position='absolute';
						parent.document.getElementById('FrameOpener').style.top='1px';
						parent.document.getElementById('FrameOpener').style.left='1px';
						parent.document.getElementById('FrameOpener').style.width='1';
						parent.document.getElementById('FrameOpener').style.height='1';
						parent.document.getElementById('FrameOpener').style.display='none';
					}
					function Insertar(Cod,Nom)
					{
						parent.document.getElementById('Cup').value=Cod;
						parent.document.getElementById('NomCup').value=Nom;
						CerrarThis();
					}
				</script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>   
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<?
					if($Codigo!=''||$Nombre!=''){?>
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<?		/*if($Valor!=''){
								if($Valor=="999999"){
									$It="and item!='$Item'";
								}
							}*/
							if($Valor)
							{
								$ValSub="and VrItem='' or Item!='$Item'";
							}
							if($Codigo==''){
								 $cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]' and Nombre ilike '%$Nombre%'
								 and codigo not in (select cup from historiaclinica.cupsxformatos where compania='$Compania[0]' and tipoformato='$TipoFormato' and formato='$Formato'
								 and cargo='$Cargo' $ValSub)";
							}
							else{
								if($Nombre==''){
									 $cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]' and codigo ilike '$Codigo%'
									 and codigo not in 
									 (select cup from historiaclinica.cupsxformatos where compania='$Compania[0]' and tipoformato='$TipoFormato' and formato='$Formato' and cargo='$Cargo'  $ValSub)";
								}
								else{
									 $cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]' and codigo ilike '$Codigo%' and Nombre ilike '%$Nombre%'
									 and codigo not in 
									 (select cup from historiaclinica.cupsxformatos where compania='$Compania[0]' and tipoformato='$TipoFormato' and formato='$Formato' and cargo='$Cargo'  $ValSub)";
								}		
							}
						
							//echo $cons;
							$res=ExQuery($cons);
							while($fila=ExFetch($res)){?>
								<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="parent.CerrarThis();
													parent.parent.document.form1.NomCup.value='<? echo $fila[1]?>';
													parent.parent.document.form1.Cup.value='<? echo $fila[0]?>';                                
													parent.parent.document.form1.submit();">
									<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td></tr>	
						<?	}?>
						</table><?        
					}
					?>
				</div>	
			</body>
		</html>