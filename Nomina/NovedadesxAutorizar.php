<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	$Fec="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
	$cont=0;
if($Guardar)
{
	if($EstadoInc)
	{
		while( list($cad,$val) = each($EstadoInc))
		{
//			echo "$cad $val<br>";
			$partes=explode("_",$cad);
			if($val=='Aprobado')
			{
				$cons="select fecinicio,fecfinal,concepto,dias from nomina.incapacidades where compania='$Compania[0]' and identificacion='$partes[1]' and numero='$partes[0]'";
//				echo $cons."<br>";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$FecInicio=$fila[0];
				$FecFinal=$fila[1];
				$RegNomina=$fila[2];
				$Dias=$fila[3];
//				echo $FecInicio." --> ".$FecFinal." --> ".$partes[1]." --> ".$partes[0]."<br>";
				$AnoI=substr($FecInicio,0,4);
				$MesI=substr($FecInicio,5,2);
				$DiaI=substr($FecInicio,8,2);
				$AnoF=substr($FecFinal,0,4);
				$MesF=substr($FecFinal,5,2);
				$DiaF=substr($FecFinal,8,2);
				if($AnoI==$AnoF)
				{
					$cons="update nomina.incapacidades set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
//					echo $cons."<br>";
					$res=ExQuery($cons);
					if($MesI==$MesF)
					{
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Incapacidades','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
//						echo $cons."<br>";
					}
					while($MesI<$MesF)
					{
						$Nov=31-$DiaI;
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Incapacidades','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
//						echo $cons."<br>";
						$Dias=$Dias-$Nov;
						$MesI++;
						while($Dias>30)
						{
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Incapacidades','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
							$res=ExQuery($cons);
//							echo $cons."<br>";
							$Dias=$Dias-30;
							$MesI++;
						}
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Incapacidades','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
//						echo $cons."<br>";
					}
					?>
					<script language="javascript">alert("Las Incapacidades han sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
				elseif($AnoI<$AnoF)
				{
					$cons="select * from nomina.salarios where anio='$AnoF' and identificacion='$Identificacion' and mesi <= $MesI and mesf >= $MesI";
//					echo $cons."<br>";
					$res=ExQuery($cons);
					$CAnos=ExNumRows($res);
//					echo $CAnos."<br>";
					if($CAnos==0)
					{
						?><script language="javascript">alert("no hay salario para este año !!");</script><?
					}
					if($CAnos==1)
					{
//						$partes=explode("_",$cad);
						$cons="update nomina.incapacidades set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
//						echo $cons."<br>";
						$res=ExQuery($cons);
						while($MesI<=12)
						{
							$Nov=31-$DiaI;
							$Dias=$Dias-$Nov;
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Incapacidades','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
//							echo $cons."<br>";
							if($Dias>30)
							{
								$DiaI=1;
							}
							$MesI++;
						}
						$AnoI++;
						if($AnoI==$AnoF)
						{
							$MesI=1;
							if($MesI==$MesF)
							{
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Incapacidades','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
//								echo $cons."<br>";
							}
							while($MesI<$MesF)
							{
								$Nov=31-$DiaI;
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Incapacidades','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
//								echo $cons."<br>";
								$Dias=$Dias-$Nov;
								$MesI++;
								while($Dias>30)
								{
									$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Incapacidades','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
									$res=ExQuery($cons);
//									echo $cons."<br>";
									$Dias=$Dias-30;
									$MesI++;
								}
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Incapacidades','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
//								echo $cons."<br>";
							}
						}
					}
					?>
					<script language="javascript">alert("Las Incapacidades han sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
			}
			elseif($val=='Rechazado')
			{
				$cons="update nomina.incapacidades set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
//				echo $cons."<br>";
				$res=ExQuery($cons);
			}
		}
	}
	if($EstadoLic)
	{
		while( list($cad,$val) = each($EstadoLic))
		{
			$partes=explode("_",$cad);
			if($val=='Aprobado')
			{
				$cons="select fecinicio,fecfinal,concepto,dias from nomina.Licencias where compania='$Compania[0]' and identificacion='$partes[1]' and numero='$partes[0]'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$FecInicio=$fila[0];
				$FecFinal=$fila[1];
				$RegNomina=$fila[2];
				$Dias=$fila[3];
				//echo $FecInicio." --> ".$FecFinal." --> ".$partes[1]." --> ".$partes[0];
				$AnoI=substr($FecInicio,0,4);
				$MesI=substr($FecInicio,5,2);
				$DiaI=substr($FecInicio,8,2);
				$AnoF=substr($FecFinal,0,4);
				$MesF=substr($FecFinal,5,2);
				$DiaF=substr($FecFinal,8,2);
				if($AnoI==$AnoF)
				{
					$cons="update nomina.licencias set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
					$res=ExQuery($cons);
					if($MesI==$MesF)
					{
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Licencias','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons;
					}
					while($MesI<$MesF)
					{
						$Nov=31-$DiaI;
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Licencias','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons."<br>";
						$Dias=$Dias-$Nov;
						$MesI++;
						while($Dias>30)
						{
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Licencias','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
							$res=ExQuery($cons);
							//echo $cons."<br>";
							$Dias=$Dias-30;
							$MesI++;
						}
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Licencias','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons."<br>";
					}
					?>
					<script language="javascript">alert("Las Licencias ha sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
				elseif($AnoI<$AnoF)
				{
					
					$cons="select * from nomina.salarios where anio='$AnoF' and identificacion='$Identificacion' and mesi <= $MesI and mesf >= $MesI";
					//echo $cons."<br>";
					$res=ExQuery($cons);
					$CAnos=ExNumRows($res);
					//echo $CAnos."<br>";
					if($CAnos==0)
					{
						?><script language="javascript">alert("no hay salario para este año !!");</script><?
					}
					if($CAnos==1)
					{
						$cons="update nomina.licencias set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($MesI<=12)
						{
							$Nov=31-$DiaI;
							$Dias=$Dias-$Nov;
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Licencias','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
							//echo $cons."<br>";
							if($Dias>30)
							{
								$DiaI=1;
							}
							$MesI++;
						}
						$AnoI++;
						if($AnoI==$AnoF)
						{
							$MesI=1;
							if($MesI==$MesF)
							{
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Licencias','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons;
							}
							while($MesI<$MesF)
							{
								$Nov=31-$DiaI;
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Licencias','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons."<br>";
								$Dias=$Dias-$Nov;
								$MesI++;
								while($Dias>30)
								{
									$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Licencias','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
									$res=ExQuery($cons);
									//echo $cons."<br>";
									$Dias=$Dias-30;
									$MesI++;
								}
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Licencias','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons."<br>";
							}
						}
					}
					?>
					<script language="javascript">alert("Las Licencias ha sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
			}
			elseif($val=='Rechazado')
			{
				$cons="update nomina.licencias set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
//				echo $cons."<br>";
				$res=ExQuery($cons);
			}
		}
	}
	if($EstadoSus)
	{
		while( list($cad,$val) = each($EstadoSus))
		{
			$partes=explode("_",$cad);
			if($val=='Aprobado')
			{
				$cons="select fecinicio,fecfinal,concepto,dias from nomina.Suspensiones where compania='$Compania[0]' and identificacion='$partes[1]' and numero='$partes[0]'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$FecInicio=$fila[0];
				$FecFinal=$fila[1];
				$RegNomina=$fila[2];
				$Dias=$fila[3];
				//echo $FecInicio." --> ".$FecFinal." --> ".$partes[1]." --> ".$partes[0];
				$AnoI=substr($FecInicio,0,4);
				$MesI=substr($FecInicio,5,2);
				$DiaI=substr($FecInicio,8,2);
				$AnoF=substr($FecFinal,0,4);
				$MesF=substr($FecFinal,5,2);
				$DiaF=substr($FecFinal,8,2);
				if($AnoI==$AnoF)
				{
					$cons="update nomina.Suspensiones set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
					$res=ExQuery($cons);
					if($MesI==$MesF)
					{
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Suspensiones','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons;
					}
					while($MesI<$MesF)
					{
						$Nov=31-$DiaI;
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Suspensiones','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons."<br>";
						$Dias=$Dias-$Nov;
						$MesI++;
						while($Dias>30)
						{
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Suspensiones','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
							$res=ExQuery($cons);
							//echo $cons."<br>";
							$Dias=$Dias-30;
							$MesI++;
						}
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Suspensiones','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons."<br>";
					}
					?>
					<script language="javascript">alert("Las Suspensiones ha sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
				elseif($AnoI<$AnoF)
				{
					
					$cons="select * from nomina.salarios where anio='$AnoF' and identificacion='$Identificacion' and mesi <= $MesI and mesf >= $MesI";
					//echo $cons."<br>";
					$res=ExQuery($cons);
					$CAnos=ExNumRows($res);
					//echo $CAnos."<br>";
					if($CAnos==0)
					{
						?><script language="javascript">alert("no hay salario para este año !!");</script><?
					}
					if($CAnos==1)
					{
						$cons="update nomina.Suspensiones set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($MesI<=12)
						{
							$Nov=31-$DiaI;
							$Dias=$Dias-$Nov;
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Suspensiones','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
							//echo $cons."<br>";
							if($Dias>30)
							{
								$DiaI=1;
							}
							$MesI++;
						}
						$AnoI++;
						if($AnoI==$AnoF)
						{
							$MesI=1;
							if($MesI==$MesF)
							{
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Suspensiones','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons;
							}
							while($MesI<$MesF)
							{
								$Nov=31-$DiaI;
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Suspensiones','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons."<br>";
								$Dias=$Dias-$Nov;
								$MesI++;
								while($Dias>30)
								{
									$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Suspensiones','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
									$res=ExQuery($cons);
									//echo $cons."<br>";
									$Dias=$Dias-30;
									$MesI++;
								}
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Suspensiones','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons."<br>";
							}
						}
					}
					?>
					<script language="javascript">alert("Las Suspensiones ha sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
			}
			elseif($val=='Rechazado')
			{
				$cons="update nomina.suspenciones set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
//				echo $cons."<br>";
				$res=ExQuery($cons);
			}
		}
	}
	if($EstadoVac)
	{
		while( list($cad,$val) = each($EstadoVac))
		{
			$partes=explode("_",$cad);
			if($val=='Aprobado')
			{
				$cons="select fecinicio,fecfinal,concepto,dias from nomina.Vacaciones where compania='$Compania[0]' and identificacion='$partes[1]' and numero='$partes[0]'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$FecInicio=$fila[0];
				$FecFinal=$fila[1];
				$RegNomina=$fila[2];
				$Dias=$fila[3];
				//echo $FecInicio." --> ".$FecFinal." --> ".$partes[1]." --> ".$partes[0];
				$AnoI=substr($FecInicio,0,4);
				$MesI=substr($FecInicio,5,2);
				$DiaI=substr($FecInicio,8,2);
				$AnoF=substr($FecFinal,0,4);
				$MesF=substr($FecFinal,5,2);
				$DiaF=substr($FecFinal,8,2);
				if($AnoI==$AnoF)
				{
					$cons="update nomina.vacaciones set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
					$res=ExQuery($cons);
					if($MesI==$MesF)
					{
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Vacaciones','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons;
					}
					while($MesI<$MesF)
					{
						$Nov=31-$DiaI;
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Vacaciones','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons."<br>";
						$Dias=$Dias-$Nov;
						$MesI++;
						while($Dias>30)
						{
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Vacaciones','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
							$res=ExQuery($cons);
							//echo $cons."<br>";
							$Dias=$Dias-30;
							$MesI++;
						}
						$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$partes[1]','Vacaciones','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
						$res=ExQuery($cons);
						//echo $cons."<br>";
					}
					?>
					<script language="javascript">alert("Las Vacaciones ha sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
				elseif($AnoI<$AnoF)
				{
					
					$cons="select * from nomina.salarios where anio='$AnoF' and identificacion='$Identificacion' and mesi <= $MesI and mesf >= $MesI";
					//echo $cons."<br>";
					$res=ExQuery($cons);
					$CAnos=ExNumRows($res);
					//echo $CAnos."<br>";
					if($CAnos==0)
					{
						?><script language="javascript">alert("no hay salario para este año !!");</script><?
					}
					if($CAnos==1)
					{
						$cons="update nomina.Vacaciones set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($MesI<=12)
						{
							$Nov=31-$DiaI;
							$Dias=$Dias-$Nov;
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Vacaciones','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
							//echo $cons."<br>";
							if($Dias>30)
							{
								$DiaI=1;
							}
							$MesI++;
						}
						$AnoI++;
						if($AnoI==$AnoF)
						{
							$MesI=1;
							if($MesI==$MesF)
							{
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Vacaciones','$Dias','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons;
							}
							while($MesI<$MesF)
							{
								$Nov=31-$DiaI;
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Vacaciones','$Nov','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons."<br>";
								$Dias=$Dias-$Nov;
								$MesI++;
								while($Dias>30)
								{
									$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Vacaciones','30','$MesI','$AnoI','$partes[0]','$RegNomina')";
									$res=ExQuery($cons);
									//echo $cons."<br>";
									$Dias=$Dias-30;
									$MesI++;
								}
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto) values ('$Compania[0]','$Identificacion','Vacaciones','$DiaF','$MesI','$AnoI','$partes[0]','$RegNomina')";
								$res=ExQuery($cons);
								//echo $cons."<br>";
							}
						}
					}
					?>
					<script language="javascript">alert("Las Vacaciones ha sido Guardadas !!!");</script>
					<script language="javascript">location.href="NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>";</script>
					<?
				}
			}
			elseif($val=='Rechazado')
			{
				$cons="update nomina.vacaciones set estado='$val' where numero='$partes[0]' and identificacion='$partes[1]' and compania='$Compania[0]'";
//				echo $cons."<br>";
				$res=ExQuery($cons);
			}
		}
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/calendario/popcalendar.js"></script>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function ValidarInc(Numero,Identificacion,Resolucion,Autorizacion,Estado)
{
	//alert(Numero+' '+Identificacion+' '+Autorizacion+' '+Resolucion+' '+Estado)
	if(document.getElementById(Estado).value!='')
	{
		if(document.getElementById(Autorizacion).value==''){alert("Ingrese un valor en la autorizacion para la identificacion "+Identificacion);return false;}
		if(document.getElementById(Resolucion).value==''){alert("Ingrese un valor en la resolucion para la identificacion "+Identificacion);return false;}
	}
	location.href='NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>&GuardarInc=1&Identificacion='+Identificacion+'&Numero='+Numero+'&Autorizacion='+document.getElementById(Autorizacion).value+'&Resolucion='+document.getElementById(Resolucion).value+'&Estado='+document.getElementById(Estado).value;
}

function ValidarLic(Numero,Identificacion,Resolucion,Autorizacion,Estado)
{
	//alert(Numero+' '+Identificacion+' '+Autorizacion+' '+Resolucion+' '+Estado)
	if(document.getElementById(Estado).value!='')
	{
		if(document.getElementById(Autorizacion).value==''){alert("Ingrese un valor en la autorizacion para la identificacion "+Identificacion);return false;}
		if(document.getElementById(Resolucion).value==''){alert("Ingrese un valor en la resolucion para la identificacion "+Identificacion);return false;}
	}
	location.href='NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>&GuardarLic=1&Identificacion='+Identificacion+'&Numero='+Numero+'&Autorizacion='+document.getElementById(Autorizacion).value+'&Resolucion='+document.getElementById(Resolucion).value+'&Estado='+document.getElementById(Estado).value;
}

function ValidarSus(Numero,Identificacion,Resolucion,Autorizacion,Estado)
{
	//alert(Numero+' '+Identificacion+' '+Autorizacion+' '+Resolucion+' '+Estado)
	if(document.getElementById(Estado).value!='')
	{
		if(document.getElementById(Autorizacion).value==''){alert("Ingrese un valor en la autorizacion para la identificacion "+Identificacion);return false;}
		if(document.getElementById(Resolucion).value==''){alert("Ingrese un valor en la resolucion para la identificacion "+Identificacion);return false;}
	}
	location.href='NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>&GuardarSus=1&Identificacion='+Identificacion+'&Numero='+Numero+'&Autorizacion='+document.getElementById(Autorizacion).value+'&Resolucion='+document.getElementById(Resolucion).value+'&Estado='+document.getElementById(Estado).value;
}

function ValidarVac(Numero,Identificacion,Resolucion,Autorizacion,Estado)
{
	//alert(Numero+' '+Identificacion+' '+Autorizacion+' '+Resolucion+' '+Estado)
	if(document.getElementById(Estado).value!='')
	{
		if(document.getElementById(Autorizacion).value==''){alert("Ingrese un valor en la autorizacion para la identificacion "+Identificacion);return false;}
		if(document.getElementById(Resolucion).value==''){alert("Ingrese un valor en la resolucion para la identificacion "+Identificacion);return false;}
	}
	location.href='NovedadesxAutorizar.php?DatNameSID=<? echo $DatNameSID?>&GuardarVac=1&Identificacion='+Identificacion+'&Numero='+Numero+'&Autorizacion='+document.getElementById(Autorizacion).value+'&Resolucion='+document.getElementById(Resolucion).value+'&Estado='+document.getElementById(Estado).value;
}

</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >
<?
//--------------------------------consulta para incapacidades
	$consInc="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,conceptosliquidacion.detconcepto,fecinicio,fecfinal,resolucion,autorizacion,estado,incapacidades.concepto,incapacidades.numero from nomina.incapacidades,nomina.conceptosliquidacion,central.terceros where incapacidades.compania='$Compania[0]' and incapacidades.compania=conceptosliquidacion.compania and incapacidades.identificacion=terceros.identificacion and incapacidades.compania=terceros.compania and conceptosliquidacion.concepto=incapacidades.concepto and estado='' order by fecinicio";
	$resInc=ExQuery($consInc);
	$contInc=(ExNumRows($resInc));
	if($contInc>0)
	{
		?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma; width:100%' align="center">
		<tr bgcolor="#666699"style="color:white" align="center"><td colspan="9">LISTADO DE INCAPACIDADES SIN AUTORIZAR</td>
		<tr align="center"><td>Identificacion</td><td>Nombre</td><td>Detalle</td><td>Fecha Inicio</td><td>Fecha Fin</td><td>Estado</td>
		</tr>
	<?
		while ($filaInc = ExFetch($resInc))
		{
		?>
			<tr align="center">
            <td><? echo $filaInc[0]; ?></td><td><? echo $filaInc[1]." ".$filaInc[2]." ".$filaInc[3]." ".$filaInc[4]; ?></td><td><? echo $filaInc[5]?></td><td><? echo $filaInc[6]?></td><td><? echo $filaInc[7 ]?></td>
            <td style="width:100px"><select id="EstadoInc[<? echo "$filaInc[12]_$filaInc[0]"?>]" name="EstadoInc[<? echo "$filaInc[12]_$filaInc[0]"?>]"  style="width:100%">
			<option></option>
            <option value="Aprobado" <? if($filaInc[10]=="Aprobado"){echo "selected";}?>>Aprobado</option>
            <option value="Rechazado" <? if($filaInc[10]=="Rechazado"){echo "selected";}?>>Rechazado</option>            
    		</select></td>
            
			</tr>
	<?	}	?>
		</tr>
		</table>
<?	}
	else
	{?>
		<center>No hay Incapacidades pendientes !!!</center>
	<?
	$cont=$cont+1;
	}
//----------------------------consulta para licencias	
	$consLic="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,conceptosliquidacion.detconcepto,fecinicio,fecfinal,resolucion,autorizacion,estado,licencias.concepto,licencias.numero from nomina.licencias,nomina.conceptosliquidacion,central.terceros where licencias.compania='$Compania[0]' and licencias.compania=conceptosliquidacion.compania and licencias.identificacion=terceros.identificacion and licencias.compania=terceros.compania and conceptosliquidacion.concepto=licencias.concepto and estado='' order by fecinicio";
	//echo $consLic;
	$resLic=ExQuery($consLic);
	$contLic=(ExNumRows($resLic));	
	if($contLic>0)
	{
		?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma; width:100%' align="center">
		<tr bgcolor="#666699"style="color:white" align="center"><td colspan="9">LISTADO DE LICENCIAS SIN AUTORIZAR</td>
		<tr align="center"><td>Identificacion</td><td>Nombre</td><td>Detalle</td><td>Fecha Inicio</td><td>Fecha Fin</td><td>Estado</td>
		</tr>
	<?
		while ($filaLic = ExFetch($resLic))
		{
		?>
			<tr align="center">
            <td><? echo $filaLic[0]; ?></td><td><? echo $filaLic[1]." ".$filaLic[2]." ".$filaLic[3]." ".$filaLic[4]; ?></td><td><? echo $filaLic[5]?></td><td><? echo $filaLic[6]?></td><td><? echo $filaLic[7]?></td>
            <td style="width:100px"><select id="EstadoLic[<? echo "$filaLic[12]_$filaLic[0]"?>]" name="EstadoLic[<? echo "$filaLic[12]_$filaLic[0]"?>]"  style="width:100%">
			<option></option>
            <option value="Aprobado" <? if($filaLic[10]=="Aprobado"){echo "selected";}?>>Aprobado</option>
            <option value="Rechazado" <? if($filaLic[10]=="Rechazado"){echo "selected";}?>>Rechazado</option>            
    		</select></td>
			</tr>
	<?	}	?>
		</tr>
		</table>
<?	}
	else
	{?>
		<center>No hay Licencias pendientes !!!</center>
	<?
	$cont=$cont+1;	
	}
//--------------------------------consulta para Suspensiones
	$consSus="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,conceptosliquidacion.detconcepto,fecinicio,fecfinal,resolucion,autorizacion,estado,suspensiones.concepto,suspensiones.numero from nomina.suspensiones,nomina.conceptosliquidacion,central.terceros where suspensiones.compania='$Compania[0]' and suspensiones.compania=conceptosliquidacion.compania and suspensiones.identificacion=terceros.identificacion and suspensiones.compania=terceros.compania and conceptosliquidacion.concepto=suspensiones.concepto and estado='' order by fecinicio";
//	echo $consSus;
	$resSus=ExQuery($consSus);
	$contSus=(ExNumRows($resSus));
	if($contSus>0)
	{
		?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma; width:100%' align="center">
		<tr bgcolor="#666699"style="color:white" align="center"><td colspan="9">LISTADO DE SUSPENSIONES SIN AUTORIZAR</td>
		<tr align="center"><td>Identificacion</td><td>Nombre</td><td>Detalle</td><td>Fecha Inicio</td><td>Fecha Fin</td><td>Estado</td>
		</tr>
	<?
		while ($filaSus = ExFetch($resSus))
		{
		?>
			<tr align="center">
            <td><? echo $filaSus[0]; ?></td><td><? echo $filaSus[1]." ".$filaSus[2]." ".$filaSus[3]." ".$filaSus[4]; ?></td><td><? echo $filaSus[5]?></td><td><? echo $filaSus[6]?></td><td><? echo $filaSus[7]?></td>
            <td style="width:100px"><select id="EstadoSus[<? echo "$filaSus[12]_$filaSus[0]"?>]" name="EstadoSus[<? echo "$filaSus[12]_$filaSus[0]"?>]"  style="width:100%">
			<option></option>
            <option value="Aprobado" <? if($filaSus[10]=="Aprobado"){echo "selected";}?>>Aprobado</option>
            <option value="Rechazado" <? if($filaSus[10]=="Rechazado"){echo "selected";}?>>Rechazado</option>            
    		</select></td>
			</tr>
	<?	}	?>
		</tr>
		</table>
<?	}
	else
	{?>
		<center>No hay Suspensiones pendientes !!!</center>
	<?
	$cont=$cont+1;	
	}	
//--------------------------------consulta para vacaciones
	$consVac="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,conceptosliquidacion.detconcepto,fecinicio,fecfinal,resolucion,autorizacion,estado,vacaciones.concepto,vacaciones.numero from nomina.vacaciones,nomina.conceptosliquidacion,central.terceros where vacaciones.compania='$Compania[0]' and vacaciones.compania=conceptosliquidacion.compania and vacaciones.identificacion=terceros.identificacion and vacaciones.compania=terceros.compania and conceptosliquidacion.concepto=vacaciones.concepto and estado='' order by fecinicio";
	$resVac=ExQuery($consVac);
	$contVac=(ExNumRows($resVac));
	if($contVac>0)
	{
		?>
		<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma; width:100%' align="center">
		<tr bgcolor="#666699"style="color:white" align="center"><td colspan="9">LISTADO DE VACACIONES SIN AUTORIZAR</td>
		<tr align="center"><td>Identificacion</td><td>Nombre</td><td>Detalle</td><td>Fecha Inicio</td><td>Fecha Fin</td><td>Estado</td>
		</tr>
	<?
		while ($filaVac = ExFetch($resVac))
		{
		?>
			<tr align="center">
            <td><? echo $filaVac[0]; ?></td><td><? echo $filaVac[1]." ".$filaVac[2]." ".$filaVac[3]." ".$filaVac[4]; ?></td><td><? echo $filaVac[5]?></td><td><? echo $filaVac[6]?></td><td><? echo $filaVac[7]?></td>
            <td style="width:100px"><select id="EstadoVac[<? echo "$filaVac[12]_$filaVac[0]"?>]" name="EstadoVac[<? echo "$filaVac[12]_$filaVac[0]"?>]"  style="width:100%">
			<option></option>
            <option value="Aprobado" <? if($filaVac[10]=="Aprobado"){echo "selected";}?>>Aprobado</option>
            <option value="Rechazado" <? if($filaVac[10]=="Rechazado"){echo "selected";}?>>Rechazado</option>            
    		</select></td>
			</tr>
	<?	}	?>
		</tr>
		</table>
<?	}
	else
	{?>
		<center>No hay Vacaciones pendientes !!!</center>
	<?
	$cont=$cont+1;	
	}	
?>
<center><input type="submit" name="Guardar" value="Guardar" <? if($cont==4){ echo "disabled";}?> ></center>
</form>
</body>
</html>