<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select primape,identificacion from central.terceros where compania='$Compania[0]' and tipo='Asegurador'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Aseguradoras[$fila[1]]=$fila[0];
	}
	if($Entidad){$Ent="and pagador='$Entidad'";}
	if($Contrato){$Contra="and contrato='$Contrato'";}
	if($Sexo){$Gen="and sexo='$Sexo'";}
	if($CUP){$Cu=" and codigo='$CUP'";}
	if($Cedula){$Ced=" and cedula='$Cedula'";}
	if($EdadIni){$AnioI=$ND[year]-$EdadIni;$EdI="and fecnac<='$AnioI-$ND[mon]-$ND[mday]'";}
	if($EdadFin){$AnioI=$ND[year]-$EdadFin;$EdF="and fecnac>='$AnioI-$ND[mon]-$ND[mday]'";}
	if($GrupoServ){$Grup="and grupo='$GrupoServ'";}
	if($Ambito){$Amb="and liquidacion.ambito='$Ambito'";}
	if($TipoFac){$TipF="and liquidacion.tipofactura='$TipoFac'";}
	$cons="select primape,segape,primnom,segnom,cedula,age('$ND[year]-$ND[mon]-$ND[mday]',fecnac),sexo,codigo,nombre,liquidacion.fechacrea,pagador
	from facturacion.liquidacion,central.terceros,facturacion.detalleliquidacion
	where liquidacion.compania='$Compania[0]' and terceros.compania='$Compania[0]' and estado='AC' and liquidacion.noliquidacion=detalleliquidacion.noliquidacion
	and liquidacion.fechacrea>='$FechaIni 00:00:00' and liquidacion.fechacrea<='$FechaFin 23:59:59' and cedula=identificacion and nofactura is not null
	and detalleliquidacion.tipo!='Medicamentos' $Ent $Contra $Gen $Cu $Ced $EdI $EdF $Grup $Amb $TipF
	group by primape,segape,primnom,segnom,cedula,age('$ND[year]-$ND[mon]-$ND[mday]',fecnac),sexo,codigo,nombre,liquidacion.fechacrea,pagador 
	order by primape,segape,primnom,segnom";
	echo $cons;
	$res=ExQuery($cons);
?>	
<html>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="1" align="center" cellspacing="1">  
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td colspan="11">CUPS FACTURADOS</td>
	</tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td></td><td>Paciente</td><td>Identifiacion</td><td>Edad</td><td>Genero</td><td>CUP</td><td>Nombre CUP</td><td>Fecha</td><td>Entidad</td>
	</tr>
<?	$cont=1;
	while($fila=ExFetch($res))
	{
		$fila[5]=str_replace("year","a&ntilde;o",$fila[5]);$fila[5]=str_replace("mons","meses",$fila[5]); 
		$fila[5]=str_replace("mon","mes",$fila[5]);$fila[5]=str_replace("day","dia",$fila[5]);
		echo "<tr align='center'><td>$cont</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td><td>$fila[4]</td><td>$fila[5]&nbsp;</td><td>$fila[6]&nbsp;</td>
		<td>$fila[7]&nbsp;</td><td>$fila[8]&nbsp;</td><td>$fila[9]&nbsp;</td><td>".$Aseguradoras[$fila[10]]."</td></tr>";
		$cont++;
	}
	?>    
</table>
</form>
</body>
</html>