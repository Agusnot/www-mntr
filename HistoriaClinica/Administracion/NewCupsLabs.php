<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php")
?>	
<script language='javascript' src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table border="1" bordercolor="#e5e5e5" cellpadding="5" style="font-family:Tahoma; font-size:11px;" align="center"> 
	<tr align="center">
    	<td colspan="2"><input type="button" value="Agregar" onClick="frames.Busquedascups.FORMA.Guardar.value=1;frames.Busquedascups.FORMA.submit()">
        <input type="button" value="Cancelar" onClick="location.href='CupsLabs.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
    	</td>
   	</tr>
	<tr style="color:white; font-weight:bold"  bgcolor="<? echo $Estilo[1]?>">
    	<td><div class="style3">Codigo</div></td><td><div class="style3">Nombre</div></td>
    </tr>
    <tr>
    	<td><input type="text" name="Codigo" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.Busquedascups.location.href='BusqConsulCUPSLabs.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&Codigo='+this.value+'&Nombre='+Nombre.value" style="width:80" value="<? echo $Codigo?>">
      	</td>
        <td><input type="text" name="Nombre" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.Busquedascups.location.href='BusqConsulCUPSLabs.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&Codigo='+Codigo.value+'&Nombre='+this.value" style="width:460" value="<? echo $Nombre?>">
    </tr>
</table>
</form>
<iframe frameborder="0" id="Busquedascups" src="BusqConsulCUPSLabs.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>