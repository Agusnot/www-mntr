<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Dia=$ND[mday];
	$Mes=$ND[mon];
	$Anio=$ND[year];
	$Horas=$ND[hours];
	$Minutos=$ND[minutes];
	$Segundos=$ND[seconds];	
	if($Guardar)
	{
		$cons = "Insert Into HistoriaClinica.NotasCambioTurno (Compania,Fecha,Usuario,Nota,Unidad) 
		Values('$Compania[0]','$Anio-$Mes-$Dia $Horas:$Minutos:$Segundos','$usuario[0]','$Anotacion','$SelUnidad')";
		$res = ExQuery($cons);
		?><script language='JavaScript'>location.href='NotasCambioTurno.php?DatNameSID=<? echo $DatNameSID?>&SelUnidad=<? echo $SelUnidad?>';</script>
		<?
	}
?>
<head>
<script language="javascript" src="/salud/funciones.js">
</script>
<script language="JavaScript">
function ltrim(str) { 
	for(var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++);
	return str.substring(k, str.length);
}
function rtrim(str) {
	for(var j=str.length-1; j>=0 && isWhitespace(str.charAt(j)) ; j--) ;
	return str.substring(0,j+1);
}
function trim(str) {
	return ltrim(rtrim(str));
}
function isWhitespace(charToCheck) {
	var whitespaceChars = " \t\n\r\f";
	return (whitespaceChars.indexOf(charToCheck) != -1);
}
function Validar()
{
	str=trim(document.FORMA.Anotacion.value);	
	if(str==""){alert("Por Favor Ingrese el texto de la Nota de Cambio de Turno!!!");return false;}
}
</script>

</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type='Hidden' name='SelUnidad' value='<? echo $SelUnidad?>'>
<input type="Hidden" name="AutoId" value=<? echo $AutoId ?>>
<table  border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold">
<td align="center">NUEVA NOTA DE CAMBIO DE TURNO</td>
</tr>	
	<tr><td><strong>Creada por:<font color='maroon'><? echo " $usuario[0] - $Anio/$Mes/$Dia - $Horas:$Minutos";?></font></strong></td></tr>
    <tr><td style='text-align:justify;'>
	<?
    if($SelUnidad)
	{?>
		<textarea name="Anotacion" style="width:735px;height:299px;"></textarea>
    <?
	}?>	
    </td></tr>
</table>
<center>
<input type="Submit" name="Guardar" value="Guardar">
<input type="Button" name="Cancelar" value="Cancelar" onClick="location.href='NotasCambioTurno.php?DatNameSID=<? echo $DatNameSID?>&SelUnidad=<? echo $SelUnidad?>'">
</center>
</form>
</body>
