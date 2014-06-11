<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$Cuadrante=substr($Diente,0,1);	
	$cons="Select ZonaD,Procedimiento,Nombre,ImagenProc,fechaant from odontologia.procedimientosimgs,odontologia.tmpodontogramaproc where
	ProcedimientosImgs.Compania='$Compania[0]' and TmpOdontogramaProc.Compania='$Compania[0]' and 
	ProcedimientosImgs.Codigo=TmpOdontogramaProc.Procedimiento and Tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' 
	and Diente='$Diente' and Fecha='$Fecha' and Eliminar is NULL order by fechaant desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatProcDienteTmp[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);	
	}	
?>
<body background="/Imgs/Fondo.jpg">
<table bordercolor="#e5e5e5" cellpadding="1" cellspacing="0" hspace="0" width="360px" style="position:absolute;top:1px; left:1px;font : normal normal small-caps 12px Tahoma;">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Sup.</td><td>Cod.</td><td>Nombre</td><td>Fecha</td><td>Imagen</td></tr>
<?
if(!empty($MatProcDienteTmp))
{
	foreach($MatProcDienteTmp as $ParteTmp)
	{
		foreach($ParteTmp as $ProcedimientoTmp)
		{
		?>
	<tr onMouseOver="this.bgColor='#E3FDE8'" onMouseOut="this.bgColor=''" style="font-size:11px">
		<td align="center" style="font-weight:bold" width="25px"><font color="#2A5FFF"><? echo $ProcedimientoTmp[0]?></font></td>
		<td align="right" width="25px" style="font-weight:bold"><? echo $ProcedimientoTmp[1]?></td>
		<td><? echo $ProcedimientoTmp[2]?></td>
        <td width="60px"><? echo substr($ProcedimientoTmp[4],0,10);?></td>
		<td align="center" width="45px"><input type="checkbox" name="Imagen<? echo $ProcedimientoTmp[0]?>" <? if($ProcedimientoTmp[3]!=""){echo "checked"; $tit=1; }else{echo $tit=0;} ?> disabled <? if($tit==1){echo "title='SI'";}else{echo "title='NO'";}?>/> </td>
		<!--<td width="16px"><a href="#" onClick="if(confirm('El Procedimiento se eliminar&aacute; permanentemente!!!\nDesea Eliminar el Procedimiento?')){location.href='ProcedimientosDiente.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Eliminar=1&Procedimiento=<? echo $ProcedimientoTmp[0]?>&Diente=<? echo $Diente?>&ParteD=<? echo $ParteD?>&Fecha=<? echo $Fecha?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar Procedimiento" /></a></td>-->
	</tr>
	<?
		}
	}
}
/*foreach($MatProcDiente as $Parte)
{
	foreach($Parte as $Procedimiento)
	{
	?>
<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
	<td align="center" style="font-weight:bold" width="40px"><font color="#2A5FFF"><? echo $Procedimiento[0]?></font></td>
    <td align="right" width="45px" style="font-weight:bold"><? echo $Procedimiento[1]?></td>
    <td><? echo $Procedimiento[2]?></td>
    <td align="center" width="45px"><input type="checkbox" name="Imagen<? echo $Procedimiento[0]?>" <? if($Procedimiento[3]!=""){echo "checked"; $tit=1; }else{echo $tit=0;} ?> disabled <? if($tit==1){echo "title='SI'";}else{echo "title='NO'";}?>/> </td>
    <!--<td width="16px"><a href="#" onClick="if(confirm('El Procedimiento se eliminar&aacute; permanentemente!!!\nDesea Eliminar el Procedimiento?')){location.href='ProcedimientosDiente.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Eliminar=1&Procedimiento=<? echo $Procedimiento[0]?>&Diente=<? echo $Diente?>&ParteD=<? echo $ParteD?>&Fecha=<? echo $Fecha?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar Procedimiento" /></a></td>-->
</tr>
<?
	}
}*/
?>
</table>
</body>