<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<script language="javascript" src="/Funciones.js"></script>
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
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Codigo.focus()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post">
	<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;'>    	
        <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
        	<td>Codigo</td><td>Procedimiento</td>
        </tr>
        <tr>
        	<td><input type="text" name="Codigo" style="width:100" 
            onkeyup="xLetra(this);frames.BusqProcedimientos.location.href='BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD2=<? echo $TMPCOD2?>&Codigo='+this.value+'&Nombre='+FORMA.Nombre.value;" onKeyDown="xLetra(this)"/></td>
            <td><input type="text" name="Nombre" style="width:430"
            onkeyup="xLetra(this);frames.BusqProcedimientos.location.href='BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD2=<? echo $TMPCOD2?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;"
            onFocus="frames.BusqProcedimientos.location.href='BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD2=<? echo $TMPCOD2?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;" onKeyDown="xLetra(this)" /></td>
        </tr>
        <tr align="center">
        	<td colspan="2"><input type="button" value="Agregar" 
            onClick="frames.BusqProcedimientos.document.FORMA.Guardar.value=1;frames.BusqProcedimientos.document.FORMA.submit();"></td>
        </tr>
    </table>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="BusqProcedimientos" name="BusqProcedimientos" frameborder="0" width="100%" height="75%" src="BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>"></iframe>
</body>