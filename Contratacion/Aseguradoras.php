<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	global $Ban;
	$Ban=0;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">    
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Nit</td><td>Nombre</td>		
    </tr>
    <tr>
   	<td><input type="text" name="Identificacion" style="width:70" value="<? echo $Idant?>"
        onKeyUp="frames.BusquedaAseguradoras.location.href='BusquedaAseguradoras.php?DatNameSID=<? echo $DatNameSID?>&Identificacion='+this.value+'&Nombre='+Nombre.value"
        onKeyDown="xLetra(this)"/></td>        
       <td><input type="text" name="Nombre" style="width:630" value="<? echo $Nombre?>"
        onkeyup="frames.BusquedaAseguradoras.location.href='BusquedaAseguradoras.php?DatNameSID=<? echo $DatNameSID?>&Nit='+Identificacion.value+'&Nombre='+this.value" onKeyDown="xLetra(this)" /></td>      
        <? if(($Nombreant||$Idant)&&$Ban==0){		
			?><script language="javascript">
				
				frames.BusquedaAseguradoras.location.href="BusquedaAseguradoras.php?DatNameSID=<? echo $DatNameSID?>&Nit='+Identificacion.value+'&Nombre='+Nombre.value";
			</script>
            <? $ban=1;
		}?>
    </tr>
    </table>
    <input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="BusquedaAseguradoras" src="BusquedaAseguradoras.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Idant?>&Nombre=<? echo $Nombre?>" width="100%" height="85%"></iframe>
</body>
</html>