<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio="$ND[year]";}
	if($Eliminar)
	{
//		echo $Anio;
		$AnioB=$Anio+1;
		$cons="select ano from nomina.minimo where ano='$AnioB'";
//		echo $cons;
		$res=ExQuery($cons);
		$cont=ExNumRows($res);
//		echo $cont;
		if($cont==0)
		{
			$cons="delete from nomina.minimo where ano='$Anio' and salariomin='$Monto'";
			$res=ExQuery($cons);
//			$Eliminar==0;
			?>
			<script language="javascript">location.href="SalarioMinimo.php?DatNameSID=<? echo $DatNameSID?>";
			</script>
            <?
		}
		else
		{
			?>
			<script language="javascript">alert("primero elimine el salario del año <? echo $AnioB;?>");</script>
			<? 
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr>
	<td colspan="3" bgcolor="#666699"style="color:white" align="center" >SALARIOS MINIMOS</td>
</tr>
<tr>
	<td>AÑO</td><td>MONTO</td><td>&nbsp;</td>
</tr>
<?
	$cons="select ano, salariomin from nomina.minimo order by ano";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{	?>
		<tr><td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
    	<?    
//		echo $Anio."  --> ".$fila[0];
		if($Anio<=$fila[0])
		{	?>
			<td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar este Salario Minimo?')){location.href='SalarioMinimo.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Anio=<? echo $fila[0]?>&Monto=<? echo $fila[1]?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a>
            </td>
<?            
		}
		else
		{?>
			<td width="16px"><img src="/Imgs/b_check.png" border="0" title="Correcto"/></td>
        <?
		}
		?>
        </tr>
        <?
	}
	
?>
</table>
<center><input type="button" name="Nuevo" value="Nuevo"  onClick="location.href='NewSalarioMin.php?DatNameSID=<? echo $DatNameSID?>';"/></center>
</body>
</html>
