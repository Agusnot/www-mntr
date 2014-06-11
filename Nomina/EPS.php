<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
//--------------------------------------
if($Guardar)
{
	$cons="insert into nomina.epsxc(compania,identificacion,anioinicio,mesinicio,aniofin,mesfin,eps,numcontrato) values ('$Compania[0]','$Identificacion',$AnioI,$MesI,$AnioF,$MesF,'$Eps','$NumContrato')";
//	echo $cons;
	$res=ExQuery($cons);
	?>
    <script language="javascript">location.href="EPS.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>";</script>
    <?
}
if($Eliminar)
{
	$cons="delete from nomina.epsxc where compania='$Compania[0]' and Identificacion='$Identificacion' and anioinicio=$AnoIni and mesinicio=$MesIni and eps='$Nombre' and numcontrato='$NumContrato'";
//	echo $cons;
	$res=ExQuery($cons);
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
//   if(document.FORMA.AnioF.value==""){alert("Por favor ingrese el Año Final !!!"); return false;}
//   if(document.FORMA.MesF.value==""){alert("Por favor ingrese el Mes Final !!!");return false;}
   if(document.FORMA.Eps.value==""){alert("Por favor ingrese la EPS !!!");return false;}
}
</script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="9">EPS</td>
    </tr>
    <tr align="center">
    	<td colspan="4">Periodo de Inicio</td><td colspan="4">Periodo de Finalizacion</td><td>EPS</td>
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
        <td>Año</td>
        <td><select name="AnioF" onChange="FORMA.submit()" >\
        	<option></option>
            <?
            	$cons = "select anio from central.anios where compania='$Compania[0]' and anio>=$AnioI order by anio";
				echo $cons;
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
				if($AnioI==$AnioF)
				{
					$cons = "select numero,mes from central.meses where numero>=$MesI order by numero";
					$resultado = ExQuery($cons);
					while ($fila = ExFetch($resultado))
					{                        
						if($fila[0]==$MesF)
						{
							echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
						}
						else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
					}
				}
				else
				{
					$cons = "select numero,mes from central.meses order by numero";
					$resultado = ExQuery($cons);
					while ($fila = ExFetch($resultado))
					{                        
						if($fila[0]==$MesF)
						{
							echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
						}
						else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
					}
				}
				?>
            </select>
        </td>	
        <td><select name="Eps" onChange="FORMA.submit()" style="width:200px" >
            <option></option>
            <?
            	$cons = "Select Primape from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]' order by primape";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$Eps)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}						 
                }
				?>
            </select>
        </td>
    </tr>
    <tr align="center">
	    <td colspan="9"><input type="submit" name="Guardar" value="Guardar"/>
    </tr>
</table>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<?
	$cons="select anioinicio,mesinicio,aniofin,mesfin,eps from nomina.epsxc where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' order by anioinicio,mesinicio";
//	echo $cons;
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
//	echo $cont;
	if($cont>0)
	{?>
    	<tr bgcolor="#666699"style="color:white" align="center"><td colspan="4">HISTORIAL EPS</td></tr>
        <tr bgcolor="#666699"style="color:white" align="center"><td>Periodo de Inicio</td><td>Periodo de Finalizacion</td><td>EPS</td><td>&nbsp;</td></tr>
		<? while($fila=ExFetch($res))
		{?>
			<tr align="center"><td><? echo $fila[0]." - ".$fila[1]?></td><td><? echo $fila[2]." - ".$fila[3]?><td><? echo $fila[4]?></td><td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar la EPS del Historial ?')){location.href='EPS.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Eliminar=1&Nombre=<? echo $fila[4]?>&AnoIni=<? echo $fila[0]?>&MesIni=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>'};"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></td></tr>
	<?	}
	}
	?>
</table>
</body>
</html>