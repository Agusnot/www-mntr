<?php

if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
?>
<form method="POST" name="FORMA" onSubmit="return Validar()" action="guardar.php?yy=coco">

<script language="JavaScript">

        function Validar(){
   
		if(document.FORMA.tip_error.value == ""){

                   alert('Es necesario seleccionar el tipo de error');
                   return false;
		
		}
		if(document.FORMA.nom_script.value == ""){

                   alert('Es necesario escribir la ruta del script que presenta error');
                   return false;
		
		}
		if(document.FORMA.num_linea.value == ""){

                   alert('Es necesario escribir el numero de linea que presenta el error');
                   return false;
		
		}
		if(document.FORMA.despcripcion.value == ""){

                   alert('Es necesario realizar una descripcion del error');
                   return false;
		
		}
		
	return true;

	}
</script>

<table width="600"  bordercolor="#e5e5e5" border="1"  align="center" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
<tr>
	<td colspan='2' align="center" bgcolor="#e5e5e5" style="font-weight:bold"><b>NUEVO REGISTRO DE ERROR</b></td>
</tr>
<tr>
  <td align="left"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo de Error<span class="Estilo1"></td>
  <td>
  <select name="tip_error" id="tip_error" >
	  <option value="" selected>Seleccione</option>
	  <option value="Base de Datos">Base de Datos</option>
	  <option value="HTML">HTML</option>
	  <option value="Javascript">Javascript</option>
	  <option value="Operacional">Operacional</option>
	  <option value="Compatibilidad Explorador">Compatibilidad Explorador</option>
  </select></td>
</tr>
<tr>
	<td align="left"  bgcolor="#e5e5e5" style="font-weight:bold">Script</td>
	<td><input name="nom_script" type="text" id="nom_script"></td>
</tr>
<tr>
	<td align="left"  bgcolor="#e5e5e5" style="font-weight:bold" >Linea</td>
    <td><input name="num_linea" type="text" id="num_linea" maxlength="5" ></td>
</tr>
<tr>
	<td align="left"  bgcolor="#e5e5e5" style="font-weight:bold">Descripción</td>
	<td><textarea style="resize: none;" name="despcripcion" id="despcripcion" cols="50"  rows="4" ></textarea></td>
</tr>
<tr>
	<td colspan="2" align='center'><input style="width:120px;" type="Submit" name="Guardar" value="Guardar" ></td>
</tr>
</table>


<div id="div_pas"></div>
<br>

</center>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>