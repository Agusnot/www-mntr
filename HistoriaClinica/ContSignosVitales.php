<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Servicios||$Servicios=="Servicio Actual"){$ParteCons="and NumServicio=$NumServicio";}
	if($NumServicio&&$NumServicio!=-1)
	{
		$cons="Select fecha from historiaclinica.signosvitales where compania='$Compania[0]' and cedula='$Paciente[1]' and 
		Fecha>='$ND[year] $ND[mon] $ND[mday] 00:00:00' and Fecha<='$ND[year] $ND[mon] $ND[mday] 23:59:00' and numservicio=-1";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			$cons="Update Historiaclinica.Signosvitales set numservicio=$NumServicio where Compania='$Compania[0]' and Cedula='$Paciente[1]' and NumServicio=-1";
			$res=ExQuery($cons);
			//echo "actualiza mismo dia";
		}
		else
		{
			//echo "busca serv ante<br>";
			$cons="select numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio!=$NumServicio
			order by numservicio desc limit 1";
			$res=ExQuery($cons);
			$fns=ExFetch($res);
			if($fns)
			{
				$cons="Update Historiaclinica.Signosvitales set numservicio=$fns[0] where Compania='$Compania[0]' and Cedula='$Paciente[1]' and NumServicio=-1";
				$res=ExQuery($cons);
				//echo "actualiza serv ant";
			}	
		}
	}
	elseif(!$Servicios||$Servicios=="Servicio Actual")
	{
		echo "<center>El Paciente no tiene servicios Activos!!!</center>";	
		$ParteCons="and NumServicio=-1";
	}
	//echo "$NumServicio --> $Servicios";
	$cons="Select AutoId,Fecha,Usuario,Temperatura,Pulso,Respiracion,TensionArterial1,TensionArterial2 from historiaclinica.signosvitales where Compania='$Compania[0]'
	and Cedula='$Paciente[1]' $ParteCons order by AutoId Desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatSignos[$fila[0]]=array($fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7]);	
	}
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="Servicios" value="<? echo $Servicios?>" />
<table align="center" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >
<tr bgcolor="#e5e5e5" style="font-weight:bold">
    <td colspan="8" align="center"><? echo "Signos Vitales: $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]";?></td>
</tr>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
<td>Fecha</td><td>Usuario</td><td>Temperatura <br />(ºC)</td><td>Pulso <br />(x min.)</td><td>Respiracion <br />(x min.)</td><td colspan="2">Tension Arterial <br />(Sist./Diast.)</td>
</tr>
<?
if($MatSignos)
{
	foreach($MatSignos as $Signos)
	{?>
	<tr align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">	
		<td><? echo $Signos[0]?></td>
        <td><? echo $Signos[1]?></td>
        <td><? echo $Signos[2]?></td>
        <td><? echo $Signos[3]?></td>
        <td><? echo $Signos[4]?></td>
        <td><? echo $Signos[5]?></td>
        <td><? echo $Signos[6]?></td>        
	</tr>
    <?
	}
}
else
{?>
<tr>
	<td colspan="7" align="center">No Existen Registros de Signos Vitales Para el Paciente</td>
</tr>
<?
}?>
</table>
</form>
</body>