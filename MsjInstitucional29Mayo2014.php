<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		$cons="delete from central.msjinstitucional";
		$res=ExQuery($cons);		
		$cons="insert into central.msjinstitucional (mensaje,duracion) values ('$msj','$Duracion')";
		$res=ExQuery($cons);
	}
	$cons="select mensaje,duracion from central.msjinstitucional";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.msj.value==""){alert("Debe dijitar el mensaje!!!");return false;}
		if(document.FORMA.Duracion.value==""){alert("Debe digitar la duracion!!!");return false;}
	}
</script>
</head>

<body>
<script language="javascript" src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table BORDER="1"  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td>Mensaje Institucional</td>
	</tr>
    <tr>    	
    	<td><textarea name="msj" cols="80" rows="12" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="text-align:center"><? echo $fila[0]?></textarea></td>
    </tr>
    <tr align="center">
    	<td><strong>Duracion: </strong>
        	<input type="text" name="Duracion" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" style=" width:30" maxlength="2" value="<? echo $fila[1]?>"/>
        	segundos
        </td>
    </tr>
    <tr align="center">
    	<td><input type="submit" value="Guardar" name="Guardar" /></td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
</form>            
</html>
