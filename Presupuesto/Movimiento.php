<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<HTML>
<HEAD><TITLE>Administrador Hospitalario - Contabilidad</TITLE></HEAD>
<FRAMESET ROWS="106,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="EncabMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&MesI=<?echo $Mes?>&Tipo=<?echo $Tipo?>"  NAME="opciones" marginheight=8 marginwidth=8 SCROLLING=no>
   <FRAME SRC="ListaMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&MesI=<?echo $Mes?>&Tipo=<?echo $Tipo?>#<?echo $Numero?>" NAME="Abajo" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>
</HTML>
