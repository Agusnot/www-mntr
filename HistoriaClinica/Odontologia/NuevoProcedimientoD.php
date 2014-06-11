<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]>9){$Mes=$ND[mon];}else{$Mes="0".$ND[mon];}
	if($ND[mday]>9){$Dia=$ND[mday];}else{$Dia="0".$ND[mday];}
	$FechaComp="$ND[year]-$Mes-$Dia";
	$HoraHoy="$ND[hours]:$ND[minutes]:$ND[seconds]";
	$Cuadrante=substr($Diente,0,1);
	if($Agregar)
	{		
		if($Cuadrante>4){$Denticion="Temporal";}else{$Denticion="Permanente";}		
		if($TipoOdonto=="Odontograma_Ini"){$TipoOdonto="Inicial";}else{$TipoOdonto="Seguimiento";}
		$cons="Select Procedimiento from Odontologia.tmpodontogramaproc where  Compania='$Compania[0]' and tmpcod='$TMPCOD' 
		and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha'
		and Procedimiento=$Procedimiento";
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			$cons="Insert Into Odontologia.tmpodontogramaproc (Compania,TmpCod,Identificacion,Cuadrante,Diente,ZonaD,
			Procedimiento,Fecha,TipoOdonto,Denticion,ImagenProc,Edicion,fechaant) values('$Compania[0]','$TMPCOD','$Paciente[1]','$Cuadrante',
			'$Diente','$ParteD',$Procedimiento,'$Fecha','$TipoOdonto','$Denticion','$ImagenProc','1','$Fecha $HoraHoy')";
			$res=ExQuery($cons);	
		}
		else
		{
			$cons="Update Odontologia.TmpOdontogramaProc set ImagenProc='$ImagenProc', Edicion='1', Eliminar=NULL where  Compania='$Compania[0]' 
			and tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$ParteD' 
			and Fecha='$Fecha' and Procedimiento=$Procedimiento";
			$res=ExQuery($cons);
		}			
		?>
		<script language="javascript">
			parent.Modifico=true;
			parent.frames.FrameOpener.document.getElementById("FrameProce").src='ProcedimientosDiente.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>&Diente=<? echo $Diente?>&ParteD=<? echo $ParteD?>';
			parent.frames.FrameOpener.document.getElementById("FrameProce").document.FORMA.G.value=1;
			parent.document.getElementById('FrameNewProc').style.position='absolute';
			parent.document.getElementById('FrameNewProc').style.top='1px';
			parent.document.getElementById('FrameNewProc').style.left='1px';
			parent.document.getElementById('FrameNewProc').style.width='1';
			parent.document.getElementById('FrameNewProc').style.height='1';
			parent.document.getElementById('FrameNewProc').style.display='none';
			//alert(parent.frames.FrameOpener.document.getElementById("FrameProce").document.FORMA.G.value);
        </script><?		
	}
		//-- validacion cups
	//-----------------------------------Encontrar la entidad,contrato y No Contrato de la tabla Servicios-----------------------------------------------------------------------------------
	$cons1="Select entidad,contrato,nocontrato from salud.pagadorxservicios,salud.servicios
	where servicios.cedula='$Paciente[1]' and pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]'
	and servicios.estado='AC' and pagadorxservicios.numservicio=servicios.numservicio	
	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";	
	$res1=ExQuery($cons1);		
	if(ExNumRows($res1)>0){
		$fila1=ExFetch($res1);		
		$Eps=$fila1[0]; $Contra=$fila1[1]; $NoContra=$fila1[2];
	}
	else{			
		$cons1="Select entidad,contrato,nocontrato,fechafin from salud.pagadorxservicios,salud.servicios
		where servicios.cedula='$Paciente[1]' and pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]'
		and servicios.estado='AC' and pagadorxservicios.numservicio=servicios.numservicio	and '$FechaComp'>=fechaini order by fechafin desc";
		//echo $cons1;
		$res1=ExQuery($cons1);	
		if(ExNumRows($res1)>0){				
			$fila1=ExFetch($res1);
			//echo $fila1[3];
			if(!$fila1[3]){
				$Eps=$fila1[0]; $Contra=$fila1[1]; $NoContra=$fila1[2];
			}
			else{
				$Eps='-2'; $Contra='-2'; $NoContra='-2';
			}			
		}
		else{
			$Eps='-2'; $Contra='-2'; $NoContra='-2';
		}
	}	
	//echo $cons1;
//--------------------------------------------------Encontrar el plan de servicio--------------------------------------------------------------------------------------------------------
	$cons2="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$Eps' and contrato='$Contra' 
	and numero='$NoContra' and compania='$Compania[0]'";	
	$res2=ExQuery($cons2);echo ExError();	
	$fila2=ExFetch($res2);
	//echo $cons2;
	if($fila2[0]==''){$fila2[0]='-2';}
//-------------------------------------------Encontrar los cups para el plan de servicios------------------------------------------------------------------------------------------------
	
	$cons3="select codigo,nombre,cups.grupo,cups.tipo from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
	where codigo=cupsxplanservic.cup and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] 
	and cupsxplanservic.clase='CUPS' and cups.compania='$Compania[0]' order by codigo";
	//echo $cons3;
	$res3=ExQuery($cons3);echo ExError();
	while($fila=ExFetch($res3))
	{
		$MatCups[$fila[0]]=array($fila[0],$fila[2]);
	}
	//---
	$cons="Select Codigo, nombre, ruta, cup from odontologia.procedimientosimgs where Compania='$Compania[0]' 
	and (estadoimg='Activo' or estadoimg is null) and  
	Codigo not in(Select Procedimiento from odontologia.tmpodontogramaproc where  Compania='$Compania[0]' 
	and tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' 
	and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha' and Eliminar is NULL) order by Nombre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MatCups[$fila[3]][0]==$fila[3]){$cupsi=1;}else{$cupsi="";}
		if($MatCups[$fila[3]][1]){$gruposi=1;}else{$gruposi="";}
		$MatProc[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$cupsi,$gruposi);	
	}
?>
<script language="javascript"> 
//alert(parent.document.FORMA.Info.value);
function CerrarThis()
{
	parent.document.getElementById('FrameNewProc').style.position='absolute';
	parent.document.getElementById('FrameNewProc').style.top='1px';
	parent.document.getElementById('FrameNewProc').style.left='1px';
	parent.document.getElementById('FrameNewProc').style.width='1';
	parent.document.getElementById('FrameNewProc').style.height='1';
	parent.document.getElementById('FrameNewProc').style.display='none';		
}
function Ocultar()
{
	parent.document.getElementById('FrameNewProc').style.position='absolute';
	parent.document.getElementById('FrameNewProc').style.top='1px';
	parent.document.getElementById('FrameNewProc').style.left='1px';
	parent.document.getElementById('FrameNewProc').style.width='1';
	parent.document.getElementById('FrameNewProc').style.height='1';
	parent.document.getElementById('FrameNewProc').style.display='none';	
}
function VerImg(Imagen,ruta)
{
	if(Imagen.checked)	
		Imagen.value=ruta;
	else
		Imagen.value="";
}
function AbrirDiag(TMPCOD,Procedimiento,Diente,ParteD,Fecha,TipoOdonto,ImagenProc)
{
	//alert(TMPCOD+" "+Procedimiento+" "+Diente+" "+ParteD+" "+Fecha+" "+TipoOdonto+" "+ImagenProc);
	
	parent.frames.FrameDiag.location.href="Diagnosticos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD="+TMPCOD+"&Procedimiento="+Procedimiento+"&Diente="+Diente+"&ParteD="+ParteD+"&Fecha="+Fecha+"&TipoOdonto="+TipoOdonto+"&ImagenProc="+ImagenProc;
	parent.document.getElementById('FrameDiag').style.position='absolute';	
	parent.document.getElementById('FrameDiag').style.top=parent.FrameOpener.document.getElementById('TABLA').clientHeight/1.245;	
	parent.document.getElementById('FrameDiag').style.left=parent.document.getElementById('FrameOpener').style.left;	
	parent.document.getElementById('FrameDiag').style.display='';
	parent.document.getElementById('FrameDiag').style.width='420';
	parent.document.getElementById('FrameDiag').style.height='250';
	parent.frames.FrameDiag.focus();
}
</script>	
<body background="/Imgs/Fondo.jpg" >
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>"/>
<input type="hidden" name="Diente" value="<? echo $Diente?>"/>
<input type="hidden" name="ParteD" value="<? echo $ParteD?>"/>
<input type="hidden" name="Fecha" value="<? echo $Fecha?>"/>

<table bordercolor="#e5e5e5" cellpadding="1" cellspacing="0" hspace="0" width="100%" style="position:absolute;top:1px;font : normal normal small-caps 12px Tahoma;">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td>Imagen</td><td></td></tr>
<?
if(!empty($MatProc))
{
	foreach($MatProc as $Procedimiento)
	{?>
	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
		<td align="right" width="45px"><? echo $Procedimiento[0]?></td>
		<td><? echo $Procedimiento[1]?></td>
		<td><input type="checkbox" name="Imagen<? echo $Procedimiento[0]?>" /> </td>
        <td width="16px"><a href="#" <? if(!$Procedimiento[4]){?>onClick="alert('El Procedimiento no esta cubierto por el Plan de servicios!!!');" <? }elseif(!$Procedimiento[5]){?> onClick="alert('No es Posible agregar el procedimiento porque no se encuentra en Ningun Grupo!!!');"<? }else{?>onClick="VerImg(document.FORMA.Imagen<? echo $Procedimiento[0]?>,'<? echo $Procedimiento[2]?>'); AbrirDiag('<? echo $TMPCOD?>','<? echo $Procedimiento[0]?>','<? echo $Diente?>','<? echo $ParteD?>','<? echo $Fecha?>',parent.document.FORMA.Info.value,document.FORMA.Imagen<? echo $Procedimiento[0]?>.value);"<? }?>><img src="/Imgs/ico_agregar.gif" border="0" title="Agregar" /></a></td>
    
	<!--<td width="16px"><a href="#" <? if(!$Procedimiento[4]){?>onClick="alert('El Procedimiento no esta cubierto por el Plan de servicios!!!');" <? }elseif(!$Procedimiento[5]){?> onClick="alert('No es Posible agregar el procedimiento porque no se encuentra en Ningun Grupo!!!');"<? }else{?>onClick="VerImg(document.FORMA.Imagen<? echo $Procedimiento[0]?>,'<? echo $Procedimiento[2]?>');location.href='NuevoProcedimientoD.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Agregar=1&Procedimiento=<? echo $Procedimiento[0]?>&Diente=<? echo $Diente?>&ParteD=<? echo $ParteD?>&Fecha=<? echo $Fecha?>&TipoOdonto='+parent.document.FORMA.Info.value+'&ImagenProc='+document.FORMA.Imagen<? echo $Procedimiento[0]?>.value"<? }?>><img src="/Imgs/ico_agregar.gif" border="0" title="Agregar" /></a></td>
    -->    
    </tr>
	<?
	}
}
?>
</table>
<input type="button" value="X" onClick="CerrarThis()" style="position:absolute;top:0px; right:0px; width:18px; height:18px; text-align:center;cursor:hand;"  title="Cerrar esta ventana">
</form>
</body>