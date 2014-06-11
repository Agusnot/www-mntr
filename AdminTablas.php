		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Elim){
				$cons="Delete from $Tabla where ";
				$Valores = explode("|",$Criterio);
				for($i=0;$i<count($Valores)-2;$i+=2)
				{
					if($i==count($Valores)-3){$cons = $cons. $Valores[$i]. "='". $Valores[$i+1]."'";}
					else{$cons = $cons. $Valores[$i]. " ='". $Valores[$i+1]. "' and ";}
				}
				$res=ExQuery($cons);echo ExError();
			}
		?>
		
	<html>			
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
		</head>
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php	
					if (strtoupper($_GET['Tabla'])== "CENTRAL.TIPOSTERCERO"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "TIPOS DE TERCERO";
					}
			
			
					if (strtoupper($_GET['Tabla'])== "CENTRAL.REGIMENTERCERO"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "REGIMEN POR TERCERO";
					}
					
					
					if (strtoupper($_GET['Tabla'])== "CONTABILIDAD.FORMASPAGO"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "FORMAS DE PAGO";
					}
			
					if (strtoupper($_GET['Tabla'])== "CONTABILIDAD.CODIGOSEXOGENA"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "CUENTAS CONTABLES";
						$rutaarchivo[3] = "CODIGOS EXOGENA";
					}
			
					if (strtoupper($_GET['Tabla'])== "CONTABILIDAD.TIPOSRETENCION"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "CUENTAS CONTABLES";
						$rutaarchivo[3] = "TIPOS DE RETENCION";
					}
			
					if (strtoupper($_GET['Tabla'])== "CENTRAL.ENTIDADESBANCARIAS"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "CUENTAS CONTABLES";
						$rutaarchivo[3] = "ENTIDADES BANCARIAS";
					}
			
					if (strtoupper($_GET['Tabla'])== "CONTABILIDAD.TIPOSPAGO"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "CUENTAS CONTABLES";
						$rutaarchivo[3] = "TIPOS DE PAGO";
					}
					
					
					if (strtoupper($_GET['Tabla'])== "CONTABILIDAD.CLASESPAGO"){
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "CUENTAS CONTABLES";
						$rutaarchivo[3] = "CLASES DE PAGO";
					}
					
					
				
					if (strtoupper($_GET['Tabla'])== "CONTABILIDAD.CONVDIRECCIONES"){
						$rutaarchivo[0] = "ADMINISTRADOR";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "CONV DIRECCIONES";
					}
					
					if (strtoupper($_GET['Tabla'])== "CENTRAL.DEPARTAMENTOS"){	
						$rutaarchivo[0] = "ADMINISTRADOR";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "DEPARTAMENTOS";
					}
					
					if (strtoupper($_GET['Tabla'])== "CENTRAL.MUNICIPIOS"){
						$rutaarchivo[0] = "ADMINISTRADOR";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "MUNICIPIOS";
					}
					
					if (strtoupper($_GET['Tabla'])== "CENTRAL.ESTILOS"){	
						$rutaarchivo[0] = "ADMINISTRADOR";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "ESTILOS";
					}
					
					if (strtoupper($_GET['Tabla'])== "CENTRAL.VEREDAS"){
						$rutaarchivo[0] = "ADMINISTRADOR";
						$rutaarchivo[1] = "CONFIGURACION";
						$rutaarchivo[2] = "VEREDAS";
					}
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1">
				<?php
					$Original=$Tabla;
					$Tabla=explode(".",$Tabla);
					$NomTabla=$Tabla[1];
					$BD=$Tabla[0];
				?>
				
				<div align="center" style="margin-top:15px; margin-bottom:15px;">
					<input type="button" value="Nuevo Registro" class="boton2Envio" onClick="location.href='NewAdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Original?>'">
				</div>	
				
				<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
					<?
						
					
					echo "<tr>";						
						$cons="SELECT column_name,column_default,data_type,character_maximum_length,data_type FROM information_schema.columns WHERE table_name ='".strtolower($NomTabla)."' and table_schema='".strtolower($BD)."'";
						$res=ExQuery($cons);
						$cantidadRegistros = ExNumrows($res);
							while($fila=ExFetch($res)){
								$n++;
								$NomCampo[$n]=$fila[0];
								$Pl = strtoupper(substr($NomCampo[$n],0,1));
								$NomCampo[$n] = $Pl.substr($NomCampo[$n],1,strlen($NomCampo[$n]));
								if($fila[0]=="compania"){
									$TieneCompania="SI";
								}
								else {
									
									?>
									<td class='encabezado2Horizontal'> 
										<?php  echo strtoupper($NomCampo[$n]);?>
									</td>
									<?
									if ($n==$cantidadRegistros){
										?><td class='encabezado2Horizontal' colspan="2">&nbsp; </td><?php
									}	
								}
							}
					echo "</tr>";
					
					$cons="Select ";
					foreach($NomCampo as $Nombre){$cons = $cons. $Nombre . ",";}
					$cons=substr($cons,0,strlen($cons)-1);
					$cons = $cons. " from $BD.$NomTabla";
					if($TieneCompania){ $cons = $cons." where Compania='$Compania[0]'";}
					$res=ExQuery($cons);
					while($fila=ExFetch($res)){
						echo "<tr>";
						$Criterio = "";
						for($x=0;$x<=$n-1;$x++)
						{
							//echo $NomCampo[$x+1]."-->";
							$Criterio=$Criterio. $NomCampo[$x+1]."|$fila[$x]|";
							if($fila[$x]!=$Compania[0]){
							echo "<td>".$fila[$x]."</td>";}
						}
						?>
						<td><a href="NewAdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Tabla=<? echo $Original?>&Criterio=<? echo $Criterio?>">
							<img border="0" title="Editar" src='/Imgs/b_edit.png'>
						 </a></td>
						<td><img onClick="if(confirm('Eliminar Este registro?')){location.href='AdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Elim=1&Criterio=<? echo $Criterio?>&Tabla=<? echo $Original?>'}" style="cursor:hand" title="Eliminar" src='/Imgs/b_drop.png'></a></td>
				<?		echo "</tr>";$Criterio="";
					}
				?>
				</table>
			</div>
		</body>
	</html>
