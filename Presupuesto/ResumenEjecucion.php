<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
ini_set("memory_limit","512M");
	include("Funciones.php");
	include("GeneraValoresEjecucion.php");
	$ND=getdate();
	$Anio=$AnioAc;
	if(!$MesIni){$MesIni=1;}
	if(!$MesFin){$MesFin=$ND[mon];}
	$ClaseVigencia=$TipoVigencia;
?>
<form name="FORMA">
<input type="Hidden" name="Cuenta" value="<?echo $Cuenta?>">
<center>
<table  bordercolor="white" cellspacing="0" cellpadding="4" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr align="center"><td>Mes Inicial</td><td>Mes Final</td>
<tr>
<td><select name="MesIni" onChange="document.FORMA.submit();">
<?
	$cons="Select Numero,Mes from Central.Meses order by numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MesIni==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1]</option>";}
	}
?>
</select></td>
<td><select name="MesFin" onChange="document.FORMA.submit();">
<?
	$cons="Select Numero,Mes from Central.Meses order by numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MesFin==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1]</option>";}
	}
?>
</select></td>
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
</tr>
<input type="Hidden" name="Vigencia" value="<?echo $Vigencia?>">
<input type="Hidden" name="TipoVigencia" value="<?echo $TipoVigencia?>">

</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<?
	if(!$Cuenta){exit;}

	$ApropIni=GeneraApropiacion();
	$Adiciones=GeneraValor("Adicion","Ambos",1);
	$Reducciones=GeneraValor("Reduccion","Ambos",1);
	$Creditos=GeneraValor("Traslado","Credito",1);
	$CCreditos=GeneraValor("Traslado","ContraCredito",1);
	$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;
	
	$DisponibilidadesAnt=GeneraValor("Disponibilidad","Credito",2);
	$DisminucDispoAnt=GeneraValor("Disminucion a disponibilidad","ContraCredito",2);
	$TotDispoAnt=$DisponibilidadesAnt-$DisminucDispoAnt;
	
	$DispoPeriodo=GeneraValor("Disponibilidad","Credito",3);
	$DismPeriodo=GeneraValor("Disminucion a disponibilidad","ContraCredito",3);
	$TotDispoPeriodo=$DispoPeriodo-$DismPeriodo;
	
	$TotDisponibilidades=$TotDispoAnt+$TotDispoPeriodo;
	$SaldoDisponible=$ApropDef-$TotDisponibilidades;

	$CompromisosAnt=GeneraValor("Compromiso presupuestal","Credito",2);
	$DisminucCompAnt=GeneraValor("Disminucion a compromiso","ContraCredito",2);
	$TotCompAnt=$CompromisosAnt-$DisminucCompAnt;

	$CompromisosPer=GeneraValor("Compromiso presupuestal","Credito",3);
	$DisminucCompPer=GeneraValor("Disminucion a compromiso","ContraCredito",3);
	$TotCompPer=$CompromisosPer-$DisminucCompPer;
	$TotCompromisos=$TotCompAnt+$TotCompPer;

	$CompromisosPer=GeneraValor("Compromiso presupuestal","Credito",3);
	$DisminucCompPer=GeneraValor("Disminucion a compromiso","ContraCredito",3);
	$TotCompPer=$CompromisosPer-$DisminucCompPer;
	$TotCompromisos=$TotCompAnt+$TotCompPer;


	$ObligacionesAnt=GeneraValor("Obligacion presupuestal","Credito",2);
	$DisminucObligAnt=GeneraValor("Disminucion a obligacion presupuestal","ContraCredito",2);
	$TotObligAnt=$ObligacionesAnt-$DisminucObligAnt;

	$ObligacionesPer=GeneraValor("Obligacion presupuestal","Credito",3);
	$DisminucObligPer=GeneraValor("Disminucion a obligacion presupuestal","ContraCredito",3);
	$TotObligPer=$ObligacionesPer-$DisminucObligPer;
	$TotObligaciones=$TotObligPer+$TotObligAnt;

	$EgresoAnt=GeneraValor("Egreso presupuestal","Credito",2);
	$DisminucEgrAnt=GeneraValor("Disminucion a egreso presupuestal","ContraCredito",2);
	$TotEgrAnt=$EgresoAnt-$DisminucEgrAnt;

	$EgresosPer=GeneraValor("Egreso presupuestal","Credito",3);
	$DisminucEgrPer=GeneraValor("Disminucion a egreso presupuestal","ContraCredito",3);
	$TotEgrPer=$EgresosPer-$DisminucEgrPer;
	
	$TotEgresos=$TotEgrAnt+$TotEgrPer;
	


	$DispoSinCompro=$TotDisponibilidades-$TotCompromisos;

	$ApropiacionxAfectar=$SaldoDisponible+$DispoSinCompro;
	
	$CompSinObligac=$TotCompromisos-$TotObligaciones;
	
	$ObligacionesxPagar=$TotObligaciones-$TotEgresos;


	$IngAnteriores=GeneraValor("Ingreso presupuestal","ContraCredito",2);
	$IngAnterioresDism=GeneraValor("Disminucion a ingreso presupuestal","Credito",2);
	$IngAnteriores=$IngAnteriores-$IngAnterioresDism;
	
	$IngActuales=GeneraValor("Ingreso presupuestal","ContraCredito",3);
	$IngActualesDism=GeneraValor("Disminucion a ingreso presupuestal","Credito",3);
	$IngActuales=$IngActuales-$IngActualesDism;
	
	$IngTotales=$IngAnteriores+$IngActuales;
	
	$RecDisminucionAnt=GeneraValor("Disminucion a reconocimiento","Credito",2);
	$RecAnteriores=GeneraValor("Reconocimiento presupuestal","ContraCredito",2);
	$RecAnteriores=$RecAnteriores-$RecDisminucionAnt;
	
	$RecDisminucionAct=GeneraValor("Disminucion a reconocimiento","Credito",3);
	$RecActuales=GeneraValor("Reconocimiento presupuestal","ContraCredito",3);
	$RecActuales=$RecActuales-$RecDisminucionAct;

	$RecTotales=$RecAnteriores+$RecActuales;
	$SaldoSinAfectar=$ApropDef-$RecTotales;
	$RecxRecaudar=$RecTotales-$IngTotales;
	
	$cons="Select Naturaleza from Presupuesto.PlanCuentas where Cuenta='$Cuenta'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Naturaleza=$fila[0];
?>
<body background="/Imgs/Fondo.jpg">
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<center>

<script language="JavaScript">
	function AbrirListaDocs(Tipo,MesIni,MesFin,Cuenta,Movimiento,Modo,Disminucion)
	{
		open("ListaDocumentosGeneral.php?DatNameSID=<? echo $DatNameSID?>&Anio="+document.FORMA.Anio.value+"&Tipo="+Tipo+"&MesIni="+MesIni+"&MesFin="+MesFin+"&Cuenta="+Cuenta+"&Movimiento="+Movimiento+"&Modo="+Modo+"&Disminucion="+Disminucion+"&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $TipoVigencia?>","","width=850,height=500,scrollbars=yes,resizable")
	}
	function AbrirListaDocsxSaldo(Tipo,MesIni,MesFin,Cuenta,Movimiento,Modo,Disminucion)
	{
		open("ListaDocumentosxSaldo.php?DatNameSID=<? echo $DatNameSID?>&Anio="+document.FORMA.Anio.value+"&Tipo="+Tipo+"&MesIni="+MesIni+"&MesFin="+MesFin+"&Cuenta="+Cuenta+"&Movimiento="+Movimiento+"&Modo="+Modo+"&Disminucion="+Disminucion+"&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $TipoVigencia?>","","width=850,height=500,scrollbars=yes,resizable")
	}
</script>
<?	if($Naturaleza=="Credito"){?>

<table  bordercolor="#666699" cellspacing="0" rules="groups"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td align="center" bgcolor="#666699" style="color:white"><strong>Apropiaci&oacute;n</td>
<td rowspan="4">


<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td colspan="2" align="center" bgcolor="#666699" style="color:white"><strong>Compromisos</td>
<tr><td><a href="#" onclick="AbrirListaDocs('Compromiso presupuestal','<? echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','1','Disminucion a compromiso')">Compromisos anteriores</td><td align="right"><?echo number_format($TotCompAnt,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Compromiso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<? echo $Cuenta?>','','2','Disminucion a compromiso')">Compromisos periodo</td><td align="right"><?echo number_format($TotCompPer,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Compromiso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<? echo $Cuenta?>','','0','Disminucion a compromiso')">Total Compromisos</td><td align="right"><?echo number_format($TotCompromisos,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocsxSaldo('Compromiso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','Credito',0,'')">Compromisos sin obligaci&oacute;n</td><td align="right"><?echo number_format($CompSinObligac,2)?></td></tr>
</table>


<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td colspan="2" align="center" bgcolor="#666699" style="color:white"><strong>Obligaciones</td>
<tr><td><a href="#" onclick="AbrirListaDocs('Obligacion presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','1','Disminucion a obligacion presupuestal')">Obligaciones anteriores</td><td align="right"><?echo number_format($TotObligAnt,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Obligacion presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','2','Disminucion a obligacion presupuestal')">Obligaciones periodo</td><td align="right"><?echo number_format($TotObligPer,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Obligacion presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','0','Disminucion a obligacion presupuestal')">Total Obligaciones</td><td align="right"><?echo number_format($TotObligaciones,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocsxSaldo('Obligacion presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','Credito',0,'')">Obligaciones x pagar</td><td align="right"><?echo number_format($ObligacionesxPagar,2)?></td></tr>
</table>

<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td colspan="2" align="center" bgcolor="#666699" style="color:white"><strong>Pagos</td>
<tr><td><a href="#" onclick="AbrirListaDocs('Egreso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','1','Disminucion a egreso presupuestal')">Pagos anteriores</td><td align="right"><?echo number_format($TotEgrAnt,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Egreso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','2','Disminucion a egreso presupuestal')">Pagos periodo</td><td align="right"><?echo number_format($TotEgrPer,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Egreso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','0','Disminucion a egreso presupuestal')">Total Pagos</td><td align="right"><?echo number_format($TotEgresos,2)?></td></tr>

</table>


</td>

</tr>
<tr><td>
<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td>Apropiaci&oacute;n Inicial</td>
<td align="right"><?echo number_format($ApropIni,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Adicion','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','',0,'')">Adiciones</td><td align="right"><?echo number_format($Adiciones,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Reduccion','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','',0,'')">Reducciones</td><td align="right"><?echo number_format($Reducciones,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Traslado','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','Credito',0,'')">Cr&eacute;ditos</td><td align="right"><?echo number_format($Creditos,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Traslado','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','ContraCredito',0,'')">Contra Cr&eacute;ditos</td><td align="right"><?echo number_format($CCreditos,2)?></td></tr>
<tr><td>Apropiacion Definitiva</td><td align="right"><?echo number_format($ApropDef,2)?></td></tr>
</table>
</td>
<td rowspan="3">


<tr bgcolor="#666699" style="color:white"><td align="center"><strong>Disponibilidades</td></tr>
<tr>
<td>
<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td><a href="#" onclick="AbrirListaDocs('Disponibilidad','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','1','Disminucion a disponibilidad')">Disponibilidades anteriores</td><td align="right"><?echo number_format($TotDispoAnt,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Disponibilidad','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','2','Disminucion a disponibilidad')">Disponibilidades periodo</td><td align="right"><?echo number_format($TotDispoPeriodo,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Disponibilidad','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','0','Disminucion a disponibilidad')">Total Disponibilidades</td><td align="right"><?echo number_format($TotDisponibilidades,2)?></td></tr>
<tr style="font-weight:bold;color:blue"><td>Saldo Disponible</td><td align="right"><?echo number_format($SaldoDisponible,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocsxSaldo('Disponibilidad','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','Credito',0,'')">Disponibilidades sin compromiso</td><td align="right"><?echo number_format($DispoSinCompro,2)?></td></tr>
<tr><td>Apropiaci&oacute;n x afectar</td><td align="right"><?echo number_format($ApropiacionxAfectar,2) ?></td></tr>
</table>
</td></tr>

<?	}
	else
	{?>
	
	
<table  bordercolor="white" cellspacing="0" rules="groups"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr align="center"><td bgcolor="#666699" style="color:white;font-weight:bold">Apropiaci&oacute;n</td><td style="width:30px;"></td><td bgcolor="#666699" style="color:white;font-weight:bold">Reconocimientos</td>
<tr>
<td>

<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td>Apropiaci&oacute;n Inicial</td>
<td align="right"><?echo number_format($ApropIni,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Adicion','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','',0,'')">Adiciones</td><td align="right"><?echo number_format($Adiciones,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Reduccion','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','',0,'')">Reducciones</td><td align="right"><?echo number_format($Reducciones,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Traslado','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','Credito',0,'')">Cr&eacute;ditos</td><td align="right"><?echo number_format($Creditos,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Traslado','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','ContraCredito',0,'')">Contra Cr&eacute;ditos</td><td align="right"><?echo number_format($CCreditos,2)?></td></tr>
<tr><td>Apropiacion Definitiva</td><td align="right"><?echo number_format($ApropDef,2)?></td></tr>
</table>
</td><td></td>
<td>
	
<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td><a href="#" onclick="AbrirListaDocs('Reconocimiento presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','1','Disminucion a reconocimiento')">Reconocimientos Anteriores</td><td align="right"><?echo number_format($RecAnteriores,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocs('Reconocimiento presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','2','Disminucion a reconocimiento')">Reconocimientos Periodo</td><td align="right"><?echo number_format($RecActuales,2)?></td></tr>
<tr><td>Total Reconocimientos</td><td align="right"><?echo number_format($RecTotales,2)?></td></tr>
<tr><td>Saldo sin Afectar</td><td align="right"><?echo number_format($SaldoSinAfectar,2)?></td></tr>
<tr><td><a href="#" onclick="AbrirListaDocsxSaldo('Reconocimiento presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','ContraCredito',0,'')">Reconocimientos x Recaudar</td><td align="right"><?echo number_format($RecxRecaudar,2)?></td></tr>
</table>
</tr>
<tr bgcolor="#666699" style="color:white;font-weight:bold"><td colspan="3" align="center">Recaudos</td></tr>
<tr>
<td colspan="3" align="center">
<table width="100%" bordercolor="#ffffff"  border="1" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr><td><a href="#" onclick="AbrirListaDocs('Ingreso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','1','Disminucion a ingreso presupuestal')">Recaudos Anteriores</td><td align="right"><?echo number_format($IngAnteriores,2)?></td></tr>
<tr bgcolor="#EEF6F6"><td><a href="#" onclick="AbrirListaDocs('Ingreso presupuestal','<?echo $MesIni?>','<?echo $MesFin?>','<?echo $Cuenta?>','','2','Disminucion a ingreso presupuestal')">Recaudos Periodo</td><td align="right"><?echo number_format($IngActuales,2)?></td></tr>
<tr><td>Total Recaudos</td><td align="right"><?echo number_format($IngTotales,2)?></td></tr>
</table>
	</td></tr>
	
	
<?	}
?>




</body>
