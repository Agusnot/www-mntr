<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();	
	if($ND[mon]<10){$Mes="0".$ND[mon];}else{$Mes=$ND[mon];}	
	if($ND[mday]<10){$Dia="0".$ND[mday];}else{$Dia=$ND[mday];}
	if($Fecha!=$ND[year]."-".$Mes."-".$Dia){$Deshabilitar="Disabled";}else{$Deshabilitar="";}
	$Diente=substr($Diente,1);
	$Cuadrante=substr($Diente,0,1);		
	if($Guardar1)
	{
		$MatCol["A"]=$AA;
		$MatCol["B"]=$BB;
		$MatCol["C"]=$CC;
		$MatCol["D"]=$DD;
		$MatCol["E"]=$EE;		
		$cons="Select Identificacion,Cuadrante,Diente,ZonaD,Procedimiento,Fecha,Eliminar,Nombre,ImagenProc 
		from odontologia.procedimientosimgs,odontologia.tmpodontogramaproc where
		ProcedimientosImgs.Compania='$Compania[0]' and TmpOdontogramaProc.Compania='$Compania[0]' and 
		ProcedimientosImgs.Codigo=TmpOdontogramaProc.Procedimiento and Tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' 
		and Cuadrante='$Cuadrante' and Diente='$Diente'  and Fecha='$Fecha'";//and ZonaD='$ParteD'			
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{		
			while($fila=ExFetch($res))
			{
				//echo $fila[3]." ".$fila[4]."<br>";
				$cons1="Update Odontologia.TmpOdontogramaProc set Edicion=NULL, TransaccionTmp=NULL where Compania='$Compania[0]' 
				and TMPCOD='$TMPCOD' and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]'
				and procedimiento=$fila[4]	and Fecha='$fila[5]'";					
				$res1=ExQuery($cons1);
				//echo $fila[3]." -> ".$MatCol[$fila[3]]." ";
				if($MatCol[$fila[3]]!="")
				{					
					$cons1="Update Odontologia.TmpOdontogramaProc set ImagenZona='".$MatCol[$fila[3]]."'
					where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and
					Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$fila[3]' and procedimiento=$fila[4]
					and Fecha='$fila[5]'";					
					$res1=ExQuery($cons1);
				}					
				if($fila[6]!="")
				{				
					$cons1="Update Odontologia.OdontogramaProc set Eliminar='1' where Compania='$Compania[0]' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]'
					and procedimiento=$fila[4]	and Fecha='$fila[5]'";
					$res1=ExQuery($cons1);
					$cons="Delete from odontologia.tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$Paciente[1]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]'
					and procedimiento=$fila[4]	and Fecha='$fila[5]' and Elimiar IS NOT NULL";
				}				
			}			
		}
		else
		{
			$cons="Select Identificacion,Cuadrante,Diente,ZonaD,Procedimiento,Fecha,Eliminar 
			from odontologia.tmpodontogramaproc where
			TmpOdontogramaProc.Compania='$Compania[0]' and TmpOdontogramaProc.Procedimiento=-1 and Tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' 
			and Cuadrante='$Cuadrante' and Diente='$Diente'  and Fecha='$Fecha'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				//echo $fila[3]." ".$fila[4]."<br>";				
				$cons1="Update Odontologia.TmpOdontogramaProc set Edicion=NULL, TransaccionTmp=NULL where Compania='$Compania[0]' 
				and TMPCOD='$TMPCOD' and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]'
				and procedimiento=$fila[4]	and Fecha='$fila[5]'";					
				$res1=ExQuery($cons1);
				//echo $fila[3]." -> ".$MatCol[$fila[3]]." ";
				if($MatCol[$fila[3]]!="")
				{					
					$cons1="Update Odontologia.TmpOdontogramaProc set ImagenZona='".$MatCol[$fila[3]]."'
					where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and
					Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$fila[3]' and procedimiento=$fila[4]
					and Fecha='$fila[5]'";					
					$res1=ExQuery($cons1);
				}					
				if($fila[6]!="")
				{				
					$cons1="Update Odontologia.OdontogramaProc set Eliminar='1' where Compania='$Compania[0]' 
					and Identificacion='$fila[0]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]'
					and procedimiento=$fila[4]	and Fecha='$fila[5]'";
					$res1=ExQuery($cons1);
					$cons="Delete from odontologia.tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' 
					and Identificacion='$Paciente[1]' and Cuadrante='$fila[1]' and Diente='$fila[2]' and ZonaD='$fila[3]'
					and procedimiento=$fila[4]	and Fecha='$fila[5]' and Elimiar IS NOT NULL";
				}
				
			}		
		}
		?><script language="javascript">parent.Modifico=true;</script><?		
		
		$CSG="";
		$G="";
		$Guardar1="";
		$Color="";
		?>
		<script language="javascript">	
		parent.Trabajando=false;
		if(parent.document.FORMA.Info.value=="Odontograma_Ini"){TipoOdontograma="Inicial";}else{TipoOdontograma="Seguimiento";}
		parent.document.getElementById('FrameInfo').src='OdontogramaIni.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>&TipoOdontograma='+TipoOdontograma;			
		
		parent.document.getElementById('FrameNewProc').style.position='absolute';
		parent.document.getElementById('FrameNewProc').style.top='1px';
		parent.document.getElementById('FrameNewProc').style.left='1px';		
		parent.document.getElementById('FrameNewProc').style.display='none';
		parent.document.getElementById('FrameNewProc').style.width='0';
		parent.document.getElementById('FrameNewProc').style.height='0';
		//-
		parent.document.getElementById('FrameDiag').style.position='absolute';
		parent.document.getElementById('FrameDiag').style.top='1px';
		parent.document.getElementById('FrameDiag').style.left='1px';		
		parent.document.getElementById('FrameDiag').style.display='none';
		parent.document.getElementById('FrameDiag').style.width='0';
		parent.document.getElementById('FrameDiag').style.height='0';
		//-
		parent.document.getElementById('FrameVD').style.position='absolute';
		parent.document.getElementById('FrameVD').style.top='1px';
		parent.document.getElementById('FrameVD').style.left='1px';		
		parent.document.getElementById('FrameVD').style.display='none';
		parent.document.getElementById('FrameVD').style.width='0';
		parent.document.getElementById('FrameVD').style.height='0';
		//--
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		//--
		parent.document.getElementById('FrameFondo').style.position='absolute';
		parent.document.getElementById('FrameFondo').style.top='1px';
		parent.document.getElementById('FrameFondo').style.left='1px';		
		parent.document.getElementById('FrameFondo').style.display='none';
		parent.document.getElementById('FrameFondo').style.width='0';
		parent.document.getElementById('FrameFondo').style.height='0';
        </script>
		<?	
	}
	if($CSG)
	{
		$cons="Delete from odontologia.tmpodontogramaproc where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Identificacion='$Paciente[1]'
		and Diente='$Diente' and fecha='$Fecha' and Edicion='1'";
		//echo $cons;
		$res=ExQuery($cons);	
		$cons="Update Odontologia.TmpOdontogramaproc set Eliminar=NULL where Compania='$Compania[0]' and TmpCod='$TMPCOD' 
		and Identificacion='$Paciente[1]'and Diente='$Diente' and Fecha='$Fecha'";		
		$res=ExQuery($cons);			
		$CSG="";
		$G="";
		?>
		<script language="javascript">	
		parent.Trabajando=false;	
		parent.document.getElementById('FrameNewProc').style.position='absolute';
		parent.document.getElementById('FrameNewProc').style.top='1px';
		parent.document.getElementById('FrameNewProc').style.left='1px';		
		parent.document.getElementById('FrameNewProc').style.display='none';
		parent.document.getElementById('FrameNewProc').style.width='0';
		parent.document.getElementById('FrameNewProc').style.height='0';
		//-
		parent.document.getElementById('FrameDiag').style.position='absolute';
		parent.document.getElementById('FrameDiag').style.top='1px';
		parent.document.getElementById('FrameDiag').style.left='1px';		
		parent.document.getElementById('FrameDiag').style.display='none';
		parent.document.getElementById('FrameDiag').style.width='0';
		parent.document.getElementById('FrameDiag').style.height='0';
		//-
		parent.document.getElementById('FrameVD').style.position='absolute';
		parent.document.getElementById('FrameVD').style.top='1px';
		parent.document.getElementById('FrameVD').style.left='1px';		
		parent.document.getElementById('FrameVD').style.display='none';
		parent.document.getElementById('FrameVD').style.width='0';
		parent.document.getElementById('FrameVD').style.height='0';
		//--		
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		//--
		parent.document.getElementById('FrameFondo').style.position='absolute';
		parent.document.getElementById('FrameFondo').style.top='1px';
		parent.document.getElementById('FrameFondo').style.left='1px';		
		parent.document.getElementById('FrameFondo').style.display='none';
		parent.document.getElementById('FrameFondo').style.width='0';
		parent.document.getElementById('FrameFondo').style.height='0';
        </script>
		<?	
	}	
	if($TipoOdontograma=="Inicial"){$consini="and TipoOdonto='Inicial'";}else{$consini="";}
	$cons="Select ZonaD,ImagenZona from odontologia.odontogramaproc where Compania='$Compania[0]' and Identificacion='$Paciente[1]' 
	and Cuadrante='$Cuadrante' and Diente='$Diente' and Fecha='$Fecha' $consini";	
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatZonas[$fila[0]]=array($fila[0],$fila[1]);	
	}
	if(!empty($MatZonas))
	{
		foreach($MatZonas as $Zonas)
		{
			if($Zonas[0]=="A"){$AA=$Zonas[1];}
			else
			{
				if($Zonas[0]=="B"){$BB=$Zonas[1];}
				else
				{
					if($Zonas[0]=="C"){$CC=$Zonas[1];}
					else
					{
						if($Zonas[0]=="D"){$DD=$Zonas[1];}
						else
						{
							if($Zonas[0]=="E"){$EE=$Zonas[1];}				
						}		
					}
				}
			}
		}
	}
	$cons="Select ZonaD,ImagenZona from odontologia.tmpodontogramaproc where Compania='$Compania[0]' and Tmpcod='$TMPCOD'
	and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' 
	and Fecha='$Fecha'  $consini";	
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatZonasTmp[$fila[0]]=array($fila[0],$fila[1]);	
	}
	if(!empty($MatZonasTmp))
	{
		foreach($MatZonasTmp as $Zonas)
		{
			if($Zonas[0]=="A"){$AA=$Zonas[1];}
			else
			{
				if($Zonas[0]=="B"){$BB=$Zonas[1];}
				else
				{
					if($Zonas[0]=="C"){$CC=$Zonas[1];}
					else
					{
						if($Zonas[0]=="D"){$DD=$Zonas[1];}
						else
						{
							if($Zonas[0]=="E"){$EE=$Zonas[1];}				
						}		
					}
				}
			}
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">	
	var letraa="x";
	function CerrarThis()
	{
		parent.Trabajando=false;
		parent.document.getElementById('FrameNewProc').style.position='absolute';
		parent.document.getElementById('FrameNewProc').style.top='1px';
		parent.document.getElementById('FrameNewProc').style.left='1px';		
		parent.document.getElementById('FrameNewProc').style.display='none';
		parent.document.getElementById('FrameNewProc').style.width='0';
		parent.document.getElementById('FrameNewProc').style.height='0';
		//-
		parent.document.getElementById('FrameDiag').style.position='absolute';
		parent.document.getElementById('FrameDiag').style.top='1px';
		parent.document.getElementById('FrameDiag').style.left='1px';		
		parent.document.getElementById('FrameDiag').style.display='none';
		parent.document.getElementById('FrameDiag').style.width='0';
		parent.document.getElementById('FrameDiag').style.height='0';
		//-
		parent.document.getElementById('FrameVD').style.position='absolute';
		parent.document.getElementById('FrameVD').style.top='1px';
		parent.document.getElementById('FrameVD').style.left='1px';		
		parent.document.getElementById('FrameVD').style.display='none';
		parent.document.getElementById('FrameVD').style.width='0';
		parent.document.getElementById('FrameVD').style.height='0';
		//--
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		//--
		parent.document.getElementById('FrameFondo').style.position='absolute';
		parent.document.getElementById('FrameFondo').style.top='1px';
		parent.document.getElementById('FrameFondo').style.left='1px';		
		parent.document.getElementById('FrameFondo').style.display='none';
		parent.document.getElementById('FrameFondo').style.width='0';
		parent.document.getElementById('FrameFondo').style.height='0';
	}
	function raton(e,N) 
	{ 
		x = e.clientX; 
		y = e.clientY; 	
		frames.FrameOpener.location.href="ColorDiente.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y;
		document.getElementById('FrameOpener').style.left=x;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='120';
		document.getElementById('FrameOpener').style.height='120';
	}
	function CambiarInfo(Letra,RutaC)
	{			
		document.FORMA.ParteD.value=Letra;
		if(parent.document.FORMA.Info.value=="Odontograma_Ini"){TipoOdontograma="Inicial";}else{TipoOdontograma="Seguimiento";}
		frames.FrameInfo.location.href="ColorDiente.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>&Diente=<? echo $Diente?>&ParteD="+Letra+"&RutaC="+RutaC+"&TipoOdonto="+TipoOdontograma;
		frames.FrameProce.location.href="ProcedimientosDiente.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>&Diente=<? echo $Diente?>&ParteD="+Letra;
		//--
		if(letraa!=Letra)
		{
			parent.document.getElementById('FrameNewProc').style.position='absolute';
			parent.document.getElementById('FrameNewProc').style.top='1px';
			parent.document.getElementById('FrameNewProc').style.left='1px';		
			parent.document.getElementById('FrameNewProc').style.display='none';
			parent.document.getElementById('FrameNewProc').style.width='0';
			parent.document.getElementById('FrameNewProc').style.height='0';
			letraa=Letra;
		}
	}
	function GG()
	{		
		if(document.FORMA.G.value=="")
		{	
			//alert("no hay datos");
			document.FORMA.Guardar.disabled=true;
			document.FORMA.Guardar.style.cursor="default";
			document.FORMA.Guardar.title="Debe realizar algun cambio antes de Guardar";				
		}
		else
		{
			//alert("hay datos");
			document.FORMA.Guardar.disabled=false;
			document.FORMA.Guardar.style.cursor="hand";		
			document.FORMA.Guardar.title="Salir Guardando Cambios";
		}
	}
	function Validar()
	{
		//alert(parent.document.FORMA.Info.value);
		if(document.FORMA.G.value=="")
		{
			if(parent.document.FORMA.Info.value=="Odontograma_Ini"){TipoOdontograma="Inicial";}else{TipoOdontograma="Seguimiento";}
			parent.document.getElementById('FrameInfo').src='OdontogramaIni.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>&TipoOdontograma='+TipoOdontograma;			
			parent.Trabajando=false;
			CerrarThis();
			return true;	
		}	
		else
		{
			if(confirm("Si Cierra la ventana sin antes guardar se perderan todos los cambios realizados\nDesea Continuar?"))
			{				
				parent.Trabajando=false;
				if(parent.document.FORMA.Info.value=="Odontograma_Ini"){TipoOdontograma="Inicial";}else{TipoOdontograma="Seguimiento";}
		parent.document.getElementById('FrameInfo').src='OdontogramaIni.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>&TipoOdontograma='+TipoOdontograma;
				document.FORMA.CSG.value=1;				
				document.FORMA.submit();return true;
			}	
			else
			{
				return false;	
			}
		}
	}
	function GuardarCambios()
	{		
		/*alert(document.FORMA.AA.value);
		alert(document.FORMA.BB.value);
		alert(document.FORMA.CC.value);
		alert(document.FORMA.DD.value);
		alert(document.FORMA.EE.value);*/
		document.FORMA.Guardar1.value=1;
		document.FORMA.submit();
		//CerrarThis();	
	}	
</script>
</head>
<body background="/Imgs/Fondo.jpg" onLoad="parent.Trabajando=true;GG();" onUnload="parent.Trabajando=false;">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>"/>
<input type="hidden" name="Fecha" value="<? echo $Fecha?>"/>
<input type="hidden" name="ParteD" value="<? echo $ParteD?>"/>
<input type="hidden" id="AA" name="AA" value="<? echo $AA?>"/>
<input type="hidden" id="BB" name="BB" value="<? echo $BB?>"/>
<input type="hidden" id="CC" name="CC" value="<? echo $CC?>"/>
<input type="hidden" id="DD" name="DD" value="<? echo $DD?>"/>
<input type="hidden" id="EE" name="EE" value="<? echo $EE?>"/>
<input type="hidden" name="G" value="<? echo $G?>"/>
<input type="hidden" name="CSG" value="<? echo $CSG?>"/>
<input type="hidden" name="Guardar1" value="<? echo $Guardar1?>"/>
<input type="hidden" name="Color" value="<? echo $Color?>"/>
<input type="button" value=" X " onClick="return Validar()"  title="Salir sin Guardar Cambios" style="position:absolute;top:0px; right:0px;cursor:hand; font-weight:bold; height:25px; width:25px" >
<button name="Guardar" <? echo $Deshabilitar;?> onClick="GuardarCambios();" style="position:absolute;top:0px; right:24px;cursor:hand;height:25px; width:25px; font-weight:bold" title="Guardar Cambios">
<img src="/Imgs/b_save.png"/>
</button>
<br>
<table border="1" bordercolor="#e5e5e5" style="font : normal normal small-caps 13px Tahoma; " id="TABLA" width="100%"> 
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="3">Pieza Dental <? echo $Diente?></td>
        <td>Superficies del Diente</td>
    </tr>
   <tr>   		
    	<td colspan="3" align="center" onClick="CambiarInfo('B',document.FORMA.BB.value);" style="cursor:hand" >
        <img id="B" <? if(!$BB){?>src="/Imgs/Odontologia/D1.gif" <? }else{?> src="<? echo $BB?>"<? }?>height="55" width="120" ></td> 
        <td align="center">
        	<?
			//echo $Cuadrante;
           	if($Cuadrante==1||$Cuadrante==5){$ImgDL="/Imgs/Odontologia/diente_letras1.png";}
			elseif($Cuadrante==2||$Cuadrante==6){$ImgDL="/Imgs/Odontologia/diente_letras2.png";}
			elseif($Cuadrante==3||$Cuadrante==7){$ImgDL="/Imgs/Odontologia/diente_letras3.png";}
			elseif($Cuadrante==4||$Cuadrante==8){$ImgDL="/Imgs/Odontologia/diente_letras4.png";}
			?>
            <img src="<? echo $ImgDL?>" height="55" width="55">            
        </td>   
    </tr>
    <tr>
    	<td onClick="CambiarInfo('A',document.FORMA.AA.value);" style="cursor:hand" align="center"><img id="A" <? if(!$AA){?>src="/Imgs/Odontologia/D3.gif" <? }else{?> src="<? echo $AA?>"<? }?>height="100" width="55" ></td>
        <td style="cursor:hand" align="center" onClick="CambiarInfo('E',document.FORMA.EE.value);"><img id="E" <? if(!$EE){?>src="/Imgs/Odontologia/D5.gif"<? }else{?> src="<? echo $EE?>"<? }?> height="75" width="75" ></td>
        <td onClick="CambiarInfo('C',document.FORMA.CC.value);" style="cursor:hand" align="center"><img id="C" <? if(!$CC){?>src="/Imgs/Odontologia/D4.gif" <? }else{?> src="<? echo $CC?>"<? }?>height="100" width="55" ></td>
        <td rowspan="2">
        <iframe id="FrameInfo" name="FrameInfo" src="Info.php" frameborder="0" width="100%" height="100%"></iframe>
        </td>
    </tr>
    <tr>
    	<td colspan="3" align="center" onClick="CambiarInfo('D',document.FORMA.DD.value);" style="cursor:hand" >
        <img id="D" <? if(!$DD){?>src="/Imgs/Odontologia/D2.gif" <? }else{?> src="<? echo $DD?>"<? }?>height="55" width="120" >           
    </tr>
    <tr>
    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="4">Procedimientos Diente</td>
    </tr>
    <tr >
    <td colspan="4">
    <iframe id="FrameProce" name="FrameProce" src="PD.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Fecha=<? echo $Fecha?>&Diente=<? echo $Diente?>" frameborder="0" width="100%" height="100%"></iframe>
    </td>
    </tr>
</table>
</form>  
<iframe scrolling="no" id="FrameFondo1" name="FrameFondo1" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
</body>
</html>