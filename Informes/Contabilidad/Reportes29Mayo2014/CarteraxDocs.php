<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	$ND=getdate();

	$Corte="$Anio-$MesFin-$DiaFin";
	$Dias=array(30,30,30,90,180,5000);
	$DetDias=array("DE 0 A 30 DIAS","DE 30 A 60 DIAS","DE 60 A 90 DIAS","DE 90 A 180 DIAS","DE 180 A 360 DIAS","MAS DE 360 DIAS");
	if($Tercero){$condAdc=" and Movimiento.Identificacion='$Tercero'";}
	if($NoDoc){$cond2=" and DocSoporte='$NoDoc'";}
	function Encabezados()
	{
		global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $Corte;
		?>
		<table border="1" bordercolor="#FFFFFF" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>ESTADO DE CARTERA<br>Corte a: <?echo $Corte?></td></tr>
		<tr><td colspan="8" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		</table>
<?	}
	$NumRec=0;$NumPag=1;
	Encabezados();
	?>
		<table border="1" bordercolor="#FFFFFF" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">

<?	
	$cons1="Select Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta from Contabilidad.Movimiento,Central.Terceros 
	where Movimiento.Identificacion=Terceros.Identificacion 
	and Terceros.Compania='$Compania[0]'
	and Movimiento.Compania='$Compania[0]'
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	$condAdc Group By Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		$MatTerceros[$fila1[5]][$fila1[0]]=array($fila1[0],$fila1[1],$fila1[2],$fila1[3],$fila1[4],$fila1[5]);
	}

	$cons2="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0 Order By Fecha Desc";

	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$Date2=$fila2[2];$Date1="$Corte";
		$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
		$fila2[5]=$d;

		$DiasMin=0;$DiasMax=0;$Periodo=0;
		for($i=0;$i<=count($Dias);$i++)
		{
			$DiasMax=$DiasMax+$Dias[$i];
			if($fila2[5]>=$DiasMin and $fila2[5]<=$DiasMax)
			{
				$Periodo=$i;break;
			}
			$DiasMin=$DiasMax;
		}
//		echo "$fila2[3]-->$fila2[4]--->$Periodo--->$fila2[5]--->$fila2[2]--->$fila2[0]<br>";
		$MatDocSoporte[$fila2[3]][$fila2[4]][$fila2[1]]=$Periodo;
		$MatCartera[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]]=array($MatCartera[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]][0]+$fila2[0],$fila2[1],$fila2[2]);

	}

	$cons3="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0";

	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$Date2=$fila3[2];$Date1="$Corte";
		$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
		$fila3[5]=$d;

		$Periodo=0;
		$Periodo=$MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]];
		if(!count($MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]]))
		{
			$DiasMin=0;$DiasMax=0;
			for($i=0;$i<=count($Dias);$i++)
			{
				$DiasMax=$DiasMax+$Dias[$i];
				if($fila3[5]>=$DiasMin and $fila3[5]<=$DiasMax)
				{
					$Periodo=$i;break;
				}
				$DiasMin=$DiasMax;
			}
//			$CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]=array($CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]+$fila3[0],$fila3[1],$fila3[2]);
		}

		$MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]=$MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]-$fila3[0];
	}

	$cons="Select Movimiento.Cuenta,Nombre from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
	where Movimiento.Cuenta=PlanCuentas.Cuenta 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin' $condAdc and 
	Movimiento.Compania='$Compania[0]' 
	and PlanCuentas.Compania='$Compania[0]'
	and PlanCuentas.Anio=$Anio
	Group By Movimiento.Cuenta,Nombre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td colspan=5><strong>$fila[0] $fila[1]</td></tr>";
		foreach($MatTerceros[$fila[0]] as $Ident)
		{?>
			<table border="1" bordercolor="#FFFFFF"  width="50%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?			echo "<tr bgcolor='#e5e5e5'><td colspan=5><strong>$Ident[0] $Ident[1] $Ident[2] $Ident[3] $Ident[4]</td></tr>";
			for($i=0;$i<=count($Dias)-1;$i++)
			{
				if(count($MatCartera[$fila[0]][$Ident[0]][$i])>0)
				{
					foreach($MatCartera[$fila[0]][$Ident[0]][$i] as $Documento)
					{
						$TotPeriodo[$i]=$TotPeriodo[$i]+$Documento[0];
					}
					if($TotPeriodo[$i]>0)
					{
						echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td colspan=3 align='center'><font size=3>".$DetDias[$i]."</td></tr>";
						echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td>Fecha</td><td>Doc</td><td align='right'>Saldo</td></tr>";
						foreach($MatCartera[$fila[0]][$Ident[0]][$i] as $Documento)
						{
							if($Documento[0]>0)
							{
								echo "<tr><td>$Documento[2]</td><td>$Documento[1]</td><td align='right'>".number_format($Documento[0],2)."</td></tr>";
							}
						}
						echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan=2>TOTAL PERIODO</td><td>".number_format($TotPeriodo[$i],2)."</td></tr>";
						$TotEntidad=$TotEntidad+$TotPeriodo[$i];$TotPeriodo[$i]=0;
					}
				}
				if(count($CarteraSinSoporte[$fila[0]][$Ident[0]][$i])>0)
				{
					echo "<tr style='color:red'><td align='right'><strong>CREDITOS SIN REFERENCIA</td></tr>";
					echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td>Fecha</td><td>Doc</td><td align='right'>Saldo</td></tr>";
					foreach($CarteraSinSoporte[$fila[0]][$Ident[0]][$i] as $Documento)
					{
						echo "<tr><td>$Documento[2]</td><td>$Documento[1]</td><td align='right'>-".number_format($Documento[0],2)."</td></tr>";
						$TotEntidad=$TotEntidad-$Documento[0];
					}
				}
			}
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan=2>TOTAL ENTIDAD</td><td>".number_format($TotEntidad,2)."</td></tr>";
			$TotCartera=$TotCartera+$TotEntidad;
			$TotEntidad=0;
		}
		echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan=2>TOTAL CARTERA</td><td>".number_format($TotCartera,2)."</td></tr>";
	}
	echo "</table>";
?>
</body>