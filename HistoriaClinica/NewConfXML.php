<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		if($Edit)
		{
			$cons="update historiaclinica.formatosxml set formatoxml='$EtiquetaXML' where compania='$Compania[0]' and codigoxml=$CodXMLAnt and formatoxml='$EtiquetaXMLAnt'";
		}
		else
		{
			$cons="select codigoxml from historiaclinica.formatosxml where compania='$Compania[0]' order by codigoxml desc";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$AutoIdCod=$fila[0]+1;
			$cons="insert into historiaclinica.formatosxml (formatoxml,codigoxml,compania) values ('$EtiquetaXML',$AutoIdCod,'$Compania[0]')";	
		}
		$res=ExQuery($cons);?>
		<script language="javascript">
			location.href='ConfXML.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
<?	}	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" cellpadding="3"> 
		<tr align="center">
    		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre Formato XML</td>
	  	</tr>
        <tr align="center">
    		<td><input type="text" name="EtiquetaXML" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" maxlength="99" value="<? echo $EtiquetaXML?>"></td>
	  	</tr>
        <tr>
        	<td><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfXML.php?DatNameSID=<? echo $DatNameSID?>'"></td>
        </tr>
	</table>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
    <input type="hidden" name="CodXMLAnt" value="<? echo $CodXML?>">
    <input type="hidden" name="EtiquetaXMLAnt" value="<? echo $EtiquetaXML?>">
    <input type="hidden" name="Edit" value="<? echo $CodXML?>">
</form>            
</body>
</html>
