<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<HTML>
<HEAD><TITLE>Listado de existencias</TITLE></HEAD>
<FRAMESET ROWS="15%,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabInfHC.php?DatNameSID=<? echo $DatNameSID?>"  NAME="Arriba" marginheight=8 marginwidth=8 SCROLLING=no>
   <FRAME SRC="RptInfHC.php?DatNameSID=<? echo $DatNameSID?>" NAME="Abajo" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>
</HTML>
