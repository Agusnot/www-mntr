<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	
	$ND=getdate();	
	if($Guardar){
		if(!$Edit){
			$cons2="select codigo from facturacion.codrespuestaglosa 
			where compania='$Compania[0]' and codigo='$ClaseGlosa'";
			$res2=ExQuery($cons2);
			//echo $cons2;
			if(ExNumRows($res2)<=0){
				$cons="insert into facturacion.codrespuestaglosa (codigo,detalle,compania) 
				values ('$ClaseGlosa','$NombreGlosa','$Compania[0]')";	
				$res=ExQuery($cons); echo ExError();
				?><script language="javascript">location.href="CodigoRtGlosa.php?DatNameSID=<? echo $DatNameSID?>";</script><?		
			}					
			else{
				?><script language="javascript">alert("Este codigo de la glosa ya ha sido registrado!!!");</script><?
				
			}
		}
		else{
			$cons2="select codigo from facturacion.codrespuestaglosa  
			where compania='$Compania[0]' and codigo!='$ClaseG' and codigo='$ClaseG'";
			$res2=ExQuery($cons2);
			//echo $cons2;
			if(ExNumRows($res2)<=0){
			$cons="update facturacion.codrespuestaglosa  set codigo='$ClaseGlosa',detalle='$NombreGlosa'
				where compania='$Compania[0]' and codigo='$ClaseG'";	
				$res=ExQuery($cons); echo ExError();
				?><script language="javascript">location.href="CodigoRtGlosa.php?DatNameSID=<? echo $DatNameSID?>";</script><?
			}		
			else{
				?><script language="javascript">alert("Este codigo de respuesta de la glosa ya ha sido registrado!!!");</script><?
				
			}	
		}		
	}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar(){
		if(document.FORMA.ClaseGlosa.value==""){alert("Debe digitar el codigo de glosa!!!"); return false;}
	}
	function evitarSubmit(evento)
	{
		if(document.all){ tecla = evento.keyCode;}
		else{ tecla = evento.which;}
		return(tecla != 13);
	}
	function Pasar(evento,proxCampo)
	{
		if(evento.keyCode == 13){document.getElementById(proxCampo).focus();}
	}
</script>

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table  style='font : normal normal small-caps 12px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2"> 
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
	<td width="144">Codigo</td>
	<td width="432">Nombre</td>
	</tr>
    <tr align="center">
    	<td>
        	<input type="text" name="ClaseGlosa" value="<? echo $ClaseG?>" onKeyPress="return evitarSubmit(event)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')"/>
      	</td>
		<td>
        	<input type="text" name="NombreGlosa" value="<? echo $ClaseI?>" onKeyPress="return evitarSubmit(event)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" size="100"/>
      	</td>
  	</tr>
	<tr align="center" >
    	<td colspan="2"><input type="submit" value="Guardar" name="Guardar" /><input type="button" value="Cancelar" onClick="location.href='CodigoRtGlosa.php?DatNameSID=<? echo $DatNameSID?>'"/></td>
  	</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="ClaseG" value="<? echo $ClaseG?>" />
</form>
</body>
</html>
