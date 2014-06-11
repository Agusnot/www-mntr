<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons="Insert into Salud.Cargos(Cargos,Asistencial,tratante,vistobuenojefe,vistobuenoaux,Compania,interpretar,asigrutaimg,autorizaegr)
			values ('$Cargo','$Asistencial',$Tratante,$VBJefe,$VBAuxiliar,'$Compania[0]',$Interpretar,$AsigRuta,$AutorizaEgr)";
		}
		else
		{
			$cons="Update Salud.Cargos 
			set Cargos='$Cargo',Asistencial='$Asistencial',tratante=$Tratante,vistobuenojefe=$VBJefe,vistobuenoaux=$VBAuxiliar,interpretar=$Interpretar,asigrutaimg=$AsigRuta
			,autorizaegr=$AutorizaEgr where Cargos='$CargoAnt' and Compania='$Compania[0]'";
		}
		$res=ExQuery($cons);echo ExError();
		//echo $cons;		
		?>
        <script language="javascript">
	        location.href='ConfCargos.php?DatNameSID=<? echo $DatNameSID?>';
        </script>
        <?
	}
	if($Edit)
	{
		$cons="Select * from Salud.Cargos where Cargos='$Cargo' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
	}

	if(!$Edit){$fila['asistencial']=1;}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<script language="javascript">
	function salir(){
		 location.href='ConfCargos.php?DatNameSID=<? echo $DatNameSID?>';
	}
	function Validar()
	{
		if(document.FORMA.Cargo.value=="")
		{
			alert("Debe ingresar un cargo!!!");return false;
		}
	}
</script>

<script language='javascript' src="/Funciones.js"></script>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Cargo</td>
        <td><input type="text" maxlength="30" name="Cargo" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $Cargo?>"></td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Asistencial</td><td>
    	  <select name="Asistencial">
          <? if($fila['asistencial']==1){?>
          		<option value="1" selected>Si</option>
	    	    <option value="0">No</option><? }?>

          <? if($fila['asistencial']==0){?>
          		<option value="1">Si</option>
	    	    <option value="0" selected>No</option><? }?>

          </select>
    	</td>
 	</tr>
     <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Tratante</td><td>
    	  <select name="Tratante">
          <? if($fila['tratante']==1){?>
          		<option value="1" selected>Si</option>
	    	    <option value="0">No</option><? }?>

          <? if($fila['tratante']==0){?>
          		<option value="1">Si</option>
	    	    <option value="0" selected>No</option><? }?>

          </select>
    	</td>
 	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Visto Bueno Jefe</td><td>
    	  <select name="VBJefe">
          <? if($fila['vistobuenojefe']==1){?>
          		<option value="1" selected>Si</option>
	    	    <option value="0">No</option><? }?>

          <? if($fila['vistobuenojefe']==0){?>
          		<option value="1">Si</option>
	    	    <option value="0" selected>No</option><? }?>

          </select>
    	</td>
 	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Visto Bueno Auxiliar</td><td>
    	  <select name="VBAuxiliar">
          <? if($fila['vistobuenoaux']==1){?>
          		<option value="1" selected>Si</option>
	    	    <option value="0">No</option><? }?>

          <? if($fila['vistobuenoaux']==0){?>
          		<option value="1">Si</option>
	    	    <option value="0" selected>No</option><? }?>

          </select>
    	</td>
 	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Interpretar Procedimiento</td><td>
    	  <select name="Interpretar">
          <? if($fila['interpretar']==1){?>
          		<option value="1" selected>Si</option>
	    	    <option value="0">No</option><? }?>

          <? if($fila['interpretar']==0){?>
          		<option value="1">Si</option>
	    	    <option value="0" selected>No</option><? }?>

          </select>
    	</td>
 	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Asignar Imagen Procedimiento</td><td>
    	  <select name="AsigRuta">
          <? if($fila['asigrutaimg']==1){?>
          		<option value="1" selected>Si</option>
	    	    <option value="0">No</option><? }?>

          <? if($fila['asigrutaimg']==0){?>
          		<option value="1">Si</option>
	    	    <option value="0" selected>No</option><? }?>

          </select>
    	</td>
 	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Autoriza Egreso</td><td>
    	  <select name="AutorizaEgr">
          <? if($fila['autorizaegr']==1){?>
          		<option value="1" selected>Si</option>
	    	    <option value="0">No</option><? }?>

          <? if($fila['autorizaegr']==0){?>
          		<option value="1">Si</option>
	    	    <option value="0" selected>No</option><? }?>

          </select>
    	</td>
 	</tr>
    <tr>
    	<td colspan="2" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="salir()"></td></tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="CargoAnt" value="<? echo $Cargo?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
