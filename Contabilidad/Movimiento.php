	
	<?php
		include_once("General/Configuracion/Configuracion.php");
		include_once("General/Configuracion/Configuracion.php");
	?>
	
	
	
	<html>	
			<FRAMESET ROWS="150px,*" FRAMEBORDER=0 framespacing=0 BORDER=0>
			   <FRAME SRC="EncabMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&MesI=<?echo $Mes?>&Tipo=<?echo $Tipo?>"  NAME="opciones" marginheight=8 marginwidth=8 SCROLLING=no>
			   <FRAME SRC="ListaMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&MesI=<?echo $Mes?>&Tipo=<?echo $Tipo?>#<?echo $Numero?>" NAME="Abajo" marginheight=8 marginwidth=8>
			</FRAMESET><noframes></noframes>
		
	</html>
