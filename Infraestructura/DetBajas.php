<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.Bajas Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and AutoId=$AutoId";
		$res = ExQuery($cons);	
	}
	$cons = "Select Bajas.AutoId,Bajas.Codigo,Nombre,Caracteristicas,Modelo,Serie 
	From InfraEstructura.Bajas, InfraEstructura.CodElementos
	Where Bajas.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]'
	and Bajas.AutoId = CodElementos.AutoId and Bajas.TMPCOD='$TMPCOD' order by Bajas.AutoId";
	$res = ExQuery($cons);
	$NumFilas = ExNumRows($res);
	if($NumFilas>0)
	{
	?>
	<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
    	<tr bgcolor="#e5e5e5" style=" font-weight:bold;">
        	<td width="15%">Codigo</td><td>Elemento</td><td>Caracteristicas</td><td>Modelo</td><td>Serie</td>
        </tr>
    <?
    while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td>";
		?>
		<td><a href="NewDetBajas.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&AutoId=<? echo $fila[0];?>&TMPCOD=<? echo $TMPCOD;?>&Clase=<? echo $Clase?>"><img border="0" src="/Imgs/b_edit.png" /></a></td>
        <td><img src="/Imgs/b_drop.png" style="cursor:hand;" 
        onclick="if(confirm('Desea Eliminar el Registro?'))
        {location.href='DetBajas.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AutoId=<? echo $fila[0]?>&TMPCOD=<? echo $TMPCOD;?>&Tipo=<? echo $Tipo;?>&Clase=<? echo $Clase;?>&Anio=<? echo $Anio?>'}"  /></td>
        </tr>
		<?	
	}	
	?>    
    </table>
	<?	
	}

?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" action="NewDetBajas.php">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Clase" value="<? echo $Clase;?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
<input type="submit" name="Nuevo" value="Nuevo" />
</form>
</body>