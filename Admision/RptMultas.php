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
		
	$cons="select identificacion,primnom,segnom,primape,segape from central.terceros where compania='$Compania[0]' and tipo='Asegurador'";	
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$Aseguradores[$fila[0]]="$fila[1] $fila[2] $fila[3] $fila[4]";
		//echo "$fila[0]=$fila[1] $fila[2] $fila[3] $fila[4]";
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte Multas</title>
</head>
<body background="/Imgs/Fondo.jpg"> 
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center"> 
	<tr><td colspan="11"><center><strong><? echo strtoupper($Compania[0])?><br>
		<? echo $Compania[1]?><br>MULTAS<br></strong></td></tr>
	<tr>
   	   <td colspan="11" align="right">Impresi&oacute;n: Fecha <? echo " $ND[year]-$cero1$ND[mon]-$cero3$ND[mday] "?> Hora <? echo " $cero3$ND[hours]:$cero4$ND[minutes]:$cero5$ND[seconds]"?>
       </td>
	</tr>
<?
	$cons="select primnom,segnom,primape,segape,multas.cedula,multas.entidad,usuarios.nombre,multas.fechacrea,multas.valor from salud.multas,central.terceros,central.usuarios
	where multas.compania='$Compania[0]' and terceros.compania='$Compania[0]' and multas.cedula=terceros.identificacion  and usuarios.usuario=multas.usuario
	group by primnom,segnom,primape,segape,multas.cedula,multas.entidad,multas.fechacrea,multas.valor,usuarios.nombre
	order by primnom,segnom,primape,segape";	
	$res=ExQuery($cons); echo ExError($res);
	if(ExNumRows($res)>0)
	{  ?>
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Nombre</td><td>Identificacion</td><td>Entidad</td><td>Usuario Cancelador</td><td>Fecha Cancelacion</td><td>Valor</td>
    	</tr>
     <? while($fila=ExFetch($res))
		{?>
			<tr>
    	    	<td><? echo "$fila[0] $fila[1] $fila[2] $fila[3]";?></td><td><? echo $fila[4]?></td><td><? echo $Aseguradores[$fila[5]]?></td><td><? echo $fila[6]?></td>
        	    <td><? echo $fila[7]?></td><td align="right"><? echo  number_format($fila[8],2)?></td>        
	        </tr>	
	<?		$Total=$Total+$fila[8];
		}?>   
		<tr>
	    	<td colspan="5" align="right"><strong>Total</strong></td> 
    	    <td><? echo number_format($Total,2);?></td>       
	    </tr>
<?	}
	else{
		echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'><td>No Se Han Registrado Multas Hasta El Momento</td></tr>";
	}
	?>   
</table>
</body>
</html>
