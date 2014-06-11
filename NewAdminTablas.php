		<? 
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			$Original=$Tabla;
			$Tabla=explode(".",$Tabla);
			$NomTabla=$Tabla[1];
			$BD=$Tabla[0];
		?>
		
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
		</script>
		<?
			$cons0="SELECT column_name,column_default,data_type,character_maximum_length,data_type FROM information_schema.columns 
					WHERE table_name ='".strtolower($NomTabla)."' and table_schema='".strtolower($BD)."'";
			$res0=ExQuery($cons0);
			if($Guardar)
			{
				if(!$Editar)
				{
					$cons="Insert into $Original (";
					$b=0;
					while($fila0=ExFetch($res0))
					{
						$cons = $cons . $fila0[0] . ",";
						$b++;
					}
					$cons=substr($cons,0,strlen($cons)-1);
					$cons= $cons .") values (";
					$b = 0;
					while( list($cad,$val) = each ($Valor))
					{
						$cons = $cons ."'". $val . "',";
						$b++;
					}
					$cons=substr($cons,0,strlen($cons)-1);
					$cons=$cons . ")";
				}
				else
				{
					$cons = "Update $Original set ";
					while( list($cad,$val) = each ($Valor))
					{
						$cons = $cons. $cad . "='" . $val ."',";
					}
					$cons=substr($cons,0,strlen($cons)-1). " where ";
					$Valores = explode("|",$Criterio);
					for($i=0;$i<count($Valores)-2;$i+=2)
					{
						if($i==count($Valores)-3){$cons = $cons. $Valores[$i]. "='". $Valores[$i+1]."'";}
						else{$cons = $cons. $Valores[$i]. " ='". $Valores[$i+1]. "' and ";}
					}
				}
				$res = ExQuery($cons);
				?><script language="javascript">
				<? if($VienedeOtro) { ?>CerrarThis()<? }
				else { ?>location.href="AdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Original?>"<? } ?>
				</script><?
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
			<script language="javascript" src="/Funciones.js"></script>
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
				$rutaarchivo[] = "NUEVO REGISTRO";
				
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1"> 
				<form name="FORMA" method="post">
						<?
						if($Editar)	{
							$Valores = explode("|",$Criterio);
							for($i=0;$i<count($Valores);$i+=2)	{
								$Valor[$Valores[$i]] = $Valores[$i+1];
								//echo "Valor[".$Valores[$i]."] = ".$Valores[$i+1]."<hr>";
							}
						}
						?>
					<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
						<tr>
							<td class="encabezado1Horizontal" colspan="2"> CREACI&Oacute;N REGISTRO </td>						
						</tr>
					<?
						$res0=ExQuery($cons0);
						while($fila0=ExFetch($res0)){
							$Pl = strtoupper(substr($fila0[0],0,1));
							$fila0[0] = $Pl.substr($fila0[0],1,strlen($fila0[0]));
							if($fila0[0]=="Compania")
							{$Campo = "<input type='hidden' name='Valor[$fila0[0]]' value='$Compania[0]'/>";}
							else
							{$Campo = "<tr><td class='encabezado1VerticalInvertido'>".strtoupper($fila0[0])."</td><td><input type='text' name='Valor[$fila0[0]]' onKeyUp='xLetra(this)' onKeyDown='xLetra(this)' 
							value='".$Valor[$fila0[0]]."' maxlength='$fila0[3]'></td></tr>";}
							echo $Campo;
						}
					?>
					</table>
					<div style="margin-top:15px;margin-bottom:15px">
						<input type="hidden" name="Original" value="<? echo $Original?>">
						<input type="submit" class="boton2Envio" name="Guardar" value="Guardar">
						<input type="button" name="Cancelar" class="boton2Envio" value="Volver" 
							<? if($VienedeOtro) { ?>onClick="CerrarThis()"<? }
								else { ?>onClick="location.href='AdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Original?>'"<? } ?> />
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</div>
				</form>
			</div>	
		</body>
		</html>
