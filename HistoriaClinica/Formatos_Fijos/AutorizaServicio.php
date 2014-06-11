<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Homologa){
		$cons="select numservicio,cedula from salud.servicios where compania='$Compania[0]' and estado='AC'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res)){
			$NumS=$fila[0];
			if($fila[1]){
				$cons2="update salud.ordenesmedicas set numservicio=$fila[0] where numservicio!=$NumS and compania='$Compania[0]' and cedula='$fila[1]'";
				$res2=ExQuery($cons2); echo $cons2."<br>";
			}
		}
	}
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}
	$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";	
	global $Activo;
	$Activo=0;
	$cons="select super from central.usuarios where usuario='$usuario[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Super=$fila[0];
	if($Eliminar)
	{
		$cons="Delete from Salud.servicios where cedula='$Paciente[1]' and Compania='$Compania[0]' and numservicio=$Numservicio";
		$res=ExQuery($cons);
		echo ExError();
	}	
	$result=ExQuery("Select * from Salud.Pabellones where Compania='$Compania[0]'");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<? 	$cons="Select tiposervicio,fechaing,fechaegr,estado,numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' order by numservicio";
  	$res=ExQuery($cons);
	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}		
if($Paciente[1]){?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
<form name="FORMA" method="post">
<!-- <input type="submit" name="Homologa" value="Homologa">-->
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td align="center">No Servicio</td><td align="center">Tipo Servicio</td><td align="center">Fecha Ingreso</td><td align="center">Fecha Egreso</td><td align="center">Entidad</td>
        <td align="center">Contrato</td><td align="center">No. Contrato</td><td align="center">Estado</td><td colspan="2"></td>
    </tr>
<? 	while($fila = ExFetch($res))
	{ 
		
		$cons2="select primnom,segnom,primape,segape,contrato,nocontrato from central.terceros,salud.pagadorxservicios 
		where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
		pagadorxservicios.numservicio=$fila[4]	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";
		
		$res2=ExQuery($cons2);			
		if(ExNumRows($res2)==0){
			$cons2="select primnom,segnom,primape,segape,contrato,nocontrato from central.terceros,salud.pagadorxservicios 		
			where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
			pagadorxservicios.numservicio=$fila[4]	and '$FechaComp'>=fechaini and fechafin is null";
			$res2=ExQuery($cons2);
		}
		//echo $cons2."<br>";
		$Raiz=$_SERVER['DOCUMENT_ROOT'];
		$Ruta="/HistoriaClinica/";?>		
    	<tr align='center'><td align='center'><? echo $fila[4]?></td><td><? echo $fila[0]?></td><td align='center'><? echo substr($fila[1],0,11)?></td>
        <td <? if($fila[2]){?> style="cursor:hand" title="Ver Boleta de Egreso"
        		onClick="open('<? echo $Ruta?>BoletaEgreso.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Paciente[1]?>&NumServ=<? echo $fila[4]?>&Ambito=<? echo $fila[0]?>','','width=500,height=350')"
		<? }?>><? echo substr($fila[2],0,11)?></td>
	<?	if(ExNumRows($res2)>0){
			$fila2=ExFetch($res2);
			echo "<td>$fila2[0] $fila2[1] $fila2[2] $fila2[3]</td><td>$fila2[4]</td><td>$fila2[5]</td><td>";
		}
		else{			
			$cons3="select primnom,segnom,primape,segape,contrato,nocontrato,fechafin from central.terceros,salud.pagadorxservicios 
			where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
			pagadorxservicios.numservicio=$fila[4]	and '$FechaComp'>=fechaini";			
			$res3=ExQuery($cons3);	
			if(ExNumRows($res3)>0){				
				$fila3=ExFetch($res3);
				if(!$fila3[6]){
					echo "<td>$fila3[0] $fila3[1] $fila3[2] $fila3[3]</td><td>$fila3[4]</td><td>$fila3[5]</td><td>";	
				}
				else{
					echo "<td colspan='3' align='center'> - Sin Entidad - </td><td>";					
				}
			}
			else{
				echo "<td colspan='3' align='center'> - Sin Entidad - </td><td>";
			}
		}
		if($fila[3]=='AC'){echo "Activo"; $Activo=1;}else{echo "Inactivo";} echo "</td><td>&nbsp;";?>
              	
				<img src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewAutorizarServicio.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Numservicio=<? echo $fila[4]?>'">
        	</td>
  	<?	if($Super){?>  
        	<td>
				<img style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='AutorizaServicio.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Numservicio=<? echo $fila[4]?>';}" src="/Imgs/b_drop.png">
        	
<? 		}?>
		</td></tr>
<?	}?>
     <tr>     
     	<td colspan="10" align="center"><input type="button" onClick="if(<? echo $Activo?>==0){;location.href='NewAutorizarServicio.php?DatNameSID=<? echo $DatNameSID?>'}else{alert('El usuario tiene un servicio activo por lo tanto no puede crear uno nuevo!!!');}" value="Nuevo"></td>
     </tr>
</table><?
}
else{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No hay un paciente seleccionado!!! </b></font></center>";
}?> 
</form>
</body>
</html>