<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("FuncionesUnload.php");
	$ND=getdate();
	$raiz=$_SERVER['DOCUMENT_ROOT'];	
	@require_once ("$raiz/xajax/xajax_core/xajax.inc.php");
	
	$obj = new xajax(); 
	$obj->registerFunction("Borrar_Temporales");
	$obj->registerFunction("Quitar_TransaccionTmp");
	$obj->processRequest(); 
	
	if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if(!$Info){$Info="Odontograma_Seg";}	
	
	$cons="Select numservicio from Salud.Servicios where Compania='$Compania[0]' and Cedula='$Paciente[1]' and estado='AC'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Servicio=$fila[0];	
	if(!$Servicio)
	{
		$cons="Select numservicio from Salud.Servicios where Compania='$Compania[0]' and Cedula='$Paciente[1]' and fechaing<='$ND[year]-$ND[mon]-$ND[mday] 23:59:59' order by numservicio desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Servicio=$fila[0];
		
	}//permite agregar datos al odontograma sin servicio activo
	//$CamposOC="Codigo,FechaAdquisicion,Serie,Estado,Cedula,Detalle,FechaOrdenCompra,EstadoOrdenCompra";
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("/xajax");?>

<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
var ValorInfo;
var Modifico=false;
var Trabajando=false;
function MensajeAlerta()
{	
	if(SeModificoAlgo())
	{
		if(confirm("Usted desea salir de la pagina. Si ha realizado algun cambio y desea guardarlo pulse en Cancelar y presione en el boton Guardar, de lo contrario se perderan los cambios.\nDesea Continuar?"))
		{			
			
			xajax_Borrar_Temporales('Odontologia.tmpodontogramaproc','<? echo $TMPCOD?>','Odontologia.odontogramaproc');
			//xajax_Quitar_TransaccionTmp('Odontologia.odontogramaproc');
			CambiarSrc(document.FORMA.Info.value);
			Modifico=false;
		}
		else
		{
			document.FORMA.Info.value=ValorInfo;
			return false;
		}
	}
	else
	{
		CambiarSrc(document.FORMA.Info.value);
	}
}
//--
window.onbeforeunload = confirmExit; 
function confirmExit() 
{ 
	//xajax_Borrar_Temporales('Odontologia.tmpodontogramaproc','<? echo $TMPCOD?>');
	if(SeModificoAlgo())
	{
		return "Usted desea salir de la pagina. Si ha realizado algun cambio y desea guardarlo pulse en Cancelar y posteriormente presione en el boton guardar, de lo contrario se perderan los cambios.";			    		
	}
	else
	{
		if(TrabajandoDiente())
		{
			return "Actualmente se encuentra en medio de un procedimiento si desea guardarlo pulse en Cancelar y posteriormente presione en el boton guardar, de lo contrario se perderan los cambios.";	
		}	
	}	
} 
//--
function SeModificoAlgo()
{
	return Modifico;
}
function TrabajandoDiente()
{
	return Trabajando;
}
function CamValorInfo(valor)
{
	ValorInfo=valor;
}
function CambiarSrc(Valor)
{
	if(Valor == "Odontograma_Ini")
	{document.getElementById('FrameInfo').src="OdontogramaIni.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&TipoOdontograma=Inicial&Servicio=<? echo $Servicio?>";}
	if(Valor == "Odontograma_Seg")
	{document.getElementById('FrameInfo').src="OdontogramaIni.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&TipoOdontograma=Seguimiento&Servicio=<? echo $Servicio?>";}
}	
</script>
</head>

<body background="/Imgs/Fondo.jpg" onLoad="//CamValorInfo(document.FORMA.Info.value);" onUnload="xajax_Borrar_Temporales('Odontologia.tmpodontogramaproc','<? echo $TMPCOD?>','Odontologia.odontogramaproc');"  >

<form name="FORMA" method="post">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>"/>
<input type="hidden" name="Servicio" value="<? echo $Servicio;?>"/>
<? 
	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	
if($Servicio)
{
?>
<!--<font face="Tahoma" color="#0000FF" size="2" style="font-weight:bold">
Tipo:
</font>-->
<select name="Info" id="Info" onFocus="CamValorInfo(this.value);" onChange="MensajeAlerta();" style="width:220px; font-weight:bold" title="Por Favor Seleccione una Opci&oacute;n">
    <option value="Odontograma_Ini" <? if($Info=="Odontograma_Ini"){ echo " selected ";}?> style="background-color:#6C3" >Odontograma Inicial</option>
    <option value="Odontograma_Seg" <? if($Info=="Odontograma_Seg"){ echo " selected ";}?> style="background-color:#39F" >Odontograma de Seguimiento</option>    
</select>
<iframe id="FrameInfo" name="FrameInfo" frameborder="0" width="100%" height="96%" 
<?
	if($Info=="Odontograma_Ini"){ echo "src='OdontogramaIni.php?DatNameSID=$DatNameSID&TMPCOD=$TMPCOD&TipoOdontograma=Inicial&Servicio=$Servicio'";}
	if($Info=="Odontograma_Seg"){ echo "src='OdontogramaIni.php?DatNameSID=$DatNameSID&TMPCOD=$TMPCOD&TipoOdontograma=Seguimiento&Servicio=$Servicio'";}
?>></iframe>
<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" frameborder="0" height="1" style="display:none;border:#e5e5e5; border-style:solid; " ></iframe>
<iframe scrolling="yes" id="FrameNewProc" name="FrameNewProc" frameborder="0" height="1" style="display:none;border:#e5e5e5; border-style:solid; " ></iframe>   
<iframe scrolling="yes" id="FrameDiag" name="FrameDiag" frameborder="0" height="1" style="display:none;border:#e5e5e5; border-style:solid; " ></iframe>   
<iframe scrolling="yes" id="FrameVD" name="FrameVD" frameborder="0" height="1" style="display:none;border:#e5e5e5; border-style:solid; " ></iframe>   

</form>
<?
}
else
{
echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>El Paciente no tiene Servicios Activos!!! </b></font></center>";
	
}
?>
</body>
</html>
