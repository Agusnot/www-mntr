<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$cons="select cc,porcentaje from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and anio='$Anio' and mesi='$MesI' and mesf='$MesF' and cc='$CC'";
$res=ExQuery($cons);
$fila=Exfetch($res);
if(!$CC){$CC=$fila[0];}
if(!$Porcentaje){$Porcentaje=$fila[1];}
//echo $CC." --> ".$Porcentaje." --> ".$CCAnt." --> ".$DatNameSID." --> ".$Identificacion." --> ".$NumContrato." --> ".$Anio."<br>";
if($Guardar==1)
{
	$cons="select porcentaje from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and 		
	anio='$Anio' and mesi='$MesI' and mesf='$MesF' and cc!='$CCAnt'";
//	echo $cons;
	$res=ExQuery($cons);
	while ($fila = ExFetch($res))
	{
		$SumCC=$SumCC+$fila[0];
	}
	$SumCC=$SumCC+$Porcentaje;
//	echo $SumCC;
	if($SumCC<100)
	{
		$cons="update nomina.centrocostos set cc='$CC', porcentaje='$Porcentaje' where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and cc='$CCAnt' and porcentaje='$PorcentajeAnt'";
		//echo $cons;
		$res=ExQuery($cons);
		?>
		<script language="javascript">
		location.href="CC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&FecIni=<? echo $FecIni?>&Anio=<? echo $Anio?>&MesI=<? echo $MesI?>&MesF=<? $MesF?>";
		</script>
<?
	}
	else
	{ ?>
		<script language="javascript">alert("Ha Excedido el 100%");</script>
<?	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function AsistBusqueda(Valor)
	{
		parent.frames.FrameOpener.location.href="AsistenteCC.php?DatNameSID=<? echo $DatNameSID?>&Valor="+Valor.value;
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='10px';
		parent.document.getElementById('FrameOpener').style.right='10px';
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='300px';
		parent.document.getElementById('FrameOpener').style.height='450px';
	}
function Ocultar()
	{
		parent.frames.FrameOpener.style.display='';
		parent.document.getElementById('FrameOpener').style.width='0';
		parent.document.getElementById('FrameOpener').style.height='0';
	}		
</script>	
</head>
<body background="/Imgs/Fondo.jpg"/>
<form name="FORMA" method="post">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#666699"style="color:white" align="center">
    	<td>CENTROS DE COSTOS</td><td>PORCENTAJE</td>
    </tr>
    <tr>
    	<td><input type="Text" name="CC" style="width:100%" onFocus="AsistBusqueda(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsistBusqueda(this)" value="<? echo $CC?>"></td>
        <td><input type="text" name="Porcentaje" style="width:100%" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"  onBlur="if(parseInt(this.value)>
        100){alert('El valor de los dias no puede ser mayor a 100!!!');this.value=100;}" value="<? echo $Porcentaje?>" ></td>
        <td><a href="#" onClick="location.href='EditCC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&Anio=<? echo $Anio?>&MesI=<? echo $MesI?>&MesF=<? echo $MesF?>&CCAnt=<? echo $CC?>&PorcentajeAnt=<? echo $Porcentaje?>&Guardar=1&CC='+document.FORMA.CC.value+'&Porcentaje='+document.FORMA.Porcentaje.value"/><img src="/Imgs/b_save.png" border="0" title="Guardar"/></a></td>
    </tr>
</table>
</form>
</body>
</html>