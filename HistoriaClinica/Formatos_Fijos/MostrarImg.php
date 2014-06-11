<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<table align="center">
<?
$cons="select rutaimg from salud.plantillaprocedimientos where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$Numserv and numprocedimiento=$NumProced";
//echo $cons;
$res=ExQuery($cons);
$fila=ExFetch($res);
$RutaImg=explode(".",$fila[0]);
//echo $RutaImg[1];
?>
<tr>
	<? 
		if($RutaImg[1]=="pdf"){?>
        	<td width="100%">
                <div style="width:100%">
                    <iframe frameborder="0" id="PDF" src="<? echo $fila[0]?>" width="800" height="650">
                    </iframe>
                </div>
	<?	}
		else{?>
        	<td>
				<img src="<? echo $fila[0]?>">
  	<?	}?>
   	</td>
</tr>
<tr>
	<td align="center">
    	<input type="button" value="Regresar" onClick="location.href='AyudasDiagnosticas.php?DatNameSID=<? echo $DatNameSID?>'">
    </td>
</tr>
</table>
</body>
</html>
