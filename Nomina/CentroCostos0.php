<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$Year="$ND[year]";
if(!$AnioI){$AnioI=$Year;}
if(!$MesI){$MesI="$ND[mon]";}
if(!$MesF){$MesF=$MesI;}
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
   if(document.FORMA.Entidad.value==""){alert("Por favor ingrese la Entidad !!!");return false;}
}
</script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="FecIni" value="<? echo $FecIni?>">
<?
$cons = "SELECT sum(porcentaje) as suma from nomina.centrocostos where anio<='$AnioI' and mesi<='$MesI' and numcontrato='$NumContrato'";
echo $cons;
$res = ExQuery($cons);
$Porcentaje=ExFetch($res);
echo $Porcentaje[0];
$cons="select anio,mesi,mesf from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' group by anio,mesi,mesf order by anio, mesi";
$res=ExQuery($cons);
$cont=ExNumRows($res);
if($cont==0)
{
	?>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="9">Centro de Costos</td>
    </tr>
    <tr align="center">
    	<td colspan="4">Periodo de Inicio</td>
    </tr>
    <tr>
    	<td>Año</td>
        <td><select name="AnioI" onChange="FORMA.submit()" >
            <option></option>
            <?
            	$cons = "select anio from central.anios where compania='$Compania[0]' order by anio";
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
    </tr>
    <tr align="center">
	    <td colspan="9"><input type="submit" name="Nuevo" value="Nuevo" /></td>
    </tr>
    
</table>
<?
}
?>
<!-- /////////////////////////////////////////////////-------------------------------------------->
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<?
	$cons="select anio,mesi from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' group by anio,mesi,mesf order by anio, mesi";
//	echo $cons;
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
//	echo $cont;
	if($cont>0)
	{?>
    	<tr bgcolor="#666699"style="color:white" align="center"><td colspan="5">HISTORIAL CENTRO DE COSTOS</td></tr>
        <tr bgcolor="#666699"style="color:white" align="center"><td>Periodo de Inicio</td><td>Periodo de Finalizacion</td><td>ENTIDAD</td><td colspan="2">&nbsp;</td></tr>
		<? while($fila=ExFetch($res))
		{?>
			<tr align="center">
            <td><? echo $fila[0]." - ".$fila[1]?></td><td><? if($fila[2]==0&&$fila[3]==0){echo "&nbsp;";}else{echo $fila[2]." - ".$fila[3];}?><td><? echo $fila[4]?></td>
            <td width="16px"><a href="#" onClick="location.href='NewEps.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Nombre=<? echo $fila[4]?>&AnoIni=<? echo $fila[0]?>&MesIni=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Opc=<? echo $Opc?>'"><img src="/Imgs/b_usredit.png" border="0" title="Editar"/></a></td>
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar la Entidad del Historial ?')){location.href='Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Eliminar=1&Nombre=<? echo $fila[4]?>&AnoIni=<? echo $fila[0]?>&MesIni=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Tabla=<? echo $Tabla?>&Campo=<? echo $Campo?>&Opc=<? echo $Opc?>&FecIni=<? echo $FecIni?>'};"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a></td>
            </tr>
	<?	}
	}
	?>
</table>
</body>
</html>