<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	
	
	if($Guardar)
	{			
		if($Edit)
		{	
			$cons="Update Central.Terceros set Identificacion='$Identificacion',Primape='$Primape',Direccion='$Direccion',Telefono='$Telefono',Replegal='$Replegal',Codigosgsss='$Codigosgsss',Nomcontacto='$Nomcontacto',Telcontacto='$Telcontacto',Notas='$Notas' where Identificacion='$IdentificacionAnt' and Compania='$Compania[0]'";
		}		
		//echo $cons;
		$res=ExQuery($cons);echo ExError();		
		?>
	    <script language="javascript">
	    location.href='Pensiones.php?DatNameSID=<? echo $DatNameSID?>&Idant=<? echo $Idant?>&Nombre=<? echo $Nombre?>';
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
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="50%">
    <tr><td colspan=4 bgcolor="#666699" style="color:white" align="center"><? echo $fila[1]?></td></tr>
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Nit</td><td><input type="text" style="width:200" readonly name="Identificacion" value="<? echo $fila[0]?>"></td> 
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td><td><input type="text" style="width:300" name="Primape" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[1]?>"></td>
   </tr> 
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Direccion</td><td><input type="text" style="width:200" name="Direccion" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[6]?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Telefono</td><td><input type="text" style="width:300" name="Telefono" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[7]?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Representate Legal</td><td><input style="width:200" type="text"  name="Replegal" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[5]?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Codigo SGSSS</td><td><input  type="text" style="width:300"  name="Codigosgsss" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['codigosgsss']?>"></td>
    </tr>
    <tr>    	
        <td bgcolor="#e5e5e5" style="font-weight:bold">Contacto</td><td><input  type="text" style="width:200" name="Nomcontacto" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"value="<? echo $fila['nomcontacto']?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Telefono</td><td><input  type="text" style="width:300"  name="Telcontacto" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila['telcontacto']?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Notas</td><td colspan="4"><textarea rows="6"  name="Notas"  style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $fila['notas']?></textarea></td>
    </tr>
    <tr>
    	<td align="center" colspan="11"><input type="submit" name="Guardar" value="Guardar"><input type="button" name="Cancelar" value="Cancelar" onClick=" location.href='Pensiones.php?DatNameSID=<? echo $DatNameSID?>&Idant=<? echo $Idant?>&Nombre=<? echo $Nombre?>';"></td>
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
