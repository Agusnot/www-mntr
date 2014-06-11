<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
	<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}	
</script>
<?
	if($Guardar)
	{
		$ND=getdate();
		if($NC){$NoC=1;}else{$NoC=0;}
		if($Baja||$FN==1){$Salida=",fechasalida='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',usuariosalida='$usuario[1]'";}
		$cons="update salud.elementoscustodia set nota='$Nota',estado='$EstadoElto',observacion='$Observacion',nc=$NoC$Salida where cedula='$Ced' and compania='$Compania[0]' and numservicio='$NumServ' and elemento='$Elemento'";
		$res=ExQuery($cons);echo ExError();
		//echo $cons;	
		?><script language="javascript">
			CerrarThis();
			parent.location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UnidadHosp?>';
		</script>
        <?
	}	
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language='javascript' src="/Funciones.js"></script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">	
    <tr><td align="center" colspan="2">No Conformidad <input type="checkbox" name="NC" /></td></tr>
	<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="2">Observacion</td></tr>
    <tr>
    	<td colspan="2"><input type="text" style="height:50px; width:220px" name="Observacion" id="Observacion" 
        	onkeydown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Nota')" onKeyPress="return evitarSubmit(event)"/></td>
   	</tr>
    <tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="2">Nota</td></tr>
    <tr>
    	<td colspan="2"><input type="text" style="height:50px; width:220px" name="Nota" id="Nota" 
        	onkeydown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)"/></td>
   	</tr>	
    <tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Estado Elemento </td>

    	<td><select name="EstadoElto">
          	<option value="Bueno">Bueno</option>
		    <option value="Regular">Regular</option>
            <option value="Malo">Malo</option>
      	</select></td>
   	</tr>	
    <tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Baja </td>
    	<td align="center"><input type="checkbox" name="Baja" <? if($FN==1){?> checked="checked" disabled="disabled"<? }?> />
    <tr><td align="center" colspan="2"><input type="submit" value="Guardar" name="Guardar" id="Guardar"/><input style="width:70px" type="button"  value="Cerrar" onclick="CerrarThis()"</td></tr>
</table>
<input type="hidden" name="Ced" value="<? echo $Ced?>">
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="Elemento" value="<? echo $Elemento?>" />
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="UndHosp" value="<? echo $UndHosp?>">
<input type="hidden" name="FN" value="<? echo $FN?>">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
