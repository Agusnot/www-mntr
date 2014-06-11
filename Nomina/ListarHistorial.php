<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");	
$ND=getdate();
if($ND[mon]<10){$ND[mon]="0".$ND[mon];}
if($ND[mday]<10){$ND[mday]="0".$ND[mday];}
//$UltDFF=UltimoDia($ND[year],$ND[mon]);
if(!$FechaIni){$FechaIni="$ND[year]-$ND[mon]-01";}
if(!$FechaFin){$FechaFin="$ND[year]-$ND[mon]-$ND[mday]";}
//-------Eliminar---------------------
if($Eliminar==1)
{
	$Eliminar="Null";
	$cons="delete from nomina.$Novedad where identificacion='$Identificacion' and compania='$Compania[0]' and fecinicio='$FecI' and fecfinal='$FecF' and id='$Id'";
//	echo $cons."<br>";
	$res=ExQuery($cons);
	$cons="delete from nomina.novedades where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Concep' and id='$Id'";
//	echo $cons;
	$res=ExQuery($cons);
	?>
    <script language="javascript">alert("la <? echo $Novedad?> fue Eliminada")</script>
<?	
}
//--------------------
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/calendario/popcalendar.js"></script>
<script language="javascript" src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA1" method="post">
<input type="hidden" name="Novedad" value="<? echo $Novedad?>">
<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>" >
<input type="hidden" name="FecI">
<input type="hidden" name="FecF">
<input type="hidden" name="Concep">
<input type="hidden" name="Dias">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" align="center">
	<tr bgcolor="#666699" style="color:white" align="center">
    	<td colspan="2">HISTORIAL DE <? echo strtoupper($Novedad)?></td>
    </tr>
   	<tr bgcolor="#666699" style="color:white" align="center">
       	<td>PERIODO INICIAL</td><td>PERIODO FINAL</td>
    </tr>
    <tr>
        <td>
        <input type="text" name="FechaIni" value="<? echo $FechaIni?>" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" onChange="FORMA1.submit()" readonly>
        </td>
        <td>
        <input type="text" name="FechaFin" value="<? echo $FechaFin?>" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" onChange="FORMA1.submit()" readonly>
        </td>
	</tr>
    <tr align="center">
	    <td colspan="2"><input type="submit" name="Ver" value="Ver"></td>
    <td>
</table>
</form>
    <?
	if($FechaIni <= $FechaFin &&$FechaIni && $FechaFin)
	{
		$BusFecIni=explode("-",$FechaIni);
		$BusFecFin=explode("-",$FechaFin);
		$cons="select identificacion,anio,mes from nomina.nomina where identificacion='$Identificacion' and anio='$BusFecIni[0]' and mes='$BusFecIni[1]'";
		//echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetchArray($res))
		{
			if($fila['mes']<10){$fila['mes']="0".$fila['mes'];}
			$mmovi[$fila['identificacion']][$fila['anio']][$fila['mes']]=$fila['identificacion'];
			//echo $fila['identificacion']." -- ".$fila['anio']." -- ".$fila['mes']."<br>";
		}
		$cons="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,conceptosliquidacion.detconcepto,fecinicio,fecfinal,resolucion,autorizacion,estado,$Novedad.concepto,$Novedad.numero,$Novedad.dias,id from nomina.$Novedad,nomina.conceptosliquidacion,central.terceros where $Novedad.compania='$Compania[0]' and $Novedad.compania=conceptosliquidacion.compania and terceros.Identificacion='$Identificacion' and $Novedad.identificacion=terceros.identificacion and $Novedad.compania=terceros.compania and conceptosliquidacion.concepto=$Novedad.concepto and fecinicio >= '$FechaIni' and fecinicio <= '$FechaFin' order by fecinicio";
	//echo $cons;
	$res=ExQuery($cons);
	$cont=(ExNumRows($res));
		if($cont>0)
		{
			?>
			<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma; width:100%' align="center">
			<tr bgcolor="#666699"style="color:white" align="center"><td colspan="11">LISTADO DE <? echo strtoupper($Novedad)?></td>
			<tr align="center"><td>Identificacion</td><td>Nombre</td><td>Detalle</td><td>Fecha Inicio</td><td>Fecha Fin</td><td>Estado</td>
			</tr>
		<?
			while ($fila = ExFetch($res))
			{
				/*echo substr($fila[6],5,2)."<br>";
				echo $mmovi[$fila[0]][substr($fila[6],0,4)][substr($fila[6],5,2)]."<br>";*/
			?>
				<tr align="center">
				<td><? echo $fila[0]; ?></td><td><? echo $fila[1]." ".$fila[2]." ".$fila[3]." ".$fila[4]; ?></td><td><? echo $fila[5]?></td><td><? echo $fila[6]?></td><td><? echo $fila[7]?></td>
				<td style="width:100px"><? echo $fila[10]?></td><td width="16px"><? if(empty($mmovi[$fila[0]][substr($fila[6],0,4)][substr($fila[6],5,2)])){?><a href="Novedades.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Identificacion=<? echo $Identificacion?>&Novedad=<? echo $Novedad?>&Numero=<? echo $fila[12]?>&FechaFin=<? echo $FechaFin?>"><img src="/Imgs/b_edit.png" border="0" title="Editar" /></a></td>
                <td width="16px"><a href="ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Identificacion=<? echo $Identificacion?>&Novedad=<? echo $Novedad?>&Numero=<? echo $fila[12]?>&FecI=<? echo $fila[6];?>&FecF=<? echo $fila[7];?>&Concep=<? echo $fila[11];?>&Dias=<? echo $fila[13];?>&Id=<? echo $fila[14]?>"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/><?  }?></a></td>
				</tr>
		<?	
//			echo $fila[14];
			}	?>
			</tr>
			</table>
	<?	}
		else
		{?>
			<center>No hay <? echo $Novedad?> pendientes !!!</center>
		<?
		$cont=$cont+1;
		}
			?>
			</table>
		<?
	}
	else
	{
		?><script language="javascript">alert("La Fecha Final es Menor que la Fecha Inicial !");</script>
        <script language="javascript">location.href="ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=<? echo $Novedad?>";</script>
<?
		
	}
	?>
    <center><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='Novedades.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=<? echo $Novedad?>';"></center>
</body>
</html>