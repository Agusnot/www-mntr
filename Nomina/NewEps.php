<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if(!$AnioF){$AnioF="$ND[year]";}
if(!$MesF){$MesF="$ND[mon]";}
if("$MesF"<10)
{
	$MesF="0$MesF";
}
if("$MesF"==2)
{
	$Dia=28;
}
else
{
	$Dia=30;
}
if($Opc=='EPS'){$Tabla='epsxc'; $Campo='eps'; $cond="Tipo='Asegurador' and"; }
if($Opc=='Cesantias'){$Tabla='cesantiasxc'; $Campo='cesantias'; $cond="Tipo='Fondo de Cesantias' or Tipo='Pensiones y Cesantias' and";}
if($Opc=='Pensiones'){$Tabla='pensionesxc'; $Campo='pensiones'; $cond="Tipo='Fondo de Pensiones' or Tipo='Pensiones y Cesantias' and";}
if($Guardar)
{
	//echo $MesF;
	if($ClaNovedad=="TAE")
	{
		$ClaNovedad="TDE";
	}
	elseif($ClaNovedad=="TDE")
	{
		$ClaNovedad="TAE";
	}
//-----------------------	
	elseif($ClaNovedad=="TPP")
	{
		$ClaNovedad="TAP";
	}
	elseif($ClaNovedad=="TAP")
	{
		$ClaNovedad="TPP";
	}
	$FecFin="$AnioF-$MesF-$Dia";
	$cons="update nomina.$Tabla set fecfin='$FecFin',novgral='$ClaNovedad' where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and fecfin is NULL";
//	echo $cons;
	$res=ExQuery($cons);
	$MesF++;
	if($MesF==13)
	{
		$MesF=1;
		$AnioF++;
	}
	$FecInicio="$AnioF-$MesF-01";
	if($ClaNovedad=="TAE")
	{
		$ClaNovedad="TDE";
	}
	elseif($ClaNovedad=="TDE")
	{
		$ClaNovedad="TAE";
	}
	elseif($ClaNovedad=="TPP")
	{
		$ClaNovedad="TAP";
	}
	elseif($ClaNovedad=="TAP")
	{
		$ClaNovedad="TPP";
	}
	$cons="insert into nomina.$Tabla(fecinicio,fecfin,$Campo,compania,identificacion,numcontrato,novgral) values('$FecInicio',NULL,'$Entidad',
	'$Compania[0]','$Identificacion','$NumContrato','$ClaNovedad')";
	$res=ExQuery($cons);
//	echo $cons;
	?>
	<script language="javascript">location.href="Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&Opc=<? echo $Opc?>";</script>
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
   if(document.FORMA.Eps.value==""){alert("Por favor ingrese la Entidad !!!");return false;}
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
    <tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="4">ENTIDAD</td>
    </tr>
	<tr align="center">
    	<td colspan="4"><select name="Entidad" onChange="FORMA.submit()" style="width:100%" >
            <option></option>
            <?
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
    <tr>
    	<td colspan="3">Clase Novedad</td>
        <td>
            <?
			if($Opc=='EPS')
			{
			?>
            	<select  name="ClaNovedad" onChange="FORMA.submit()" style="width:100%" >
               	<option></option>                
            	<option value="TDE" <? if($ClaNovedad=="TDE"){echo "selected";}?>>TDE</option>
            	<option value="TAE" <? if($ClaNovedad=="TAE"){echo "selected";}?>>TAE</option>
                </select>
            <?
			}
			elseif($Opc=='Cesantias')
			{
			?>
            	<select  name="ClaNovedad" onChange="FORMA.submit()" style="width:100%">
                <option></option>
				<option value="NA" <? if($ClaNovedad=="NA"){echo "selected";}?>>NA</option>
                </select>
            <?
			}
			elseif($Opc=='Pensiones')			
			{
			?>
            	<select  name="ClaNovedad" onChange="FORMA.submit()" style="width:100%">
                <option></option>
                <option value="TPP" <? if($ClaNovedad=="TPP"){echo "selected";}?>>TPP</option>
                <option value="TAP" <? if($ClaNovedad=="TAP"){echo "selected";}?>>TAP</option>
                </select>
            <?
			}
			?>
            </td>
    </tr>
</table>
<center><input type="submit" name="Guardar" value="Guardar"><input type="button" name="Cancelar" value="Cancelar" onClick="location.href='Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Nombre=<? echo $fila[4]?>&AnoIni=<? echo $fila[0]?>&MesIni=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Tabla=<? echo $Tabla?>&Campo=<? echo $Campo?>&Opc=<? echo $Opc?>'">
</form>
</body>
</html>