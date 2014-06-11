<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar==1){
		$cons="Delete from contratacionsalud.cupsxconsulextern where Cargo='$Cargo' and Codigo='$Codigo' and Compania='$Compania[0]'";		
		$res = ExQuery($cons);
		echo ExError($res);	
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
<? 	if($Cargo!=''){?>	
		<tr><td align="center" colspan="5"><input type="button" value="Nuevo" onClick="parent.location.href='EncNewConsExtr.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>'"></td></tr>
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td>Codigo</td><td>Detalle</td><td>Tiempo por Consulta Sugerido </td><td bgcolor="#e5e5e5" style="font-weight:bold" ></td>
	    </tr>
<? 	$cons="select cupsxconsulextern.codigo,nombre,timeconsulsuge from contratacionsalud.cupsxconsulextern,contratacionsalud.cups where 
	cargo='$Cargo' and cupsxconsulextern.codigo=cups.codigo and cupsxconsulextern.compania='$Compania[0]' and cups.compania='$Compania[0]' order by cupsxconsulextern.codigo"; 
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td align='center'>$fila[2]</td>";?>
		<td><a href="#" onClick="if(confirm('Desea eliminar el registro?')){location.href='VerConsulExtr.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Cargo=<? echo $Cargo?>&Codigo=<? echo $fila[0]?>';}">
        <img title="Eliminar" border="0" src="/Imgs/b_drop.png"/>
        </td></tr>        
<? }
?>
<? 	}?>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
