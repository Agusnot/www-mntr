<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=Exfetch($res))
	{
		$Cups[$fila[0]]=$fila[1];	
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>


<body background="/Imgs/Fondo.jpg"><?
if($Anio!=''&&$MesIni!=''&&$MesFin!=''&&$DiaIni!=''&&$DiaFin!='')
{	?>
	<table bordercolor="#e5e5e5" border="1"  cellpadding="1" cellspacing="1"style="font : normal normal small-caps 11px Tahoma;" align="center">
<?		$cont=1;
		if($MesIni<10){$C1="0";}else{$C1="";}
		if($DiaIni<10){$C2="0";}else{$C2="";}
		if($MesFin<10){$C3="0";}else{$C3="";}
		if($DiaFin<10){$C4="0";}else{$C4="";}?>
   		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td colspan="8">Consulta Externa Por Dia Res <? echo "$Anio-$C1$MesIni-$C2$DiaIni Hasta $Anio-$C3$MesFin-$C4$DiaFin"?></td>
       	</tr>  	    	
		</tr>
   	</table>
    <br>
    <table bordercolor="#e5e5e5" border="1"  cellpadding="2" cellspacing="2"style="font : normal normal small-caps 11px Tahoma;" align="center">
    	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
           	<td></td><td>Cargo</td><td>Fecha Creacion<td>Fecha Solicitud</td><td>Fecha Atencion</td><td>Diferencia</td><td>Tipo Atencion</td>
            <td>Cedula</td><td>Nombre</td>
         </tr>
 	<?	if($Especialidad&&$Especialidad!="Todas"){$Esp="and especialidad='$Especialidad'";}
		
		$cons="select numservicio,cargo,fecha,fechasolicita,fechacrea,cedula,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom),cup	 
		from salud.agenda,salud.medicos,central.terceros
		where agenda.compania='$Compania[0]' and medicos.compania='$Compania[0]' and terceros.compania='$Compania[0]' and medicos.usuario=agenda.medico
		and identificacion=cedula and fechacrea>='$Anio-$C1$MesIni-$C2$DiaIni 00:00:00' 
		and fechacrea<='$Anio-$C3$MesFin-$C4$DiaFin 23:59:59' and estado!='Cancelada'
		$Esp order by cargo,fecha";
		$res=ExQuery($cons);	
		$cont=0;
		while($fila=ExFetch($res))
		{
			$cont++;
			$FecCrea=explode(" ",$fila[4]);
			if($fila[3]){
				$Fecha1=explode("-",$fila[3]);
				$timestamp1 = mktime(0,0,0,$Fecha1[1],$Fecha1[2],$Fecha1[0]); 				
			}
			else
			{
				$Fecha1=explode("-",$FecCrea[0]);
				$timestamp1 = mktime(0,0,0,$Fecha1[1],$Fecha1[2],$Fecha1[0]);
			}
			$Fecha2=explode("-",$fila[2]);
			$timestamp2 = mktime(0,0,0,$Fecha2[1],$Fecha2[2],$Fecha2[0]);
			
			$segundos_diferencia = $timestamp1 - $timestamp2;
			$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			//$dias_diferencia = abs($dias_diferencia); 
			$dias_diferencia = floor($dias_diferencia); 
			if($fila[3]){if($fila[3]==$fila[2]){$dias_diferencia = 0;}}
			else{if($FecCrea[0]==$fila[2]){$dias_diferencia = 0;}}
			$SumDifs=$SumDifs+$dias_diferencia;?>			
       		<tr>
            	<td><? echo $cont;?></td><td><? echo $fila[1]?></td><td><? echo $fila[4]?></td>
                <td><? if($fila[3]){ echo $fila[3];}else{ echo $FecCrea[0];}?></td><td><? echo $fila[2]?></td>
                <td align="center">
           	<?	if($dias_diferencia<0){?><font color="#FF0000">
				<? echo $dias_diferencia?>
          	<?	}?>
                </td><td><? echo $Cups[$fila[7]]?></td><td><? echo $fila[5]?></td><td><? echo $fila[6]?></td>
            </tr>		<?		
		}		
		if($cont>0){
			$Prom=round(($SumDifs/$cont),2);
		}
		else{$Prom="0";}?>
       	<tr>
        	<td colspan="5" align="right"><strong>Total</strong></td><td align="center"><? echo $SumDifs?></td>
      	</tr>
        	<tr>
        	<td colspan="5" align="right"><strong>Promedio</strong></td><td align="center"><? echo $Prom?></td>
      	</tr>
    </table><?
}?>    
</body>
</html>