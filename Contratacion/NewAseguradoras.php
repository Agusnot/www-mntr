<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	
	
	if($Guardar)
	{			
		if($Edit)
		{	
			$cons="Update Central.Terceros set Identificacion='$Identificacion',Primape='$Primape',Direccion='$Direccion',Telefono='$Telefono',Replegal='$Replegal',Codigosgsss='$Codigosgsss',Nomcontacto='$Nomcontacto',Telcontacto='$Telcontacto',Notas='$Notas',tipoasegurador='$TipoAseguramiento',usuariomod='$usuario[1]',copago=$Copago,cuotamoderadora=$CuotaModeradora where Identificacion='$IdentificacionAnt' and Compania='$Compania[0]'";
		}		
		//echo $cons;
		$res=ExQuery($cons);echo ExError();		
		?>
	    <script language="javascript">
	    location.href='Aseguradoras.php?DatNameSID=<? echo $DatNameSID?>&Idant=<? echo $Idant?>&Nombre=<? echo $Nombre?>';
       	</script>        
	     <?
	}
	
	if($Editar)
	{
		$cons="Select * from Central.Terceros where Identificacion='$Identificacion' and Compania='$Compania[0]'";
		$res=ExQuery($cons);		
		$fila=ExFetchArray($res); echo ExError();		
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">	
	function Validar()
	{
		
			if(document.FORMA.Primape.value=="" ||document.FORMA.Direccion.value=="" ||document.FORMA.Telefono.value==""||document.FORMA.Replegal.value==""||document.FORMA.Codigosgsss.value==""||document.FORMA.Nomcontacto.value==""||document.FORMA.Telcontacto.value=="")
			{
				alert("No deben quedar espacios en blanco!!!");return false;
			}
		
	}
</script>
<script language='javascript' src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">    
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Nit</td><td colspan="3"><input type="text" readonly name="Identificacion" value="<? echo $fila[0]?>"></td> 
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td><td><input type="text" style="width:500" name="Primape" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[1]?>"></td>
   </tr> 
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Direccion</td><td colspan="3"><input type="text" style="width:400" name="Direccion" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[6]?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Telefono</td><td><input type="text"  name="Telefono" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[7]?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Representate Legal</td><td colspan="3"><input style="width:400" type="text"  name="Replegal" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[5]?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Codigo SGSSS</td><td><input  type="text"  name="Codigosgsss" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['codigosgsss']?>"></td>
    </tr>
    <tr>    	
        <td bgcolor="#e5e5e5" style="font-weight:bold">Contacto</td><td colspan="3"><input  type="text" style="width:400" name="Nomcontacto" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"value="<? echo $fila['nomcontacto']?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Telefono</td><td><input  type="text"  name="Telcontacto" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['telcontacto']?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Aseguramiento</td>
    	<td><select name="TipoAseguramiento">
        <? 	$cons2="select * from central.tiposaseguramiento";
			$res2=ExQuery($cons2);
			while($fila2=ExFetchArray($res2)){
				if($fila2[0]==$fila['tipoasegurador']){
					echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
				}
				else{
					echo "<option value='$fila2[0]'>$fila2[0]</option>";
				}
			}?>	
            </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Copago</td>
        <td><select name="Copago">
        <?	echo "<option value='0'>No</option>";
			if($fila['copago']==1){
				echo "<option value='1' selected>Si</option>";
			}else{
				echo "<option value='1'>Si</option>";
			}?>
        	</select>
        </td>  
        <td bgcolor="#e5e5e5" style="font-weight:bold">Cuota Moderadora</td>
        <td><select name="CuotaModeradora">
        <?	echo "<option value='0'>No</option>";
			if($fila['cuotamoderadora']==1){
				echo "<option value='1' selected>Si</option>";
			}else{
				echo "<option value='1'>Si</option>";
			}?>
        	</select>
        </td>      
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Notas</td><td colspan="5"><textarea rows="6"  name="Notas"  style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $fila['notas']?></textarea></td>
    </tr>
    <tr>
    	<td align="center" colspan="11"><input type="submit" name="Guardar" value="Guardar"><input type="button" name="Cancelar" value="Cancelar" onClick=" location.href='Aseguradoras.php?DatNameSID=<? echo $DatNameSID?>&Idant=<? echo $Idant?>&Nombre=<? echo $Nombre?>';"></td>
    </tr>
</table>
 <input type="hidden" name="Edit" value="<? echo $Editar?>">
<input type="hidden" name="IdentificacionAnt" value="<? echo $Identificacion?>">
<input type="hidden" name="Idant" value="<? echo $Idant?>">
<input type="hidden" name="Nombre" value="<? echo $Nombre?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
