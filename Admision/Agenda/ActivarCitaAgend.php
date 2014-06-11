<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
	
	$F=explode("-",$Fecha);	
	$cons="select nombre from central.usuarios where usuario='$Profecional'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$d=date('w',mktime(0,0,0,$F[1],$F[2],$F[0]));	
	$ND=getdate(); 
	switch($d){
		case 1: $Diasem='Lun'; break;
		case 2: $Diasem='Mar'; break;
		case 3: $Diasem='Mie'; break;
		case 4: $Diasem='Juv'; break;
		case 5: $Diasem='Vie'; break;
		case 6: $Diasem='Sab'; break;
		case 0: $Diasem='Dom'; break;
	}
	if($Agregar){		
		
		//Consumo del contrato al momento de ralizar el contrato
		$cons="select consumcontra,monto,tipofactura,cuentacont,compfacturacion,cuentacaja,cuentadeposito,comprobantecaja
		from ContratacionSalud.Contratos where compania='$Compania[0]' and entidad='$Entidad' 
		and contrato='$Contrato' and numero='$Nocontrato'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $ConsumoContra=$fila[0]; $MontoContra=$fila[1]; $TipoFactura=$fila[3];
		if(!$fila[3]||!$fila[4]||!$fila[5]||!$fila[6]||!$fila[7]){?>
			<script language="javascript">
				alert("No es posible activar citas sin la configuracion contable!!!"); 
			</script>            
	<?		//exit;
			
		}
		else{
			//exit;
			//echo $cons."<br>";
			//Consumo del contrato hasta el momento facturado
			$consFac="select sum(total),entidad,contrato,nocontrato from facturacion.facturascredito 
			where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$Nocontrato' and estado='AC' group by entidad,contrato,nocontrato";				
			$resFac=ExQuery($consFac);
			$filaFac=ExFetch($resFac); $EjecucionContra=$filaFac[0]; 
			//Consumo del contrato hasta el momento liquidado sin facturar		
			$consLiq="select sum(total) from facturacion.liquidacion 
			where compania='$Compania[0]' and pagador='$Entidad' and contrato='$Contrato' and nocontrato='$Nocontrato' and estado='AC' and nofactura is null";	
			$resLiq=ExQuery($consLiq);
			$filaLiq=ExFetch($resLiq); $xFacturarContra=$filaLiq[0];
			
			$SaldoContra=$MontoContra-$ConsumoContra-$EjecucionContra-$xFacturarContra;
			//Encotramos el plan de beneficios
			$consPlan="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$Nocontrato' 
			and compania='$Compania[0]'";
			
			$resPlan=ExQuery($consPlan); 
			$filaPlan=ExFetch($resPlan);
			
			//Encontramos el valor del cup
			$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$CUP' and autoid=$filaPlan[1]";			
			$resVr=ExQuery($consVr);
			$filaVr=ExFetch($resVr);
			if($NoCobrar==1){$Cuotamoderadora="";}
			
			if($SaldoContra>=($filaVr[0]-$Cuotamoderadora)){
				//Verifica si se aun hay consumo para este contrato para cobra esta cita
				//LIQUIDACION
				$cons6="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";
				$res6=ExQuery($cons6);
				$fila6=ExFetch($res6);				
				$AutoIdLiq=$fila6[0]+1;
				
				$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
				where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$CUP' and cups.compania='$Compania[0]' and cups.codigo=cup and autoid=$filaPlan[0]";
				$res3=ExQuery($cons3); 
				$fila3=ExFetch($res3);			
				if($fila3[0]==''){$filaVr[0]="0";$ban2=0;}else{$ban2=1;}
				if($fila3[3]==1){$NoFacturable="0";$ban2=1;}else{$NoFacturable="1";$filaVr[0]="0";$ban2=0;}													
				$vT=$filaVr[0];
				if($vT==''){$vT="0";}
				if($filaVr[0]==''){$filaVr[0]="0";}
				if($fila3[1]==''){$fila3[1]="012";}
				$Total=$filaVr[0];	
				if($Cuotamoderadora!=''){
					$consul="select tipoasegurador from central.terceros where identificacion='$Entidad' and compania='$Compania[0]' and Tipo='Asegurador'";
					//echo $consul."<br>";
					$result=ExQuery($consul);
					$row=ExFetch($result);		
					$TipoAsegurador=$row[0];
					if($row[0]=='Particular'){$Parti="1";}else{$Parti="0";}
					$consul2="select valor,clase,tipocopago from salud.topescopago where anio='$ND[year]' and compania='$Compania[0]' and tipousuario='$Tipousu' and tipoasegurador='$row[0]' 
					and nivelusu='$Nivelusu' and ambito='Consulta Externa'";		
					//echo "$consul2<br>\n";
					$result2=ExQuery($consul2); $fil=ExFetch($result2);
					$TipoCopago=$fil[2];
					$ClaseCopago=$fil[1];
					//echo "$Cuotamoderadora ";
					if($fil[1]=='Fijo'){
						$Porsentajecopago="0";			
					}
					else{
						$Porsentajecopago=$fil[0];				
					}
					
					if($Porsentajecopago==''){$Porsentajecopago="0";}				
					if(!$Cuotamoderadora){$Cuotamoderadora="0";}
					if($Cuotamoderadora==1){$Cuotamoderadora="0";}
					if($Cuotamoderadora&&$Cuotamoderadora!=$CuotaMod){
					//str_replace("$","",$CuotaMod);//echo "$CuotaMod";
						$Cuotamoderadora=$CuotaMod;
					}	
					$Copago1=",valorcopago,porsentajecopago,tipocopago,clasecopago";
					$Copago2=",$Cuotamoderadora,$Porsentajecopago,'$Tipocopago','$ClaseCopago'";
					$Total=$filaVr[0]-$Cuotamoderadora;
				}
				
				$cons5 = "Select numservicio from Salud.Servicios where Compania = '$Compania[0]' order by numservicio desc";						
				//echo $cons5."<br>";
				$res5 = ExQuery($cons5);
				$fila5 = ExFetch($res5);			
				$AutoId = $fila5[0]+1;
				if($NumServCitaAnt){$AutoId=$NumServCitaAnt;	}
				$cons4="update salud.agenda set numservicio=$AutoId,estado='Activa',usuactiva='$usuario[1]',fechaactiva='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
				where compania='$Compania[0]' and hrsini='$HrIni' and minsini='$MinIni' and fecha='$Fecha' and medico='$Profecional' and cedula='$Cedula' and id=$Id";		
				//echo $cons4."<br>";
				$res4=ExQuery($cons4);echo ExError();
				
				$ND=getdate();
				$FechaIng=$Fecha." ".$ND[hours].":".$ND[minutes].":".$ND[seconds];
				//echo $Fecha."<br>";
				$cons6="insert into 			salud.servicios(cedula,numservicio,tiposervicio,fechaing,fechaegr,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,estado,nocarnet,compania,medicotte,usuarioingreso,pagina,usucreaserv)
			values ('$Cedula','$AutoId','$Ambito','$FechaIng','$Fecha','$Tipousu','$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3','AC','$Nocarnet','$Compania[0]','$Medico','$usuario[1]','ActivarCitaAgend.php','$usuario[1]')";
				//echo $cons6."<br>";
				$res6=ExQuery($cons6);
				$cons6="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,fechafin,usuariocre,fechacre) values 
				('$AutoId','$Compania[0]','$Entidad','$Contrato','$Nocontrato','$ND[year]-$ND[mon]-$ND[mday]','$ND[year]-$ND[mon]-$ND[mday]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
				//echo $cons6."<br>";
				$res6=ExQuery($cons6);echo ExError();	
				     
				$cons4="update central.terceros set  tipousu='$Tipousu',nivelusu='$Nivelusu',eps='$Entidad',nocarnet='$Nocarnet',sexo='$Sexo',celular='$Celular'
				,numha='$HistoClin',tipodoc='$TipoDoc',ecivil='$ECivil',departamento='$Departamento',municipio='$Municipio',vereda='$Vereda',zonares='$ZonaRecid'
				where identificacion='$Cedula' and compania='$Compania[0]'";		
				//echo $cons4."<br>";
				$res4=ExQuery($cons4);echo ExError();
								
				if($Cuotamoderadora){
					//$consult="insert into salud.copagos(compania,numserv,tipocopago,valor) values ('$Compania[0]',$AutoId,'$TipoCopago',$Cuotamoderadora)";							
					//$row=ExQuery($consult);
				}		
				//if($Cuotamoderadora){}
				
				if($Cuotamoderadora>0){$Reacu="0";}else{$Reacu="1";}
				
				if($Parti=="1"){$Reacu="0";}			
				$Reacu="0";//RECTIFICAR EL RECAUDO PARA LA AFECTACION CONTABLE PARA PODER ELIMINAR  ESTA LINEA
				if($NoCobrar){
					$Copago1=",motivonocopago";
					$Copago2=",'$MotivoNoCopago'";
				}
				
	
				$cons="select ambito from salud.ambitos where consultaextern=1 and compania='$Compania[0]'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				if($fila[0]){$Amb=$fila[0];}else{$Amb="1";}
				
				$cons="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechaini,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,
							noliquidacion,numservicio,cedula,fechafin,pagador,contrato,nocontrato,subtotal,total,recaudo  $Copago1 ) 
				values ('$Compania[0]','$usuario[1]',
							'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Amb','$Medico','$ND[year]-$ND[mon]-$ND[mday]','$Nocarnet','$Tipousu',
											'$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3',$AutoIdLiq,$AutoId,'$Cedula','$ND[year]-$ND[mon]-$ND[mday]','$Entidad','$Contrato','$Nocontrato',$filaVr[0],
											$Total,$Reacu $Copago2)";
				$res = ExQuery($cons);
				//echo "<br>\n$consF<br>\n";		
				
				$cons="insert into facturacion.detalleliquidacion 
				(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion									,fechainterpret,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,nofacturable,codproducto) 
				values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila3[0]','$fila3[1]',									'$CUP','$fila3[2]',1,$filaVr[0],$filaVr[0],$AutoIdLiq,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','','','','','','','','',1,$NoFacturable,'$CUP')";	
				//echo "<br>\n$consF<br>\n";		
				$res = ExQuery($cons);
				
				if($TipoFactura=="Colectiva"||!$GeneraFactura){
				?>
					<script language="javascript">
						open('/Facturacion/VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $AutoIdLiq?>&Ced=<? echo $Cedula?>&Estado=<? echo "AC";?>&ActCtAg=<? echo "true";?>','','left=10,top=10,width=900,height=700,menubar=yes,scrollbars=YES');
					</script>
					<?
				}
				else{			
					if($GeneraFactura){
						$cons="select nofactura from facturacion.facturascredito where compania='$Compania[0]' order by nofactura desc";
						$res=ExQuery($cons); $fila=ExFetch($res); 
						$AutoIdFac=$fila[0]+1;//Numero de factura
						$AutoIdIniFac=$AutoIdFac;
						if(!$Cuotamoderadora){$Cuotamoderadora="0";}
						if($Cuotamoderadora==1){$Cuotamoderadora="0";}
						$consF="insert into facturacion.facturascredito 
						(compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura,individual)
						values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] 
						$ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday]','$ND[year]-$ND[mon]-$ND[mday]','$Entidad','$Contrato','$Nocontrato',
						'$Amb',$filaVr[0],$Cuotamoderadora,0,$Total,$AutoIdFac,1)";
						
						//echo "<br>\n$consF<br>\n";		
						$resF=ExQuery($consF);
						$consDF="insert into facturacion.detallefactura 
						(compania,usuario,fechacrea,codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,nofactura)
						values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] 
						$ND[hours]:$ND[minutes]:$ND[seconds]','$CUP','$fila3[0]','$fila3[1]','$fila3[2]',1,$filaVr[0],$filaVr[0],$AutoIdFac)";
						//echo "$consDF<br>\n";
						$resDF=ExQuery($consDF);
	
						//////////////////AFECTACION CONTABLE DE LA FACTURA/////////////////////////
/*						$cons33="Select cuentacont,compfacturacion from ContratacionSalud.Contratos where Compania='$Compania[0]'
						and Entidad='$Entidad' and Contrato='$Contrato' and Numero='$Nocontrato'"; 
						$res33=ExQuery($cons33);
						$fila33=ExFetch($res33);
						$CompFactura=$fila33[1];
						$CuentaDebito=$fila33[0];
						
						$cons31="Select cuentaconta from contratacionsalud.cuentaxgrupos where Compania='$Compania[0]' and Codigo='$fila3[0]' and TipoAseg='$TipoAsegurador'";
						$res31=ExQuery($cons31);
						$fila31=ExFetch($res31);
						$CuentaCredito=$fila31[0];
		
						$NumCompFactura=ConsecutivoComp($CompFactura,$ND[year],"Contabilidad");
	
	
						$cons22="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Compania='$Compania[0]' and Identificacion='$Cedula'";
						$res22=ExQuery($cons22);
						$fila22=ExFetch($res22);
						$NomPaciente="$fila22[0] $fila22[1] $fila22[2] $fila22[3]";
	
						if($TipoAsegurador=="Particular")
						{
							$IdeTercero=$Cedula;
							$DetalleCont="FACTURA SERVICIOS PARTICULARES";
							
						}
						else
						{
							$IdeTercero=$Entidad;
							$DetalleCont=strtoupper("FACTURA $AutoIdFac, USUARIO: $NomPaciente ($Cedula)");
						}
						
						
						$cons34="INSERT INTO contabilidad.movimiento(autoid, fecha, comprobante, numero, identificacion, detalle,cuenta, debe, haber, cc, docsoporte,compania, 
								usuariocre, fechacre, estado, fechadocumento, anio)
								VALUES (1,'$ND[year]-$ND[mon]-$ND[mday]','$CompFactura','$NumCompFactura','$IdeTercero','$DetalleCont','$CuentaDebito',$Total,0,'000'
								,'$AutoIdFac','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$ND[year]-$ND[mon]-$ND[mday]'
								,'$ND[year]');";
						//$res34=ExQuery($cons34);
		
						$cons35="INSERT INTO contabilidad.movimiento(autoid, fecha, comprobante, numero, identificacion, detalle,cuenta, debe, haber, cc, docsoporte,compania, 
								usuariocre, fechacre, estado, fechadocumento, anio)
								VALUES (2,'$ND[year]-$ND[mon]-$ND[mday]','$CompFactura','$NumCompFactura','$IdeTercero','$DetalleCont','$CuentaCredito',0,$Total,'000'
								,'0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$ND[year]-$ND[mon]-$ND[mday]'
								,'$ND[year]');";
						//$res35=ExQuery($cons35);
						$CuentaCredito=NULL;$CuentaDebito=NULL;$cons22=NULL;$cons23=NULL;$cons31=NULL;$cons33=NULL;$cons34=NULL;$cons35=NULL;$DetalleCont=NULL;*/

						//////////////////TERMINA AFECTACION CONTABLE DE LA FACTURA/////////////////////////
	
						
						$consUp="update facturacion.liquidacion set nofactura=$AutoIdFac where compania='$Compania[0]' and noliquidacion=$AutoIdLiq";
						$resUp=ExQuery($consUp);
						?>
						<script language="javascript">
							open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $AutoIdFac?>&Estado=<? echo "AC"?>&ActCtAg=<? echo "true";?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')
						</script>
				<?	}
				}///////////////////////////AFECTACION CONTABLE DEL RECIBO DE CAJA///////////////////////////////////
	
				if(($Cuotamoderadora>0 && !$NoCobrar)||($TipoAsegurador=="Particular"))
				{
					$cons33="Select cuentacaja,cuentadeposito,comprobantecaja from ContratacionSalud.Contratos where Compania='$Compania[0]'
					and Entidad='$Entidad' and Contrato='$Contrato' and Numero='$Nocontrato'"; 
					$res33=ExQuery($cons33);
					$fila33=ExFetch($res33);
					$CompCaja=$fila33[2];
					$CuentaDebito=$fila33[0];
					$CuentaCredito=$fila33[1];
	
					$cons23="Select Formato from Contabilidad.Comprobantes where Comprobante='$CompCaja' and Compania='$Compania[0]'";
					$res23=ExQuery($cons23);
					$fila23=ExFetch($res23);
					$Archivo=$fila23[0];
	
					$NumCompCaja=ConsecutivoComp($CompCaja,$ND[year],"Contabilidad");
	
	
					$cons22="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Compania='$Compania[0]' and Identificacion='$Cedula'";
					$res22=ExQuery($cons22);
					$fila22=ExFetch($res22);
					$NomPaciente="$fila22[0] $fila22[1] $fila22[2] $fila22[3]";
					
					if($TipoAsegurador=="Particular")
					{
						$IdeTercero=$Cedula;
						$DetalleCont="PAGO CONSULTA EXTERNA - PARTICULAR";
						$ValContable=$Total;
						$DocSoporteCont=$AutoIdFac;
						
					}
					else
					{
						$IdeTercero=$Entidad;
						$DetalleCont=strtoupper("$TipoCopago, USUARIO: $NomPaciente ($Cedula)");
						$ValContable=$Cuotamoderadora;
						$DocSoporteCont=$AutoIdLiq;
					}
					
					
					$cons34="INSERT INTO contabilidad.movimiento(autoid, fecha, comprobante, numero, identificacion, detalle,cuenta, debe, haber, cc, docsoporte,compania, 
							usuariocre, fechacre, estado, fechadocumento, anio)
							VALUES (1,'$ND[year]-$ND[mon]-$ND[mday]','$CompCaja','$NumCompCaja','$IdeTercero','$DetalleCont','$CuentaDebito',$ValContable,0,'000'
							,'0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$ND[year]-$ND[mon]-$ND[mday]'
							,'$ND[year]');";
					$res34=ExQuery($cons34);
	
					$cons35="INSERT INTO contabilidad.movimiento(autoid, fecha, comprobante, numero, identificacion, detalle,cuenta, debe, haber, cc, docsoporte,compania, 
							usuariocre, fechacre, estado, fechadocumento, anio)
							VALUES (2,'$ND[year]-$ND[mon]-$ND[mday]','$CompCaja','$NumCompCaja','$IdeTercero','$DetalleCont','$CuentaCredito',0,$ValContable,'000'
							,'$DocSoporteCont','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$ND[year]-$ND[mon]-$ND[mday]'
							,'$ND[year]');";
					$res35=ExQuery($cons35);?>
					
					<script language="javascript">
						open("/Informes/Contabilidad/<? echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $NumCompCaja?>&Comprobante=<?echo $CompCaja?>","","width=650,height=500,scrollbars=yes");
					</script>
					<?
					$CuentaCredito=NULL;$CuentaDebito=NULL;$cons22=NULL;$cons23=NULL;$cons31=NULL;$cons33=NULL;$cons34=NULL;$cons35=NULL;$ValContable=0;
				}
				///////////////////////////TERMINA AFECTACION CONTABLE DEL RECIBO DE CAJA///////////////////////////////////			
				
					
			?> 	<script language="javascript">
					location.href='ConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&AnioCalend=<? echo $F[0]?>&MesCalend=<? echo $F[1]?>&DiaCalend=<? echo $F[2]?>';
				</script><?
			}
			else
			{?>
				<script language="javascript">	
					alert('El Saldo del contrato es insuficiente para cubrir el costo de esta cita!!!');
				</script>
		<?	}
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function validar(){
	if(document.FORMA.Telefono.value==""){
		alert("Debe seleccionar una Entidad!!!");return false;
	}
	if(document.FORMA.TipoDoc.value==""){
		alert("Debe seleccionar el tipo de documento!!!");return false;
	}
	if(document.FORMA.Sexo.value==""){
		alert("Debe seleccionar el sexo!!!");return false;
	}
	if(document.FORMA.ECivil.value==""){
		alert("Debe seleccionar el estado civil!!!");return false;
	}
	if(document.FORMA.Departamento.value==""){
		alert("Debe seleccionar el Departamento!!!");return false;
	}
	if(document.FORMA.Municipio.value==""){
		alert("Debe seleccionar el Muncipio!!!");return false;
	}
	if(document.FORMA.ZonaRecid.value==""){
		alert("Debe seleccionar la Zona de Residencia!!!");return false;
	}
	if(document.FORMA.Nivelusu.value==""){
		alert("Debe seleccionar el nivel del usuario!!!");return false;
	}
	if(document.FORMA.Entidad.value==""){
		alert("Debe seleccionar una Entidad!!!");return false;
	}
	if(document.FORMA.Nocontrato.value==""){
		alert("Debe haber un numero de contrato"); return false;
	}
	if(document.FORMA.CUP.value==""){
		alert("Debe seleccionar un CUP!!!"); return false;
	}		
	
	if(document.FORMA.Autorizac1.value=="")
	{
		alert("Debe digitar la autorizacion 1!!!"); return false;
	}
	if(document.FORMA.Cuotamoderadora.value!=""){
		if(document.FORMA.NoCobrar.value=="1"){
			if(document.FORMA.MotivoNoCopago.value==""){
				alert("Debe digitar el motivo por el cual lo se cobra el copago");
			}
		}
	}	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()"> 
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td align="center" colspan="4">Activar Cita</td>
</tr>
<tr>
   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4"><? echo "$fila[0]-$Especialidad";?></td>            
</tr>
<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4"> <? echo "$Fecha - $Diasem";?></td></tr>
<?	
$cons2="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,cup,medico,contrato,nocontrato,sexo,ecivil,numha,tipodoc,celular
,departamento,municipio,vereda,zonares
from central.terceros,salud.agenda where
terceros.identificacion=agenda.cedula and medico='$Profecional' and hrsini='$HrIni' and minsini='$MinIni'and fecha='$Fecha' and estado='Pendiente' and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' and id=$Id";
$res2=ExQuery($cons2);
if(ExNumRows($res2)>0){?> 					

 <? $fila2 = ExFetchArray($res2); 
	 if($fila2[3]==0){$cero1='0';}else{$cero1='';}
	if($fila2[1]==0){$cero='0';}else{$cero='';} ?>
	<tr>    	
        <input type="hidden" name="Medico" value="<? echo $fila2[14]?>">
        <input type="hidden" name="Cedula" value="<? echo $fila2[4]?>">
  	<?	if(!$fila2[19]){$fila2[19]=$fila2[4];}?>
        <input type="hidden" name="HistoClin" value="<? echo $fila2[19]?>">
        <?
        if($fila2[15]&&!$Contrato){$Contrato=$fila2[15];}
		if($fila2[16]&&!$Nocontrato){$Nocontrato=$fila2[16];}
		?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hora Cita</td><td><? echo "$fila2[0]:$fila2[1]$cero-$fila2[2]:$fila2[3]$cero1";?></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre</td><td><? echo "$fila2[5] $fila2[6] $fila2[7] $fila2[8]";?></td>
	</tr>
    <tr>        
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td><td><? echo $fila2[4]?></td>    	
     <? if(!$Telefono){$Telefono=$fila2[9];}?>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Telefono</td><td><input type="text" name="Telefono" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  value="<? echo $Telefono?>"></td>    	
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo documento</td>
   	<?	if(!$TipoDoc){$TipoDoc=$fila2[20];}?>
        <td>
        	<select name="TipoDoc" style="width:152px;"  onFocus="Cerrar()">        
  	<?		$cons = "SELECT TipoDoc FROM Central.TiposDocumentos";
            $resultado = ExQuery($cons,$conex);echo ExError();
            while ($fila = ExFetch($resultado))
            {
                if(!$Paciente[19]){$Paciente[19]="Cedula de ciudadania";}
                if($Paciente[19]==$fila[0])
                {
                    echo "<option value='$fila[0]' selected>$fila[0]</option>";
                }
                else
                {
                    echo "<option value='$fila[0]'>$fila[0]</option>";
                }
            }?>
        </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Celular</td>
        <?	if(!$Celular){$Celular=$fila2[21];}?>
        <td><input type="Text" class="Texto" name="Celular" value="<? echo $Celular;?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Sexo</td>
        <td>
        <?	if(!$Sexo){$Sexo=$fila2[17];}?>
        	<select name="Sexo" style="width:152px;">
            <option value=""></option>
            <?php
                    $cons = "SELECT * FROM Central.ListaSexo Order By Sexo Desc";
                    $resultado = ExQuery($cons,$conex);
                    while ($fila = ExFetch($resultado))
                    {
                        if($Sexo==$fila[1])
                        {
                            echo "<option value='$fila[1]' selected>$fila[0]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[1]'>$fila[0]</option>";
                        }
                    }?>
            </select>	
        </td>  	
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Estado Civil</td>
        </td>
     <?	if(!$ECivil){$ECivil=$fila2[18];}?>
        <td>
        	<select name="ECivil" style="width:152px;">
            <option value=""></option>
            <?php
                    $cons = "SELECT * FROM Central.EstadosCiviles";
                    $resultado = ExQuery($cons,$conex);
                    while ($fila = ExFetch($resultado))
                    {
                        if($ECivil==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[0]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[0]</option>";
                        }
                    }?>
            </select>
        </td>
    </tr>    
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Departamento</td>
        <td>
        <?	if(!$Departamento){$Departamento=$fila2[22];}
			$cons="select departamento from central.departamentos order by departamento";
			$res=ExQuery($cons);?>
            <select name="Departamento" onChange="document.FORMA.submit()">
            	<option></option>
           	<?	while($fila=ExFetch($res))
				{
					if($fila[0]=="$Departamento")
					{echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else
					{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>     
            </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Municipio</td>
        <td>
        <?	if(!$Municipio){$Municipio=$fila2[23];}
			$cons="select municipio from central.municipios,central.departamentos 
			where municipios.departamento=departamentos.codigo and departamentos.departamento='$Departamento' order by municipio";
			$res=ExQuery($cons);?>
            <select name="Municipio" onChange="document.FORMA.submit()">
            	<option></option>
         	<?	while($fila=ExFetch($res))
				{
					if($fila[0]=="$Municipio")
					{echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else
					{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Vereda</td>
        <td>
        <?	if(!$Vereda){$Vereda=$fila2[24];}
			$cons="select vereda from central.veredas,central.municipios,central.departamentos where municipios.departamento=departamentos.codigo 
			and departamentos.departamento='$Departamento' and veredas.municipio=municipios.codmpo and municipios.municipio='$Municipio'";
			$res=ExQuery($cons);?>
            <select name="Vereda">
	            <option></option>
         	<?	while($fila=ExFetch($res))
				{
					if($fila[0]=="$Vereda")
					{echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else
					{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Zona Residencia</td>
        <td>
        <?	if(!$ZonaRecid){$ZonaRecid=$fila2[25];}
			$cons = "SELECT zona FROM Central.ZonasResidencia";
		   	$res = ExQuery($cons);?>
        	<select name="ZonaRecid"> 
           		<option></option>
             <?	while($fila=ExFetch($res))
				{
					if($ZonaRecid=="$fila[0]")
					{echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else
					{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad Aseguradora (EPS)</td><td colspan="3">
        <select name="Entidad" onChange="document.FORMA.submit();">
	<?
	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre,cuotamoderadora  from Central.Terceros where Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
	if(!$Entidad){
		$result=ExQuery($cons);
		$row=ExFetch($result);
		$Cuotamoderadora=$row[2];
		$IDEntidad=$row[0];
	}
	else{
		$consul="Select cuotamoderadora,identificacion  from Central.Terceros where identificacion='$Entidad' and Tipo='Asegurador' and Compania='$Compania[0]'";
		$result=ExQuery($consul);
		$row=ExFetch($result);
		$Cuotamoderadora=$row[0];
		$IDEntidad=$row[1];
	}
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{	
		if($Entidad!=''){
			if($fila[0]==$Entidad){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
			else{echo "<option value='$fila[0]'>$fila[1]</option>";}			
		}
		else{
			if($fila[0]==$fila2[10]){echo "<option selected value='$fila[0]'>$fila[1]</option>";$Entidad=$fila[0];}
			else{echo "<option value='$fila[0]'>$fila[1]</option>";}
		}
	}
?> </select>
<?
		$consulxx="Select cuotamoderadora,identificacion  from Central.Terceros where identificacion='$Entidad' and Tipo='Asegurador' and Compania='$Compania[0]'";
		$resultxx=ExQuery($consulxx);
		$rowxx=ExFetch($resultxx);
		$Cuotamoderadora=$rowxx[0];
		$IDEntidad=$rowxx[1];
?>
		</td>                
    </tr>
     <tr>
     <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' Group By Contrato order by contrato"; 
	 //echo $cons." $Contrato<br>";?>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Contrato</td><td>
        <select name="Contrato" onChange="document.FORMA.submit()"><option></option>
         <?	
		$res=ExQuery($cons);
		$banContrato=0;
		while($fila=ExFetch($res))
		{	
			if($Contrato==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";$Aux=$fila[0];
					//if(!$Contrato){$Contrato=$fila[0];}
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
		}
		if($Aux!=''){$Contra=$Aux;}
		?>        
        </select></td>
        
        <? 
	 	/*if(!$Contra){
	 		$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' Group By Contrato"; 
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$Contra=$fila[0];
	 	}*/
			
        $cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Contrato='$Contra'
		and Entidad='$Entidad' and fechaini<='$ND[year]-$ND[mon]-$ND[mday]' and (fechafin>='$ND[year]-$ND[mon]-$ND[mday]' or fechafin is null) order by numero"; 
		//echo $cons;?>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Contrato</td><td>
        <? if($Compania[0]=='Hospital San Rafael de Pasto'&&$Contrato=='VERBAL AGUDOS Y URGENCIAS'){$Nocontrato="0";}
		 //echo $Nocontrato;?>
        <select name="Nocontrato" onChange="document.FORMA.submit();"><option></option>
        <?	
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($Compania[0]=='Hospital San Rafael de Pasto'&&$Contrato=='VERBAL AGUDOS Y URGENCIAS'){ 
				if($fila[0]==0){$fila[0]="0";}				
				if($Nocontrato==0){$Nocontrato="0";}
			}
			//else{
				if($Nocontrato=="$fila[0]"){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			//}
		}
		?>
        </select></td>
  	</tr>
    <tr>
     <? $cons3="select tipousu,nivelusu,nocarnet from central.terceros where compania='$Compania[0]' and identificacion='$fila2[4]'";	
	 	$res3=ExQuery($cons3);$fila3=ExFetch($res3);
		if(!$Tipousu){$Tipousu=$fila3[0];}
		if(!$Nivelusu){$Nivelusu=$fila3[1];}
		if(!$Nocarnet){$Nocarnet=$fila3[2];}?>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo Usuario</td><td>
        <select name="Tipousu" onChange="document.FORMA.submit()">
    <?	$cons="select * from salud.tiposusuarios"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$Tipousu){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}?>
        </select></td>     
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nivel Usuario</td><td>
        <select name="Nivelusu" onChange="document.FORMA.submit()"><option></option>
     <?	$cons="select * from salud.nivelesusu"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$Nivelusu){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}?>
        </select></td>
	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Proceso</td>
        <td>			
        <select name="Ambito" onChange="document.FORMA.submit()">
		<?	$cons="Select Ambito from salud.Ambitos where compania='$Compania[0]' and consultaextern=1";
			if(!$Ambito){
				$result=ExQuery($cons);
				$row=ExFetch($result);
				$CAmbito=$row[0];
			}
			else{				
				$CAmbito=$Ambito;
			}
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{			
					if($fila[0]==$Ambito){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}								
			}
		?></select></td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Carnet</td>
        <td><input type="text" name="Nocarnet" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  value="<? echo $Nocarnet?>"></td>
  	</tr>
    <tr>
    	<!--<input type="hidden" name="CUP" value="<? echo $fila2[13]?>">-->
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold" >Procedimiento</td>
    	<td colspan="3"><? if(!$CUP){$CUP=$fila2[13];}
			$consPlan="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$Nocontrato' 
			and compania='$Compania[0]'";
			$resPlan=ExQuery($consPlan);
			$filaPlan=ExFetch($resPlan);
			if(!$filaPlan[0]){$filaPlan[0]="-0";}
			//echo $consPlan;
			$cons="select nombre,cupsxconsulextern.codigo,timeconsulsuge from contratacionsalud.cupsxconsulextern,contratacionsalud.cups 
			where cupsxconsulextern.codigo=cups.codigo and cupsxconsulextern.compania='$Compania[0]' and cups.compania='$Compania[0]' and cargo='$Especialidad'
			and  cupsxconsulextern.codigo in (select cup from contratacionsalud.cupsxplanservic where autoid='$filaPlan[0]' and contratacionsalud.cupsxplanservic.compania='$Compania[0]')
			order by nombre";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();?>
        	<select name="CUP" onChange="Cambio()"><option></option>
        <?	while($fila = ExFetchArray($res)){
				if($fila[1]==$CUP){
					echo "<option value='$fila[1]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[1]'>$fila[0]</option>";
				}
			} ?>
            <select> 
         </td> 
        </td>  
   	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Autorizacion 1</td>
        <td><input type="Text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Autorizac1" value="<? echo $Autorizac1?>" maxlength="15"></td>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Autorizacion 2</td>
        <td><input type="Text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Autorizac2" value="<? echo $Autorizac2?>" maxlength="15"></td>
  	</tr>
    <tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Autorizacion 3</td>
        <td colspan="3"><input type="Text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Autorizac3" value="<? echo $Autorizac3?>" maxlength="15"></td>
    </tr>
 <?	//if($Cuotamoderadora){
	 	//if()
	 	$consPlan="select planbeneficios,plantarifario,tipofactura,cuotamod
		from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$Nocontrato' 
		and compania='$Compania[0]'";
		//echo $consPlan."<br>";
		$resPlan=ExQuery($consPlan); 
		$filaPlan=ExFetch($resPlan); $TipoFac=$filaPlan[2]; $SiCutoaMod=$filaPlan[3];
		if(!$filaPlan[1]){$filaPlan[1]="-0";}
		$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$CUP' and autoid=$filaPlan[1]";			
		$resVr=ExQuery($consVr);
		$filaVr=ExFetch($resVr);	
					
		if($SiCutoaMod=='1'){
			$consul="select tipoasegurador from central.terceros where identificacion='$IDEntidad' and compania='$Compania[0]' and Tipo='Asegurador'";
			//echo $consul."<br>";
			$result=ExQuery($consul);
			$row=ExFetch($result);		
			$consul2="select valor,clase,tipocopago from salud.topescopago where anio='$F[0]' and compania='$Compania[0]' and tipousuario='$Tipousu' and tipoasegurador='$row[0]' and  			        nivelusu='$Nivelusu' and ambito='$CAmbito'";		
			//echo $consul2;
			$result2=ExQuery($consul2);
			if(ExNumRows($result2)>0){
				$row2=ExFetch($result2);
				if($row2[1]=="Porcentual"){
					if($filaVr[0]){$Cuotamoderadora=$filaVr[0]*($row2[0]/100);}	
					else{$Cuotamoderadora="";}
				}
				else{
					$Cuotamoderadora=$row2[0];
				}
				$V=$row2[0]?>            
				<input type="hidden" name="NoCobrar" value="<? echo $NoCobrar?>" >
				<tr> 
					<td colspan="4" align="center" style="font-weight:bold">
					<?	if(!$NoCobrar){?>
							Cuota Moderadora $
							<input type="text" size="8" name="CuotaMod" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)"
							value="<? if($row2[1]=="Porcentual"&&$filaVr[0]!=""){ echo $Cuotamoderadora;}else{ echo $Cuotamoderadora;}?>">
							<? if($row2[1]=="Porcentual"){echo "($row2[0] %)";}?>
							No Cobrar
							<input type="checkbox" name="NoCob" onClick="document.FORMA.NoCobrar.value=1;document.FORMA.submit();">
					<?	}
						else{?>
							Cobrar Cuota Moderadora							
							<input type="checkbox" onClick="document.FORMA.NoCobrar.value='';document.FORMA.submit();">
							</td>
							</tr>
							<tr>
							<td colspan="4" style=" font-weight:bold">Motivo de No Cobro
								<input type="text" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" 
								name="MotivoNoCopago" value="<? echo $MotivoNoCopago?>" style="width:600">
							<td>
					<?	}?>
					</td>
				</tr>    
	<? 		}
		}
		else
		{?>
			<td colspan="4" style=" font-weight:bold">Motivo de No Cobro
                <input type="text" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" 
                name="MotivoNoCopago" value="<? echo $MotivoNoCopago?>" style="width:600">
            </td>
	<?	}
	//}	 		
}
if($TipoFac=="Individual")
{
	$GeneraFactura="on"; ?>
    <tr>
		<td colspan="4" style=" font:bold" align="center">Generar Factura <input type="checkbox" name="GeneraFactura" <? /* if($GeneraFactura){ echo"checked"; */?>  <? /* } */ ?>></td>
		
	</tr>
<?
}?>    
<tr>
	<td align="center" colspan="4">
    <input type="submit" value="Activar Cita" name="Agregar">
    <input type="button" value="Regresar"  onClick="location.href='NewEstadoAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>'"></td>
	
</tr>
</table>
<input type="hidden" name="Especialidad" value="<? echo $Especialidad?>">
<input type="hidden" name="Profecional" value="<? echo $Profecional?>">
<input type="hidden" name="Fecha" value="<? echo $Fecha?>">
<input type="hidden" name="HrIni" value="<? echo $HrIni?>">
<input type="hidden" name="MinIni" value="<? echo $MinIni?>">
<input type="hidden" name="Cedula" value="<? echo $fila2[4]?>">
<input type="hidden" name="Id"     value="<? echo $Id?>">
<input type="hidden" name="Cuotamoderadora" value="<? echo $Cuotamoderadora?>">
<input type="hidden" name="Valor" value="<? echo $V?>">
<input type="hidden" name="TipoCopago" value="<? echo $CAmbito?>">
<input type="hidden" name="NumServCitaAnt" value="<? echo $NumServCitaAnt?>">
</form>
</body>
</html>
