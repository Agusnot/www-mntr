<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	session_register("Empleado");
?>
	<FRAMESET ROWS="110,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
	   <FRAME SRC="EncabAbrirMes.php?DatNameSID=<? echo $DatNameSID;?>" NAME="Encabezados" marginheight=0 marginwidth=0>
	   <FRAME SRC="CuerAbrirMes.php?DatNameSID=<? echo $DatNameSID;?>" NAME="Cuerpo" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>