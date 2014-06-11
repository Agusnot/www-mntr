<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if(!$AnioF){$AnioF=$ND[year];}
if(!$MesF){$MesF=$ND[mon];}
if($Guardar)
{
	if($MesF<10){$MesF="0$MesF";}
	//echo $MesF;
	$FecFin="$AnioF-$MesF-30";
	$cons="update nomina.arpxc set fecfin='$FecFin' where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and fecfin is NULL";
//	echo $cons;
	$res=ExQuery($cons);
	$MesF++;
	if($MesF==13)
	{
		$MesF=1;
		$AnioF++;
	}
	if($MesF<10){$MesF="0$MesF";}
	$FecInicio="$AnioF-$MesF-01";
	$cons="insert into nomina.arpxc(compania,identificacion,arp,fecinicio,fecfin,numcontrato) values ('$Compania[0]','$Identificacion','$CenTrabajo','$FecInicio',NULL,'$NumContrato')";
	$res=ExQuery($cons);
//	echo $cons;
	?>
	<script language="javascript">location.href="ARPF.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&Opc=<? echo $Opc?>";</script>
	<?
}
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
   if(document.FORMA.CenTrabajo.value==""){alert("Por favor ingrese la ARP !!!");return false;}
}
</script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="FecIni" value="<? echo $FecIni?>" >
<input type="hidden" name="NumContrato" value="<? echo $NumContrato?>">
<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>">
<input type="hidden" name="NumContrato" value="<? echo $NumContrato?>">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="9"><? echo strtoupper($Opc)?></td>
    </tr>
    <tr bgcolor="#666699" style="color:white" align="center">
    	<td colspan="4">Fecha Finalizacion</td>
    </tr>
    <tr align="center">
        <td>Año</td>
        <td><select name="AnioF" onChange="FORMA.submit()" >
            <option></option>
            <?
            	$cons = "select anio from central.anios where compania='$Compania[0]' order by anio";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$AnioF)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}						 
                }
				?>
            </select>
        </td>
        <td>Mes</td>
        <td><select name="MesF" onChange="FORMA.submit()" >
            <option></option>
            <?
            	$cons = "select numero,mes from central.Meses order by numero";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$MesF)
					{
						echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                }
				?>
            </select>
        </td>
    <tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="4">CLASE DE RIESGO</td>
    </tr>
	<tr align="center">
    	<td colspan="4"><select name="CenTrabajo" onChange="FORMA.submit()" style="width:100%" >
            <?
            	$cons = "select porcentaje,clase from nomina.centrabajo order by clase";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$CenTrabajo)
					{
						echo "<option value='$fila[0]' selected>$fila[1] - $fila[0]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[1] - $fila[0]</option>";}						                 }
				?>
            </select>
        </td>
    </tr>

</table>
<center><input type="submit" name="Guardar" value="Guardar"><input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ARPF.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Nombre=<? echo $fila[4]?>&AnoIni=<? echo $fila[0]?>&MesIni=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Tabla=<? echo $Tabla?>&Campo=<? echo $Campo?>&Opc=<? echo $Opc?>&FecIni=<? echo $FecIni?>'">
</form>
</body>
</html>