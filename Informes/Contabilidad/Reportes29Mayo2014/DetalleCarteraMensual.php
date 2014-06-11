<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	$ND=getdate();

	$Corte="$Anio-$MesFin-$DiaFin";
	$Dias=array(30,30,30,90,180,5000);
	if($Tercero){$condAdc=" and Movimiento.Identificacion='$Tercero'";}
	if($NoDoc){$cond2=" and DocSoporte='$NoDoc'";}
	function Encabezados()
	{
		global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $Corte;
		?>
		<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>ESTADO DE CARTERA<br>Corte a: <?echo $Corte?></td></tr>
		<tr><td colspan="8" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		</table>
<?	}
	$NumRec=0;$NumPag=1;
	Encabezados();
	?>
		<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">

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

	$cons2="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion,'$Corte'-Fecha,date_part('month',Fecha),date_part('year',Fecha) from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0 Order By Fecha Desc";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$AniosCart[$fila2[3]][$fila2[4]][$fila2[7]]=$fila2[7];
		$MesCart[$fila2[3]][$fila2[4]][$fila2[7]][$fila2[6]]=$fila2[6];
		if(!$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]])
		{
			$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]]=array($fila2[7],$fila2[6]);
			$AnioCT=$fila2[7];
			$MesCT=$fila2[6];
		}
		else
		{
			$AnioCT=$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]][0];
			$MesCT=$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]][1];
		}
		$DocxFecha[$fila2[3]][$fila2[4]][$AnioCT][$MesCT][$fila2[1]]=$fila2[1];
		$MatCartera[$fila2[3]][$fila2[4]][$fila2[1]]=array($fila2[2],$fila2[1],$MatCartera[$fila2[3]][$fila2[4]][$fila2[1]][2]+$fila2[0]);
	}

	$cons3="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion,'$Corte'-Fecha,date_part('month',Fecha),date_part('year',Fecha) from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0";

	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$MatPagos[$fila3[3]][$fila3[4]][$fila3[1]]=array($fila3[2],$fila3[1],$MatPagos[$fila3[3]][$fila3[4]][$fila3[1]][2]+$fila3[0],"*");
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
			<table border="1" rules="groups" bordercolor="#ffffff" width="50%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?			echo "<tr><td colspan=5><strong>$Ident[0] $Ident[1] $Ident[2] $Ident[3] $Ident[4]</td></tr>";
			if(count($AniosCart[$fila[0]][$Ident[0]])>0)
			{
				foreach($AniosCart[$fila[0]][$Ident[0]] as $AniosC)
				{
					foreach($MesCart[$fila[0]][$Ident[0]][$AniosC] as $MesC)
					{
						foreach($DocxFecha[$fila[0]][$Ident[0]][$AniosC][$MesC] as $Documentos)
						{
							$Deuda=$MatCartera[$fila[0]][$Ident[0]][$Documentos][2];
							$Pagos=$MatPagos[$fila[0]][$Ident[0]][$Documentos][2];
							$MatPagos[$fila[0]][$Ident[0]][$Documentos][3]="Ok";
							$Saldo=$Deuda-$Pagos;
							if($Saldo!=0)
							{
								if($PerAnt!=$MesC.$AniosC)
								{
									echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td colspan=5><strong>$NombreMes[$MesC] - $AniosC</td></tr>";
									echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td>Fecha</td><td>Documento</td><td>Valor DB</td><td>Valor CR</td><td>Saldo</td></tr>";
									$PerAnt=$MesC.$AniosC;
								}
	
							echo "<tr><td>".$MatCartera[$fila[0]][$Ident[0]][$Documentos][0]."</td><td>".$MatCartera[$fila[0]][$Ident[0]][$Documentos][1]."</td><td align='right'>".number_format($MatCartera[$fila[0]][$Ident[0]][$Documentos][2])."</td><td align='right'>".number_format($Pagos[2],2)."</td><td align='right'>".number_format($Saldo,2)."</td></tr>";
							$TotalSaldo=$TotalSaldo+$Saldo;
							$TotMes=$TotMes+$Saldo;$TotGral=$TotGral+$Saldo;}
						}
						if($TotMes!=0)
						{
							echo "<tr align='right' style='font-weight:bold' ><td colspan='4'>TOTAL MES</td><td align='right'>".number_format($TotMes,2)."</td></tr>";
							$TotMes=0;
						}
					}
				}
			}
			if(count($MatPagos[$fila[0]][$Ident[0]])>0)
			{
				foreach($MatPagos[$fila[0]][$Ident[0]] as $Validar)
				{
					if($Validar[3]=="*")
					{
						echo "<tr><td colspan=5><hr></td></tr>";
						echo "<tr style='color:red;font-weight:bold' bgcolor='#e5e5e5' align='center'><td colspan=5>CREDITOS SIN REFERENCIA</td></tr>";
						echo "<tr style='color:red;font-weight:bold'><td>$Validar[0]</td><td>$Validar[1]</td><td align='right'>0.00</td><td align='right'>-".number_format($Validar[2],2)."</td></tr>";
						$TotalSaldo=$TotalSaldo-$Validar[2];$TotGral=$TotGral-$Validar[2];
					}
				}
			}
			echo "<tr align='right' style='font-weight:bold' ><td colspan='4'>TOTAL ENTIDAD</td><td align='right'>".number_format($TotalSaldo,2)."</td></tr>";$TotalSaldo=0;
		}
	}
		echo "<tr align='right' style='font-weight:bold' ><td colspan='4'>TOTAL CARTERA</td><td align='right'>".number_format($TotGral,2)."</td></tr>";

	echo "</table>";
?>
</body>