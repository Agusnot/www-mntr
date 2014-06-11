<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Elim)
	{
		$cons="Delete from $Tabla where ";
		$Valores = explode("|",$Criterio);
		for($i=0;$i<count($Valores)-2;$i+=2)
		{
			if($i==count($Valores)-3){$cons = $cons. $Valores[$i]. "='". $Valores[$i+1]."'";}
			else{$cons = $cons. $Valores[$i]. " ='". $Valores[$i+1]. "' and ";}
		}
		$res=ExQuery($cons);echo ExError();
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" 
        style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
<?
	$Original=$Tabla;
	$Tabla=explode(".",$Tabla);
	$NomTabla=$Tabla[1];
	$BD=$Tabla[0];
	
	echo "<tr bgcolor='#e5e5e5' align='center' style='font-weight:bold'>";
        //Select table_schema, table_name,Column_name from information_schema.columns Where Column_name='identificacion'
        //--cedula,identificacion,cedpaciente,paciente
	$cons="SELECT column_name,column_default,data_type,character_maximum_length,data_type FROM information_schema.columns WHERE table_name ='".strtolower($NomTabla)."' and table_schema='".strtolower($BD)."'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$n++;
		$NomCampo[$n]=$fila[0];
		$Pl = strtoupper(substr($NomCampo[$n],0,1));
		$NomCampo[$n] = $Pl.substr($NomCampo[$n],1,strlen($NomCampo[$n]));
		if($fila[0]=="compania"){$TieneCompania="SI";}
		else {echo "<td>$NomCampo[$n]</td>";}
	}
	echo "</tr>";
	
	$cons="Select ";
	foreach($NomCampo as $Nombre){$cons = $cons. $Nombre . ",";}
	$cons=substr($cons,0,strlen($cons)-1);
	$cons = $cons. " from $BD.$NomTabla";
	if($TieneCompania){ $cons = $cons." where Compania='$Compania[0]'";}
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr>";
		$Criterio = "";
		for($x=0;$x<=$n-1;$x++)
		{
			//echo $NomCampo[$x+1]."-->";
			$Criterio=$Criterio. $NomCampo[$x+1]."|$fila[$x]|";
			if($fila[$x]!=$Compania[0]){
			echo "<td>".$fila[$x]."</td>";}
		}
		?>
		<td><a href="NewAdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Tabla=<? echo $Original?>&Criterio=<? echo $Criterio?>">
        	<img border="0" title="Editar" src='/Imgs/b_edit.png'>
         </a></td>
		<td><img onClick="if(confirm('Eliminar Este registro?')){location.href='AdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Elim=1&Criterio=<? echo $Criterio?>&Tabla=<? echo $Original?>'}" style="cursor:hand" title="Eliminar" src='/Imgs/b_drop.png'></a></td>
<?		echo "</tr>";$Criterio="";
	}
?>
</table>
<input type="button" value="Nuevo" onClick="location.href='NewAdminTablas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Original?>'">


   

</body>
</html>
