<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	session_register("PeriodoxFormatos");

	$ND=getdate();
	$at_urgencias=$_GET['at_urgencias'];
	if($at_urgencias!=null){
		$consA="update salud.salasintriage set estado=0, atender_psiqui=1, psiquiatra='$usuario[1]' where cedula='$Pacie' and estado=1";
		$resA=ExQuery($consA);
	}

	$cons="Select FechaIng,FechaEgr from Salud.Servicios where Cedula='$Paciente[1]' Order By NumServicio Desc";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$PeriodoxFormatos="$fila[0]|$fila[1]";
	if($Pacie){$Paciente[1]=$Pacie;}
?>
<html>
<head>
<title><? echo $Sistema[$NoSistema]?></title>
</head>    
<FRAMESET COLS="210,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0 id="OpcIzquierda"  >
   <FRAME SRC="Principal.php?DatNameSID=<? echo $DatNameSID?>&Pacie=<? echo $Pacie?>&Reabrir=<? echo $Reabrir?>&NoCerrar=<?echo $NoCerrar?>" NAME="opciones" marginheight=8 marginwidth=8 SCROLLING=no>
	<FRAMESET ROWS="130,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
	   <FRAME SRC="/HistoriaClinica/Encabezado.php?DatNameSID=<? echo $DatNameSID?>&Pacie=<? echo $Pacie?>" NAME="Encabezados" marginheight=8 marginwidth=8 scrolling="no">
	   <FRAME SRC="/HistoriaClinica/Formatos_Fijos/FichaIdentificacion.php?DatNameSID=<? echo $DatNameSID?>&Pacie=<? echo $Pacie?>&cedula=<? echo $_GET['cedula'];?>" NAME="Datos" marginheight=8 marginwidth=8>
	</FRAMESET>
</FRAMESET><noframes></noframes>

</body>
</html>
