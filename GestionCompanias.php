<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($Guardar)
	{
		$cons="Update Central.Compania set Nit='$NIT',Direccion='$Direccion',Telefonos='$Telefono' where Nombre='$Nombre'";
		$res=ExQuery($cons);
		echo ExError($res);
	}
	if($Nuevo)
	{
		$cons="Insert into Central.Compania(Nombre,Nit,Direccion,Telefonos,Estilo) values ('$Nombre','$NIT','$Direccion','$Telefono','Estandar')";
		$res=ExQuery($cons);
	}
?>
<body background="/Imgs/Fondo.jpg">
<table border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:white;font-weight:bold" align="center" bgcolor="#666699">

<td>Entidad</td><td>Nit</td><td>Direccion</td><td>Telefonos</td></tr>
<?
	$cons="Select Nombre,Nit,Direccion,Telefonos from Central.Compania";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$i++;
		echo "<form name='FORMA$i'>";
		echo "<tr><td>$fila[0]</td>";
		echo "<td><input type='text' name='NIT' value='$fila[1]'></td>";
		echo "<td><input type='text' name='Direccion' value='$fila[2]'></td>";
		echo "<td><input type='text' name='Telefono' value='$fila[3]'></td>";
		echo "<td><button type='submit' name='Guardar'><img alt='Guardar informaci&oacute;n' src='/Imgs/b_import.png'></button>";?>
		<button onClick="open('ImportarDatosCompania.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $fila[0]?>','','width=600,height=300')"><img alt="Cargar Informacion de otras compa&ntilde;ias" src="/Imgs/b_tblanalyse.png"></button>
		<button onClick="open('EliminarCompania.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $fila[0]?>','','width=400,height=200')"><img alt="Eliminar Compa&ntilde;ia" src="/Imgs/b_deltbl.png"></button>
        <button onClick="open('ListadoCargos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $fila[0]?>','','width=650,height=400')"><img alt="Ver Cargos Compa&ntilde;ia" src="/Imgs/b_usradd.png"></button></td></tr>
<?		echo "<input type='Hidden' name='Nombre' value='$fila[0]'>";
		echo "</form>";
	}

	echo "<form name='FORMANew'>";
	echo "<td><input type='Text' name='Nombre' value='$fila[0]'>";
	echo "<td><input type='text' name='NIT' value='$fila[1]'></td>";
	echo "<td><input type='text' name='Direccion' value='$fila[2]'></td>";
	echo "<td><input type='text' name='Telefono' value='$fila[3]'></td>";
	echo "<td><button type='submit' name='Nuevo'><img src='/Imgs/b_import.png'></button></td></tr>";
	echo "<input type='hidden' name='DatNameSID' value='$DatNameSID'>";
	echo "</form>";
?>
</table>
</body>