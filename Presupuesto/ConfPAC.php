<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	include("GeneraValoresEjecucion.php");
	$Vigencia="Actual";
	if($Generar)
	{
		$PAC=$Valor/$NoMeses;
		for($i=1;$i<=$NoMeses;$i++)
		{
			$cons="Insert into Presupuesto.PAC (Compania,Cuenta,Mes,Anio,PACProgramado) 
			values ('$Compania[0]','$Cuenta','$MesIniciar','$Anio','$PAC')";
			$res=ExQuery($cons);
			$MesIniciar++;
		}
	}
?>
<title>Compuconta Software</title>
<body background="/Imgs/Fondo.jpg">
<?
	$cons="Select PAC from Central.Compania where Nombre='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$TIPOPAC=$fila[0];

	$cons="Select Anio,Mes,PACProgramado from Presupuesto.PAC where Cuenta='$Cuenta' and Anio='$Anio' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0 && $TIPOPAC=="Programado")
	{?>
		<form name="FORMA"><center>
		<table border="1" cellpadding="4"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" >
		<tr style="color:white" bgcolor="<?echo $Estilo[1]?>"><td colspan="2"><center><strong>Generar PAC Cuenta: <?echo $Cuenta?></td></tr>
		<tr><td>No Meses</td><td><input style="width:40px;" type="Text" name="NoMeses"></td></tr>
		<tr><td>Valor</td><td><input style="width:95px;" type="Text" name="Valor"></td></tr>
		<tr><td>Iniciar en</td>
		<td><select name="MesIniciar">
		<?
			$cons2="Select Mes,Numero from Central.Meses Order By Numero";
			$res2=ExQuery($cons2);
			while($fila2=ExFetch($res2))
			{
				echo "<option value='$fila2[1]'>$fila2[0]</option>";
			}
		?>
		</select></td>
		</tr>
		</table><br>
		<input type="Submit" name="Generar" value="Generar" style="width:70px;">
		<input type="Button" value="Cerrar" style="width:70px;">
		<input type="Hidden" name="Cuenta" value="<?echo $Cuenta?>">
		<input type="Hidden" name="Anio" value="<?echo $Anio?>">	
		<input type="Hidden" name="Naturaleza" value="<?echo $Naturaleza?>">
        <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		</form></center>
		
		
<?	}
	else
	{?>

		<table border="1" width="100%" cellpadding="4"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" >
		<tr style="color:white" bgcolor="<?echo $Estilo[1]?>"><td colspan="5"><center><strong>PAC Cuenta: <?echo $Cuenta?></td></tr>
		<tr style="color:white;" align="center" bgcolor="<?echo $Estilo[1]?>"><td>A&ntilde;o</td><td>Mes</td><td>PAC Programado</td><td>PAC Ejecutado</td><td>Saldo</td></tr>
		<?

			if($TIPOPAC=="Automatico")
			{
				for($n=1;$n<=12;$n++)
				{
					if(!$AuxMesIni){$AuxMesIni=$MesIni;}
					$MesIni=$n;$MesFin=$n;if(!$Anio){$Anio=$ND[year];}
					if($Naturaleza=="Credito")
					{
						$EgresosPer=GeneraValor("Egreso presupuestal","Credito",3);
						$DisminucEgrPer=GeneraValor("Disminucion a egreso presupuestal","ContraCredito",3);
						$PACEjec=$EgresosPer-$DisminucEgrPer;
					}
					else
					{
						$IngActuales=GeneraValor("Ingreso presupuestal","ContraCredito",3);
						$IngActualesDism=GeneraValor("Disminucion a ingreso presupuestal","Credito",3);
						$PACEjec=$IngActuales-$IngActualesDism;
					}
					$Saldo=0;
					$SumaPAC=$SumaPAC+$PACEjec;
					$cons9="Select Mes from Central.Meses where Numero=$n";
					$res9=ExQuery($cons9);
					$fila9=ExFetch($res9);
					echo "<tr align='right'><td>$Anio</td><td>$fila9[0]</td><td>".number_format($PACEjec,2)."</td><td>".number_format($PACEjec,2)."</td><td>".number_format($Saldo,2)."</td></tr>";
					$AuxMesFin=$MesFin;
				}
			}
			else
			{
				while($fila=ExFetch($res))
				{
					if(!$AuxMesIni){$AuxMesIni=$MesIni;}
					$MesIni=1;$MesFin=12;if(!$Anio){$Anio=$ND[year];}
					if($Naturaleza=="Credito")
					{
						$EgresosPer=GeneraValor("Egreso presupuestal","Credito",3);
						$DisminucEgrPer=GeneraValor("Disminucion a egreso presupuestal","ContraCredito",3);
						$PACEjec=$EgresosPer-$DisminucEgrPer;
					}
					else
					{
						$IngActuales=GeneraValor("Ingreso presupuestal","ContraCredito",3);
						$IngActualesDism=GeneraValor("Disminucion a ingreso presupuestal","Credito",3);
						$PACEjec=$IngActuales-$IngActualesDism;
					}
					$Saldo=$fila[2]-$PACEjec;
					$SumaPAC=$SumaPAC+$fila[2];
					$cons9="Select Mes from Central.Meses where Numero=$fila[1]";
					$res9=ExQuery($cons9);
					$fila9=ExFetch($res9);
					echo "<tr align='right'><td>$fila[0]</td><td>$fila9[0]</td><td>".number_format($fila[2],2)."</td><td>".number_format($PACEjec,2)."</td><td>".number_format($Saldo,2)."</td></tr>";
					$AuxMesFin=$MesFin;
				}
			}
		?>
		</table>
		<?
			if(!$MesIni){$MesIni=1;}
			if(!$MesFin){$MesFin=12;}
			$MesIni=$AuxMesIni;$MesFin=$AuxMesFin;
			$ApropIni=GeneraApropiacion();
			$Adiciones=GeneraValor("Adicion","Ambos",1);
			$Reducciones=GeneraValor("Reduccion","Ambos",1);
			$Creditos=GeneraValor("Traslado","Credito",1);
			$CCreditos=GeneraValor("Traslado","ContraCredito",1);
			$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;
		?>
		<table style="font-weight:bold" border="1" width="100%" cellpadding="4"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" >
		<tr><td>Sumatoria PAC Programado</td><td align="right"><?echo number_format($SumaPAC,2)?></td></tr>
		<tr bgcolor="#e5e5e5"><td>Apropiacion Definitiva</td><td align="right"><?echo number_format($ApropDef,2)?></td></tr>
		<?$Resago=$ApropDef-$SumaPAC;?>
		<tr><td>Resago</td><td align="right"><?echo number_format($Resago,2)?></td></tr>
		</table>
<?	}
?>
<br>
<input type="Button" value="Cerrar" onClick="window.close();">
</body>
