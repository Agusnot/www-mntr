<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">    
   	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    <td>Codigo</td><td>Nombre</td>		
    </tr>
    <tr>
   	<td><input type="text" name="Codigo" style="width:70" value="<? echo $Codigo?>"
        onKeyUp="xLetra(this);frames.NewConsExtr.location.href='NewConsExtr.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&Cargo=<? echo $Cargo?>&Nombre='+Nombre.value"
        onKeyDown="xLetra(this)"/></td>
       <td><input type="text" name="Nombre" style="width:630" value="<? echo $Nombre?>"
        onkeyup="xLetra(this);frames.NewConsExtr.location.href='NewConsExtr.php?DatNameSID=<? echo $DatNameSID?>&Nit='+Codigo.value+'&Cargo=<? echo $Cargo?>&Nombre='+this.value" onKeyDown="xLetra(this)" /></td>      
         <td><button type="button" name="Regresar" onClick="parent(2).location.href='ConfConsulExtr.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>'"><img src="/Imgs/b_drop.png" title="Regresar"></button></td>
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="NewConsExtr" src="NewConsExtr.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
<?
	if($Cargo && $Nombre)
	{
		?><script language="javascript">
        	frames.NewConsExtr.location.href="NewConsExtr.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Cargo=<? echo $Clase?>";
        </script><?
	}
?>
</body>
</html>
