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
<?echo $Compania[1]?><br>PLAN PRESUPUESTAL<br>

<table  bordercolor="white" width="100%" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Año</td><td>Cuenta</td><td>Nombre</td></tr>
<?
	}
	Encabezados();
	$cons="Select Anio,Cuenta,Nombre,Apropiacion from PlanCuentas
	Order By Cuenta";
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

		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td></tr>";
		$NumRec++;
	}
	
?>
</table>