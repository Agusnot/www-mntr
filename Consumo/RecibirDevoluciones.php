<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$cons="select tipoformato,formato,tblformat from historiaclinica.formatos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Formatos[$fila[0]][$fila[1]]=$fila[2];
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">

function validar( yy, nom ){

	var r = confirm("¿Esta seguro de Iniciar Egreso del Paciente "+nom+"?");
	if(r){		
			document.FORMA.con_egreso.value=yy;
			document.FORMA.nom_egreso.value=nom;
		    document.FORMA.submit();
	}
	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"> 
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="15">Egresos Pacientes</td>
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
				
			}
			
			?>
   		</select></td>
	</tr>
</table>       
<br>
<? 	
if ($con_egreso) {
	$query = "UPDATE salud.servicios SET egreso = 1 WHERE cedula = '$con_egreso' ";
	
	if ($res=ExQuery($query)) {
		echo '<script>alert("Se ha iniciado el proceso de egreso para el paciente '.$nom_egreso.'");</script>';
	}
}
$ne= $Ambito;
if($Ambito){
		$Amb="and tiposervicio='$Ambito'";
		
	?>  
	<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4"><?
		
		     $cons="SELECT ordenesmedicas.cedula, primnom, segnom, primape, segape, tipoorden, ordensalida as fecha_salida, 
					comprobante, ordenesmedicas.numservicio
					FROM salud.ordenesmedicas,central.terceros, salud.servicios, consumo.movimiento 
					WHERE ordenesmedicas.compania= '$Compania[0]' AND terceros.compania=ordenesmedicas.compania 
					AND ordenesmedicas.cedula=identificacion AND ordenesmedicas.estado='AC' AND ordenesmedicas.tipoorden='Orden Egreso' 
					AND servicios.cedula = ordenesmedicas.cedula AND ordenesmedicas.numservicio = servicios.numservicio 
					AND ordenesmedicas.cedula=terceros.identificacion AND egreso = 2 and movimiento.estado = 'AC' 
					AND movimiento.cedula = servicios.cedula  AND tiposervicio= '$Ambito'
					GROUP BY ordenesmedicas.cedula, primnom, segnom, primape, segape, tipoorden, ordensalida, comprobante, 
		            ordenesmedicas.numservicio ";

		$res=ExQuery($cons); 
		if(ExNumRows($res)>0){
   
		?>
        <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
        	<td>Cedula</td><td>Nombre</td><td>Proceso</td><td>Fecha Egreso</td><td>Comprobante</td>
       	</tr>
		<?	while($fila=ExFetch($res)){
			
			    ?>
			    <tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" 
			    onClick="location.href='GuardarRecibido.php?DatNameSID=<? echo $fila[8]?>&Ced=<? echo $fila[0]?>&Ambito=<? echo $Ambito?>'" >
			    <?php 
			    echo "<td>$fila[0]</td>";
			    echo "<td>$fila[1] $fila[2] $fila[3] $fila[4]</td>";
			    echo "<td>$fila[5]</td>";
			    echo "<td>$fila[6]</td>";
			    echo "<td>$fila[7]</td>";
			    echo "</tr>";	
			 
				}
			}
		else
		{?>
			<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No hay Pacientes con Orden de Egreso en esta Unidad</td></tr>
<?		}
}
	?>
	</table> 
  
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="Hidden" name="con_egreso">
<input type="Hidden" name="nom_egreso">
</form>
</body>
</html>
