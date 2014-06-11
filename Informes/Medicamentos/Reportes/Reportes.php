<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<HTML>
<HEAD><TITLE>Reportes</TITLE></HEAD>
<FRAMESET ROWS="20%,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>" NAME="Arriba" scrolling="NO">   
   <FRAME SRC="blanco.php" NAME="Abajo" ID="Abajo">
</FRAMESET><noframes></noframes>
</HTML>