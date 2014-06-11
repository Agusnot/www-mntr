<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		if(!$Edit){
			$cons="insert into salud.tiposdiagnostico(compania,codigo,tipodiagnost) values ('$Compania[0]','$Codigo','$Nombre')";		
		}
		else{
			$cons="update salud.tiposdiagnostico set codigo='$Codigo',tipodiagnost='$Nombre' where codigo='$CodigoAnt'";
		}
		$res=ExQuery($cons);echo ExError();
	?>	<script language="javascript">
			location.href='ConfTiposDx.php?DatNameSID=<? echo $DatNameSID?>';
		</script><?
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">	
function Validar(){
	if(document.FORMA.Codigo.value==""){
		alert("Debe digitar un Codigo!!!");return false;
	}
	if(document.FORMA.Nombre.value==""){
		alert("Debe digitar un Nombre!!!");return false;
	}						
}
</script>
<script language='javascript' src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
     <tr>
    	<td colspan="2" align="center" bgcolor="#e5e5e5" style=" font-weight:bold">Nuevo Tipo de Diagnostico</td>
  	</tr>
    <tr>
    	<td align="center" bgcolor="#e5e5e5" style=" font-weight:bold">Codigo</td><td><input type="text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Codigo" value="<? echo $Codigo?>"></td>
	</tr>
    <tr>
        <td align="center" bgcolor="#e5e5e5" style=" font-weight:bold">Nombre</td><td><input type="text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Nombre" value="<? echo $Nombre?>"</td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfTiposDx.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="CodigoAnt" value="<? echo $Codigo?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
</body>
</html>
