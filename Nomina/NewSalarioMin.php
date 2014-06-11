<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$AnioC="$ND[year]";
	$AnioC1=$AnioC+1;
	if(!$Anio){$Anio="$ND[year]";}
	if($Guardar)
	{
		//echo $AnioC1." --> ".$Anio." = ".$Monto;
		if($AnioC==$Anio||$AnioC+1==$Anio)
		{
			$cons="select ano from nomina.minimo where ano='$Anio'";
			$res=Exquery($cons);
			$cont=ExNumRows($res);
//			echo $cont;
			if($cont==0)
			{
				$cons="insert into nomina.minimo(ano,salariomin) values ('$Anio','$Monto')";
				$res=ExQuery($cons);
				?>
				<script language="javascript">location.href="SalarioMinimo.php?DatNameSID=<? echo $DatNameSID?>";
				</script>
            <?
			}
			else
			{
				?>
				<script language="javascript">alert("El Salario Minimo para este Año ya esta Configurado");</script>
				<?
			}
		}
		else
		{
			?>
			<script language="javascript">alert("No se Puede Configurar el Salario para este Año ");
			location.href="NewSalarioMin.php?DatNameSID=<? echo $DatNameSID?>";
			</script>
            <?
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.Anio.value==""){alert("Por favor ingrese el Año!!!");return false;}
   if(document.FORMA.Monto.value==""){alert("Por favor ingrese el Salario Minimo!!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();" />
<input type="hidden" name="Anio" />
<input type="hidden" name="Monto" />
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr>
	<td colspan="3" bgcolor="#666699"style="color:white" align="center" >SALARIOS MINIMOS</td>
</tr>
<tr>
	<td>AÑO</td><td>MONTO</td>
</tr>
<tr>
	<td><input type="text" name="Anio" value="<? echo $Anio?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" /></td>
	<td><input type="text" name="Monto" value="<? echo $Monto?>"  onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" /></td>
</tr>
</table>
<center><input type="submit" name="Guardar" value="Guardar" /><input type="button" name="Cancelar" value="Cancelar" onclick="location.href='SalarioMinimo.php?DatNameSID=<? echo $DatNameSID?>';"/>
</center>
</Form>
</body>
</html>