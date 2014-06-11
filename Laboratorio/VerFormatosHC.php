<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons9="Select * from Central.Terceros where Identificacion='$CedPac' and compania='$Compania[0]'";
	//echo $cons9;
	$res9=ExQuery($cons9);echo ExError();
	$fila9=ExFetch($res9);

	$Paciente[1]=$fila9[0];
	$n=1;
	for($i=1;$i<=ExNumFields($res9);$i++)
	{
		$n++;
		$Paciente[$n]=$fila9[$i];
		//echo "<br>$n=$Paciente[$n]";
	}
	session_register("Paciente");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<?

?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>"  align="center">
<tr bgcolor="#e5e5e5" align="center">
<?	$cons="select nombre from contratacionsalud.cups where compania='$Compania[0]' and codigo='$CUP'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);?>
	<td>FORMATOS DE LABORATORIO RELACIONADOS AL PROCEDIMIENTO: <strong><? echo strtoupper("$CUP - $fila[0]");?></strong></td>        	
</tr>
<?	$cons="select cupslabs.formato,cupslabs.tipoformato from historiaclinica.cupslabs,historiaclinica.formatos where cup = '$CUP' 
	and cupslabs.compania='$Compania[0]' and formatos.compania='$Compania[0]' and formatos.formato=cupslabs.formato and formatos.tipoformato=cupslabs.tipoformato 
	and estado='AC' order by cupslabs.tipoformato,cupslabs.formato";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{?>
        <tr>
            <td>
                <input type="radio" name="Formatos" onClick="location.href='/HistoriaClinica/NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $fila[0]?>&TipoFormato=<? echo $fila[1]?>&SoloUno=1&CUPProced=<? echo $CUP?>&FechaProced=<? echo $Fecha?>&NumSerProced=<? echo $NumSer?>&NumProced=<? echo $NumProced?>'"><? echo "$fila[1] - $fila[0]";?>
            </td>
        </tr>	
	<?		
	}	?>        
</table>
</form>
</body>
</html>
