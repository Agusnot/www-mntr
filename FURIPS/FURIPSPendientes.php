<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$FechaHoy="$ND[year]-$ND[mon]-$ND[mday]";
	$HoraHoy="$ND[hours]:$ND[minutes]:$ND[seconds]";
	//--
	
	$cons="Select identificacion, primape || ' ' || segape || ' ' || primnom || ' ' || primnom from central.terceros where compania='$Compania[0]' 
	and tipo='Paciente' order by primape,segape,primnom,segnom limit 10";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatPendientes[$fila[0]]=array($fila[0],$fila[1]);
	}
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr  bgcolor="#e5e5e5" style="font-weight:bold">
<td align="center" colspan="4">PACIENTES PENDIENTES POR DILIGENCIAR FURIPS</td>
</tr>
<?
if($MatPendientes)
{?>
<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td>Identificacion</td><td>Apellidos y Nombres</td><td colspan="2"></td>
</tr>
<?
	foreach($MatPendientes as $Pac)
	{?>
	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
    <td align="right"><? echo $Pac[0]?></td>
    <td><? echo $Pac[1]?></td>
    <td><img src="/Imgs/furips.png" border="0" title="Diligenciar Formulario FURIPS" style="cursor:hand;" onclick="location.href='FormularioFURIPS.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Pac[0]?>'" /></td>
    </tr>	
	<?
	}
}
else
{?>
	<tr>
    <td align="center" colspan="4">No se Encontraron pacientes por diligenciar FURIPS!!!</td>
   </tr>	
<?
}?>
</table>
</body>