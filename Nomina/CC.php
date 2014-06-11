<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
//if($MesI<10){$MesI="0$MesI";}
$SumCC=0;
//echo $AnioI." --> ".$MesI;
//echo $FecIni;
if($Fin!=1){if($MesF<$MesI){$MesF=$MesI;}}
//echo $DatNameSID." --> ".$Identificacion." --> ".$NumContrato." --> ".$Anio." --> ".$MesI." --> ".$MesF."<br>";
if($Fin==1)
{	if(strlen($MesF)==1)
	{
		$MesF="0$MesF";
	}
	if($MesF==2)
	{
		$FecFin="$AnioF-$MesF-28";
	}
	else
	{
		$FecFin="$AnioF-$MesF-30";
	}
	$cons="update nomina.centrocostos set fecfin='$FecFin' where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and fecfin is NULL";
//	echo $cons;
	$res=ExQuery($cons);
	$MesI=$MesF+1;
	if($MesI>12)
	{
		$MesI=01;
		$AnioI=$AnioF+1;
	}
}
if($Ban==1)
{
	$cons="select cc from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and mesf>='$MesI' and anio='$Anio'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
	?>
		 <script language="javascript">
		 alert("Ya Esta Configurado el Centro de Costo para este Mes !!!");
		 location.href="NewCC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&FecIni=<? echo $FecIni?>";
		 </script>
	<?
	}
}
if($Guardar)
{
	if(!$FecInicio)
	{
		$FecInicio="$AnioI-$MesI-01";
	}
	$cons="select porcentaje from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and 		
	fecinicio='$FecInicio' and fecfin is NULL";
//	echo $cons;
	$res=ExQuery($cons);
	while ($fila = ExFetch($res))
	{
		$SumCC=$SumCC+$fila[0];
	}
	$SumCC=$SumCC+$Porcentaje;
//	echo $SumCC;
	if($SumCC<=100)
	{
		$cons="insert into nomina.centrocostos(compania,identificacion,cc,porcentaje,fecinicio,fecfin,numcontrato) 
		values('$Compania[0]','$Identificacion','$CC','$Porcentaje','$FecInicio',NULL,'$NumContrato')";
		//echo $cons;
		$res=ExQuery($cons);
	}
	else
	{ ?>
		<script language="javascript">alert("Ha Excedido el 100%");</script>
<?	
	}
	$SumCC=0;
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
    <?
	if($MesI<10){$MesI="0$MesI";}
	if(!$FecInicio&&!$AnioI)
	{
		$FecInicio="$AnioI-$MesI-01";
	}
	elseif($AnioI)
	{
		$Anio=substr($AnioI,0,4);
		$FecInicio="$Anio-$MesI-01";
	}
//	echo $AnioI." --> ".$FecInicio;
	$cons="select cc,porcentaje from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and 		
	fecinicio='$FecInicio'";
//	echo $cons;
	$res=ExQuery($cons);
	while ($fila = ExFetch($res))
	{
		?>
        <tr>
        <td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
        </tr>
<?        	$SumCC=$SumCC+$fila[1];
	}
	$Resta=100-$SumCC;
//	echo $SumCC;
 	if($SumCC<100)
	{    
//	echo $FecInicio;
	?>
	<tr>
    	<td><input type="Text" name="CC" style="width:100%" onFocus="AsistBusqueda(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsistBusqueda(this)" ></td>
        <td><input type="text" name="Porcentaje" style="width:100%" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"  onBlur="if(parseInt(this.value)>
        100){alert('El valor del Porcentaje no puede ser mayor a 100!!!');this.value=100;}" value="<? echo $Resta?>"></td>
        <td><a href="#" onClick="location.href='CC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&FecInicio=<? echo $FecInicio?>&Ban=0&Guardar=1&CC='+document.FORMA.CC.value+'&Porcentaje='+document.FORMA.Porcentaje.value"/><img src="/Imgs/b_save.png" border="0" title="Guardar"/></a></td>
    </tr>
<?
	}
	?>
</table>
<center>
<?
if($Resta==100)
{
	?>
   <input type="button" name="Cancelar" value="Cancelar" onClick="location.href='CentroCostos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&AnioI=<? echo $AnioI?>&MesI=<? echo $MesI?>';" />
<?
}
?>
<input type="button" name="Salir" value="Salir" onClick="location.href='CentroCostos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&Anio=<? echo $Anio?>&MesI=<? echo $MesI?>&MesF=<? echo $MesF?>';" <? if($SumCC<100){ echo "disabled";}?>/></center>
</form>
</body>
</html>
