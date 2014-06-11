<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select idpaquete,paquete,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom),entidad,contrato,nocontrato 
	from contratacionsalud.paquetesxcontratos,central.terceros 
	where paquetesxcontratos.compania='$Compania[0]' and terceros.compania='$Compania[0]' and identificacion=entidad
	order by primape,segape,primnom ,segnom,contrato,nocontrato,paquete";
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<TR bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Paquete</td><td>Entidad</td><td>Contrato</td><td>No. Contrato</td><td></td>
    </TR>
<?	while($fila=Exfetch($res))
	{
		echo "<tr><td>$fila[1]</td><td>$fila[2]</td><td>$fila[4]</td><td>$fila[5]</td>";?>
        <td>
        	<img src="/Imgs/b_edit.png" style="cursor:hand" title="Editar"
            onClick="location.href='NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&IdPaquete=<? echo $fila[0]?>'"/>
        </td>
        <td>
            <img src="/Imgs/b_sbrowse.png" style="cursor:hand" title="Validar paquete para otros contratos"
            onClick="location.href='ValidarPaquete.php?Paquete=<?echo $fila[1]?>&DatNameSID=<? echo $DatNameSID?>&NomEntidad=<?echo $fila[2]?>&Entidad=<?echo $fila[3]?>&Contrato=<?echo $fila[4]?>&NoContrato=<?echo $fila[5]?>&IdPaquete=<? echo $fila[0]?>'"/>
        </td>
</tr>
<?	}?>    
    <tr>
    	<td colspan="6" align="center">
        	<input type="button" value="Nuevo" onClick="location.href='NewPaquete.php?DatNameSID=<? echo $DatNameSID?>'">
      	</td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>