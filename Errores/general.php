<?php
if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	
	$usu  = $usuario[1];
	$final = explode(" ", $usu);
	$final[0]; 
	$final[1]; 


	
	IF($final[0] == 'ADMINISTRADOR'){
	include("Funciones.php");	
?>	
	
	<form method="POST" name="FORMA" onSubmit="return Validar()">
<script language="JavaScript">

        function Validar(yy){
		
			document.FORMA.id.value = yy;
		}
</script>		
<table width="100px" bordercolor="#e5e5e5" border="1"  align="center" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
<img src="../Imgs/new_error.png" width="30" height="30" alt="Resolver">
<a target="Derecha" href="/Errores/nuevo.php?DatNameSID=$DatNameSID"><b>Nuevo Registro</b></a> <!--<input type="button" value="Nuevo Registro">-->
	<br><br>
<tr>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">ID</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo Error</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Detalle</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Script</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Linea</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Estado</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Responsable</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Fecha Registro</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Fecha Solución</td>
	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Acción</td>
</tr>
<?php
	$cons2="SELECT
			a.id_mejora,
			a.tipo_error,
			a.detalle_error,
			a.archivo_error,
			a.numlin_error,
			a.ind_estado,
			a.usu_responsable,
			a.ip_responsable,
			a.fec_registro,
			a.fec_solucion
			FROM central.mejoras_sistema as a
			 "; //WHERE ind_estado = '0'
			 
	$res2=ExQuery($cons2);
	while($fila=ExFetch($res2))
	{
	
?>
<tr>
	<td><?php echo $fila[0] ?></td>
	<td><?php echo $fila[1] ?></td>
	<td><?php echo $fila[2] ?></td>
	<td><?php echo $fila[3] ?></td>
	<td><?php echo $fila[4] ?></td>
	<td><?php echo ($fila[5]== 0)?'Activa':'Inactiva'; ?></td>
	<td><?php echo $fila[7] ?></td>
	<td><?php echo $fila[8] ?></td>
	<td><?php echo ($fila[9] =='')?'Sin resolver':$fila[9]; ?></td>
	<td><?php 
	if($fila[5]== 0){
	?>
	<img src="../Imgs/ok.png" width="15" height="15" ALT="Resolver"><a href="guardar.php?yy=<?php echo $fila[0] ?>" onClick="Validar('<?php echo $fila[0] ?>');"><b>Resolver</b></a>
	<?php
	}else{
	echo "Resuelta";
	}
	?>
	</td>
</tr>
<?php
}
?>
</table>
<input name="id" id="id" type="hidden">
<div id="div_pas"></div>
<br>
</center>
</form>
</body>
</html>

<?php	
	}ELSE{
	?>
	<script language="javascript">
		alert('Lo sentimos no tiene acceso a este modulo, por favor comuniquese con el administrador del sistea :P');
		history.back();
	</script>
	<?php
	}
?>