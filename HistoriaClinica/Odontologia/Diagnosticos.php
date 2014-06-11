<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	if($ND[mon]>9){$Mes=$ND[mon];}else{$Mes="0".$ND[mon];}
	if($ND[mday]>9){$Dia=$ND[mday];}else{$Dia="0".$ND[mday];}
	$HoraHoy="$ND[hours]:$ND[minutes]:$ND[seconds]";
	$FechaComp="$ND[year]-$Mes-$Dia";
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
			Procedimiento,Fecha,TipoOdonto,Denticion,ImagenProc,Edicion,Diagnostico1,Diagnostico2,Diagnostico3,Diagnostico4,Diagnostico5,fechaant)
			values('$Compania[0]','$TMPCOD','$Paciente[1]','$Cuadrante', '$Diente','$ParteD',$Procedimiento,'$Fecha',
			'$TipoOdonto','$Denticion','$ImagenProc','1','$CodDiagnostico1','$CodDiagnostico2','$CodDiagnostico3','$CodDiagnostico4','$CodDiagnostico5','$Fecha $HoraHoy')";
			$res=ExQuery($cons);	
		}
		else
		{
			$cons="Update Odontologia.TmpOdontogramaProc set ImagenProc='$ImagenProc', Edicion='1', Eliminar=NULL, Diagnostico1='$CodDiagnostico1', 
			Diagnostico2='$CodDiagnostico2', Diagnostico3='$CodDiagnostico3', Diagnostico4='$CodDiagnostico4', Diagnostico5='$CodDiagnostico5' 
			where  Compania='$Compania[0]' and tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' 
			and ZonaD='$ParteD' and Fecha='$Fecha' and Procedimiento=$Procedimiento";
			$res=ExQuery($cons);
		}	
		$cons="Select Procedimiento from Odontologia.tmpodontogramaproc where  Compania='$Compania[0]' and tmpcod='$TMPCOD' 
		and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' and ZonaD='$ParteD' and Fecha='$Fecha'
		and Procedimiento=-1";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			$cons="Update Odontologia.TmpOdontogramaProc set Eliminar='1'
			where  Compania='$Compania[0]' and tmpcod='$TMPCOD' and Identificacion='$Paciente[1]' and Cuadrante='$Cuadrante' and Diente='$Diente' 
			and ZonaD='$ParteD' and Fecha='$Fecha' and Procedimiento=-1";
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
			parent.document.getElementById('FrameDiag').style.position='absolute';
			parent.document.getElementById('FrameDiag').style.top='1px';
			parent.document.getElementById('FrameDiag').style.left='1px';
			parent.document.getElementById('FrameDiag').style.width='1';
			parent.document.getElementById('FrameDiag').style.height='1';
			parent.document.getElementById('FrameDiag').style.display='none';
			//alert(parent.frames.FrameOpener.document.getElementById("FrameProce").document.FORMA.G.value);
        </script><?		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameDiag').style.position='absolute';
		parent.document.getElementById('FrameDiag').style.top='1px';
		parent.document.getElementById('FrameDiag').style.left='1px';
		parent.document.getElementById('FrameDiag').style.width='1';
		parent.document.getElementById('FrameDiag').style.height='1';
		parent.document.getElementById('FrameDiag').style.display='none';
		//-	
		parent.document.getElementById('FrameVD').style.position='absolute';
		parent.document.getElementById('FrameVD').style.top='1px';
		parent.document.getElementById('FrameVD').style.left='1px';
		parent.document.getElementById('FrameVD').style.width='1';
		parent.document.getElementById('FrameVD').style.height='1';
		parent.document.getElementById('FrameVD').style.display='none';						
	}
	function Ocultar()
	{
		parent.document.getElementById('FrameDiag').style.position='absolute';
		parent.document.getElementById('FrameDiag').style.top='1px';
		parent.document.getElementById('FrameDiag').style.left='1px';
		parent.document.getElementById('FrameDiag').style.width='1';
		parent.document.getElementById('FrameDiag').style.height='1';
		parent.document.getElementById('FrameDiag').style.display='none';	
	}
	function ValidaDiagnostico2(Objeto1,Objeto2)
	{		
		parent.frames.FrameVD.location.href="ValidaDiagnostico2.php?DatNameSID=<? echo $DatNameSID?>&NameCod="+Objeto1.name+"&NameNom="+Objeto2.name;
		parent.document.getElementById('FrameVD').style.position='absolute';
		//parent.document.getElementById('FrameVD').style.top='360px';
		parent.document.getElementById('FrameVD').style.top=parent.FrameOpener.document.getElementById('TABLA').clientHeight/1.115;
		parent.document.getElementById('FrameVD').style.left='110px';
		parent.document.getElementById('FrameVD').style.display='';
		parent.document.getElementById('FrameVD').style.width='750px';
		parent.document.getElementById('FrameVD').style.height='250px';
	}
function validar(){			
	if(document.FORMA.CodDiagnostico1.value==""){
		alert("Debe haber al menos un Diagnostico!!!");return false;
	}	
}	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>"/>
<input type="hidden" name="Procedimiento" value="<? echo $Procedimiento?>"/>
<input type="hidden" name="Diente" value="<? echo $Diente?>"/>
<input type="hidden" name="ParteD" value="<? echo $ParteD?>"/>
<input type="hidden" name="Fecha" value="<? echo $Fecha?>"/>
<input type="hidden" name="TipoOdonto" value="<? echo $TipoOdonto?>"/>
<input type="hidden" name="ImagenProc" value="<? echo $ImagenProc?>"/>

<input type="button" value="X" onClick="CerrarThis()" style="position:absolute;top:0px; right:0px; width:18px; height:18px; text-align:center;cursor:hand;"  title="Cerrar esta ventana">
	<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
	<table style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" cellpadding="1" align="center" width="100%"> 
	<tr>
    	<td align="center" colspan="2" bgcolor="#e5e5e5" style="font-weight:bold">Diagnostico de Ingreso</td>
    </tr>
    <tr>    	
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" width="10%">Codigo</td><td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre</td>      
    </tr>
    <tr>
    	<td ><input type="text" size="3" readonly name="CodDiagnostico1" onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>"></td>
        <td ><input type="text" style="width:100%" name="NomDiagnostico1" readonly onFocus="ValidaDiagnostico2(CodDiagnostico1,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>"></td>
    </tr>   
    <tr>
    	<td><input type="text" size="3" readonly name="CodDiagnostico2" onFocus="ValidaDiagnostico2(this,NomDiagnostico2)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico2);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico2?>"></td>
        <td ><input type="text" style="width:100%" name="NomDiagnostico2" onFocus="ValidaDiagnostico2(CodDiagnostico2,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico2,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico2?>"></td>
    </tr>
     <tr>
    	<td><input type="text" size="3" readonly name="CodDiagnostico3" onFocus="ValidaDiagnostico2(this,NomDiagnostico3)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico3);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico3?>"></td>
        <td ><input type="text" style="width:100%" name="NomDiagnostico3" onFocus="ValidaDiagnostico2(CodDiagnostico3,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico3,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico3?>"></td>
    </tr>  
     <tr>
    	<td><input type="text" size="3" readonly name="CodDiagnostico4" onFocus="ValidaDiagnostico2(this,NomDiagnostico4)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico4);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico4?>"></td>
        <td ><input type="text" style="width:100%" name="NomDiagnostico4" onFocus="ValidaDiagnostico2(CodDiagnostico4,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico4,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico4?>"></td>
    </tr>
     <tr>
    	<td><input type="text" size="3" name="CodDiagnostico5" readonly onFocus="ValidaDiagnostico2(this,NomDiagnostico5)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico5);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico5?>"></td>
        <td ><input type="text" style="width:100%" name="NomDiagnostico5" onFocus="ValidaDiagnostico2(CodDiagnostico5,this)"  onKeyUp="ValidaDiagnostico2(CodDiagnostico5,this);xLetra(this)" readonly onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico5?>"></td>
    </tr>    
</table>
<input type="submit" name="Agregar" value="Agregar"/>
</form>
</body>
</html>
