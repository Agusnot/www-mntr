<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Agregar)
	{
		if($Identificacion)
		{
			$cons = "Select Vinculacion from Infraestructura.VinculacionTros Where Compania='$Compania[0]' and Anio = $ND[year] and Tercero='$Identificacion'";
			$res = ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				$cons = "Insert into Infraestructura.TercerosxCC (Compania,Tercero,CC,Anio) 
				values ('$Compania[0]','$Identificacion','000',$ND[year])";
				$res = ExQuery($cons);
				$Ag=1;	
			}
			else
			{
				?><script language="javascript">alert("El tercero ya se ha ingresado anteriormente");</script><?	
			}	
		}	
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='110px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function AbrirCCxTercero(Tercero,Identificacion)
	{
		St = document.body.scrollTop;
		frames.FrameOpener.location.href="AutTercerosxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $ND[year]?>&Tercero="+Tercero+"&Identificacion="+Identificacion;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=St + 20;
		document.getElementById('FrameOpener').style.left='38px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='450';
	}
</script>
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr><td>Vinculaci&oacute;n de Terceros</td></tr>
<tr><td>Tercero Vinculaci&oacute;n</td></tr>
<tr><td><input type="Text" name="Tercero" style="width:100%" onFocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;" 
        onKeyUp="xLetra(this);Identificacion.value='';
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;"
        onKeyDown="xLetra(this)"/>
        <input type="hidden" name="Identificacion" />
	</td>
    <td><button type="submit" name="Agregar" title="Agregar" onClick="Ocultar()"><img src="/Imgs/b_save.png" /></button></td>
    <td bgcolor="#e5e5e5">&nbsp;</td></tr>
</table>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>