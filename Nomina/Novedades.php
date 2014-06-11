<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	$Fec="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
	$ConsTipV="select tiposvinculacion.tipovinculacion from nomina.contratos, central.terceros, nomina.tiposvinculacion where terceros.compania='$Compania[0]' and contratos.compania=terceros.compania and
	terceros.identificacion='$Identificacion' and contratos.identificacion=terceros.identificacion and contratos.estado='Activo' and contratos.tipovinculacion=tiposvinculacion.codigo";
	$resTipV=ExQuery($ConsTipV);
	$fila=ExFetch($resTipV);
	$TipVinculacion=$fila[0];
//	echo $TipVinculacion;
//	echo $TipVinculacion;
//	echo $resTipV[0];
//	echo $ConsTipV;
echo $Numero;
	if($Editar)
	{
		$cons="select $Novedad.concepto,detconcepto,fecinicio,dias,fecfinal,detalle,resolucion,autorizacion,id from nomina.$Novedad,nomina.conceptosliquidacion where $Novedad.compania='$Compania[0]' and $Novedad.compania=conceptosliquidacion.compania and $Novedad.identificacion='$Identificacion' and $Novedad.concepto=conceptosliquidacion.concepto and numero='$Numero'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if(!$RegNomina){$RegNomina=$fila[0];}
		if(!$FecInicio){$FecInicio=$fila[2];}
		if(!$Dias){$Dias=$fila[3];}
		if(!$FecFinal){$FecFinal=$fila[4];}
		if(!$Detalle){$Detalle=$fila[5];}
		if(!$Resolucion){$Resolucion=$fila[6];}
		if(!$Autorizacion){$Autorizacion=$fila[7];}
		if(!$IdNov){$IdNov=$fila[8];}
	}
	if($Guardar)
	{
		$AnoI=substr($FecInicio,0,4);
		$MesI=substr($FecInicio,5,2);
		$consN="select mes from nomina.nomina where anio='$AnoI' and mes='$MesI' and Identificacion='$Identificacion' and compania='$Compania[0]'";
		$resN=ExQuery($consN);
		$fila=ExFetch($resN);
//		echo "aqui".$fila[0];
		if(!$fila[0])
		{
	//		$IdNov=$Numero;
	//----------------------------------------------------------				
			$cons1="select * from nomina.$Novedad where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio <= '$FecInicio' and fecfinal >= '$FecInicio'";
	//		echo $cons1;
			$res=ExQuery($cons1);
			$cont=ExNumRows($res);
	//		echo $cont;
			if($cont==0)
			{
				if($Editar)
				{
					$cons="update nomina.$Novedad set concepto='$RegNomina',fecinicio='$FecInicio',dias='$Dias',fecfinal='$FecFinal',detalle='$Detalle',resolucion='$Resolucion', 
					autorizacion='$Autorizacion' where numero='$Numero' and id='$IdNov'";
					$res=ExQuery($con);
					//echo $cons;
				}
				else
				{
					$cons="select id from nomina.$Novedad where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$RegNomina' order by id desc";
	//				echo $cons;
					$res=ExQuery($cons);$fila=ExFetch($res);
					if($fila){$IdNov=$fila[0]+1;}else{$IdNov=1;}
					$AnoI=substr($FecInicio,0,4);
					$MesI=substr($FecInicio,5,2);
					$DiaI=substr($FecInicio,8,2);
					$AnoF=substr($FecFinal,0,4);
					$MesF=substr($FecFinal,5,2);
					$DiaF=substr($FecFinal,8,2);
	//				echo $AnoI." --> ".$MesI." --> ".$DiaI." hasta ".$AnoF." --> ".$MesF." --> ".$DiaF;
					if($AnoI==$AnoF)
					{
					$cons="insert into nomina.$Novedad (compania,identificacion,concepto,fecinicio,fecfinal,dias,detalle,resolucion,autorizacion,estado,usuario,fecha,numero,id) values ('$Compania[0]','$Identificacion','$RegNomina','$FecInicio','$FecFinal',$Dias,'$Detalle','$Resolucion','$Autorizacion','Aprobado','$usuario[1]','$Fec','$Numero','$IdNov')";
						$res=ExQuery($cons);
						if($MesI==$MesF)
						{
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','$Dias','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
							$res=ExQuery($cons);
							//echo $cons;
						}
						while($MesI<$MesF)
						{
							$Nov=31-$DiaI;
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','$Nov','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
							$res=ExQuery($cons);
	//						echo $cons."<br>";
							$Dias=$Dias-$Nov;
							$MesI++;
							while($Dias>30)
							{
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','30','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
								$res=ExQuery($cons);
	//							echo $cons."<br>";
								$Dias=$Dias-30;
								$MesI++;
							}
							$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','$DiaF','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
							$res=ExQuery($cons);
	//						echo $cons."<br>";
						}
						?>
						<script language="javascript">alert("Las <? echo $Novedad?> ha sido Guardadas !!!");</script>
						<script language="javascript">location.href="ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=<? echo $Novedad?>&FechaFin=<? echo $FechaFin?>";</script> 
						<?
					}
					elseif($AnoI<$AnoF)
					{
						
						$cons="select * from nomina.salarios where anio='$AnoF' and identificacion='$Identificacion' and mesi <= $MesI and mesf >= $MesI";
						//echo $cons."<br>";
						$res=ExQuery($cons);
						$CAnos=ExNumRows($res);
	//					echo $CAnos."<br>";
						if($CAnos==0)
						{
							?><script language="javascript">alert("no hay salario para este año !!");</script><?
						}
						if($CAnos==1)
						{
							$cons="insert into nomina.$Novedad (compania,identificacion,concepto,fecinicio,fecfinal,dias,detalle,resolucion,autorizacion,estado,usuario,fecha,numero,id) values ('$Compania[0]','$Identificacion','$RegNomina','$FecInicio','$FecFinal',$Dias,'$Detalle','$Resolucion','$Autorizacion','Aprobado','$usuario[1]','$Fec','$Numero','$IdNov')";
							$res=ExQuery($cons);
							
							while($MesI<=12)
							{
								$Nov=31-$DiaI;
								$Dias=$Dias-$Nov;
								$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','$Nov','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
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
									$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','$Dias','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
									$res=ExQuery($cons);
	//								echo $cons;
								}
								while($MesI<$MesF)
								{
									$Nov=31-$DiaI;
									$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','$Nov','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
									$res=ExQuery($cons);
	//								echo $cons."<br>";
									$Dias=$Dias-$Nov;
									$MesI++;
									while($Dias>30)
									{
										$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','30','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
										$res=ExQuery($cons);
	//									echo $cons."<br>";
										$Dias=$Dias-30;
										$MesI++;
									}
									$cons="insert into nomina.novedades(compania,identificacion,novedad,dias,mes,anio,numero,concepto,vinculacion,id) values ('$Compania[0]','$Identificacion','$Novedad','$DiaF','$MesI','$AnoI','$Numero','$RegNomina','$TipVinculacion','$IdNov')";
									$res=ExQuery($cons);
	//								echo $cons."<br>";
								}
							}
						}
						if($CAnos!=0)
						{
						?>
						<script language="javascript">alert("Las <? echo $Novedad?> ha sido Guardadas !!!");</script>
						<script language="javascript">location.href="ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=<? echo $Novedad?>&FechaFin=<? echo $FechaFin?>";</script>
						<?
						}
					}
				}
			}
			else
			{
				?>
				<script language="javascript">alert("Ya Existe Una <? echo $Novedad?> para la Fecha Seleccionada")</script> 
				<?
			}
		}
		else
		{
			?>
			<script language="javascript">alert("No se Puede ingresar la <? echo $Novedad?> por que este mes ya se liquido !!!")</script> 
			<?
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/calendario/popcalendar.js"></script>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.RegNomina.value==""){alert("Por favor ingrese el Registro de Nomina!!!");return false;}
   if(document.FORMA.FecInicio.value==""){alert("Por favor ingrese la Fecha de Inicio!!!");return false;}
   if(document.FORMA.Dias.value==""){alert("Por favor ingrese los Dias!!!");return false;}
//   if(document.FORMA.Resolucion.value==""){alert("Por favor ingrese el Numero de Resolucion!!!");return false;}
//   if(document.FORMA.Autorizacion.value==""){alert("Por favor ingrese el Numero de Autorizacion!!!");return false;}   
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="Novedad" value="<? echo $Novedad?>">
<input type="hidden" name="TipVinculacion" value="<? echo $TipVinculacion?>">
<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td colspan="6" bgcolor="#666699" style="color:white" align="center"><? echo strtoupper($Novedad)?></td>
</tr>
<tr>
	<td>Registro de Nomina</td>
	<td colspan="2">
	<select name="RegNomina" onChange="FORMA.submit()" style="width:100%" value="<? echo $RegNomina?>" >
		<option></option>
		<?
		$cons="select concepto,detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]' and claseconcepto='Dias' and novedad='$Novedad' and tipovinculacion='$TipVinculacion'";
		//echo $cons;
		$res=ExQuery($cons);
		while($fila=Exfetch($res))
		{
			if($fila[0]==$RegNomina)
			{
				echo "<option value='$fila[0]' selected>$fila[1]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[1]</option>";
			}
		}
		?>
	</select>
	</td>
     <?
		if($Novedad=="Incapacidades")
		{
			
		?>
        	<td>Prorroga
    		<input type="checkbox" name="prorroga">
    		</td>
        <?
		}
		?>
</tr>
<tr>
	<td>Fecha Inicio</td>
	<td><input type="text" name="FecInicio" value="<? echo $FecInicio?>" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" onFocus="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" onChange="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" onKeyDown="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" onKeyUp="document.FORMA.FecFinal.value=SumaDiasFecha(this,document.FORMA.Dias)" readonly/></td>
	<td>Dias de <? echo $Novedad?></td>
	<td><input type="text" name="Dias" value="<? echo $Dias?>" onKeyDown="xNumero(this);document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this);" onKeyUp="xNumero(this);document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this)" maxlength="3" onChange="document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this)" onBlur="if(parseInt(this.value)>364){alert('El valor de los dias no puede ser mayor a 364!!!');this.value=364;document.FORMA.FecFinal.value=SumaDiasFecha(document.FORMA.FecInicio,this);}" /></td>
	<td>Fecha Final</td>
	<td><input type="text" name="FecFinal" value="<? echo $FecFinal?>"  maxlength="10" /></td>
</tr>
<tr>
	<td colspan="6" colspan=4 bgcolor="#666699" style="color:white" align="center">Detalle</td>
</tr>
<tr>    
	<td colspan="6"><textarea name="Detalle" style="width:100%" rows="4" ><? echo $Detalle?></textarea></td>
</tr>
<tr>
	<td>Resolucion o Acuerdo No.</td>
	<td colspan="2"><input type="text" name="Resolucion" value="<? echo $Resolucion?>" style="width:100%" /></td>
	<td>Codigo Autorizacion</td>
	<td colspan="2"><input type="text" name="Autorizacion" value="<? echo $Autorizacion?>" style="width:100%" /></td>
</tr>
</table>
<center><input type="submit" name="Guardar" value="Guardar" /><input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ListarHistorial.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Novedad=<? echo $Novedad?>';"></center>
</form>
</body>
</html>