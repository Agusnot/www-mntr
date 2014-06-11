<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4" align="center">
<?	if($Codigo||$Nombre)
	{
		if($Codigo){$Cod="and codigo ilike '$Codigo%'";}
		if($Nombre){$Cod="and diagnostico ilike '%$Nombre%'";}
		$cons="select codigo,diagnostico from salud.cie where codigo is not null $Cod order by codigo";
		$res=ExQuery($cons);?>
        <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center" > 
            <td>codigo</td><td>Nombre</td>
        </tr>
<?		while($fila=ExFetch($res))
		{?>
        	<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"
            onclick="parent.parent.document.FORMA.AuxDxRNMuere.value='<? echo $fila[0]?>'
            ;parent.parent.document.FORMA.DxRNMuerte.value='<? echo "$fila[0] - $fila[1]"?>';parent.CerrarThis()">
            	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
          	</tr>
	<?	}
	}?>
</table>
</form>
</body>
</html>	