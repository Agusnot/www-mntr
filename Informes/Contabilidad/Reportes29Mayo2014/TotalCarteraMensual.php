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
		$MatCartera[$fila2[3]][$fila2[4]][$fila2[7]][$fila2[6]]=$MatCartera[$fila2[3]][$fila2[4]][$fila2[7]][$fila2[6]]+$fila2[0];
		$MatDocSoporte[$fila2[3]][$fila2[4]][$fila2[1]]=array($fila2[6],$fila2[7]);
	}

	$cons3="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion,'$Corte'-Fecha,date_part('month',Fecha),date_part('year',Fecha) from Contabilidad.Movimiento where 
	Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
	and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Fecha<='$Corte'
	Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0";

	$res3=ExQuery($cons3);
	while($fila3=ExFetch($res3))
	{
		$MesPag=$MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]][0];
		$AnioPag=$MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]][1];
		if(!$MesPag || !$AnioPag){$MesPag=$fila3[6];$AnioPag=$fila3[7];}
		$MatPagos[$fila3[3]][$fila3[4]][$AnioPag][$MesPag]=array($MatPagos[$fila3[3]][$fila3[4]][$AnioPag][$MesPag][0]+$fila3[0],"*",$AnioPag,$MesPag);
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
<?			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td colspan=5><strong>$Ident[0] $Ident[1] $Ident[2] $Ident[3] $Ident[4]</td></tr>";
			echo "<TR bgcolor='#e5e5e5' style='font-weight:bold'><TD>Periodo</td><td align='right'>Debitos</td><td align='right'>Creditos</td><td align='right'>Saldo</td></tr>";
			if(count($AniosCart[$fila[0]][$Ident[0]])>0)
			{
				foreach($AniosCart[$fila[0]][$Ident[0]] as $AniosC)
				{
					foreach($MesCart[$fila[0]][$Ident[0]][$AniosC] as $MesC)
					{
						$Saldo=$MatCartera[$fila[0]][$Ident[0]][$AniosC][$MesC]-$MatPagos[$fila[0]][$Ident[0]][$AniosC][$MesC][0];
						if($Saldo!=0)
						{
							echo "<tr><td>$NombreMes[$MesC] - $AniosC</td><td align='right'>".number_format($MatCartera[$fila[0]][$Ident[0]][$AniosC][$MesC],2)."</td>";
							echo "<td align='right'>".number_format($MatPagos[$fila[0]][$Ident[0]][$AniosC][$MesC][0],2)."</td>";
							$MatPagos[$fila[0]][$Ident[0]][$AniosC][$MesC][3]="Ok";
							echo "<td align='right'>".number_format($Saldo,2)."</td>";
							echo "</tr>";
							$TotEnt=$TotEnt+$Saldo;$TotGral=$TotGral+$Saldo;
						}
					}
				}
			}
			echo "<tr align='right' style='font-weight:bold' ><td colspan='3'>TOTAL ENTIDAD</td><td align='right'>".number_format($TotEnt,2)."</td></tr>";$TotEnt=0;
		}
	}
	echo "<tr align='right' style='font-weight:bold' ><td colspan='3'>TOTAL CARTERA</td><td align='right'>".number_format($TotGral,2)."</td></tr>";
	echo "</table>";
?>
</body>