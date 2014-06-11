<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
	<title><? echo "$Sistema[$NoSistema] - Control Liquidos: $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"; ?></title>
</head>
<FRAMESET rows="12%,78%,10%" COLS="*" FRAMESPACING=0 FRAMEBORDER=0 BORDER=0 name="Principal">   	
    	<FRAME SRC="EncabControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>" NAME="EncabezadoCL" marginheight=8 marginwidth=8>
		<FRAME SRC="ContControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>" NAME="ContenidoCL" marginheight=8 marginwidth=8>
		<FRAME SRC="OpcionesControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>" NAME="OpcionesCL" marginheight=8 marginwidth=8>
</FRAMESET><noframes></noframes>	
</body>
</html>
