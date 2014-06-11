<?php
	session_start();
?>
<HTML>
<HEAD><TITLE>Reportes</TITLE></HEAD>
<FRAMESET id="Superior" ROWS="83,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>"  NAME="Arriba" scrolling="NO">
   <FRAME SRC="about:blank" NAME="Abajo">
</FRAMESET><noframes></noframes>
</HTML>
