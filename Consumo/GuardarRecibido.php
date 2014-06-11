<?php
if ($DatNameSID) {
	session_name ( "$DatNameSID" );
}
session_start ();
include ("Funciones.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">

function validar(yy){

 var nom = document.FORMA.nom_egreso.value;
 
	var r = confirm("Esta seguro de Iniciar Egreso del Paciente "+nom+"? ");
	if(r){		
			document.FORMA.con_egreso.value=yy;
			document.FORMA.submit();
	}
	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
	<form name="FORMA" method="post" >
		<table bordercolor="#e5e5e5" border="1" style='font: normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
		<?
		$cons="SELECT terceros.primnom, terceros.segnom, terceros.primape, terceros.segape, ordenesmedicas.fecha
				FROM salud.ordenesmedicas, central.terceros
				WHERE ordenesmedicas.cedula = '$Ced'
				AND terceros.identificacion = ordenesmedicas.cedula
				AND tipoorden = 'Orden Egreso' ";

		$res=ExQuery($cons); 
		if(ExNumRows($res)){

         while ($fila=ExFetch($res)){
		 $Nom = "".$fila[0]." ".$fila[1]." ".$fila[2]." ".$fila[3];
		?>
        <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
        	<td>Cedula</td><td>Nombre</td><td>Fecha Egreso</td>
       	</tr>
		<tr>
			    <?php 
			    echo "<td>$Ced</td>";
			    echo "<td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";
			    echo "<td>$fila[4]</td>";
			    ?>
	   </tr>
	   <input type='hidden' name= 'con_egreso' id= 'con_egreso'/>
	   <input type='hidden' name= 'nom_egreso' id= 'non_egreso' value= '<?php echo $Nom ?>'/>
<?php }
}
echo $con_egreso;
if ($con_egreso) {

	$query = "UPDATE salud.servicios SET egreso = 3 WHERE cedula = '$con_egreso' ";
	$query1 = "UPDATE consumo.movimiento SET estado = 'AN'  WHERE cedula = '$con_egreso' ";
	$query2 = "UPDATE salud.ordenesmedicas SET estado='AN' WHERE tipoorden= 'Orden Egreso' AND cedula= '$con_egreso' ";
	
	if ($res=ExQuery($query) && $res1=ExQuery($query1) && $res2=ExQuery($query2) ) {
		echo '<script>alert("Se ha recibido el Medicamento de forma Exitosa del Paciente'.$nom_egreso.'");</script>';
	
	?> 
	<script type="text/javascript">
            window.location.href = "RecibirDevoluciones.php";
    </script>
<?
	}
}
?>	
		 
		<br>

		<table bordercolor="#e5e5e5" border="1" align="center"
			style='font: normal normal small-caps 12px Tahoma;' cellpadding="4"
			style="width:70%">
			<tr>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold"
					colspan="8" style="width:50%">Notas Enfermeria</td>
			</tr>
			<tr>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">#</td>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">Fecha
					Entrega</td>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">Medicamento</td>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">Lote</td>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">Cum</td>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">Entrego
					Farmacia</td>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">Suministradas</td>
				<td align="center" bgcolor="#e5e5e5" style="font-weight: bold">Devolver</td>
			</tr>
<?php
$cons = "SELECT a.fechadespacho, CONCAT (c.nombreprod1, ' ', c.presentacion, ' ', c.unidadmedida) as medicamento, 
a.lote, a.cum, a.cantidad as salio_farmacia, b.cantidad as entr_paciente,
(a.cantidad - b.cantidad) as devolver   
FROM consumo.movimiento a, salud.registromedicamentos b, consumo.codproductos c
WHERE a.cedula = '$Ced'
AND a.estado = 'AC'
AND b.cedula = a.cedula 
AND a.autoid = b.autoid
AND a.numorden = b.numorden
AND a.cum = c.cum ";

$res = ExQuery ( $cons );
if (ExNumRows ( $res ) > 0) {
	$cont = 1;
	$tot = 0;
	while ( $fila = ExFetch ( $res ) ) {
		?>
<tr>
				<td><? echo $cont ?></td>
				<td><? echo $fila[0]?></td>
				<td><? echo $fila[1]?></td>
				<td><? echo $fila[2]?></td>
				<td><? echo $fila[3]?></td>
				<td><? echo $fila[4]?></td>
				<td><? echo $fila[5]?></td>
				<td><? echo $fila[6]?></td>
			</tr>
<?
		$tot += $fila [6];
		$cont ++;
	}
}
?>
<tr>
				<td align="left" bgcolor="#FFFFCC" style="font-weight: bold"
					colspan=7>TOTAL A DEVOLVER</td>
				<td bgcolor="#FFFFCC"><strong><?php echo $tot?></strong></td>
			</tr>
			<br>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4" style="width:70%">  
    <tr><td align="center" colspan="5">
	    <input type="submit" value="Dar Salida" name="Salida" onClick="validar('<?echo $Ced ?>')">
	    <input type="button" value="Cancelar" onClick="/RecibirDevoluciones.php" ></td>
		</tr>
</table>
			</form>

</body>
</html>
