<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	mysql_select_db("Presupuesto");
	function Encabezados()
	{
	global $Compania;global $Anio;global $MesIniLet;global $MesFinLet;
?>
<font face="tahoma" style="font-variant:small-caps" style="font-size:11px">
<strong><?echo strtoupper($Compania[0])?></strong><br>
<?echo $Compania[1]?><br>CRUCE CUENTAS CERO<br>

<table  bordercolor="white" width="100%" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Año</td><td>Cuenta</td><td>Nombre</td><td>Comprobante</td><td>Cta Debito</td><td>Cta Credito</td></tr>
<?
	}
	Encabezados();
	$cons="Select Cuenta,Nombre,TipoCompPresupuestal,CtaDebe,CtaHaber,CruceCuentasCero.Anio  from PlanCuentas,CruceCuentasCero
	where Cuenta=CtaPresupuestal and PlanCuentas.Compania=CruceCuentasCero.Compania Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}
	
		$cons1="Select Nombre from Contabilidad.PlanCuentas where Cuenta='$fila[3]'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$NomDebe=$fila1[0];

		$cons1="Select Nombre from Contabilidad.PlanCuentas where Cuenta='$fila[4]'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$NomHaber=$fila1[0];

		echo "<tr><td>$fila[5]</td><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3] $NomDebe</td><td>$fila[4] $NomHaber</td></tr>";
		$NumRec++;
	}
	
?>
</table>