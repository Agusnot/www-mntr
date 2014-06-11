<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
require('LibPDF/fpdf.php');
$Raiz=$_SERVER['DOCUMENT_ROOT'];
$contp=0;
ob_end_clean();
$pdf = new FPDF();
$pdf->SetFont('Arial','B',12);
$cons="select terceros.identificacion,mesi,mesf,salarios.salario,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape,
tiposvinculacion.tipovinculacion,contratos.numero,contratos.cargo from central.terceros,nomina.salarios,nomina.contratos,nomina.tiposvinculacion
where terceros.compania='$Compania[0]' and terceros.compania=salarios.compania and salarios.compania=contratos.compania and terceros.identificacion=salarios.identificacion and salarios.identificacion=contratos.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo and (terceros.tipo='Empleado' or regimen='Empleado') and contratos.estado='Activo'
and salarios.anio='$Anio' and salarios.mesi<='$Mes' and salarios.mesf>='$Mes' order by primape";
//echo $cons."<br>";
$res=ExQuery($cons);
while($fila=Exfetch($res))
{
	$Empleados[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[8]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
}
if($Empleados)
{
	foreach($Empleados as $Identi)
	{
		foreach($Identi as $MesI)
		{
			foreach($MesI as $MesF)
			{
				foreach($MesF as $Sal)
				{
					foreach($Sal as $Vinc)
					{
						$consvin="select codigo from nomina.tiposvinculacion where compania='$Compania[0]' and tipovinculacion='$Vinc[8]'";
						//echo $Vinc[8];
						$resvin=ExQuery($consvin);
						$filavin=ExFetch($resvin);
						$conscar="select cargo from nomina.cargos where compania='$Compania[0]' and vinculacion='$filavin[0]' and codigo='$Vinc[10]'";
						$rescar=ExQuery($conscar);
						$filacar=Exfetch($rescar);
						$conssal="select salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Vinc[0]' and anio<='$Anio' and aniof>='$Anio'
						and mesi<='$Mes' and mesf>='$Mes' and numcontrato='$Vinc[9]'";
						$ressal=ExQuery($conssal);
						$filasal=ExFetch($ressal);
						$cons="select identificacion from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]' and mes='$Mes' and anio='$Anio'";
						$res=ExQuery($cons);
						$cont=ExNumRows($res);
//						echo $cons;
						if($cont>0)
						{
							$TotDevengados=0;$TotDeducidos=0;$TotPostDevengados=0;$TotPostDeducidos=0;
							if($contp==0)
							{
								$pdf->AddPage();
								$Y=10;
							}
							$pdf->Cell(0,10,$Compania[0],0,1);
							$pdf->Ln(0);
							$pdf->Image($Raiz.'/Imgs/Logo.jpg',170,$Y,24,28);
							$pdf->Cell(0,10,$Compania[1],0,1);							
							$pdf->Cell(0,10,$Compania[2],0,1);
							$pdf->Cell(0,10,$Compania[3],0,1);
							$contp++;
							if($contp==2){$contp=0;}
							$cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]' 
							and mes='$Mes' and anio='$Anio' and movimiento='Devengados' and valor!=0 and claseregistro!='Cantidad'";
				//			echo $cons;
							$res=ExQuery($cons);
							while($fila1=ExFetch($res))
							{
								$pdf->Cell(50,10,$fila1[0],1,0);
								$pdf->Cell(20,10,"$ ".$fila1[1],1,0,R);
							}
							$cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]'
							 and mes='$Mes' and anio='$Anio' and movimiento='Deducidos' and valor!=0 and claseregistro!='Cantidad'";
					//		echo $cons;
							$res=ExQuery($cons);
							while($fila2=ExFetch($res))
							{
								$pdf->Cell(50,10,$fila2[0],1,0);
								$pdf->Cell(20,10,"$ ".$fila2[1],1,1,R);
							}
						}
					}
				}
			}
		}
	}
}
$pdf->Output();
?>