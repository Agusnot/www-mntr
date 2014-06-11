<?php
include("Funciones.php");

	$yy  = $_GET['yy'];

	if($yy == 'coco'){
	
	$tip_error   = $_POST['tip_error'];
	$nom_script  = $_POST['nom_script'];
	$num_linea   = $_POST['num_linea'];
	$descripcion = $_POST['despcripcion'];
	$ip = $_SERVER[REMOTE_ADDR];
	$descrip = str_replace("\'","|",$descripcion);
  
		$query="insert into central.mejoras_sistema
				(tipo_error, detalle_error, archivo_error, numlin_error, ind_estado, ip_responsable, fec_registro) values
				('$tip_error','$descrip','$nom_script','$num_linea','0','$ip','NOW()')";
			
				if(ExQuery($query))
				{
					echo '<script>alert("El Registro fue guardado con exito ");</script>';
					echo '<script>window.history.go(-2);</script>';
				}else{
					echo '<script>alert("El Registro NO pudo ser guardado, por favor vuelva a intentarlo ");</script>';
					echo '<script>window.history.go(-1);</script>';
				}
    }else{
	
	$aa  = $_GET['aa'];
	
	if($aa == ''){
	$query = "SELECT
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
			WHERE a.id_mejora = '$yy' ";
			$res2=ExQuery($query);
			
				if($fila=ExFetch($res2)){
	
	?>
		<form method="POST" name="FORMA" onSubmit="return Validar()" action="guardar.php?aa=yy">
<script language="JavaScript">

        function Validar(){
		
			if(document.FORMA.observa.value ==''){
				alert('Por favor escribir las observaciones de la correción correspondiente');
                   return false;
			}
			document.FORMA.valida.value= '1';
			return true;
		}
</script>

	<table width="600"  bordercolor="#e5e5e5" border="1"  align="center" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
<tr>
	<td colspan='2' align="center" bgcolor="#e5e5e5" style="font-weight:bold"><b>ACTUALIZAR REGISTRO DE ERROR</b></td>
</tr>
<tr>
  <td align="left"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo de Error<span class="Estilo1"></td>
  <td><?php echo $fila[0] ?></td>
</tr>
<tr>
	<td align="left"  bgcolor="#e5e5e5" style="font-weight:bold">Script</td>
	<td><?php echo $fila[2] ?></td>
</tr>
<tr>
	<td align="left"  bgcolor="#e5e5e5" style="font-weight:bold" >Linea</td>
    <td><?php echo $fila[3] ?></td>
</tr>
<tr>
	<td align="left"  bgcolor="#e5e5e5" style="font-weight:bold">Descripción</td>
	<td><textarea style="resize: none;" readonly="readonly" cols="50"  rows="4"><?php echo $fila[1] ?></textarea></td>
</tr>
<tr>
	<td align="left" colspan="2" bgcolor="#e5e5e5" style="font-weight:bold">Observaciones</td>
</tr>
<tr>
	<td colspan="2"><textarea style="resize: none;" cols="70"  rows="4" id="observa" name="observa"></textarea></td>
	<input type="hidden" id="valida" name="valida">
	<input type="hidden" id="llave" name="llave" value="<?php echo $yy ?>">
</tr>
<tr>
	<td colspan="2" align='center'><input style="width:120px;" type="Submit" name="Guardar" value="Guardar" ></td>
</tr>
</table>
</form>
	<?
	}
			
	}
	else{
	
		echo $usu  = $usuario[1];
		$id       = $_POST['llave'];
		$observa  = $_POST['observa'];
	
		$query = "UPDATE central.mejoras_sistema 
				  SET ind_estado='1', fec_solucion='NOW()',observaciones= '$observa', usu_responsable='$usu' 
				  WHERE id_mejora= '$id'";
		
		if($res=ExQuery($query)){
		
		echo '<script>alert("El Registro fue actualizado con exito ");</script>';
		echo '<script>window.history.go(-2);</script>';
		
		}else{
		
			echo '<script>alert("El Registro NO pudo ser guardado, por favor vuelva a intentarlo ");</script>';
			echo '<script>window.history.go(-1);</script>';
		}
		
	}
}	
?>