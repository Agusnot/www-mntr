	<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		session_register("MATMOVMPCuenta");
		session_register("MATMOVSICuenta");
		include("Funciones.php");
		include_once("General/Configuracion/Configuracion.php");
		$ND=getdate();
		if($AnioAc1){$AnioAc=$AnioAc1;}
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
		<body class="encabPlanCuentas">
			
				<table class="tabla1" style="position:absolute; top:7px; left: 10px;" border="0" <?php  echo $cellspacingTabla1Mentor ; echo $cellpaddingTabla1Mentor; ?>>
					<tr>
						<td class="encabezado1Horizontal">A&Ntilde;O</td>
						<td class="encabezado1Horizontal">CUENTA</td>
				<tr>
				<td>
				<select name="Anio" onchange="parent.Abajo.location.href='ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&AnioCamb='+this.value;location.href='EncabSelCuenta2.php?DatNameSID=<? echo $DatNameSID?>&Anio='+this.value+'&AnioAc1='+this.value;parent.parent(2).location.href='SaldosxCuenta.php?DatNameSID=<? echo $DatNameSID?>'">
				<?		
					$cons="Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{
						if($AnioAc==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
					echo "</select>";
				?> 
				</td>
				<td><input type="Text" name="CtaBuscar" onkeyup="parent(1).location.href='ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&CtaBuscar='+this.value"></td>
				<td><input type="Button" class="boton2Envio" value="Cerrar" onClick="parent.parent.location.href='/Principal.php?DatNameSID=<? echo $DatNameSID?>'"></td>
				</tr>
				</table>

				<?

					$PerIni="$AnioAc-01-01";
					$PerFin="$AnioAc-12-31";

					$MATMOVSICuenta=NULL;$MATMOVMPCuenta=NULL;
					$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc Order By Nivel";
					$res=ExQuery($cons,$conex);
					while($fila=ExFetchArray($res))
					{
						$Nivel++;$TotNivel++;
						if(!$fila[0]){$fila[0]="-100";}
						$TotCaracteres=$TotCaracteres+$fila[0];
						$Digitos[$Nivel]=$TotCaracteres;
					}

					$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as MovAnio,date_part('month',Fecha) as MovMes from Contabilidad.Movimiento 
					where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0'
					Group By Cuenta,MovAnio,MovMes Order By Cuenta";

					$res2=ExQuery($cons2);
					while($fila2=ExFetch($res2))
					{
						$CuentaMad=substr($fila2[2],0,1);
						if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $AnioAc!=$fila2[3]){}
						else
						{
							for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
							{
								$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
								if($ParteAnt!=$ParteCuenta){
								$MATMOVSICuenta[$ParteCuenta][0]=$MATMOVSICuenta[$ParteCuenta][0]+$fila2[0];
								$MATMOVSICuenta[$ParteCuenta][1]=$MATMOVSICuenta[$ParteCuenta][1]+$fila2[1];}
								$ParteAnt=$ParteCuenta;
							}
						}
					}

					$cons3="Select sum(Debe),sum(Haber),Cuenta,date_part('month',Fecha) as MovMesMI from Contabilidad.Movimiento 
					where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0'
					Group By Cuenta,MovMesMI Order By Cuenta";

					$res3=ExQuery($cons3);
					while($fila3=ExFetch($res3))
					{

						for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
						{
							$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
							if($ParteAnt!=$ParteCuenta)
							{
								$MATMOVMPCuenta[$ParteCuenta][0][$fila3[3]]=$MATMOVMPCuenta[$ParteCuenta][0][$fila3[3]]+$fila3[0];
								$MATMOVMPCuenta[$ParteCuenta][1][$fila3[3]]=$MATMOVMPCuenta[$ParteCuenta][1][$fila3[3]]+$fila3[1];
							}
							$ParteAnt=$ParteCuenta;
						}
					}

				?>