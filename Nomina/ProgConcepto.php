<? 
if($DatNameSID){session_name("$DatNameSID");}
session_start();
echo $Vinculacion;
include "Funciones.php";
if($Eliminar==1)
{
	$delcons="delete from nomina.conceptosprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and detconcepto='$DetConcepto' and fecinicio='$FecIni' and fecfin='$FecFin' and valor='$Valor'";
//	echo $delcons;
	$resdel=ExQuery($delcons);
	$Eliminar=0;
}
$ConsCont="select numero,tipovinculacion from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion' and estado='Activo'";
$resCont=ExQuery($ConsCont);
$filaC=ExFetch($resCont);
$Numero=$filaC[0];
$consVin="select tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]' and codigo='$filaC[1]'";
//echo $consVin;
$resVin=ExQuery($consVin);
$filaV=ExFetch($resVin);
$Vinculacion=$filaV[0];
$ND=getdate();
if("$ND[mon]"<10)
{
	$Mes="0$ND[mon]";
}
if("$ND[mon]"==2)
{
	$Dia=28;
}
else
{
	$Dia=30;
}
$FechaHoy="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
if(!$FecInicio){$FecInicio="$ND[year]-$Mes-01";}
if(!$FecFin){$FecFin="$ND[year]-$Mes-$Dia";}
if($Guardar)
{
	if($FecFin>$FecInicio)
	{
		$Concepto1=explode("_",$Concepto);
		$Concepto=$Concepto1[0];
		$Concptmp=$Concepto1[1];
		$Movimiento=$Concepto1[2];
		$Arrastra=$Concepto1[3];
		$cons="select * from nomina.conceptosprogramados where fecfin>'$FecInicio' and concepto='$Concptmp' and detconcepto='$Concepto' and identificacion='$Identificacion'";
		$res=ExQuery($cons);
		$cont=ExNumRows($res);
	//	echo $cons;
		if($cont==0)
		{
			$cons="insert into nomina.conceptosprogramados(compania,identificacion,concepto,detconcepto,valor,fecinicio,fecfin,movimiento,claseregistro,arrastracon,vinculacion,numero,usuario,fecha) values ('$Compania[0]','$Identificacion','$Concptmp','$Concepto','$Valor','$FecInicio','$FecFin','$Movimiento','Valor','$Arrastra','$Vinculacion','$Numero','$usuario[1]','$FechaHoy')";
		//	echo $cons;
			$res=ExQuery($cons);
		}
		else
		{
			?>
			<script language="javascript">
			alert("Ya existe este concepto para esta fecha de inicio");
			</script>
			<?
		}
	}
	else
	{
		?>
		<script language="javascript">
		alert("La Fecha Final debe ser mayor a la Fecha Inicial");
		</script>
		<?
	}
}
?>
<html>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-es.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-setup.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function Validar()
{
	if(document.FORMA.Concepto.value==""){alert("Por favor ingrese el Concepto a programar !!!");return false;}
	if(document.FORMA.Valor.value==""){alert("Por favor ingrese el Valor del Concepto !!!");return false;}
}
</script>
</head>
<body>
<form name="FORMA" method="post" onSubmit="return Validar()">
<table border="1" align="center">
<tr>
	<td bgcolor="#666699" style="color:white" align="center" colspan="2">PROGRAMAR CONCEPTOS</td>
</tr>
<tr>
	<td bgcolor="#666699" style="color:white" align="center">Concepto</td>
    <td>
    <select name="Concepto" >
    	<option ></option>
        <?
		$cons="select detconcepto,concepto,movimiento,arrastracon from nomina.conceptosliquidacion where compania='$Compania[0]' and claseconcepto='Valor' and tipoconcepto!='Formula' and tipovinculacion='$Vinculacion'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$Concepto)
			{
				echo "<option value='$fila[0]_$fila[1]_$fila[2]_$fila[3]' selected onclick='document.FORMA.concptmp.value=$fila[1]'>$fila[0]</option>";
				?>
                <script language="javascript">
					//document.FORMA.Concptmp.value="<? echo $fila[1];?>";
				</script>
                <?
			}
			else
			{
				echo "<option value='$fila[0]_$fila[1]_$fila[2]_$fila[3]'>$fila[0]</option>";
			}
		}
		?>
    </select>
    </td>
</tr>
<tr>
	<td bgcolor="#666699" style="color:white" align="center">Valor</td>
    <td><input type="text" name="Valor" size="37%" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)"></td>
</tr>
<tr>
	<td bgcolor="#666699" style="color:white" align="center">Fecha de Inicio</td>
    <td><input type="text" name="FecInicio" value="<? echo $FecInicio;?>"  onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" readonly size="37%"></td>
</tr>
<tr>
	<td bgcolor="#666699" style="color:white" align="center">Fecha de Finalizacion</td>
    <td><input type="text" name="FecFin" value="<? echo $FecFin;?>"  onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" readonly size="37%"></td>
</tr>
</table>
<center><input type="submit" name="Guardar" value="Guardar"></center>
</form>
<?
$consprog="select detconcepto,valor,fecinicio,fecfin from nomina.conceptosprogramados where compania='$Compania[0]' and identificacion='$Identificacion' order by fecinicio,fecfin,detconcepto";
$resprog=ExQuery($consprog);
$Cont=ExNumRows($resprog);
//echo $consprog;
if($Cont>0)
{
	?>
	<table align="center" border="1">
	<tr>
		<td bgcolor="#666699" style="color:white" align="center" colspan="5">HISTORIAL DE CONCEPTOS PROGRAMADOS</td>
	</tr>
    <tr bgcolor="#666699" style="color:white" align="center">
    	<td>Detalle del concepto</td>
    	<td>Valor</td>
        <td>Fecha de Inicio</td>
        <td>Fecha de Finalizacion</td>        
    </tr>
    <?
    while($fila=ExFetch($resprog))
	{
		?>
        <tr align="center">
            <td><? echo $fila[0];?></td>
            <td><? echo number_format($fila[1],0,'','.');?></td>
            <td><? echo $fila[2];?></td>
            <td><? echo $fila[3];?></td>  
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar el Concepto <? echo $fila[0];?> ?')){location.href='ProgConcepto?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&FecIni=<? echo $fila[2];?>&DetConcepto=<? echo $fila[0];?>&FecFin=<? echo $fila[3]?>&Identificacion=<? echo $Identificacion?>&Valor=<? echo $fila[1]?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a>
            </td>                                   
        </tr>
        <?
	}
	?>
	</table>
    <?
}
?>
</body>
</html>
