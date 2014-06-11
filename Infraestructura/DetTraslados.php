<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from InfraEstructura.Traslados Where Compania='$Compania[0]' and AutoId=$AutoId and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);
	}
?>
<body background="/Imgs/Fondo.jpg"
onload="if(document.FORMA.NumFilas.value>0){frames.parent.document.FORMA.Guardar.disabled=false;}
else{frames.parent.document.FORMA.Guardar.disabled=true;}">
<form name="FORMA" method="post" action="DetNuevoTraslado.php">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>"  />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="Clase" value="<? echo $Clase?>" />
<input type="hidden" name="CC" value="<? echo $CC?>" />
<input type="hidden" name="Responsable" value="<? echo $Responsable?>" />
<input type="hidden" name="IDRA" value="<? echo $IDRA?>" />
<?
	$cons = "Select Traslados.AutoId,Traslados.Codigo,Nombre,Caracteristicas,Modelo,Serie 
	From InfraEstructura.Traslados, InfraEstructura.CodElementos
	Where Traslados.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]'
	and Traslados.AutoId = CodElementos.AutoId and Traslados.TMPCOD='$TMPCOD' order by Traslados.AutoId";
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
		<td><a href="DetNuevoTraslado.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&AutoId=<? echo $fila[0];?>&TMPCOD=<? echo $TMPCOD;?>&Numero=<? echo $Numero?>&Anio=<? echo $Anio?>&Tipo=<? echo $Tipo?>&Clase=<? echo $Clase?>"><img border="0" src="/Imgs/b_edit.png" /></a></td>
        <td><img src="/Imgs/b_drop.png" style="cursor:hand;" 
        onclick="if(confirm('Desea Eliminar el Registro?'))
        {location.href='DetTraslados.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AutoId=<? echo $fila[0]?>&TMPCOD=<? echo $TMPCOD;?>&Numero=<? echo $Numero;?>&Tipo=<? echo $Tipo;?>&Clase=<? echo $Clase;?>&Anio=<? echo $Anio?>'}"  /></td>
        </tr>
		<?	
	}	
	?>    
    </table>
	<?	
	}
?>
<input type="hidden" name="NumFilas" value="<? echo $NumFilas;?>" />
<input type="submit" name="Nuevo" value="Nuevo" onClick="parent.Ocultar()" />
</form>
</body>