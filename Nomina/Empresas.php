<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
// 	echo $FecIni;
if(!$AnioI){$AnioI=substr($FecIni,0,4);}
if(!$MesI){$MesI=substr($FecIni,5,2);}
if($Opc=='EPS'){$Tabla='epsxc'; $Campo='eps'; $cond="Tipo='Asegurador' and"; }
if($Opc=='Cesantias'){$Tabla='cesantiasxc'; $Campo='cesantias'; $cond="Tipo='Fondo de Cesantias' or Tipo='Pensiones y Cesantias' and";}
if($Opc=='Pensiones'){$Tabla='pensionesxc'; $Campo='pensiones'; $cond="Tipo='Fondo de Pensiones' or Tipo='Pensiones y Cesantias' and";}
if($Guardar)
{
	if($MesI<10){$MesI="0$MesI";}
	$FecInicio="$AnioI-$MesI-01";
//	$FecFin="NULL";
	$cons="insert into nomina.$Tabla(compania,identificacion,$Campo,fecinicio,fecfin,numcontrato) values
	('$Compania[0]','$Identificacion','$Entidad','$FecInicio',NULL,'$NumContrato')";
//	echo $cons;
	$res=ExQuery($cons);
}
if($Eliminar)
{
	$cons="delete from nomina.$Tabla where compania='$Compania[0]' and Identificacion='$Identificacion' and fecinicio='$FecInicio' and $Campo='$Nombre' and numcontrato='$NumContrato'";
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
	$cons="update nomina.$Tabla set fecfin=NULL where compania='$Compania[0]' and identificacion='$Identificacion' and fecfin='$FecInicio'";
	$res=ExQuery($cons);
//	echo $cons;
	?>
    <script language="javascript">//location.href="Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&Opc=<? echo $Opc?>&FecIni=<? echo $FecIni?>";</script>
    <?
}
$cons="select fecInicio,Fecfin,$Campo,novgral from nomina.$Tabla where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' order by fecinicio";
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
   if(document.FORMA.Entidad.value==""){alert("Por favor ingrese la Entidad !!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();"/>
<input type="hidden" name="FecIni" value="<? echo $FecIni?>">
<?
if($cont==0)
{
	?>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="9"><? echo strtoupper($Opc)?></td>
    </tr>
    <tr align="center">
    	<td colspan="4">Periodo de Inicio</td><td><? echo strtoupper($Opc)?></td>
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
        <td><select name="Entidad" onChange="FORMA.submit()" style="width:200px" >
            <option></option>
            <?
				if($cond=="Tipo='Fondo de Cesantias' or Tipo='Pensiones y Cesantias' and" or $cond=="Tipo='Fondo de Pensiones' or Tipo='Pensiones y Cesantias' and")
				{
					echo '<option value="NA" selected>NA</option>';
				}
            	$cons = "Select Primape from Central.Terceros where $cond Compania = '$Compania[0]' order by primape";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$Entidad)
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
	    <td colspan="9"><input type="submit" name="Guardar" value="Guardar" /></td>
    </tr>
    
</table>
<?
}
?>
<!-- /////////////////////////////////////////////////-------------------------------------------->
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<?
	$cons="select fecinicio,fecfin,$Campo,novgral from nomina.$Tabla where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' order by fecinicio";
//	echo $cons;
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
//	echo $cont;
	if($cont>0)
	{?>
    	<tr bgcolor="#666699"style="color:white" align="center"><td colspan="5">HISTORIAL <? echo strtoupper($Opc)?></td></tr>
        <tr bgcolor="#666699"style="color:white" align="center"><td>Periodo de Inicio</td><td>Periodo de Finalizacion</td><td>ENTIDAD</td><td>Novedad</td><td colspan="2">&nbsp;</td></tr>
		<? while($fila=ExFetch($res))
		{?>
			<tr align="center">
            <td><? echo $fila[0]?></td><td><? if($fila[1]==""){echo "&nbsp;";}else{echo $fila[1];}?><td><? echo $fila[2]?></td><td><? echo $fila[3];?></td>
            <td width="16px"><a href="#" onClick="location.href='NewEps.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Nombre=<? echo $fila[4]?>&AnoIni=<? echo $fila[0]?>&MesIni=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Opc=<? echo $Opc?>'"><img src="/Imgs/b_usredit.png" border="0" title="Editar"/></a></td>
<!-- validacion de si pago de nomina ---------------->            
<?
			$consnom="select anio from nomina.nomina where numero='$NumContrato'";
			$resnom=ExQuery($consnom);
			$ConContr=ExNumRows($resnom);
//			echo $ConContr;
			if($ConContr==0)
			{
?>
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar la Entidad del Historial ?')){location.href='Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Eliminar=1&Nombre=<? echo $fila[2]?>&FecInicio=<? echo $fila[0]?>&FecFin=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Tabla=<? echo $Tabla?>&Campo=<? echo $Campo?>&Opc=<? echo $Opc?>&FecIni=<? echo $FecIni?>'};"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a></td>
            </tr>
	<?		}
		}
	}
	?>
</table>
</body>
</html>