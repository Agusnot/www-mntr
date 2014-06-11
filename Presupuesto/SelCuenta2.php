<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<frameset rows="90,*" frameborder="no" border="0" framespacing="0">
<frame src="EncabSelCuenta2.php?DatNameSID=<? echo $DatNameSID?>" name="Arriba" scrolling="no">
<frame src="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>" name="Abajo">
