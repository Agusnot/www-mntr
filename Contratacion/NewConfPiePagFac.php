<?	
    if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){
		if(!$Edit){
			$cons="insert into facturacion.notaspiepag (compania,codigo,nota) values ('$Compania[0]',$Codigo,'$Nota')";
		}
		else{
			$cons="update facturacion.notaspiepag set codigo=$Codigo,nota='$Nota' where compania='$Compania[0]' and codigo=$CodigoAnt";
		}
		$res=ExQuery($cons);
	?>
    	<script language="javascript">
			location.href='ConfPiePagFac.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
    <?
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Codigo.value==""){alert("Debe digitar el codigo!!!");return false;}
		if(document.FORMA.Nota.value==""){alert("Debe digitar la nota!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
        <td><input type="text" name="Codigo" value="<? echo $Codigo?>" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" style="width:30"/></td>
  	</tr>
    <tr>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Codigo</td>
        <td><textarea name="Nota" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" cols="100" rows="5"><? echo $Nota?></textarea></td>
    </tr>
    <tr align="center">
    	<td colspan="2">
        	<input type="submit" value="Guardar" name="Guardar"/>
            <input type="button" value="Cancelar" onClick="location.href='ConfPiePagFac.php?DatNameSID=<? echo $DatNameSID?>'"/>
        </td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="CodigoAnt" value="<? echo $Codigo?>"/>
<input type="hidden" name="Edit" value="<? echo $Edit?>" />
</form>
</body>
</html>
