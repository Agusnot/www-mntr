<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	$ND=getdate();
	if($RetirarCierre)
	{
		$cons="Delete from Presupuesto.Movimiento where Compania='$Compania[0]' and date_part('year',Fecha)=$AnioMas and Vigencia!='Actual'";
		$res=ExQuery($cons);
		
		$cons="Delete from Presupuesto.PlanCuentas where Compania='$Compania[0]' and Anio=$AnioMas and Vigencia!='Actual'";
		$res=ExQuery($cons);
	}

	if(!$AnioSel){$AnioSel=$ND[year];}
	$Anio=$AnioSel;$MesIni=1;$MesFin=12;
	include("Informes/Presupuesto/Reportes/GeneraValoresEjecucion2.php");
	$Apropiacion=GeneraApropiacion();
	$Valores=GeneraValores();
	include("CalcularSaldos.php");

	if($Iniciar)
	{

		ObtieneValoresxDocxCuenta("$Anio-01-01","$Anio-12-31",'Reconocimiento presupuestal');
		$PerIni="$Anio-01-01";
		$PerFin="$Anio-12-31";

		$AnioSig=$AnioSel+1;
		$cons="Select Cuenta,Nombre,Naturaleza,Tipo from Presupuesto.PlanCuentas where Cuenta ilike '1%' and Anio=$AnioSel and Vigencia='Actual' and Compania='$Compania[0]' and Tipo='Detalle' Order By Cuenta";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$ApropIni=$Apropiacion[$fila[0]];
			$Cuenta=$fila[0];

			$TotReconoc=$Valores["Reconocimiento presupuestal"][$fila[0]]["CCredito"]-$Valores["Disminucion a reconocimiento"][$fila[0]]["Credito"];
			$IngTotales=$Valores["Ingreso presupuestal"][$fila[0]]["CCredito"]-$Valores["Disminucion a ingreso presupuestal"][$fila[0]]["Credito"];

			$ReconocSinAfectar=$TotReconoc-$IngTotales;

			if($ReconocSinAfectar>0)
			{

				$NumCar=strlen($fila[0]);
				$cons2="Select NoCaracteres from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioSig Order By Nivel";
				$res2=ExQuery($cons2,$conex);
				while($fila2=ExFetch($res2))
				{
					$NoCaracteres=$NoCaracteres+$fila2[0];
					if($NumCar<=$NoCaracteres){$NoCaracteres=0;break;}
					$ParteCta=substr($fila[0],0,$NoCaracteres);
					$cons3="Select Cuenta from Presupuesto.PlanCuentas where Cuenta = '$ParteCta' and Anio=$AnioSig and Vigencia='Anteriores'  and ClaseVigencia='CxP'
					and Compania='$Compania[0]'";
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)==0)
					{
						$cons4="Select Cuenta,Nombre,Naturaleza from Presupuesto.PlanCuentas where Cuenta = '$ParteCta' and Anio=$AnioSig and Vigencia='Actual' and Compania='$Compania[0]'";
						$res4=ExQuery($cons4);
						if(ExNumRows($res4)>0)
						{
							$fila4=ExFetch($res4);
							$cons5="Insert into Presupuesto.PlanCuentas(Compania,Anio,Cuenta,Nombre,Naturaleza,Tipo,Vigencia,ClaseVigencia)
							values('$Compania[0]',$AnioSig,$ParteCta,'$fila4[1]','$fila4[2]','Titulo','Anteriores','CxP')";
							$res5=ExQuery($cons5);
						}
						else
						{
							$cons5="Insert into Presupuesto.PlanCuentas(Compania,Anio,Cuenta,Nombre,Naturaleza,Tipo,Vigencia,ClaseVigencia)
							values('$Compania[0]',$AnioSig,$ParteCta,'$ParteCta','Contra Credito','Titulo','Anteriores','CxP')";
							$res5=ExQuery($cons5);
						}
					}
				}
				

				$cons2="Insert into Presupuesto.PlanCuentas(Compania,Anio,Cuenta,Nombre,Naturaleza,Tipo,Apropiacion,Vigencia,ClaseVigencia)
				values('$Compania[0]',$AnioSig,'$fila[0]','$fila[1]','$fila[2]','$fila[3]',$ReconocSinAfectar,'Anteriores','CxP')";
				$res2=ExQuery($cons2);

				$cons9="Select Cuenta,Fecha,Movimiento.Comprobante,Numero,Detalle,'','',sum(Credito),sum(ContraCredito),Identificacion,'',
				Vigencia,ClaseVigencia 
				from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and
				Cuenta = '$fila[0]' and Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31' and (TipoComprobant='Reconocimiento presupuestal')
				and Vigencia='Actual'
				and Estado='AC' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' 
				Group By Cuenta,Movimiento.Comprobante,Numero,Fecha,Detalle,Identificacion,
				Vigencia,ClaseVigencia 
				
				Order By Numero";
				$res9=ExQuery($cons9);
//				echo "Valor-->".$CompromisosSinAfecta."<br>";
				while($fila9=ExFetch($res9))
				{
					$Valor=CalcularSaldoxDocxCuenta($fila9[0],$fila9[3],$fila9[2],"$Anio-01-01","$Anio-12-31","Actual","");
					//echo "$fila9[2] $fila9[3] --> $Valor<br>";
					if($Valor!=0)
					{
//						echo "<font color='#ff0000'>$Valor</font><br>";;
					//	echo "$fila9[0]-$fila9[1]-$fila9[2]-$fila9[3]-$fila9[4]-$fila9[6]-$fila9[7]-$fila9[8]<br>";
						$AutoId++;

////////////CREAMOS EL COMPROMISO
						$cons100="Select CompDestino from Presupuesto.CruceComprobantes where CompOrigen='Compromiso presupuestal'";
						$res100=ExQuery($cons100);
						$fila100=ExFetch($res100);

						$cons101="Select Comprobante from Presupuesto.Comprobantes where TipoComprobant='$fila100[0]'";
						$res101=ExQuery($cons101);
						$fila101=ExFetch($res101);


						$cons10="Insert Into Presupuesto.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,Compania,UsuarioCre,FechaCre,Estado,DocOrigen,CompAfectado,Vigencia,ClaseVigencia,Anio)
						values($AutoId,'$AnioSig-01-01','$fila9[2]',$fila9[3],'$fila9[9]','$fila9[4] (CxP Vigencia Anterior)','$fila9[0]','$Valor','0','$fila9[3]','$Compania[0]','Cierre Fiscal $Anio','$ND[year]-$ND[mon]-$ND[mday]','AC','','$fila101[0]','Anteriores','Reservas',$AnioSig)";
						$res10=ExQuery($cons10);
					}
				}
			}
		}
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table cellpadding="6"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="<?echo $Estilo[1]?>" style="color:white;font-weight:bold"><td align="center">A&ntilde;o</td><td>
<select name="AnioSel" onChange="document.FORMA.submit();">
<?	
	$AnioInc=$AnioSel-10;
	$AnioAf=$AnioSel+10;
	for($i=$AnioInc;$i<$AnioAf;$i++)
	if($i==$AnioSel){echo "<option selected value=$i>$i</option>";}
	else{echo "<option value=$i>$i</option>";}
?>
</select>
</td></tr>
</table>
<?
	$AnioMas=$AnioSel+1;
	$cons="Select * from Presupuesto.Movimiento where date_part('year',Fecha)='$AnioMas' and Vigencia!='Actual' and Compania='$Compania[0]' and Estado='AC'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{
?>
<br><input type="Submit" name="Iniciar" value="Iniciar">
<?	}
	else
	{
		echo "<br><em>Proceso ya ejecutado para esta vigencia</em><br>";
		echo "<br>";
		$cons2="Select * from Presupuesto.Movimiento where date_part('year',Fecha)='$AnioMas' and Vigencia!='Actual' and UsuarioCre!='Cierre Fiscal $AnioSel' and Compania='$Compania[0]' and Estado='AC'";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{
			echo "<em>*** Existe afectaci&oacute;n sobre el cierre del periodo $AnioSel, NO es posible retirar cierre ***</em>";
		}
		else
		{
			echo "<input type='Button' value='Retirar Cierre' onclick=location.href='CierreFiscal.php?DatNameSID=$DatNameSID&AnioSel=$AnioSel&RetirarCierre=1&AnioMas=$AnioMas'>";
		}
	}
	?>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>

</body>