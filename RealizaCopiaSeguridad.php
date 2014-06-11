<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();

	if($Generar)
	{
		///////ASIGNO DIRECTORIO INICIAL///////
		$RutaIni=substr($RutaCopia,0,2);
		chdir("$RutaIni");

		////QUITAMOS SLASH DEL FINAL////////
		$PFinal=substr($RutaCopia,strlen($RutaCopia)-1,1);
		if($PFinal=="/"){$RutaCopia=substr($RutaCopia,0,strlen($RutaCopia)-1);}

		////CREAMOS LA RUTA DE COPIA//////
		$Carpeta=explode("/",$RutaCopia);
		for($i=1;$i<=count($Carpeta)-1;$i++)
		{
			if(is_dir($Carpeta[$i])){chdir ("$Carpeta[$i]");}
			else{mkdir ("$Carpeta[$i]");chdir ("$Carpeta[$i]");}
		}

		/////COPIAMOS LA INFORMACION A LAS CARPETAS DESTINO /////////
		while (list($val,$cad) = each ($SelDB)) 
		{
			$BaseDatos=strtolower($cad);
			$cons1="Select table_name FROM information_schema.columns where table_schema='$BaseDatos' group by table_name Order By table_name ;";
			$res1=pg_query($cons1);
			while($fila1=ExFetch($res1))
			{
				$Tabla="$BaseDatos.$fila1[0]";
				if(is_file("$RutaCopia/$Tabla.csv")){unlink ("$RutaCopia/$Tabla.csv");}
				$cons2="Copy $BaseDatos.$fila1[0] to '$RutaCopia/$Tabla.csv' USING DELIMITERS ';' WITH NULL AS 'NULL'";
				$res2=ExQuery($cons2);
			}
		}
?>
		<script language="JavaScript">
			alert("Proceso Finalizado");
		</script>
<?
	}
	if($ND[hours]<10){$Horas="0".$ND[hours];}else{$Horas=$ND[hours];}
	if($ND[minutes]<10){$Minutos="0".$ND[minutes];}else{$Minutos=$ND[minutes];}
	$cons="Select Ruta from Central.RutaCopias where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$RutaInc=$fila[0];
?>
<body background="/Imgs/Fondo.jpg">
<br><br><br><br><br><center>
<form name="FORMA">
<table cellpadding="6"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td rowspan="2" style="color:white" bgcolor="<?echo $Estilo[1]?>"><strong>Copia de Seguridad</td>
<td colspan="3">En <input type="Text" style="width:400px;" name="RutaCopia" value="<?echo $RutaInc?>/<?echo "$ND[year]$ND[mon]$ND[mday]"?>/<?echo "$Horas$Minutos"?>/"></td>
<tr>

<td>Configuraciones<input type="Checkbox" name="SelDB[0]" value="Central" checked></td>
<td>Contabilidad <input type="Checkbox" name="SelDB[1]" value="Contabilidad" checked></td>
<td>Presupuesto<input type="Checkbox" name="SelDB[2]" value="Presupuesto" checked></td>
</tr>
<tr><td colspan="4" align="center"><input type="Submit" name="Generar" value="Generar Copia"></td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>