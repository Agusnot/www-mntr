<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Indicador){$ParConsInd="and indicador='$Indicador'";}
	if($Ambito){$PartConsAmb="and Ambito='$Ambito'";}
	if($Item!=-1&&$VrItem)
	{
		//echo $fila[5]." -- ".$fila[3]." -- ".$fila[4]."<br>";
		$NumCamp="cmp".substr("00000",0,5-strlen($Item)).$Item;
		$ParteConsVrItem="and $NumCamp='$VrItem'";
	}
	else
	{
		$ParteConsVrItem="";	
	}		
	$cons="SELECT (PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom), cedula, fecha, hora,  ambito, usuario 
	FROM histoclinicafrms.$TablaFormato, central.Terceros 
	where Terceros.Compania='$Compania[0]' and $TablaFormato.Compania=Terceros.Compania and Formato='$Formato' and TipoFormato='$TipoFormato' 
	and Terceros.Identificacion=$TablaFormato.Cedula and fecha>='$FechaIni' and Fecha<='$FechaFin' $ParteConsVrItem $PartConsAmb 
	order by PrimApe, SegApe, PrimNom, SegNom, Fecha, HOra";
	//echo $cons."<br>";
	$res=ExQuery($cons);
	if(ExNumRows($res)>10){$VolA=1;}		
	while($fila=ExFetch($res))
	{			
		$MatPersonasIndicador[$fila[1]][$fila[2]][$fila[3]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]);
	}
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<?
if($VolA)
{?>
<center><input type="button" name="Volver" value="Volver" onclick="history.back();" /></center>	
<?
}?>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    <td>Paciente</td><td>Identificación</td><td>Fecha Registro</td><td>Hora</td><td>Proceso</td><td>Registró</td>
</tr>
<?
if($MatPersonasIndicador)
{
	foreach($MatPersonasIndicador as $Identificacion)
	{
		foreach($Identificacion as $Fecha)
		{
			foreach($Fecha as $Hora)
			{?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
            <td><? echo $Hora[0];?></td>
            <td><? echo $Hora[1];?></td>
            <td><? echo $Hora[2];?></td>
            <td><? echo $Hora[3];?></td>
            <td><? echo $Hora[4];?></td>
            <td><? echo $Hora[5];?></td>
            </tr>	
			<?
			}
		}	
	}
}
else
{
	?><tr><td colspan="6">No se encontrarón registros</td></tr><?
}
?>
</table>
<center><input type="button" name="Volver" value="Volver" onclick="history.back();" /></center>
</body>