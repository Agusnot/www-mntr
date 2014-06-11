		<?
				if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Informes.php");
			include_once("General/Configuracion/Configuracion.php");
			require('LibPDF/fpdf.php');
			if(!$CuentaIni){$CuentaIni=0;}
			if(!$CuentaFin){$CuentaFin=9999999999;}
			$ND=getdate();
			$PerIni="$Anio-$MesIni-$DiaIni";
			$PerFin="$Anio-$MesFin-$DiaFin";
			if(!$PDF){
		?>

	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
			<style>
				P{
					PAGE-BREAK-AFTER: always;
				}
			</style>
		</head>
		
		<body <?php echo $backgroundBodyInfContableMentor;?>>
		
		<?
			function Encabezados()
			{
				global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;
				?>
				<table rules="groups"  width="100%" class="tablaInformeContable" style="text-align:justify;" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
				<tr><td colspan="10" style="text-align:center;font-weight:bold;"><?echo strtoupper($Compania[0])?><br>
				<?echo $Compania[1]?><br>BALANCE DE PRUEBA X CC<br>CORTE A: <?echo $PerFin?></td></tr>
				<tr><td colspan="10" style="text-align:center;">FECHA DE IMPRESI&Oacute;N <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
				</tr>
				<tr>
				<td  class='encabezado2HorizontalInfCont' rowspan="2"> C&Oacute;DIGO</td>
				<td  class='encabezado2HorizontalInfCont'rowspan="2" colspan="3">DESCRIPCI&Oacute;N</td>
				<td class='encabezado2HorizontalInfCont' colspan="2">SALDO ANTERIOR</td>
				<td  class='encabezado2HorizontalInfCont' colspan="2">MOVIMIENTOS DEL PERIODO</td>
				<td  class='encabezado2HorizontalInfCont' colspan="2">SALDO FINAL</td></tr>
				<tr>
					<td class='encabezado2HorizontalInfCont' style='font-size:11px'>DEBITO</td>
					<td class='encabezado2HorizontalInfCont' style='font-size:11px'>CREDITO</td>
					<td class='encabezado2HorizontalInfCont' style='font-size:11px'>DEBITO</td>
					<td class='encabezado2HorizontalInfCont' style='font-size:11px'>CREDITO</td>
					<td class='encabezado2HorizontalInfCont' style='font-size:11px'>DEBITO</td>
					<td class='encabezado2HorizontalInfCont' style='font-size:11px'>CREDITO</td>
				</tr>
				
		<?	}
			$NumRec=0;$NumPag=1;
			Encabezados();
			}
			$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
			$res=ExQuery($cons,$conex);echo ExError();
			while($fila=ExFetchArray($res))
			{
				$Nivel++;$TotNivel++;
				if(!$fila[0]){$fila[0]="-100";}
				$TotCaracteres=$TotCaracteres+$fila[0];
				$Digitos[$Nivel]=$TotCaracteres;
			}

			$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio from Contabilidad.Movimiento 
			where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
			and $ExcluyeComprobantes Group By Cuenta,Anio,Fecha Order By Cuenta";
			$res2=ExQuery($cons2);echo ExError();
			while($fila2=ExFetch($res2))
			{
				$CuentaMad=substr($fila2[2],0,1);
				if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
				else{
				for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
				{
					$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
					if($ParteAnt!=$ParteCuenta){
					$SICuenta[$ParteCuenta]['Debitos']=$SICuenta[$ParteCuenta]['Debitos']+$fila2[0];
					$SICuenta[$ParteCuenta]['Creditos']=$SICuenta[$ParteCuenta]['Creditos']+$fila2[1];}
					$ParteAnt=$ParteCuenta;
				}}
			}

			$cons3="Select sum(Debe),sum(Haber),Cuenta from Contabilidad.Movimiento 
			where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes Group By Cuenta Order By Cuenta";
			$res3=ExQuery($cons3);echo ExError();
			while($fila3=ExFetch($res3))
			{
				for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
				{
					$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
					if($ParteAnt!=$ParteCuenta){
					$MPCuenta[$ParteCuenta]['Debitos']=$MPCuenta[$ParteCuenta]['Debitos']+$fila3[0];
					$MPCuenta[$ParteCuenta]['Creditos']=$MPCuenta[$ParteCuenta]['Creditos']+$fila3[1];}
					$ParteAnt=$ParteCuenta;
				}
			}
			
			
		///////////////////////////////BALANCE DE TERCEROS //////////////////////////////////////
		//////////////SALDOS INICIALES////////////////////
			$cons200="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as Anio,CC from Contabilidad.Movimiento 
			where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
			and $ExcluyeComprobantes Group By Cuenta,Anio,Fecha,CC Order By Cuenta";

			$res200=ExQuery($cons200);echo ExError();
			while($fila200=ExFetch($res200))
			{
				$CuentaMad=substr($fila200[2],0,1);
				if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila200[3]){}
				else{
				for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
				{
					$ParteCuenta=substr($fila200[2],0,$Digitos[$Nivel]);
					if($ParteAnt!=$ParteCuenta){
					$SITCuenta[$ParteCuenta][$fila200[4]]['Debitos']=$SITCuenta[$ParteCuenta][$fila200[4]]['Debitos']+$fila200[0];
					$SITCuenta[$ParteCuenta][$fila200[4]]['Creditos']=$SITCuenta[$ParteCuenta][$fila200[4]]['Creditos']+$fila200[1];}
					$ParteAnt=$ParteCuenta;
				}}
			}

		//////////////////MOVIMIENTOS///////////////////////

			$cons3="Select sum(Debe),sum(Haber),Cuenta,CC from Contabilidad.Movimiento 
			where Fecha>='$PerIni' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
			and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes 
			Group By Cuenta,CC Order By Cuenta";

			$res3=ExQuery($cons3);echo ExError();
			while($fila3=ExFetch($res3))
			{
				for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
				{
					$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
					if($ParteAnt!=$ParteCuenta){
					$MPTCuenta[$ParteCuenta][$fila3[3]]['Debitos']=$MPTCuenta[$ParteCuenta][$fila3[3]]['Debitos']+$fila3[0];
					$MPTCuenta[$ParteCuenta][$fila3[3]]['Creditos']=$MPTCuenta[$ParteCuenta][$fila3[3]]['Creditos']+$fila3[1];}
					$ParteAnt=$ParteCuenta;
				}
			}
		////////DATOS DEL TERCERO/////////
			$cons89="Select Cuenta,Codigo,CentroCostos from Central.CentrosCosto,Contabilidad.Movimiento 
			where Movimiento.CC=CentrosCosto.Codigo and CentrosCosto.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]'
			and Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and CentrosCosto.Anio=$Anio
			and Movimiento.Estado='AC' Group By Codigo,CentroCostos,Cuenta";
			$res89=ExQuery($cons89);
			while($fila89=ExFetch($res89))
			{
				$MatTerceros[$fila89[0]][$fila89[1]]=array($fila89[0],$fila89[1],$fila89[2],0,$fila89[1]);
			}
			
			$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos,CentroCostos from Contabilidad.PlanCuentas 
			where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Compania='$Compania[0]' and Anio=$Anio
			and length(Cuenta)<=$NoDigitos Order By Cuenta";
			$resCta=ExQuery($consCta);echo ExError();

			while($filaCta=ExFetchArray($resCta))
			{
				if(!$PDF)
				{
					if($NumRec>=$Encabezados)
					{
						echo "</table><P>&nbsp;</P>";
						$NumPag++;
						Encabezados();
						$NumRec=0;
					}
				}

				$Debitos=$MPCuenta[$filaCta[0]]['Debitos'];
				$Creditos=$MPCuenta[$filaCta[0]]['Creditos'];
				$DebitosSI=$SICuenta[$filaCta[0]]['Debitos'];
				$CreditosSI=$SICuenta[$filaCta[0]]['Creditos'];
				
				if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
				if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
				elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

				if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
				elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

				if($DebitosSI || $CreditosSI || $Debitos || $Creditos){$Muestre=1;}
				if($IncluyeCeros=="on"){$Muestre=1;}
				if($Muestre==1)
				{
					$NumRec++;

					if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
					else{$BG="white";$Fondo=1;}
					
					if(!$PDF)
					{
						echo "<tr bgcolor='$BG'>";
						echo "<td>";
						echo "$filaCta[0]</td><td colspan=3>$filaCta[1]</td>";
					}

					if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
					if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
					if($MovSI=="Debito"){if(!$PDF){echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>0.00</td>";}$SaldoIDB=$SaldoI;
					if(strlen($filaCta[0])==1){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
					else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoI,2)."</td>";}$SaldoICR=$SaldoI;
					if(strlen($filaCta[0])==1){$TotCreditosSI=$TotCreditosSI+$SaldoI;}}

					if(!$PDF){echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";}

					if($filaCta[3]=="Debito")
					{
						if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
						if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
						else{if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFDB=$SaldoF;
						if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
					}
					elseif($filaCta[3]=="Credito")
					{
						if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFDB=$SaldoF;
						if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
						else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCR=$SaldoF;
						if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
					}
					if(strlen($filaCta[0])==1)
					{
						$TotDebitosMov=$TotDebitosMov+$Debitos;
						$TotCreditosMov=$TotCreditosMov+$Creditos;
					}
					$Datos[$NumRec]=array($filaCta[0],$filaCta[1],$SaldoIDB,$SaldoICR,$Debitos,$Creditos,$SaldoFDB,$SaldoFCR);
					if($filaCta[5]=="on")
					{
						$NumRec++;
						if(count($MatTerceros[$filaCta[0]])>0){
						foreach($MatTerceros[$filaCta[0]] as $Identificacion)
						{
							$DebitosSI=$SITCuenta[$filaCta[0]][$Identificacion[4]]['Debitos'];
							$CreditosSI=$SITCuenta[$filaCta[0]][$Identificacion[4]]['Creditos'];
							
							$Debitos=$MPTCuenta[$filaCta[0]][$Identificacion[4]]['Debitos'];
							$Creditos=$MPTCuenta[$filaCta[0]][$Identificacion[4]]['Creditos'];

				
							if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
							if($filaCta[3]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
							elseif($filaCta[3]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}

							if($filaCta[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
							elseif($filaCta[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}

							if($SaldoI || $SaldoF || $Debitos || $Creditos){$Muestre=1;}
							if($IncluyeCeros=="on"){$Muestre=1;}

							if($Muestre)
							{$Muestre=0;$NumRec++;
								if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
								else{$BG="white";$Fondo=1;}
								if(!$PDF){echo "<tr bgcolor='$BG'>";}
								$Identid=explode("-",$Identificacion[4]);
								if(!$PDF){echo "<td>$filaCta[0]</td><td><ul>$Identid[0] $Identificacion[2]</td><td></td><td></td>";}

								if($SaldoI<0 && $MovSI=="Debito"){$MovSI="Credito";$SaldoI=abs($SaldoI);}
								if($SaldoI<0 && $MovSI=="Credito"){$MovSI="Debito";$SaldoI=abs($SaldoI);}
								if($MovSI=="Debito"){if(!$PDF){echo "<td align='right'>".number_format($SaldoI,2)."</td><td align='right'>0.00</td>";}$SaldoICCDB=$SaldoI;
								if(strlen($filaCta[0])==1){$TotDebitosSI=$TotDebitosSI+$SaldoI;}}
								else
								{
									if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoI,2)."</td>";}$SaldoICCCR=$SaldoI;
									if(strlen($filaCta[0])==1){$TotCreditosSI=$TotCreditosSI+$SaldoI;}
								}

								if(!$PDF){echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";}

								if($filaCta[3]=="Debito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCCCR=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
									else{if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFCCDB=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
								}
								elseif($filaCta[3]=="Credito")
								{
									if($SaldoF<0){$SaldoF=$SaldoF*-1;if(!$PDF){echo "<td align='right'>".number_format($SaldoF,2)."</td><td align='right'>0.00</td>";}$SaldoFCCDB=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFDeb=$TotSFDeb+$SaldoF;}}
									else{if(!$PDF){echo "<td align='right'>0.00</td><td align='right'>".number_format($SaldoF,2)."</td>";}$SaldoFCCCR=$SaldoF;
									if(strlen($filaCta[0])==1){$TotSFCred=$TotSFCred+$SaldoF;}}
								}
								$Datos[$NumRec]=array($filaCta[0],"				$Identid[0] $Identificacion[2]",$SaldoICCDB,$SaldoICCCR,$Debitos,$Creditos,$SaldoFCCDB,$SaldoFCCCR);

							}
						}}
					}
				}
				if($filaCta[0]=="1"){$TotActivo=$SaldoF;}
				if($filaCta[0]=="2"){$TotPasivo=$SaldoF;}
				if($filaCta[0]=="3"){$TotPatrimonio=$SaldoF;}
				$Muestre="N";
				$SaldoI=0;
			}
			$BuscCargos=array("Representante","Contador");
			foreach($BuscCargos as $GenCargos)
			{
				$cons="Select Nombre,Cargo from Central.CargosxCompania where Compania='$Compania[0]' and FechaIni<='$PerFin' and FechaFin>='$PerFin' and Categoria='$GenCargos'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);

				$DatoCargo[$GenCargos][0]=$fila[0];
				$DatoCargo[$GenCargos][1]=$fila[1];
			}
		$NumRec++;
		$Datos[$NumRec]=array("SUMAS","",$TotDebitosSI,$TotCreditosSI,$TotDebitosMov,$TotCreditosMov,$TotSFDeb,$TotSFCred);
		if(!$PDF){
			echo "<tr>";
			if($IncluyeCC=="on"){echo "<td colspan=3 align='right'>";}
			else{echo "<td class='filaTotalesInfContable' style='text-align:right:right; padding-right: 10px;' colspan=4 align='right'>";}
			echo "SUMAS IGUALES</td>";
			echo "<td class='filaTotalesInfContable' style='text-align:right:right; padding-right: 10px;'>".number_format($TotDebitosSI,2)."</td>";
			echo "<td class='filaTotalesInfContable' style='text-align:right:right; padding-right: 10px;'>".number_format($TotCreditosSI,2)."</td>";
			echo "<td class='filaTotalesInfContable' style='text-align:right:right; padding-right: 10px;' >".number_format($TotDebitosMov,2)."</td>";
			echo "<td class='filaTotalesInfContable' style='text-align:right:right; padding-right: 10px;' >".number_format($TotCreditosMov,2)."</td>";

			echo "<td class='filaTotalesInfContable' style='text-align:right:right; padding-right: 10px;'>".number_format($TotSFDeb,2)."</td>";
			echo "<td class='filaTotalesInfContable' style='text-align:right:right; padding-right: 10px;'>".number_format($TotSFCred,2)."</td>";
			echo "</tr>";
		?>
	</table>
	<br><center>

<br><br>

<table border="0">
<tr><td>______________________________</td><td style="width:130px;"></td><td>______________________________</td><td style="width:130px;"></td></tr>
<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<td><? echo $DatoCargo['Representante'][0]?></td><td></td><td><? echo $DatoCargo['Contador'][0]?></td><td></td></tr>
<tr style="font-weight:bold;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<td><? echo $DatoCargo['Representante'][1]?></td><td></td><td><? echo $DatoCargo['Contador'][1] ?></td></tr>
</table>
</center>
</div>
</body>
<?	}


class PDF extends FPDF
{
	function BasicTable($data)
	{
		$Anchos=array(25,90,25,25,25,25,25,25);
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				if($x==1){$col=substr($col,0,50);}
				if($x>1){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if($col=="SUMAS"){$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',8);}
				$this->Cell($Anchos[$x],5,$col,1,0,$Alinea,$fill);
				$x++;
			}
			$this->Ln();
		}
	}

//Cabecera de página
function Header()
{
	global $Compania;global $PerFin;
    //Logo
//    $this->Image('/Imgs/Logo.jpg',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha

    //Título
    $this->Cell(0,8,strtoupper($Compania[0]),0,0,'C');
    //Salto de línea
    $this->Ln(5);
    $this->SetFont('Arial','B',10);
    $this->Cell(0,8,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"BALANCE DE PRUEBA x CENTROS DE COSTO",0,0,'C');
    $this->Ln(5);
    $this->Cell(0,8,"CORTE: $PerFin",0,0,'C');
    $this->Ln(10);
    $this->Cell(25,10,"Codigo",1,0,'C');
    $this->Cell(90,10,"Descripcion",1,0,'C');
    $this->Cell(50,5,"Saldo Anterior",1,0,'C');
    $this->Cell(50,5,"Movimientos del Periodo",1,0,'C');
    $this->Cell(50,5,"Saldo Final",1,0,'C');
    $this->Ln(5);
    $this->Cell(115,5,"",0,0,'C');
    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Cell(25,5,"Debitos",1,0,'C');
    $this->Cell(25,5,"Creditos",1,0,'C');

    $this->Ln(5);
}

//Pie de página
function Footer()
{
	global $ND;
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    $this->Ln(3);
    $this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
}
}

$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$pdf->BasicTable($Datos);

$pdf->Ln(20);
$pdf->Cell(120,8,"____________________________________",0,0,'C');
$pdf->Cell(120,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(120,8,$DatoCargo['Representante'][0],0,0,'C');
$pdf->Cell(120,8,$DatoCargo['Contador'][0],0,0,'C');
$pdf->Ln(5);
$pdf->Cell(120,8,$DatoCargo['Representante'][1],0,0,'C');
$pdf->Cell(120,8,$DatoCargo['Contador'][1],0,0,'C');

if($PDF){$pdf->Output();}

?>