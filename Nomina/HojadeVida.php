<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
        //echo $Identificacion;
        //echo $DatNameSID;
        $ND=getdate();

	$cons="Select primape,segape,primnom,segnom from central.terceros where Identificacion='$Identificacion'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	
	//if($Emple){$Emple[1]=$Emple;}
?>
<script language='JavaScript'>
//		alert("<? echo $NoEmpleado;?>");
</script>
        
<html>
<head>
<title>Compuconta Software</title>
</head>    
<FRAMESET COLS="210,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0 id="Principal">
   <FRAME SRC="Principal.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>" NAME="opciones" marginheight=8 marginwidth=8 SCROLLING=no>
	<FRAMESET ROWS="100,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
	   <FRAME SRC="/Nomina/Encabezado.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NoEmpleado=<? echo $NoEmpleado?>&Estado=<? echo $Estado?>" NAME="Encabezados" marginheight=8 marginwidth=8>
	   <FRAME SRC="/Nomina/DatosPersonales.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&PermiteCrear=<? echo $PermiteCrear?>" NAME="Datos" id="Datos" marginheight=8 marginwidth=8>
	</FRAMESET>
</FRAMESET><noframes></noframes>

</body>
</html>
