<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();	
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

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table border="1" align="center" style="font-family:Tahoma; font-size:11px; font-variant:normal" width="100%" height="100%">
    <tr><td><input type="Button" value="Fecha Actual" onClick="Defecto.value=Defecto.value+'AHORA';Defecto.focus()">
    <input type="Button" value="Edad" onClick="Defecto.value=Defecto.value+'EDADDEF';Defecto.focus()">
    <input type="Button" value="Sexo" onClick="Defecto.value=Defecto.value+'SEXODEF';Defecto.focus()">
    <input type="Button" value="Ocupacion" onClick="Defecto.value=Defecto.value+'OCUPADEF';Defecto.focus()">
    <input type="Button" value="Residente" onClick="Defecto.value=Defecto.value+'RESIDEF';Defecto.focus()"></td></tr>

    	<tr align="center"><td bgcolor="#CCCCCC"><strong>Valor x Defecto</strong></td></tr>
        <tr><td><textarea name="Defecto" style="width:100%; height:100px"><? echo $Defecto?></textarea></td></tr>
        <tr align="center"><td bgcolor="#CCCCCC"><input type="Button" value="Guardar" name="Guardar" onClick="parent.document.FORMA.Defecto.value=Defecto.value;CerrarThis()"></td></tr>
     </table>
     <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
