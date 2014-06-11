<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
		
	$ND=getdate();
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}
	if($ND[hours]<10){$cero3='0';}else{$cero3='';}
	if($ND[minutes]<10){$cero4='0';}else{$cero4='';}
	if($ND[seconds]<10){$cero5='0';}else{$cero5='';}		
	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.FechaIni.value==""){
			alert("Debe digitar la fecha inicial!!!");return false;
		}
		else{
			if(document.FORMA.FechaFin.value!=""&&document.FORMA.FechaFin.value<document.FORMA.FechaIni.value){
				alert("La fecha final debe ser mayor a la fecha inicial!!!");return false;
			}
		}
	}
</script>
<title>Reporte Consolidado Multas</title>
</head>

<body background="/Imgs/Fondo.jpg"> 
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center"> 
	
<? 	if(!$Ver)
	{?>
		<tr>						
    		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td> 
            <td><input type="text" name="FechaIni" readonly="readonly" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"/></td>	    
    		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
            <td><input type="text" name="FechaFin" readonly="readonly" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"/></td>
	    </tr>
        <tr align="center">
        	<td colspan="4"><input type="submit" value="Ver" name="Ver" style="width:70px"/><input type="button" value="Cancelar" onClick="window.close()"/></td>
        </tr>
<?	}
	else{			
		if(!$FechaFin){
			$F="DESDE $FechaIni";
		}
		else{
			if($FechaFin==$FechaIni){
				$F="DEL DIA $FechaIni";
			}
			else{
				$F="DESDE $FechaIni HASTA $FechaFin";
			}
		}?>
		<tr><td colspan="11"><center><strong><? echo strtoupper($Compania[0])?><br>
			<? echo $Compania[1]?><br>CONSOLIDADO MULTAS <? echo $F?> <br></strong></td></tr>
		<tr>
   		   <td colspan="11" align="right">
           Impresi&oacute;n: Fecha <? echo " $ND[year]-$cero1$ND[mon]-$cero3$ND[mday] "?> Hora <? echo " $cero3$ND[hours]:$cero4$ND[minutes]:$cero5$ND[seconds]"?>
       		</td>
		</tr>
<?		$Ban=0;	 			
       	$cons="select identificacion,primnom,segnom,primape,segape from central.terceros where compania='$Compania[0]' and tipo='Asegurador' 
		order by primnom,segnom,primape,segape";	
		$res=ExQuery($cons);echo ExError();
		
		while($fila=ExFetch($res))
		{
			if($FechaFin){
				$cons2="select primnom,segnom,primape,segape,multas.cedula,multas.entidad,usuarios.nombre,multas.fechacrea,multas.valor 
				from salud.multas,central.terceros,central.usuarios
				where multas.compania='$Compania[0]' and terceros.compania='$Compania[0]' and multas.cedula=terceros.identificacion  and usuarios.usuario=multas.usuario
				and multas.entidad='$fila[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59'
				group by primnom,segnom,primape,segape,multas.cedula,multas.entidad,multas.fechacrea,multas.valor,usuarios.nombre
				order by primnom,segnom,primape,segape";				
			}
			else{
				$cons2="select primnom,segnom,primape,segape,multas.cedula,multas.entidad,usuarios.nombre,multas.fechacrea,multas.valor 
				from salud.multas,central.terceros,central.usuarios
				where multas.compania='$Compania[0]' and terceros.compania='$Compania[0]' and multas.cedula=terceros.identificacion  and usuarios.usuario=multas.usuario
				and multas.entidad='$fila[0]' and fechacrea>='$FechaIni 00:00:00' 
				group by primnom,segnom,primape,segape,multas.cedula,multas.entidad,multas.fechacrea,multas.valor,usuarios.nombre
				order by primnom,segnom,primape,segape";				
			}
			$res2=ExQuery($cons2); echo ExError($res2);			
			if(ExNumRows($res2)>0){
				$Ban=1;?>
                <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	                <td colspan="6"><? echo "$fila[1] $fila[2] $fila[3] $fila[4]";?></td>
                </tr>
				<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    				<td>Nombre</td><td>Identificacion</td><td>Usuario Cancelador</td><td>Fecha Cancelacion</td><td>Valor</td>
			    </tr>
            <?  while($fila2=ExFetch($res2))
				{?>
					<tr>
    	    			<td><? echo "$fila2[0] $fila2[1] $fila2[2] $fila2[3]";?></td><td><? echo $fila2[4]?></td><td><? echo $fila2[6]?></td>
        	    		<td><? echo $fila2[7]?></td><td align="right"><? echo  number_format($fila2[8],2)?></td>        
	       			 </tr>	
			<?		$Total=$Total+$fila2[8];
				}?>
                <tr>
	    			<td colspan="4" align="right"><strong>Total</strong></td> 
		    	    <td><? echo number_format($Total,2);?></td>       
	    		</tr>
		<?	}			
		}			
		
		if($Ban==0)
		{
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'><td>No Se Han Registrado Multas Para Este Periodo</td></tr>";
		}
	}?>
</table>
</form>
</body>
</html>
