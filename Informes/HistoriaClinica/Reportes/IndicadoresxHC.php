<?
    if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Indicador){$ParConsInd="and indicador='$Indicador'";}
	if($Ambito){$PartConsAmb="and Ambito='$Ambito'";}
	$cons="SELECT indicador, tipoformato, formato, item, vritem, tablaformato FROM historiaclinica.indicadoresxhc where
	Compania='$Compania[0]' $ParConsInd order by Indicador,tablaformato,item,vritem";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		if($fila[3]!=-1&&$fila[4])
		{
			//echo $fila[5]." -- ".$fila[3]." -- ".$fila[4]."<br>";
			$NumCamp="cmp".substr("00000",0,5-strlen($fila[3])).$fila[3];
			$ParteConsVrItem="and $NumCamp='$fila[4]'";
		}
		else
		{
			$ParteConsVrItem="";	
		}		
		$cons1="SELECT count(cedula) as NumRegistros FROM histoclinicafrms.$fila[5] where Compania='$Compania[0]' and Formato='$fila[2]' and TipoFormato='$fila[1]' and fecha>='$FechaIni' and Fecha<='$FechaFin' $ParteConsVrItem $PartConsAmb";
		//echo $cons1."<br>";
		$res1=ExQuery($cons1);		
		$fila1=ExFetch($res1);
		if($fila1[0]>0)
		{			
			$MatIndicadores[$fila[0]][0]=$fila[0];
			$MatIndicadores[$fila[0]][1]+=$fila1[0];
			$MatIndicadores[$fila[0]][2]=$fila[5];	
			$MatIndicadores[$fila[0]][3]=$fila[2];		
			$MatIndicadores[$fila[0]][4]=$fila[1];	
			$MatIndicadores[$fila[0]][5]=$fila[3];	
			$MatIndicadores[$fila[0]][6]=$fila[4];	
		}
		//$MatIndicadores[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]);	
	}	
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="FechaIni" value="<? echo $FechaIni?>" />
<input type="hidden" name="FechaFin" value="<? echo $FechaFin?>" />
<input type="hidden" name="Ambito" value="<? echo $Ambito?>" />
<input type="hidden" name="Indicador" value="<? echo $Indicador?>">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    <td>Indicador</td><td>Cantidad</td>
</tr>
<?
if($MatIndicadores)
{
	foreach($MatIndicadores as $Ind)
	{?>
	<tr  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
    <td><? echo $Ind[0]?></td>
    <td align="right" style="cursor:hand;" title="Ver Pacientes" onClick="location.href='ListaPacientesIndicadores.php?DatNameSID=<? echo $DatNameSID?>&Indicador=<? echo $Ind[0]?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Ambito=<? echo $Ambito?>&TablaFormato=<? echo $Ind[2]?>&Formato=<? echo $Ind[3]?>&TipoFormato=<? echo $Ind[4]?>&Item=<? echo $Ind[5]?>&VrItem=<? echo $Ind[6]?>'"><? echo $Ind[1]?></td>
    </tr>	
	<?
    }?>	
<?
}
else
{?>
<tr>
<td align="center" colspan="2">No se Encontrarón Indicadores</td></tr>	
<?
}?>
</table>
</form>
</body>