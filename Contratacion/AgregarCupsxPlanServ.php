<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and autoid=$Autoid";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NomPlan=$fila[0];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">       	
     <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="3"><? echo $NomPlan?></td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    <td>Codigo</td><td>Nombre</td>	<TD></TD>	
    </tr>
    <tr>
   	<td><input type="text" name="Codigo" style="width:70" value="<? echo $Codigo?>"
        onKeyUp="frames.CUPSxAgrearxPlanesServ.location.href='CUPSxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&Autoid=<? echo $Autoid?>&Nombre='+Nombre.value"
        onKeyDown="xLetra(this)"/></td>
       <td><input type="text" name="Nombre" style="width:630" value="<? echo $Nombre?>"
        onkeyup="frames.CUPSxAgrearxPlanesServ.location.href='CUPSxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>&Nit='+Codigo.value+'&Autoid=<? echo $Autoid?>&Nombre='+this.value" onKeyDown="xLetra(this)" /></td>      
         <td><button type="button" name="Regresar" onClick="parent(2).location.href='PlanesServicio.php?DatNameSID=<? echo $DatNameSID?>&Autoid=<? echo $Autoid?>&Clase=<? echo $Clase?>'"><img src="/Imgs/b_drop.png" title="Regresar"></button></td>
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="CUPSxAgrearxPlanesServ" src="CUPSxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
<?
	if($Identificacion && $Nombre)
	{
		?><script language="javascript">
        	frames.CUPSxAgrearxPlanesServ.location.href="CUPSxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Autoid=<? echo $Autoid?>&Nombre=<? echo $Nombre?>&Clase=<? echo $Clase?>";
        </script><?
	}
?>
</body>
</html>
