<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$Year="$ND[year]";
if(!$AnioI){$AnioI=$Year;}
if(!$MesI){$MesI="$ND[mon]";}
//echo $MesI;
//if(!$MesF){$MesF=$MesI;}
//echo $Identificacion." ".$NumContrato;
if($Eliminar)
{
//	echo "Hola".$AnioI."   ".$MesI."   ".$NumContrato."<br>";
	$cons="delete from nomina.centrocostos where fecinicio='$FecInicio' and numcontrato='$NumContrato'";
//	echo $cons."<br>";
	$res=ExQuery($cons);
	$AnioI=substr($FecInicio,0,4);
	$MesI=substr($FecInicio,5,2);
	$MesI--;
	if($MesI==0)
	{
		$AnioI--;
		$MesI=12;
	}
	$FecInicio="$AnioI-$MesI-30";
//	echo "Hola".$AnioI."   ".$MesI."   ".$NumContrato."<br>";
	$cons="update nomina.centrocostos set fecfin=NULL where fecfin='$FecInicio' and numcontrato='$NumContrato'";
	$res=ExQuery($cons);
//	echo $cons."<br>";
	
//	echo $cons;
	$Eliminar="Null";
}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();"/>
<input type="hidden" name="FecIni" value="<? echo $FecIni?>">
<?
$consCC="select fecinicio from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' group by fecinicio order by 
fecinicio";
$resCC=ExQuery($consCC);
$cont=ExNumRows($resCC);
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
    </tr>
    <tr align="center">
	    <td colspan="9"><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='CC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&FecInicio=<? if($MesI<10){echo "$AnioI-0$MesI-01";}else{echo "$AnioI-$MesI-01";}?>&NumContrato=<? echo $NumContrato?>'" /></td>
    </tr>
    
</table>
    <?
}
else
{
	?>
    <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<?
	$cons="select fecinicio,fecfin,cc,porcentaje from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' order by fecinicio";
//	echo $cons;
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
//	echo $cont;
	if($cont>0)
	{?>
    	<tr bgcolor="#666699"style="color:white" align="center"><td colspan="6">HISTORIAL CENTRO DE COSTOS</td></tr>
        <tr bgcolor="#666699"style="color:white" align="center"><td>Periodo de Inicio</td><td>Periodo de Finalizacion</td><td>Centro de Costo</td><td>Porcentaje</td><td colspan="2">&nbsp;</td></tr>
<?		$consC="select count(porcentaje) from nomina.centrocostos where compania='$Compania[0]' and Identificacion='$Identificacion' and numcontrato='$NumContrato' group by fecinicio,fecfin order by fecinicio";
//		echo $consC;
		$resC=ExQuery($consC);
//----------------while de acciones a hacer --------------------		
		while($filaC = ExFetch($resC))
		{
//			echo $filaC[0]."hollla";
			for($I=0;$I<$filaC[0];$I++)
			{
				$fila=ExFetch($res);
				if($I<1)
				{
					?>
                    <tr>
						<td rowspan="<? echo $filaC[0]?>" align="center"><? echo $fila[0]?></td>
                    <?
							if($fila[1])
							{
							?>
								<td rowspan="<? echo $filaC[0]?>" align="center"><? echo $fila[1];?></td>
							<?
							}
							else
							{
								?>
                                <td rowspan="<? echo $filaC[0]?>">&nbsp;</td>
                                <?
							}
							?>
                            <td><? echo $fila[2]?></td><td><? echo $fila[3]?></td><td rowspan="<? echo $filaC[0]?>"><a href="#" onClick="location.href='NewCC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Nombre=<? echo $fila[4]?>&AnioI=<? echo $fila[0]?>&MesI=<? echo $fila[1] ?>&NumContrato=<? echo $NumContrato?>&Opc=<? echo $Opc?>'"><img src="/Imgs/b_usredit.png" border="0" title="Editar"/></a></td>
                            <?
							$AnioI=substr($fila[0],0,4);
							$MesI=substr($fila[0],5,2);
							$consnom="select anio from nomina.nomina where numero='$NumContrato' and anio='$AnioI' and mes='$MesI'";
//							echo $consnom;
							$resnom=ExQuery($consnom);
							$ConContr=ExNumRows($resnom);
//							echo $ConContr;
							if($ConContr==0)
							{
							?>
                            <td rowspan="<? echo $filaC[0]?>"><a href="#" onClick="if(confirm('Desea Eliminar el Centro de Costo ?')){location.href='CentroCostos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&FecInicio=<? echo $fila[0] ?>&NumContrato=<? echo $NumContrato?>&Eliminar=1'};"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a></td>
                            </tr>
                            
                            <?
							}
//					echo $I." --> ".$filaC[0]."<br>";
				}
				else
				{
					?>
		            <tr><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td></tr>
                    <?
//					echo $I."<br>";
				}
			}     
//--------------------------------------------			
		}
	}
	?>
</table>
<?
}
?>
</body>
</html>

