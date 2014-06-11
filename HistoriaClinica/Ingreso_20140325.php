<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}
	$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="15">Ingresos Pacientes</td>
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
    <tr>
	     <td align="center"><select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?	
			$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
   		</select></td>
	</tr>
</table>       
<br>
<br>
<? 	if($Ambito){?>
	<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4"><?		
		$cons="select primape,segape,primnom,segnom,identificacion,numservicio from salud.servicios,central.terceros where 
		servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and servicios.estado='AC' and servicios.cedula=terceros.identificacion and servicios.ingreso=0
		and tiposervicio='$Ambito' group by primnom,segnom,primape,segape,identificacion,numservicio
		order by primnom,segnom,primape,segape";
		$res=ExQuery($cons); 
		if(ExNumRows($res)>0){?>
        <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Cedula</td><td>Nombre</td><td>Aseguradora</td><td>Contrato</td><td>No. Contrato</td></tr>
		<?	while($fila=ExFetch($res)){
				$cons2="select primape,segape,primnom,segnom,contrato,nocontrato from central.terceros,salud.pagadorxservicios 
				where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad 
				and pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";	
				$res2=ExQuery($cons2);	
				//echo $cons2;  		  	
				if(ExNumRows($res2)>0){
					$fila2=ExFetch($res2);
					$EPS="$fila2[0] $fila2[1] $fila2[2] $fila2[3]"; $Contra="$fila2[4]"; $NoContra="$fila2[5]";
				}
				else{			
					$cons3="select primape,segape,primnom,segnom,contrato,nocontrato,fechafin from central.terceros,salud.pagadorxservicios 
					where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
					pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini";
					$res3=ExQuery($cons3);	
					if(ExNumRows($res3)>0){				
						$fila3=ExFetch($res3);
						if(!$fila3[6]){
							$EPS="$fila3[0] $fila3[1] $fila3[2] $fila3[3]"; $Contra="$fila3[4]"; $NoContra="$fila3[5]";	
						}
						else{
							$EPS=""; $Contra=""; $NoContra="";
						}
					}
					else{
						$EPS=""; $Contra=""; $NoContra="";
					}
				}
				?>
				<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="location.href='NewIngreso.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[4]?>&Ambito=<? echo $Ambito?>'"><? 
				echo "<td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";
				if($EPS){echo "<td>$EPS</td><td>$Contra</td><td>$NoContra</td></tr>";}else{echo "<td colspan='3'> - Sin Asegurador Activo - </td></tr>";}
			}
		}
		else
		{?>
			<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No hay Pacientes con Orden de Ingreso en esta Unidad</td>
<?		}?>
	</table> 
<?	}?>    
	<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
