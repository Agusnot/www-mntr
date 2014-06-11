<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$Year="$ND[year]";
if(!$AnioF){$AnioF=$Year;$AnioI=$AnioF;}
if(!$MesF){$MesF="$ND[mon]";$MesI=$MesF+1;if($MesI>12){$MesI=01;$AnioI=$AnioF+1;}}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.Anio.value==""){alert("Por favor ingrese el Año Inicial !!!");return false;}
   if(document.FORMA.MesI.value==""){alert("Por favor ingrese el Mes Inicial !!!");return false;}
}
</script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();">
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
        </tr>
</table>
<center>
<input type="button" name="Nuevo" value="Nuevo" onClick="location.href='CC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&AnioI=<? echo $AnioI?>&MesI=<? echo $MesI ?>&NumContrato=<? echo $NumContrato?>&Fin=1&AnioF=<? echo $AnioF?>&MesF=<? echo $MesF?>'" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='CentroCostos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&Anio=<? echo $Anio?>&MesI=<? echo $MesI?>&MesF=<? echo $MesF?>&FecIni=<? echo $FecIni?>'" /></center>
</body>
</html>