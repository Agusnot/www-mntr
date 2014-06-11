<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");			
	$d=date('w',mktime(0,0,0,$F[1],$F[2],$F[0]));	
	switch($d){
		case 1: $Diasem='Lun'; break;
		case 2: $Diasem='Mar'; break;
		case 3: $Diasem='Mie'; break;
		case 4: $Diasem='Juv'; break;
		case 5: $Diasem='Vie'; break;
		case 6: $Diasem='Sab'; break;
		case 0: $Diasem='Dom'; break;
	}
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
</head>

<body background="/Imgs/Fondo.jpg">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr><td colspan="11"><center><strong><? echo strtoupper($Compania[0])?><br>
		<? echo $Compania[1]?><br>AGENDA General<br></td></tr>
	<tr>
       <td colspan="11" align="right">Impresi&oacute;n: Fecha <? echo " $ND[year]-$cero1$ND[mon]-$cero3$ND[mday] "?> Hora <? echo " $cero3$ND[hours]:$cero4$ND[minutes]:$cero5$ND[seconds]"?></td>
	</tr>
<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="11"> <? echo "$Fecha - $Diasem";?></td></tr>
<?	$cons="select usuario from salud.medicos where compania='$Compania[0]'";
	$res=ExQuery($cons);	
while($fila = ExFetchArray($res)){ 
	$cons4="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado from central.terceros,salud.agenda where
terceros.identificacion=agenda.cedula and medico='$fila[0]' and fecha='$Fecha' and estado='Pendiente' and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' order by primape,segape,primnom,segnom";
	
	$res4=ExQuery($cons4);echo ExError();
	if(ExNumRows($res4)>0){
		$cons5="select nombre from central.usuarios where usuario='$fila[0]'";
		$res5=ExQuery($cons5);
		$fila5=ExFetch($res5);
		$cons2="select especialidad from salud.medicos where usuario='$fila[0]' and compania='$Compania[0]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
	?>  <tr><td></td></tr>
    	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="11"><? echo $fila5[0]."-".$fila2[0]?></td></tr>   
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
			<td>Hora</td><td>Cedula</td><td>Nombre<td>Telefono</td><td>Entidad</td><td>Estado</td>     
		</tr>
 	<? while($fila4 = ExFetchArray($res4)){ 
		 	$cons3="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros where  identificacion='$fila4[10]' and Tipo='Asegurador' and 	Compania='$Compania[0]' order by primape";
			$res3=ExQuery($cons3);echo ExError();//consulta de la agenda
			$fila3=ExFetchArray($res3);?>  
			<tr align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
    	 <? if($fila4[3]==0){$cero1='0';}else{$cero1='';}
			if($fila4[1]==0){$cero='0';}else{$cero='';} 
			echo "<td>$fila4[0]:$fila4[1]$cero-$fila4[2]:$fila4[3]$cero1</td><td>$fila4[4]</td><td>$fila4[5] $fila4[6] $fila4[7] $fila4[8]</td><td>$fila4[9]</td><td>$fila3[0]</td><td>$fila4[11]</td><tr>";  }		
	}
}?>
</table>
</body>
</html>