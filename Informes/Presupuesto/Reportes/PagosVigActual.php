<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	mysql_select_db("Presupuesto");
	include("GeneraValoresEjecucion.php");

	$cons="Select * from Central.meses where Numero=$MesIni";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesIniLet=$fila[0];
	$cons="Select * from Central.meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesFinLet=$fila[0];

?>
<img src="/Imgs/ContraDenar.jpg" / style="width:100px;position:absolute">
<center>
<font face="tahoma" style="font-size:12px"><strong>
CONTRALORIA DEPARTAMENTAL DE NARIÑO<BR />
NIT 800.157.830-3</strong>
</font>
<hr />
</center><br />
<font face="tahoma" style="font-variant:small-caps" style="font-size:12px"><strong>
<center>
Pagos Realizados con Cargo a Vigencia Actual<br />
Periodo: <? echo $MesIniLet?> a <? echo $MesFinLet?> de <? echo $Anio?><br />
Entidad : <? echo $Compania[0]?><br />
<? echo $Compania[1]?><br /><br />
</center>
<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>Codigo Presupuestal</td><td>Concepto del Rubro</td></td><td>Fecha de Pago</td><td>Concepto de Pago</td><td>Valor a Pagar</td><td>Valor Neto Pagado</td><td>Nombre de Beneficiario</td><td>Identificacion</td>
<td>Rete Fuente</td><td>Estampillas</td><td>Otros</td><td>Cta Bancaria</td><td>No Cheque</td>
</tr>

<?
	if($Anio){
		
		$cons="Select Cuenta,Fecha,DetConcepto,sum(Credito),Identificacion,DocOrigen,NoDocOrigen,Numero from Presupuesto.Movimiento,Presupuesto.Comprobantes 
		where Comprobantes.Comprobante=Movimiento.Comprobante and TipoComprobant='Egreso Presupuestal' and month(Fecha)>=$MesIni and month(Fecha)<=$MesFin 
		and year(Fecha)=$Anio and Cuenta!=0 and Movimiento.Compania='$Compania[0]' Group By Cuenta,Numero,Fecha,Identificacion";
		$res=ExQuery($cons);echo ExError();
		while($fila=ExFetch($res))
		{
			$cons1="Select Nombre from Presupuesto.PlanCuentas where Cuenta=$fila[0]";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			$NomCuenta=$fila1[0];
			
			$cons9="Select sum(Haber) from Contabilidad.Movimiento where Comprobante='$fila[5]' and Numero=$fila[6] and Cuenta like '2436%' and Movimiento.Compania='$Compania[0]'";
			$res9=ExQuery($cons9);echo ExError();
			$fila9=ExFetch($res9);
			$ReteFte=$fila9[0];
			
			$cons9="Select sum(Haber) from Contabilidad.Movimiento where Comprobante='$fila[5]' and Numero=$fila[6] and Cuenta like '244085003%' and Movimiento.Compania='$Compania[0]'";
			$res9=ExQuery($cons9);echo ExError();
			$fila9=ExFetch($res9);
			$Estampillas=$fila9[0];

			$cons9="Select sum(Haber) from Contabilidad.Movimiento where Comprobante='$fila[5]' and Numero=$fila[6] and Cuenta NOT like '244085003%' and Cuenta NOT Like '2436%' 
			and Cuenta NOT Like '1%' and Movimiento.Compania='$Compania[0]'";
			$res9=ExQuery($cons9);echo ExError();
			$fila9=ExFetch($res9);
			$Otros=$fila9[0];

			$cons1="Select Haber,Cuenta from Contabilidad.Movimiento where Comprobante='$fila[5]' and Numero=$fila[6] and Cuenta like '1%' and Movimiento.Compania='$Compania[0]'";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			$VrNeto=$fila1[0];
			
			$cons3="Select Nombre from Contabilidad.PlanCuentas where Cuenta='$fila1[1]'";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);
			$CuentaBanc=$fila3[0];
			
			$cons2="Select NoCheque from Contabilidad.Movimiento where Comprobante='$fila[5]' and Numero=$fila[6] and NoCheque!='' and Movimiento.Compania='$Compania[0]'";
			$res2=ExQuery($cons2);echo ExError();
			$fila2=ExFetch($res2);
			$NumCheque=$fila2[0];
			
			$cons1="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			$Nombre="$fila1[0] $fila1[1] $fila1[2] $fila1[3]";
			echo "<tr><td>$fila[0]</td><td>$NomCuenta</td><td>$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($fila[3],2)."</td><td align='right'>".number_format($VrNeto,2)."</td><td>$Nombre</td><td>$fila[4]</td>
			<td>$ReteFte</td><td>$Estampillas</td><td>$Otros</td><td>$CuentaBanc</td><td>$NumCheque</td>
			</tr>";
		}

	}
?>
</table><br /><br />
<table border="0" style="font-size:12px">
<tr>
<td>
__________________________________<br />
Representante Legal
</td>
<td style="width:120px;"></td>
<td>
__________________________________<br />
Jefe de Presupuesto
</td>

</tr></table>