		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();

			$cons="Select Mes,NumDias from Central.Meses where Numero=$MesI";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			echo ExError();$UltDia=$fila[1];
			
			$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Consumo' and Anio=$AnioI and Mes=$MesI";
			$res = ExQuery($cons);
			if(ExNumRows($res)==1)
			{	
				?><script language="javascript">
				parent(0).document.FORMA.Nuevo.title="PERIODO CERRADO, No se pueden Ingresar Nuevos Registros";
				parent(0).document.FORMA.Nuevo.disabled=true;
				</script>
				<?
			}
			else
			{
				?><script language="javascript">
				parent(0).document.FORMA.Nuevo.title="";
				parent(0).document.FORMA.Nuevo.disabled=false;
				</script>
				<?
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
				<div <?php echo $alignDiv3Mentor; ?> class="div3">
					<table width="95%" class="tabla3"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal">INVENTARIO</td>
							<td class="encabezado2Horizontal">RESPONSABLE</td>
							<td class="encabezado2Horizontal">FECHA CIERRE</td>
							<td class="encabezado2Horizontal">NO. AJUSTES</td>
							<td class="encabezado2Horizontal">VR. AJUSTE</td>
						</tr>
						<?
							$cons="Select NomInventario,Usuario,FechaCierre,sum(Diferencia),sum(TotCostoDif),diacorte from Consumo.Inventarios where AlmacenPpal='$AlmacenPpal'  and Compania='$Compania[0]'
							and Anio='$AnioI' and Mes='$MesI'
							Group By NomInventario,Usuario,FechaCierre,diacorte";
							$res=ExQuery($cons);	echo ExError();
							while($fila=ExFetch($res))
							{
								echo "<tr><td>";if($fila[2]==NULL){?>
								<a href="NuevoAjuste.php?DatNameSID=<? echo $DatNameSID?>&NomInventario=<? echo $fila[0]?>&AlmacenPpal=<? echo $AlmacenPpal?>&Editar=1&Anio=<? echo $AnioI?>&Mes=<? echo $MesI?>&diacorte=<? echo $fila[5]?>">
						<?		}echo "$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>".number_format($fila[4],2)."</td>";?>
								<td><img onClick="open('/Informes/Almacen/Formatos/Ajustes.php?DatNameSID=<? echo $DatNameSID?>&NomInventario=<? echo $fila[0]?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $AnioI?>','','width=800,height=600,scrollbars=yes')" style="cursor:hand" src="/Imgs/b_print.png">
						<?
								echo "</tr>";
							}
						?>
					</table>
				</div>	
			</body>
		</html>
