<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Eliminar)
	{
		$cons="Delete from salud.actvasistenciales where compania='$Compania[0]' and nomactvidad='$NomActElim' and especialidad='$EspElim' and formato='$FormatoElim' 
		and id_item=$ItemElim";
		//echo $cons;
		$res=ExQuery($cons); 		   
		$Eliminar="";
	}
	$cons="select nomactvidad,especialidad,formato,id_item,msjhc,cup,interprog
	from salud.actvasistenciales where actvasistenciales.compania='$Compania[0]'
	order by nomactvidad";
	$res=ExQuery($cons);
?>	

<html>
<head>
<script language='javascript' src="/Funciones.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr>
    	<td colspan="9" bgcolor="#e5e5e5" style="font-weight:bold" align="center">ACTIVIDADES ASISTENCIALES</td>        
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Nom. Actividad</td><td>Especialidad</td><td>Formato</td><td>Item</td><td>Mensaje</td><td>CUP</td><td>Interconsultas</td><td colspan="2"></td>
    </tr>
<?	while($fila=ExFetch($res))
	{
		$cons2="select nombre from contratacionsalud.cups where compania='$Compania[0]' and codigo='$fila[5]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		if($fila[6]==1){$Interp="Si";}else{$Interp="No";}?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td><td><? echo $fila[4]?></td>
            <td><? echo $fila[5]." - ".$fila2[0]?>&nbsp;</td><td><? echo $Interp?></td>
            <td><img src="/Imgs/b_edit.png" title="Editar" style="cursor:hand" onClick="location.href='NewActAsist.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&NomActAnt=<? echo $fila[0]?>&EspecialidadAnt=<? echo $fila[1]?>&FormatoAnt=<? echo $fila[2]?>&ItemAnt=<? echo $fila[3]?>&MsjHC=<? echo $fila[4]?>&CUP=<? echo $fila[5]?>&CodCUP=<? echo "$fila[5] - $fila2[0]"?>&InterProg=<? echo $fila[6]?>'">
            </td>
            <td><img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand" onClick="if(confirm('¿Esta seguro de elimiar este registro?')){location.href='ActAsistenciales.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&NomActElim=<? echo $fila[0]?>&EspElim=<? echo $fila[1]?>&FormatoElim=<? echo $fila[2]?>&ItemElim=<? echo $fila[3]?>';}"></td>
        </tr>
<?	}?>    
    <tr align="center">
    	<td colspan="9"><input type="button" value="Nuevo" onClick="location.href='NewActAsist.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
    
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
