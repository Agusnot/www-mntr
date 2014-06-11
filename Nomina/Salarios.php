<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if(!$AnioI){$AnioI="$ND[year]";}
if(!$MesI){$MesI="$ND[mon]";}
if(!$MesF){$MesF=$MesI;}
//echo $FecIni;
//--------------------------------------
if($Guardar)
{
	if($MesI<10){$MesI="0$MesI";}
	if($MesF<10){$MesF="0$MesF";}
	$FecInicio="$AnioI-$MesI-01";
	if($MesF==02)
	{
		$FecFin="$AnioF-$MesF-28";
	}
	else
	{
		$FecFin="$AnioF-$MesF-30";
	}
	
	$cons="select * from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and fecfin >= '$FecInicio'";
//	echo $cons;
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		$cons="insert into nomina.salarios(compania,identificacion,fecinicio,fecfin,salario,numcontrato) values 
		('$Compania[0]','$Identificacion','$FecInicio','$FecFin',$Salario,'$NumContrato')";
	//	echo $cons;
		$res=ExQuery($cons);
	}
	else
	{
		?><script language="javascript">alert("Ya existe Salario para esta Fecha !!!");</script><?
	}
	?>
	<script language="javascript">location.href="Salarios.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>";</script>
    <?
}
if($Eliminar==1)
{
	$Eliminar=0;
	$cons="delete from nomina.salarios where compania='$Compania[0]' and Identificacion='$Identificacion' and fecinicio='$FecInicio' and fecfin='$FecFin' 
	and salario=$Salario and numcontrato='$NumContrato'";
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
   if(document.FORMA.AnioF.value==""){alert("Por favor ingrese el Año Final !!!"); return false;}
   if(document.FORMA.MesF.value==""){alert("Por favor ingrese el Mes Final !!!");return false;}
   if(document.FORMA.Salario.value==""){alert("Por favor ingrese el Salario !!!");return false;}
}
</script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="Eliminar">
<input type="hidden" name="AnioI" value="<? echo $AnioI?>">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#666699"style="color:white" align="center">
    	<td colspan="9">SALARIO</td>
    </tr>
    <tr align="center">
    	<td colspan="4">Periodo de Inicio</td><td colspan="4">Periodo de Finalizacion</td><td>Salario</td>
    </tr>
    <tr>
    	<td>Año</td>
        <td><select name="AnioI" onChange="FORMA.submit()" >
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
        <td><select name="AnioF" onChange="FORMA.submit()" >
            <?
            	$cons = "select anio from central.anios where compania='$Compania[0]' and anio>=$AnioI order by anio";
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
        <td><input type="text" name="Salario" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" />
        </td>
    </tr>
    <tr align="center">
	    <td colspan="9"><input type="submit" name="Guardar" value="Guardar" />
    </tr>
</table>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<?
	$cons="select fecinicio,fecfin,salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato'";
//	echo $cons;
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
//	echo $cont;
	if($cont>0)
	{?>
    	<tr bgcolor="#666699"style="color:white" align="center"><td colspan="5">HISTORIAL SALARIOS</td></tr>
        <tr bgcolor="#666699"style="color:white" align="center"><td>Fecha Inicial</td><td>Fecha Final</td><td>Salario</td><td colspan="2">&nbsp;</td></tr>
		<? while($fila=ExFetch($res))
		{
			?>
			<tr align="center"><td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td>
            <?            
			$ConsEdit="select count(salario) from nomina.salarios where compania='$Compania[0]'and identificacion='$Identificacion' and numcontrato='$NumContrato' and fecfin>'$fila[1]'";
			$resEdit=ExQuery($ConsEdit);
			$filaEdit=ExFetch($resEdit);
			if($filaEdit[0]==0)
			{?>
				<td width="16px"><a href="EditSalario.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&FecInicio=<? echo $fila[0]?>&FecFin=<? echo $fila[1] ?>&Salario=<? echo $fila[2]?>&NumContrato=<? echo $NumContrato?>"><img src="/Imgs/b_edit.png" border="0" title="Editar"/></a></td>
<?			}
			else
			{ ?>
				<td>&nbsp;</td>
<?			}
// 			echo $ConsEdit."<br>";
            $consnom="select anio from nomina.nomina where numero='$NumContrato'";
			$resnom=ExQuery($consnom);
			$ConContr=ExNumRows($resnom);
//			echo $ConContr;
			if($ConContr==0)
			{
				?>
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar el Salario del Historial ?')){location.href='Salarios.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Eliminar=1&FecInicio=<? echo $fila[0]?>&FecFin=<? echo $fila[1] ?>&Salario=<? echo $fila[2]?>&NumContrato=<? echo $NumContrato?>'};"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a></td></tr>
	<?		}
		}
	}
	?>
</table>
</form>
</body>
</html>