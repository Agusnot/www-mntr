<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
	<table border="1"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
	<tr><td>Cuenta</td><td>Nombre</td><td>Cant</td></tr>
<?	$cons="Select Cuenta,Nombre from Presupuesto.PlanCuentas where Tipo='Detalle' and Anio=$Anio and Compania='$Compania[0]' and Vigencia='Actual' Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons2="Select * from Presupuesto.CruceCuentasCero where Anio=$Anio and Compania='$Compania[0]' and CtaPresupuestal='$fila[0]'";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)==0){$FC="red";$FW="bold";$Tot++;}
		else{$FC="black";$FW="normal";}
		echo "<tr style='color:$FC;font-weight:$FW'><td>$fila[0]</td><td>$fila[1]</td><td>".ExNumRows($res2)."</td></tr>";
	}
	echo "<tr><td colspan=3 bgcolor='#e5e5e5'><strong><em>Total presupuesto sin Interfaz $Tot</em></td></tr>";
	echo "</table>";
?>