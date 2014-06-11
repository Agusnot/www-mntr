<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<select name="Entidad" onchange="document.FORMA.submit();">
<option></option>
<option value="Saludcoop EPS">Salucoop EPS</option>
<option value="asdf">asdf</option>
</select>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr bgcolor="#e5e5e5" style="font-weight:bold">
<td>Id Contrato</td><td>No. Contrato</td><td>Poliza</td><td>Nombre</td><td>Plan Servicios</td><td>Plan Medicamentos</td><td>Codificacion</td><td>Modo Facturacion</td><td>Estado</td><td>Mensaje</td><td>Tipo Descto</td>
<td>Porc Descto</td></tr>
<?
	$cons="Select IdContrato,NoContrato,Poliza,Nombre,PlanServicios,PlanMedicamentos,Codificacion,ModoFac,Estado,Mensaje,TipoDescto,PorcDescto from Facturacion.ContratosxEntidades 
	where Entidad='$Entidad' and Compania='$Compania[0]'";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>";?>
        <a href="#" onclick="open('MovxContrato.php?DatNameSID=<? echo $DatNameSID?>','','width=700,height=400')">
        <? echo $fila[1];?>
		</a>
		<? echo "</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[6]</td><td>$fila[7]</td><td>$fila[8]</td><td>$fila[9]</td><td>$fila[10]</td><td>$fila[11]</td><td>$fila[12]</td><td>$fila[13]</td></tr>";
	}
?>
</table>
</table>
<input type="button" value="Nuevo Contrato" onclick="location.href='NewContrato.php?DatNameSID=<? echo $DatNameSID?>'" />
</form>
</body>
</html>
