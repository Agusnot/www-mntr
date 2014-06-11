<?php
	session_start();
?>
<HTML>
<HEAD><TITLE>Reportes</TITLE></HEAD>
<FRAMESET ROWS="90,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabReportes.php?DatNameSID=<?echo $DatNameSID?>&Tipo=<?echo $Tipo?>"  NAME="Arriba" marginheight=8 marginwidth=8 SCROLLING=no>
   <FRAME SRC="" NAME="Abajo" marginheight=8 marginwidth=8>
</FRAMESET>
</HTML>
