<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<HTML>
<HEAD><TITLE>Terceros</TITLE></HEAD>
<FRAMESET ROWS="130,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabTerceros.php?DatNameSID=<? echo $DatNameSID?>&ModOrigen=<? echo $ModOrigen?>"  NAME="Arriba" marginheight=8 marginwidth=8 SCROLLING=no>
   <FRAME SRC="nada.html?DatNameSID=<? echo $DatNameSID?>" NAME="Abajo" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>
</HTML>
