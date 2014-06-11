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
   	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Almacen Principal</td><td><select name="AlmacenPrincipal">
        <? 	$cons="Select almacenppal from consumo.almacenesppales where Compania='$Compania[0]' and ssfarmaceutico=1";
			$res = ExQuery($cons);
			while($fila = ExFetch($res)){?>
            
				<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
		<?	}
			
		?>
        </select></td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    <td>Codigo</td><td>Nombre</td>		
    </tr>
    <tr align="center">
   	<td><input type="text" name="Codigo" style="width:70" value="<? echo $Codigo?>"
        onKeyDown="xLetra(this)" onKeyUp="xLetra(this);frames.CUPSxAgrearxPlanesServ.location.href='MedicamentosxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPrincipal='+AlmacenPrincipal.value+'&Codigo='+this.value+'&Autoid=<? echo $Autoid?>&Nombre='+Nombre.value"
        /></td>
       <td><input type="text" name="Nombre" style="width:630" value="<? echo $Nombre?>"
         onKeyDown="xLetra(this)" onKeyUp="xLetra(this);frames.CUPSxAgrearxPlanesServ.location.href='MedicamentosxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPrincipal='+AlmacenPrincipal.value+'&Codigo='+Codigo.value+'&Autoid=<? echo $Autoid?>&Nombre='+this.value" /></td>      
         <td><button type="button" name="Regresar" onClick="parent(2).location.href='PlanesServicio.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Autoid=<? echo $Autoid?>'"><img src="/Imgs/b_drop.png" title="Regresar"></button></td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="CUPSxAgrearxPlanesServ" src="MedicamentosxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
<?
	if($Identificacion && $Nombre)
	{
		?><script language="javascript">
        	frames.CUPSxAgrearxPlanesServ.location.href="MedicamentosxAgrearxPlanesServ.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Autoid=<? echo $Autoid?>&Nombre=<? echo $Nombre?>";
        </script><?
	}
?>
</body>
</html>