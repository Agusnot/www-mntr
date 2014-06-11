<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){		
		$cons="delete from odontologia.rutaimgs where compania='$Compania[0]'";
		$res=ExQuery($cons);
		$Aux=str_replace('\\','/',$Ruta);	
		$cons="insert into odontologia.rutaimgs (compania,ruta) values ('$Compania[0]','$Aux')";
		//echo $cons;
		$res=ExQuery($cons);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Ruta.value=="")
		{
			alert("Debe digitar un ruta!!!");return false;
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" enctype="multipart/form-data" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'> 
	<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Ruta Imagenes Odontograma</td></tr>
<?
	$cons="select ruta from odontologia.rutaimgs where compania='$Compania[0]'";
	$res=ExQuery($cons); $fila=ExFetch($res);
?>    
    <tr><td align="center"><input type="text" name="Ruta"  style="width:500" value="<? echo $fila[0]?>"></td></tr>
    <tr><td align="center"><input type="submit" value="Guardar" name="Guardar"></td></tr>
</table>
</form>
</body>
</html>