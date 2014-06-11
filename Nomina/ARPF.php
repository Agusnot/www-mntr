<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
// 	echo $FecIni;
if(!$AnioI){$AnioI=substr($FecIni,0,4);}
if(!$MesI){$MesI=substr($FecIni,5,2);}
if($Guardar)
{
	$FecInicio="$AnioI-$MesI-01";
//	$FecFin="1111-11-11";
	$cons="insert into nomina.arpxc(compania,identificacion,arp,fecinicio,fecfin,numcontrato) values ('$Compania[0]','$Identificacion','$CenTrabajo','$FecInicio',NULL,'$NumContrato')";
//	echo $cons;
	$res=ExQuery($cons);
	?>
    <script language="javascript">location.href="ARPF.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&FecIni=<? echo $FecIni?>";</script>
    <?
}
if($Eliminar)
{
	$cons="delete from nomina.arpxc where compania='$Compania[0]' and Identificacion='$Identificacion' and fecinicio='$FecInicio' and arp='$Nombre' and numcontrato='$NumContrato'";
//	echo $cons."<br>";
	$res=ExQuery($cons);
	$anio=substr($FecInicio,0,4);
	$mes=substr($FecInicio,5,2);
	$dias=30;
	$mes=$mes-1;
	if($mes==0)
	{
		$mes=12;
		$anio=$anio-1;
	}
	$FecInicio="$anio-$mes-$dias";
//	echo $dias;
	$cons="update nomina.arpxc set fecfin=NULL where compania='$Compania[0]' and identificacion='$Identificacion' and fecfin='$FecInicio'";
//	echo $cons;
	$res=ExQuery($cons);
	?><script></script><?
}
$cons="select fecinicio,fecfin,arp from nomina.arpxc where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' order by fecinicio";
//	echo $cons;
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.AnioI.value==""){alert("Por favor ingrese el Año Inicial !!!");return false;}
   if(document.FORMA.MesI.value==""){alert("Por favor ingrese el Mes Inicial !!!");return false;}
   if(document.FORMA.CenTrabajo.value==""){alert("Por favor ingrese la Entidad !!!");return false;}
}
</script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="FecIni" value="<? echo $FecIni?>">
<?
if($cont==0)
{
	?>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="9">ARP</td>
    </tr>
    <tr align="center">
    	<td colspan="4">Periodo de Inicio</td><td>ARP</td>
    </tr>
    <tr>
    	<td>Año</td>
        <td><select name="AnioI" onChange="FORMA.submit()" >
            <option></option>
            <?
            	$cons = "select anio from central.anios where compania='$Compania[0]' order by anio desc";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$AnioI)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}						 
                }
				?>
            </select>
        </td>
        <td>Mes</td>
        <td><select name="MesI" onChange="FORMA.submit()" >
            <option></option>
            <?
            	$cons = "select numero,mes from central.Meses order by numero";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$MesI)
					{
						echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                }
				?>
            </select>
        </td>
        <td><select name="CenTrabajo" onChange="FORMA.submit()" style="width:200px" >
            <option></option>
            <?
            	$cons = "select porcentaje,clase from nomina.centrabajo order by clase";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$CenTrabajo)
					{
						echo "<option value='$fila[0]' selected>$fila[1] - $fila[0]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[1] - $fila[0]</option>";}						                 
				}
				?>
            </select>
        </td>
    </tr>
    <tr align="center">
	    <td colspan="9"><input type="submit" name="Guardar" value="Guardar" /></td>
    </tr>
    
</table>
<?
}
?>
<!-- /////////////////////////////////////////////////-------------------------------------------->
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<?
	$cons="select fecinicio,fecfin,arp from nomina.arpxc where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' order by fecinicio,fecfin";
//	echo $cons;
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
//	echo $cont;
	if($cont>0)
	{?>
    	<tr bgcolor="#666699"style="color:white" align="center"><td colspan="5">HISTORIAL ARP</td></tr>
        <tr bgcolor="#666699"style="color:white" align="center"><td>Periodo de Inicio</td><td>Periodo de Finalizacion</td><td>Clase de Riesgo</td><td>Porcentaje</td><td colspan="2">&nbsp;</td></tr>
		<? while($fila=ExFetch($res))
		{
			$consarp="select clase from nomina.centrabajo where porcentaje='$fila[2]'";
			$resarp=ExQuery($consarp);
			$filaarp=ExFetch($resarp);
			?>
			<tr align="center">
            <td><? echo $fila[0]?></td><td><? echo $fila[1];?></td><td><? echo $filaarp[0];?></td><td><? echo $fila[2]?></td>
            <td width="16px"><a href="#" onClick="location.href='NewArp.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Nombre=<? echo $fila[4]?>&AnoIni=<? echo $fila[0]?>&MesIni=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Opc=<? echo $Opc?>&FecIni=<? echo $FecIni?>'"><img src="/Imgs/b_usredit.png" border="0" title="Editar"/></a></td>
<?            
            $consnom="select anio from nomina.nomina where numero='$NumContrato'";
			$resnom=ExQuery($consnom);
			$ConContr=ExNumRows($resnom);
//			echo $ConContr;
			if($ConContr==0)
			{
				?>
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar la ARP del Historial ?')){location.href='ARPF.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Eliminar=1&Nombre=<? echo $fila[2]?>&FecInicio=<? echo $fila[0]?>&FecFin=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Tabla=<? echo $Tabla?>&Campo=<? echo $Campo?>&Opc=<? echo $Opc?>&FecIni=<? echo $FecIni?>';}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a></td>
            </tr>
	<?		}
		}
	}
	?>
</table>
</body>
</html>