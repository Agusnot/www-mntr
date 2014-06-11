<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<HTML>
<HEAD><TITLE>Reportes</TITLE></HEAD>
<FRAMESET ROWS="150,*" FRAMEBORDER=0 Id='Reporteador' FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<?echo $Tipo?>" Id="Arriba"  NAME="Arriba" scrolling="NO">
   <FRAME SRC="" NAME="Abajo" Id="Abajo">
</FRAMESET><noframes></noframes>
</HTML>
