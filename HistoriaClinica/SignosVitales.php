<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
	<title><? echo "$Sistema[$NoSistema] - Signos Vitales: $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"; ?></title>
</head>
<FRAMESET rows="12%,78%,10%" COLS="*" FRAMESPACING=0 FRAMEBORDER=0 BORDER=0 name="Principal">   	
    	<FRAME SRC="EncabSignosVitales.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>" NAME="EncabezadoSV" marginheight=8 marginwidth=8>
		<FRAME SRC="ContSignosVitales.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>" NAME="ContenidoSV" marginheight=8 marginwidth=8>
		<FRAME SRC="OpcionesSignosVitales.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>" NAME="OpcionesSV" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>	
</body>
</html>
