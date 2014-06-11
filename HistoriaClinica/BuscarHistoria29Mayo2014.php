<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	session_register("Paciente");
?>
	<FRAMESET ROWS="90,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
	   <FRAME SRC="EncabBuscarHC.php?UnidadIraLista=<? echo $UnidadIraLista?>&DatNameSID=<? echo $DatNameSID?>" NAME="Encabezados" marginheight=8 marginwidth=8>
	   <FRAME SRC="ResultBuscarHC.php?UnidadIraLista=<? echo $UnidadIraLista?>&DatNameSID=<? echo $DatNameSID?>#<? echo $Paciente[1]?>" NAME="Abajo" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>