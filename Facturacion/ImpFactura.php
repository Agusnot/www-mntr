<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	mysql_select_db("Facturacion");
?>
	
<style>

	P {page-break-after: always}
	td.Titulo{
	border:1px solid black;
	}
	td.Dato{
	border-left:1px solid black;
	border-right:1px solid black;	
	}
	td.Final{
	border:1px solid black;

	}

</style>

<?
	function TitulosEncab($Identificac,$FecIng,$FecEgr,$Periodo,$NomPaciente,$TipoUsu,$NivUsu,$Fact,$Entidad,$Salto,$NumPag,$NoOrden,$MotivoSal,$FechaExp,$FecVenc,$NitEps,$Procedencia,$Ambito,$TipoFactura,$AutorizacionUrgencias,$AutorizacionHospi,$AutorizacionEgreso)
	{

		$AutorizacionUrgencias="LE99930";
		$AutorizacionHospi="LE99930";
		$AutorizacionEgreso="LE99930";
		
		if($Salto>1){echo "</table><p align='left'></p>";}
		echo "<table style='position:absolute;right:35px;' rules='rows' border=1 cellpadding=5 cellspacing=0>";
		echo "<tr><td style='font : normal normal small-caps 12px Tahoma;'><center>Factura<br>de Venta</td></tr>";
		echo "<tr><td style='font : normal normal small-caps 22px Arial;'><strong><center>No. $Fact</td></tr>";
		echo "<tr><td style='font : normal normal small-caps 12px Arial;'><strong><center><em>$TipoFactura</em></td></tr>";
		echo "</table>";

		echo "<img src='/Imgs/granada1.gif' style='width:60px;position:absolute;left:50px;'><center>";
		echo "<font style='font : normal normal small-caps 18px Tahoma;'><strong>HOSPITAL SAN RAFAEL DE PASTO</font></strong><br>
		<font style='font : normal normal small-caps 12px Tahoma;'>Hermanos Hospitalarios de San Juan de Dios</strong><br>Nit: 891200274-2<br>
		Codigo SGSSS 5200100096<br>
		Calle 15 No 42C 35 - Telefonos: 7235144 - 7291481 Fax: 7231066</center></font><br>";
		
		echo "<center><font style='font : normal normal small-caps 15px Tahoma;font-weight:bold'>Cliente: $Entidad</font>
		<font style='font : normal normal small-caps 10px Tahoma;'><br>NIT: $NitEps<br></font></center><br>";
		
		echo "<table width='100%' border=0 style='font : normal normal small-caps 12px Tahoma;'>";
		echo "<tr><td><strong>Paciente</td><td>$NomPaciente</td>";
		echo "<td><strong>Identificación</td><td>$Identificac</td></tr>";
		echo "<tr><td><strong>Fecha Ingreso</td><td>$FecIng</td>";
		if($FecEgr=='0000-00-00' && $Ambito!="Consulta externa")
		{
			echo "<td colspan=2><center><strong>Continúa Hospitalizado</td>";
		}
		else{
		echo "<td><strong>Fecha Egreso</td><td>$FecEgr</td></tr>";}
		echo "<tr><td><strong>Periodo de Cuenta</td><td>$Periodo</td>";

		echo "<td><strong>No. Orden</td><td>$NoOrden</td></tr>";

		echo "<tr><td><strong>Tipo de Usuario</td><td>$TipoUsu</td>";
		echo "<td><strong>Nivel de Usuario</td><td>$NivUsu</td>";

		echo "<tr><td><strong>Fecha Expedición</td><td>$FechaExp</td>";
		echo "<td><strong>Fecha Vencimiento</td><td>$FecVenc</td>";
		
		echo "<tr><td><strong>Causa salida</td><td>$MotivoSal</td>
		<td><strong>Procedencia</td><td>$Procedencia</td></tr>";
		
		//////CONSULTA AUTORIZACIONES//////
		echo "<tr><td><strong>Autorizacion Urgencias</td><td>$AutorizacionUrgencias</td>";
		echo"<td><strong>Autorizacion Hospitalizacion</td><td>$AutorizacionHospi</td></tr>";
		echo "<tr><td><strong>Autorizacion Egreso</td><td>$AutorizacionEgreso</td><TD><strong>Contrato No.</td><td>983892</td>";
		/////////////////
		echo "</table>";

		echo "<center><table cellspacing=1 cellpadding=5 width='95%' style='text-transform:uppercase' style='font : normal normal small-caps 12px Tahoma;'>";
		echo "<tr><td colspan=5 align='right'><em><font size=1>Pág No. $NumPag</td></tr>";
		echo "<tr><td class='Titulo'>Codigo</td><td  class='Titulo'>Concepto</td><td class='Titulo'>Cantidad</td><td class='Titulo'>Vr Unitario</td><td class='Titulo'>Vr Total</td></tr>";


	}
?>
<html>
<head>
	<title>Factura(s) de Credito</title>
</head>
<body>
<?php
$Entidad='Saludcoop EPS';$FacIni="2009000001";$FacFinal="2009000001";$Mes=8;$Anio=2009;
	if($FacIni)
	{
		$cons="Select * from FacturasCredito where NoFactura>=$FacIni and NoFactura<=$FacFinal";
	}
	if($Entidad)
	{
		$cons="Select * from FacturasCredito where NombreEntidad='$Entidad' and month(FechaExp)=$Mes and Year(FechaExp)=$Anio";
	}
	$res=mysql_query($cons);echo mysql_error();
	
	while($fila=ExFetch($res))
	{
		
		$NumPag=1;
		$NumTot++;
		$i=1;
		$VrMedicamentos=$fila[16];
		$VrCopago=$fila[11];
		$VrDescuentos=$fila[13];
		$Identificac=$fila[18];
		/////MODIFICADO X JAIME CASANOVA
		$cons45="Select NumServicio from Facturacion.Liquidaciones where NoFactura='$fila[2]'";
		$res45=ExQuery($cons45);
		$fila45=ExFetch($res45);
		
		$cons46="Select FechaIng from Salud.Servicios where NumServicio='$fila45[0]' and Cedula='$Identificac'";
		$res46=ExQuery($cons46);
		$fila46=ExFetch($res46);
		
		$FecIng=$fila46[0];
		$FecEgr=$fila[31];
		$Periodo="$fila[4] a $fila[5]";
		$NomPaciente="$fila[20] $fila[21] $fila[22] $fila[23]";
		$TipoUsu=$fila[19];
		$NivUsu=$fila[39];
		$NoOrden=$fila[38];
		$MotivoSal=$fila[37];
		$FechaExp=$fila[3];
		$NitEps=$fila[42];
		$Procedencia=$fila[27];
		$Ambito=$fila[33];
		$VrIniFactura=$fila[14];
		if($fila[41]){$MsjCopago="$fila[41]";}
		if($fila[46]==""){$AutorizacionUrgencias="-";}else{$AutorizacionUrgencias=$fila[46];}
		if($fila[47]==""){$AutorizacionHospi="-";}else{$AutorizacionHospi=$fila[47];}
		if($fila[48]==""){$AutorizacionEgreso="-";}else{$AutorizacionEgreso=$fila[48];}
		$FecVenc=("$FechaExp + 30 day");
		$FecVenc=strtotime($FecVenc);
		$FecVenc=getdate($FecVenc);
		$FecVenc="$FecVenc[year]-$FecVenc[mon]-$FecVenc[mday]";
		TitulosEncab($Identificac,$FecIng,$FecEgr,$Periodo,$NomPaciente,$TipoUsu,$NivUsu,$fila[2],$fila[7],$NumTot,$NumPag,$NoOrden,$MotivoSal,$FechaExp,$FecVenc,$NitEps,$Procedencia,$Ambito,$TipoFactura,$AutorizacionUrgencias,$AutorizacionHospi,$AutorizacionEgreso);
		$consPrev="Select TipoServicio from DetalleFactura where NoFactura=$fila[2] group By TipoServicio Order By IdPriori";
		$resPrev=mysql_query($consPrev);
		while($filaPrev=ExFetch($resPrev))
		{
			$cons2="Select * from DetalleFactura where NoFactura=$fila[2] and TipoServicio='$filaPrev[0]'";
			$res2=mysql_query($cons2);
			while($fila2=ExFetch($res2))
			{
				if($fila2[7]=="0"){$fila2[7]="";}if($fila2[6]=="0"){$fila2[6]="";}if($fila2[8]=="0"){$fila2[8]="";}
				echo "<tr><td class='Dato'>$fila2[2]</td><td class='Dato'>$fila2[5] $fila2[6] $fila2[7] $fila2[8]</td><td class='Dato' align='right'>$fila2[9]</td><td class='Dato' align='right'>" . number_format(round($fila2[10]),2) . "</td><td class='Dato' align='right'>" . number_format(round($fila2[11]),2) . "</td></tr>";
				$i++;
				if($i>20)
				{
					while($i<=21)
					{
						echo "<tr align='right'><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td></tr>";
						$i++;
					}
					echo "<tr><td colspan='5' class='Final' align='right'><em>Continúa...</td></tr>";
					$i=1;
					$NumTot++;
					$NumPag++;
					TitulosEncab($Identificac,$FecIng,$FecEgr,$Periodo,$NomPaciente,$TipoUsu,$NivUsu,$fila[2],$fila[7],$NumTot,$NumPag,$NoOrden,$MotivoSal,$FechaExp,$FecVenc,$NitEps,$Procedencia,$Ambito,$TipoFactura,$AutorizacionUrgencias,$AutorizacionHospi,$AutorizacionEgreso);
				}
				$SubTotServ=$SubTotServ+$fila2[11];
			}
			$SubTotalFactura=$SubTotalFactura+$SubTotServ;
			echo "<tr align='right'><td class='Titulo' align='right' colspan=4><strong>Subtotal x $filaPrev[0]</td><td class='Titulo' align='right'><strong>" . number_format(round($SubTotServ),2) . "</td></tr>";
			$SubTotServ=0;
		}
		while($i<=13)
		{
			echo "<tr align='right'><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td><td class='Dato'>&nbsp;</td></tr>";
			$i++;
		}

		if($Ambito=="Consulta externa")
		{
			$EncabCopago="Cuota moderadora";
		}
		else
		{
			$EncabCopago="Valor Copago";
		}
		echo "<tr align='right'><td class='Titulo' align='right' colspan=4><strong>Subtotal Facturacion</td><td class='Titulo' align='right'><strong>" . number_format(round($SubTotalFactura),2) . "</td></tr>";
		echo "<tr align='right'><td class='Titulo' align='right' colspan=4><strong>$EncabCopago $MsjCopago RC 2009000088</td><td class='Titulo' align='right'><strong>" . number_format(round($VrCopago),2) . "</td></tr>";
		if($VrDescuentos>0)
		{
			echo "<tr align='right'><td class='Titulo' align='right' colspan=4><strong>Descuentos</td><td class='Titulo' align='right'><strong>" . number_format(round($VrDescuentos),2) . "</td></tr>";
		}
		
		$TotFactura=$SubTotalFactura-$VrCopago-$VrDescuentos;
		$cons4="Update FacturasCredito set VrFactura=$TotFactura where Nofactura=$fila[2]";$res4=mysql_query($cons4);echo mysql_error();

		$Letras=NumerosxLet(round($TotFactura));
		echo "<tr align='right'><td class='Titulo' align='right' colspan=3><strong>SON: $Letras<td class='Titulo'><strong>Total Factura</td><td class='Titulo' align='right'><strong>" . number_format(round($TotFactura),2) . "</td></tr>";
		echo "</table>";
		echo "<table cellspacing=0 width=95% rules='groups' style='font : normal normal small-caps 7px Tahoma'>";
		echo "<tr><td><li>Somos exentos de impuestos a la renta y complementarios según ley 223/95 articulo 65.
		<strong>Entidad sin animo de lucro</strong>, favor no hacer retención.</li></td>
		<td><li>NOTA: Este documento se asimila en todos sus efectos legales a una letra de cambio
		segun ART. 774 del codigo del comercio</li></td></tr>
		<tr><td><li>No contribuyente del impuesto de renta inciso tercero del articulo 23 del 
		estatuto tributario</td><td><li>Despues de vencido el plazo de pago de esta factura, se 
		generan intereses de mora mensual la tasa maxima legal vigente</td></tr></table>";

		echo "<img src='/Imgs/12990830.gif' style='width:90px;position:absolute;left:350px;'><br>";
		echo "<table style='font : normal normal small-caps 12px Tahoma'>";

		echo "<tr><td>__________________________________</td></tr>";

		echo "<tr><td><center>Firma responsable</td></tr>";
		echo "</table>";
		$TotFactura=0;$SubTotalFactura=0;
	}
?>
</body>
</html>
