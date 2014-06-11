<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	session_register("Empleado");
?>
	<FRAMESET ROWS="90,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
	   <FRAME SRC="EncabBusHojaVida.php?DatNameSID=<? echo $DatNameSID?>" NAME="Encabezados" marginheight=8 marginwidth=8>
	   <FRAME SRC="ResultBusHojaVida.php?DatNameSID=<? echo $DatNameSID?>>" NAME="Abajo" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>