<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Guardar)
	{
		if($color=="#FFFFFF"){$ruta="/Imgs/Odontologia/blanco.JPG";}
		if($color=="#2A9FFF"){$ruta="/Imgs/Odontologia/azulc.JPG";}
		if($color=="#2A9FFFR"){$ruta="/Imgs/Odontologia/azulcr.JPG";}
		if($color=="#2A9FFFDOA"){$ruta="/Imgs/Odontologia/azulcDOA.JPG";}
		if($color=="#2ADF00"){$ruta="/Imgs/Odontologia/verde.JPG";}
		if($color=="#2ADF00R"){$ruta="/Imgs/Odontologia/verder.JPG";}
		if($color=="#2ADF00DOR"){$ruta="/Imgs/Odontologia/verdeDOR.JPG";}
		if($color=="#FF0000"){$ruta="/Imgs/Odontologia/rojo.JPG";}
		if($color=="#FF0000C"){$ruta="/Imgs/Odontologia/rojoC.JPG";}
		if($color=="#FF0000CP"){$ruta="/Imgs/Odontologia/rojoCP.JPG";}
		if($color=="#FF0000EP"){$ruta="/Imgs/Odontologia/rojoEP.JPG";}
		if($color=="#FF0000X"){$ruta="/Imgs/Odontologia/rojoX.JPG";}
		if($color=="#FF0000F"){$ruta="/Imgs/Odontologia/rojoF.JPG";}
		if($color=="#FF0000FD"){$ruta="/Imgs/Odontologia/rojoFD.JPG";}
		if($color=="#FF0000MPD"){$ruta="/Imgs/Odontologia/rojoMPD.JPG";}
		if($color=="#FF0000MB"){$ruta="/Imgs/Odontologia/rojoMB.JPG";}
		if($color=="#FF0000R"){$ruta="/Imgs/Odontologia/rojoR.JPG";}		
		if($color=="#0000FF"){$ruta="/Imgs/Odontologia/azulo.JPG";}
		if($color=="#0000FFA"){$ruta="/Imgs/Odontologia/azuloA.JPG";}
		if($color=="#0000FFCO"){$ruta="/Imgs/Odontologia/azuloCO.JPG";}
		if($color=="#0000FFDOI"){$ruta="/Imgs/Odontologia/azuloDOI.JPG";}
		if($color=="#0000FFHP"){$ruta="/Imgs/Odontologia/azuloHP.JPG";}
		if($color=="#0000FFIMP"){$ruta="/Imgs/Odontologia/azuloIMP.JPG";}
		if($color=="#0000FFINC"){$ruta="/Imgs/Odontologia/azuloINC.JPG";}
		if($color=="#0000FFPM"){$ruta="/Imgs/Odontologia/azuloPM.JPG";}
		if($color=="#0000FFFP"){$ruta="/Imgs/Odontologia/azuloFP.JPG";}
		if($color=="#0000FFPR"){$ruta="/Imgs/Odontologia/azuloPR.JPG";}
		if($color=="#0000FFSE"){$ruta="/Imgs/Odontologia/azuloSE.JPG";}
		if($color=="#0000FFSR"){$ruta="/Imgs/Odontologia/azuloSR.JPG";}
		if($color=="#0000FFTC"){$ruta="/Imgs/Odontologia/azuloTC.JPG";}
		if($color=="#000000"){$ruta="/Imgs/Odontologia/negro.JPG";}
		if($color=="#FFE401"){$ruta="/Imgs/Odontologia/amarillo.JPG";}
		if($color=="#FFE401R"){$ruta="/Imgs/Odontologia/amarillor.JPG";}
		$Descripcion=trim($Descripcion);
		if(!$Editar)
		{
			$cons="Select color from odontologia.colorconvenciones where compania='$Compania[0]' and Color='$color'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				$cons="Insert into odontologia.colorconvenciones (Compania,Color,NColor,Ruta,Descripcion,FechaCrea) values('$Compania[0]',
				'$color','$ncolor','$ruta','$Descripcion','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
				$res=ExQuery($cons);
				//echo $cons;
				?><script language="javascript">location.href='ConvencionColores.php?DatNameSID=<? echo $DatNameSID?>';</script><?
			}
			else
			{
				?><script language="javascript">alert("El Color <? echo $ncolor?> ya se encuentra configurado!!!");</script><?
			}
		}	
		else
		{
			$cons="Update Odontologia.ColorConvenciones set Color='$color', NColor='$ncolor', Descripcion='$Descripcion', Ruta='$ruta' 
			where Compania='$Compania[0]' and Color='$colora'";
			$res=ExQuery($cons);
			?><script language="javascript">location.href='ConvencionColores.php?DatNameSID=<? echo $DatNameSID?>';</script><?
		}			
	}
	if($Editar){$color="#$color";}
	$cons="Select color from odontologia.colorconvenciones where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($color=="#FFFFFF")
		{
			$azulc=1;$verde=1;$rojo=1;$azulo=1;$negro=1;			
		}
		else
		{
			if($color!=$fila[0])
			{
				switch($fila[0])
				{
					case "#FFFFFF":	$blanco=1;
									break;
					case "#FFE401":	$amarillo=1;
									break;	
					case "#FFE401R":	$amarillor=1;
									break;						
					case "#2A9FFF":	$azulc=1;
									break;
					case "#2A9FFFR":	$azulcr=1;
									break;
					case "#2A9FFFDOA":	$azulcDOA=1;
									break;
					case "#2ADF00":	$verde=1;
									break;
					case "#2ADF00R":	$verder=1;
									break;
					case "#2ADF00DOR":	$verdeDOR=1;
									break;
					case "#FF0000":	$rojo=1;
									break;
					case "#FF0000C":	$rojoC=1;
									break;
					case "#FF0000CP":	$rojoCP=1;
									break;
					case "#FF0000EP":	$rojoEP=1;
									break;
					case "#FF0000X":	$rojoX=1;
									break;
					case "#FF0000F":	$rojoF=1;
									break;
					case "#FF0000FD":	$rojoFD=1;
									break;
					case "#FF0000MPD":	$rojoMPD=1;
									break;
					case "#FF0000MB":	$rojoMB=1;
									break;
					case "#FF0000R":	$rojoR=1;
									break;
					case "#0000FF":	$azulo=1;
									break;
					case "#0000FFA":	$azuloA=1;
									break;
					case "#0000FFCO":	$azuloCO=1;
									break;
					case "#0000FFDOI":	$azuloDOI=1;
									break;
					case "#0000FFHP":	$azuloHP=1;
									break;
					case "#0000FFIMP":	$azuloIMP=1;
									break;
					case "#0000FFINC":	$azuloINC=1;
									break;
					case "#0000FFPM":	$azuloPM=1;
									break;
					case "#0000FFFP":	$azuloFP=1;
									break;
					case "#0000FFPR":	$azuloPR=1;
									break;
					case "#0000FFSE":	$azuloSE=1;
									break;
					case "#0000FFSR":	$azuloSR=1;
									break;
					case "#0000FFTC":	$azuloTC=1;
									break;
					case "#000000":	$negro=1;
									break;
					default:
									break;
									
				}
			}
		}
	}
?> 
<script language="JavaScript">
lck=0;
function r(hval,ncol)
{
   if ( lck == 0 )
   {
     document.FORMA.color.value=hval;
	 //document.FORMA.ncolor.value=ncol;
   }
}

function l()
{
   if (lck == 0)
   {
     lck = 1;	 
	 document.FORMA.ncolor.readOnly=true;
   } else {
     lck = 0;	 
	 document.FORMA.ncolor.readOnly=false;
   }
}
function Validar()
{
	if(document.FORMA.ncolor.value==""){alert("Debe especificar el Nombre asignado al Color!!!"); return false;}
	if(document.FORMA.color.value==""){alert("Debe seleccionar un Color!!!"); return false;}	
}
</script> 

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<?
if($Editar)
{
	$titulo="Editar Color";		
	$cons="Select color,ncolor,descripcion from odontologia.colorconvenciones where compania='$Compania[0]' and Color='$color'";	
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$colora){$colora=$fila[0];}
	if(!$color){$color=$fila[0];}
	if(!$ncolor){$ncolor=$fila[1];}
	if(!$Descripcion){$Descripcion=$fila[2];}
}
else
{
	$titulo="Nuevo Color";	
}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="colora" value="<? echo $colora?>"/>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="10" align="center"><? echo $titulo?> <input type="text" class="textbox" size="10" name="color" value="<? echo $color?>" readonly style="border:thin; background:none;"></td></tr>
<tr>
<td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td>
<td colspan="8"><input type="text" class="textbox" name="ncolor" value="<? echo $ncolor?>" style="width:100%" title="Nombre Asignado" ></td>
</tr>
<tr style="cursor:hand">
<td height="18" align="center" <? if(!$blanco){ ?> onMouseOver="r('#FFFFFF','BLANCO'); return true" onClick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/blanco.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$amarillo){ ?> onMouseOver="r('#FFE401','AMARILLO'); return true" onClick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/amarillo.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$amarillor){ ?> onMouseOver="r('#FFE401R','AMARILLO R'); return true" onClick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/amarillor.JPG" width="25px" height="25px"></td>
<td height="18"  align="center" <? if(!$azulc){ ?> onMouseOver="r('#2A9FFF','AZUL CLARO'); return true" onClick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azulc.JPG" width="25px" height="25px"></td>
<td height="18"  align="center" <? if(!$azulcr){ ?> onMouseOver="r('#2A9FFFR','AZUL CLARO R'); return true" onClick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azulcr.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azulcDOA){ ?> onMouseOver="r('#2A9FFFDOA','AZUL CLARO DOA'); return true" onClick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azulcDOA.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$verde){ ?> onMouseOver="r('#2ADF00','VERDE'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/verde.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$verder){ ?> onMouseOver="r('#2ADF00R','VERDE R'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/verder.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$verdeDOR){ ?> onMouseOver="r('#2ADF00DOR','VERDE DOR'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/verdeDOR.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojo){ ?> onMouseOver="r('#FF0000','ROJO'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojo.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoC){ ?> onMouseOver="r('#FF0000C','ROJO C'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoC.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoCP){ ?> onMouseOver="r('#FF0000CP','ROJO CP'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoCP.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoEP){ ?> onMouseOver="r('#FF0000EP','ROJO EP'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoEP.JPG" width="25px" height="25px"></td>
</tr>
<tr style="cursor:hand">
<td height="18" align="center" <? if(!$rojoX){ ?> onMouseOver="r('#FF0000X','ROJO X'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoX.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoF){ ?> onMouseOver="r('#FF0000F','ROJO F'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoF.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoFD){ ?> onMouseOver="r('#FF0000FD','ROJO FD'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoFD.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoMPD){ ?> onMouseOver="r('#FF0000MPD','ROJO MPD'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoMPD.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoMB){ ?> onMouseOver="r('#FF0000MB','ROJO MB'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoMB.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$rojoR){ ?> onMouseOver="r('#FF0000R','ROJO R'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/rojoR.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azulo){ ?>onmouseover="r('#0000FF','AZUL OSCURO'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azulo.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloA){ ?>onmouseover="r('#0000FFA','AZUL OSCURO A'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloA.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloCO){ ?>onmouseover="r('#0000FFCO','AZUL OSCURO CO'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloCO.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloDOI){ ?>onmouseover="r('#0000FFDOI','AZUL OSCURO DOI'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloDOI.JPG" width="25px" height="25px"></td>
</tr>
<tr style="cursor:hand">
<td height="18" align="center" <? if(!$azuloHP){ ?>onmouseover="r('#0000FFHP','AZUL OSCURO HP'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloHP.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloIMP){ ?>onmouseover="r('#0000FFIMP','AZUL OSCURO IMP'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloIMP.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloINC){ ?>onmouseover="r('#0000FFINC','AZUL OSCURO INC'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloINC.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloPM){ ?>onmouseover="r('#0000FFPM','AZUL OSCURO PM'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloPM.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloFP){ ?>onmouseover="r('#0000FFFP','AZUL OSCURO FP'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloFP.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloPR){ ?>onmouseover="r('#0000FFPR','AZUL OSCURO PR'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloPR.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloSE){ ?>onmouseover="r('#0000FFSE','AZUL OSCURO SE'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloSE.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloSR){ ?>onmouseover="r('#0000FFSR','AZUL OSCURO SR'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloSR.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$azuloTC){ ?>onmouseover="r('#0000FFTC','AZUL OSCURO TC'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/azuloTC.JPG" width="25px" height="25px"></td>
<td height="18" align="center" <? if(!$negro){ ?>onmouseover="r('#000000','NEGRO'); return true"  onclick="JavaScript:l()"<? }else{?>onmouseover="r('',''); return true" <? }?>><img src="/Imgs/Odontologia/negro.JPG" width="25px" height="25px"></td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="10">Descripcion</td></tr>
<tr><td colspan="10"><textarea name="Descripcion" rows="4" style="width:100%"><? echo $Descripcion?></textarea></td></tr>
</table>
<input type="submit" name="Guardar" value="Guardar"/>
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConvencionColores.php?DatNameSID=<? echo $DatNameSID?>'"/>
</form>
</body>