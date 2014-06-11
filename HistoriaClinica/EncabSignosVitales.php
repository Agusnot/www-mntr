<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Servicios)
	{
	}
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" action="ContSignosVitales.php" target="ContenidoSV">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<table align="center" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    <td align="center" >Servicios del paciente</td>
    </tr>
    <tr>
    <td align="center">
	<input type="radio" name="Servicios" value="Servicio Actual"<? if(!$Servicios||$Servicios=="Servicio Actual"){echo "checked";}?> onClick="document.FORMA1.Servicios.value=this.value;FORMA.submit();FORMA1.submit();"/>Servicio Actual
<input type="radio" name="Servicios" value="Todos los Servicios" <? if($Servicios=="Todos los Servicios"){echo "checked";}?> onClick="document.FORMA1.Servicios.value=this.value;FORMA.submit();FORMA1.submit();" />Todos los Servicios
	</td>
    </tr>
</table>
</form>
<form name="FORMA1" method="post" action="OpcionesSignosVitales.php" target="OpcionesSV">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="Servicios" value="<? echo $Servicios?>" />
</form>
</body>