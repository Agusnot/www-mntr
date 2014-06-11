<? 
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();

if("$ND[mon]"<=9)
{
	$Mes="0$ND[mon]";
//	echo $Mes;
}
$FechaAct="$ND[year]-$Mes-$ND[mday]";
$cons="select salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and fecinicio='$FecInicio' and fecfin='$FecFin'";
$res=ExQuery($cons);
$fila=ExFetch($res);
if(!$Monto)
{
	$Monto=$fila[0];
}
if($Guardar)
{
//	echo $FecFin." - ".$FechaAct."<br>";
	if($FecFin>=$FechaAct)
	{
		$cons="update nomina.salarios set fecfin='$FecFin',salario='$Monto' where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio='$FecInicio' and numcontrato='$NumContrato'";
		$res=ExQuery($cons);
		?><script language="javascript">location.href="Salarios.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&FecInicio=<? echo $FecInicio?>";</script><?
	}
	else
	{
		$Monto=$Monto;
		?><script language="javascript">alert("NO SE PUEDE EDITAR UN SALARIO ANTERIOR AL MES ACTUAL");</script><?
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
   if(document.FORMA.AnioI.value==""){alert("Por favor ingrese el Año Inicial !!!");return false;}
   if(document.FORMA.MesI.value==""){alert("Por favor ingrese el Mes Inicial !!!");return false;}
   if(document.FORMA.AnioF.value==""){alert("Por favor ingrese el Año Final !!!"); return false;}
   if(document.FORMA.MesF.value==""){alert("Por favor ingrese el Mes Final !!!");return false;}
   if(document.FORMA.Salario.value==""){alert("Por favor ingrese el Salario !!!");return false;}
}
</script>
<script language="javascript" src="/calendario/popcalendar.js"></script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar();">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr bgcolor="#666699"style="color:white" align="center"><td colspan="3">SALARIO</td></tr>
<tr bgcolor="#666699"style="color:white" align="center"><td>Fecha Inicio</td><td>Fecha Final</td><td>Monto</td></tr>
<tr>
<td><input type="text"name="FecInicio" value="<? echo $FecInicio?>" readonly></td>
<td><input type="text"name="FecFin" value="<? echo $FecFin?>" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10"></td>
<td><input type="text"name="Monto" value="<? echo $Monto?>" ></td>
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" name="Cancelar" onClick="location.href='Salarios.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&FecInicio=<? echo $FecInicio?>';"></center>
</form>
</body>
</html>