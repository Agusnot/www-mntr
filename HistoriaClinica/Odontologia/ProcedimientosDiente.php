
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($ND[mon]>9){$Mes=$ND[mon];}else{$Mes="0".$ND[mon];}
	if($ND[mday]>9){$Dia=$ND[mday];}else{$Dia="0".$ND[mday];}
	$FechaComp="$ND[year]-$Mes-$Dia";
	if(number_format(substr($Fecha,0,4),0)==number_format($ND[year],0)&&number_format(substr($Fecha,5,2),0)==number_format($Mes,0)&&number_format(substr($Fecha,8,2),0)==number_format($Dia,0))
	{$Deshabilitar="";}else{$Deshabilitar="Disabled";}		
	$Cuadrante=substr($Diente,0,1);				
	if($Eliminar)
	{		
		$cons="Update Odontologia.TmpOdontogramaproc set Eliminar='1' where Compania='$Compania[0]' and TmpCod='$TMPCOD' 
		and Identificacion='$Paciente[1]'and Procedimiento=$Procedimiento and Cuadrante='$Cuadrante' and Diente='$Diente' 
		and ZonaD='$ParteD' and Fecha='$Fecha'";
		$res=ExQuery($cons);
		$cons="Delete from Odontologia.TmpOdontogramaproc where Compania='$Compania[0]' and TmpCod='$TMPCOD' and Identificacion='$Paciente[1]'
		and Procedimiento=$Procedimiento and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha' and Edicion='1'
		and Eliminar='1'";	
		$res=ExQuery($cons);
		?><script language="javascript">
		parent.document.FORMA.G.value=1;
		ParteD="<? echo $ParteD;?>";
        parent.frames.FrameInfo.document.FORMA.RutaC.value="";
		parent.frames.FrameInfo.document.FORMA.Colores.value="";
		parent.frames.FrameInfo.document.FORMA.submit();
		ID=ParteD+ParteD;		
		parent.document.getElementById(ID).value=parent.frames.FrameInfo.document.FORMA.RutaC.value;
		if(ParteD=="A"){parent.document.getElementById(ParteD).src="/Imgs/Odontologia/D3.gif";}
		else
		{
			if(ParteD=="B"){parent.document.getElementById(ParteD).src="/Imgs/Odontologia/D1.gif";}
			else
			{
				if(ParteD=="C"){parent.document.getElementById(ParteD).src="/Imgs/Odontologia/D4.gif";}
				else
				{
					if(ParteD=="D"){parent.document.getElementById(ParteD).src="/Imgs/Odontologia/D2.gif";}
					else
					{
						if(ParteD=="E"){parent.document.getElementById(ParteD).src="/Imgs/Odontologia/D5.gif";}				
					}		
				}
			}
		}
		//=parent.frames.FrameInfo.document.FORMA.RutaC.value;
        </script>
		<?		
	}

	$cons="Select Procedimiento,Nombre,ImagenProc,cup,fechaant from odontologia.procedimientosimgs,odontologia.tmpodontogramaproc where
	ProcedimientosImgs.Compania='$Compania[0]' and TmpOdontogramaProc.Compania='$Compania[0]' and 
	ProcedimientosImgs.Codigo=TmpOdontogramaProc.Procedimiento and Tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' 
	and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha' and Eliminar is NULL order by fechaant desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{		
		$MatProcDienteTmp[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);	
	}	
?>
<script language="javascript">
function VerProcedimientos(ParteD)
{		
	/*parent.frames.FrameFondo1.location.href="Framefondo.php";				
	parent.document.getElementById('FrameFondo1').style.position='absolute';
	parent.document.getElementById('FrameFondo1').style.top='1px';
	parent.document.getElementById('FrameFondo1').style.left='1px';
	parent.document.getElementById('FrameFondo1').style.display='';
	parent.document.getElementById('FrameFondo1').style.width='100%';
	parent.document.getElementById('FrameFondo1').style.height='95%';	*/
	
	parent.parent.frames.FrameNewProc.location.href="NuevoProcedimientoD.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha="+document.FORMA.Fecha.value+"&Diente=<? echo $Diente?>&ParteD="+ParteD;		
	parent.parent.document.getElementById('FrameNewProc').style.position='absolute';
	parent.parent.document.getElementById('FrameNewProc').style.top=parent.document.getElementById('TABLA').clientHeight/1.245;
	parent.parent.document.getElementById('FrameNewProc').style.left=parent.parent.document.getElementById('FrameOpener').style.left;
	parent.parent.document.getElementById('FrameNewProc').style.display='';
	parent.parent.document.getElementById('FrameNewProc').style.width='420';
	parent.parent.document.getElementById('FrameNewProc').style.height='250';
	parent.parent.frames.FrameNewProc.focus();
}
</script>
<body background="/Imgs/Fondo.jpg" onLoad="parent.GG();">
<form name="FORMA" id="FORMA" method="post"> 
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>"/>
<input type="hidden" name="Diente" value="<? echo $Diente?>"/>
<input type="hidden" name="ParteD" value="<? echo $ParteD?>"/>
<input type="hidden" name="Fecha" value="<? echo $Fecha?>"/>
<div id="ParteDD" style="position:absolute;background:none; top:1px; right:1px;font : normal normal small-caps 11px Tahoma;font-weight:bold">
Superficie Diente: <font color="#2A5FFF" size="+1"><? echo $ParteD?></font>
</div>

<table bordercolor="#e5e5e5" cellpadding="1" cellspacing="0" hspace="0" width="370px" style="position:absolute;top:20px; left:1px;font : normal normal small-caps 12px Tahoma;">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td>Fecha</td><td>Imagen</td><td></td></tr>
<?
if(!empty($MatProcDienteTmp))
{
	foreach($MatProcDienteTmp as $ProcedimientoTmp)
	{
		if($Fecha!=substr($ProcedimientoTmp[4],0,10)){$Disab2="disabled";}else{$Disab2="";}
		?>
	<tr onMouseOver="this.bgColor='#E3FDE8'" onMouseOut="this.bgColor=''" style="font-size:11px">
		<td align="right" width="45px"><? echo $ProcedimientoTmp[0]?></td>
		<td><? echo $ProcedimientoTmp[1]?></td>
        <td><? echo substr($ProcedimientoTmp[4],0,10);?></td>
		<td align="center" width="45px"><input type="checkbox" name="Imagen<? echo $ProcedimientoTmp[0]?>" <? if($ProcedimientoTmp[2]!=""){echo "checked"; $tit=1; }else{echo $tit=0;} ?> disabled <? if($tit==1){echo "title='SI'";}else{echo "title='NO'";}?>/> </td>
		<td width="16px"><a href="#" <? if(!$Deshabilitar&&!$Disab2){?>onClick="if(confirm('El Procedimiento se eliminar&aacute; permanentemente!!!\nDesea Eliminar el Procedimiento?')){location.href='ProcedimientosDiente.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Eliminar=1&Procedimiento=<? echo $ProcedimientoTmp[0]?>&Diente=<? echo $Diente?>&ParteD=<? echo $ParteD?>&Fecha=<? echo $Fecha?>'}" <? }else{?> style="cursor:default"<? }?>><img src="/Imgs/b_drop.png" border="0" <? if(!$Deshabilitar&&!$Disab2){?>title="Eliminar Procedimiento"<? }else{?> title="No se Puede Eliminar el Procedimiento"<? }?> /></a></td>
	</tr>
	<?
	}
}
?>
</table>
<button type="button" name="Nuevo" title="Crear Procedimiento" <? echo $Deshabilitar?> onClick="VerProcedimientos('<? echo $ParteD?>')" style="position:absolute;top:0px; left:0px; width:20px; height:20px; text-align:center;cursor:hand;">
    <img src="/Imgs/b_newtbl.png" style="width:16px; height:16px"/>
</button>
</form>
</body>
