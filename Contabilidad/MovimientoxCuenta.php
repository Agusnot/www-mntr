		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$PerIni){$PerIni="$ND[year]-$ND[mon]-01";}
			if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
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
				<div <?php echo $alignDiv2Mentor; ?> class="div2">

					<form name="FORMA" method="post">
					<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="2" class='encabezado2HorizontalInvertido'>PERIODO	</td>
						<td>
							<input type="Text" name="PerIni" style="width:70px;" value="<?echo $PerIni?>">
						</td>
						<td>
							<input type="Text" name="PerFin" style="width:70px;" value="<?echo $PerFin?>">
							<input type="Hidden" name="CondAdc" value="">
							<input type="Hidden" name="OrdCampo">
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						</td>
						<td><input type="Submit" class="boton2Envio" value="Ver"></td>
					</table>
					
					<script language="JavaScript">
						function Reabrir(Campos)
						{
							document.FORMA.OrdCampo.value=Campos;
							document.FORMA.submit();
						}
					</script>
					
					<table class="tabla2" width="90%"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
						<tr>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Cuenta')"  >CUENTA</a></td>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Comprobante')" >COMPROBANTE</td>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Numero')" >N&Uacute;MERO</td>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Fecha')" >FECHA</td>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Debe')" >D&Eacute;BITO</td>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Haber')" >CR&Eacute;DITO</td>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Tercero')" >TERCERO</td>
							<td style="text-align:center;font-weight:bold;"><a href="javascript:Reabrir('Detalle')" >DETALLE</td></tr>
							<?
								if($TerceroSel)
								{
									$cons="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$TerceroSel' and Terceros.Compania='$Compania[0]'";
									$res=ExQuery($cons);
									$fila=ExFetch($res);
									$CondAdc2=" and Movimiento.Identificacion='$TerceroSel'";
									echo "<tr>";
										echo "<td colspan='8' class='encabezado2HorizontalInvertido'>TERCERO SELECCIONADO: $TerceroSel - " . strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]") . "</td>";
									echo "</tr>";
								}
								if($OrdCampo)
								{
									if($OldCampo==$OrdCampo){$Ord="Desc";$OldCampo="x";}
									else{$Ord="Asc";$OldCampo=$OrdCampo;}
									$CondAdc3=" Order By $OrdCampo $Ord";
								}
								elseif(!$OrdCampo){$CondAdc3=" Order By Fecha,Comprobante,Numero,Cuenta";}
							?>
					<input type="Hidden" name="OldCampo" value="<?echo $OldCampo?>">
					</form>
					<?
						if(!$CondAdc){$Condicion="1=1";}
						else{$Condicion=" Comprobante='$CondAdc' ";}
						
						$cons="Select Cuenta,Comprobante,Numero,Fecha,Debe,Haber,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Identificacion 
						from 
						Contabilidad.Movimiento,Central.Terceros where Movimiento.Identificacion=Terceros.Identificacion and Cuenta ilike '$Cuenta%' and Fecha>='$PerIni' 
						and Terceros.Compania='$Compania[0]'
						and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and Estado='AC' and $Condicion $CondAdc2 $CondAdc3";
						$res=ExQuery($cons,$conex);echo ExError($res);
						while($fila=ExFetchArray($res))
						{
							if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
							else{$BG="white";$Fondo=1;}
							echo "<tr bgcolor='$BG'><td>".$fila['cuenta']."</td><td><a href='MovimientoxCuenta.php?DatNameSID=$DatNameSID&PerIni=$PerIni&TerceroSel=".$TerceroSel."&PerFin=$PerFin&Cuenta=$Cuenta&CondAdc=" . $fila['comprobante'] ."'>".$fila['comprobante']."</a></td><td>".$fila['numero']."</td><td>".$fila['fecha']."</td><td align='right'>".number_format($fila['debe'],2)."</td><td align='right'>".number_format($fila['haber'],2)."</td><td>".strtoupper($fila['primape']." ".$fila['segape'] ." ".$fila['primnom']." ".$fila['segnom'])."</td><td>".strtoupper(substr($fila['detalle'],0,60))."</td></tr>";
							$SumDeb=$SumDeb+$fila['debe'];
							$SumCred=$SumCred+$fila['haber'];
						}
					?>
					<tr  align="right">
						<td colspan="4" class='filaTotalesInfContable'>TOTAL</td>
						<td class='filaTotalesInfContable'><?echo number_format($SumDeb,2)?></td>
						<td class='filaTotalesInfContable'><?echo number_format($SumCred,2)?></td>
						<td class='filaTotalesInfContable' colspan="2">&nbsp;</td>
					</tr>
					</table>
				</div>	
			</body>		
	</html>		