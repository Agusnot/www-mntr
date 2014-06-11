<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Cambiar&&!$ban)
	{
		$cons="select ambito from historiaclinica.ambitosxformato where compania='$Compania[0]' and tipoformato='$TipoFormato' 
		and formato='$Formato'	and disponible='Si' and ambito!='Todos' and ambito is not null";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $NewAmb=$fila[0]; 		
		if($NewAmb)
		{
			$cons="select entidad,contrato,numero from contratacionsalud.contratos where entidad='$Paga' and ambitocontrato='$AmbFormat'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);		
			if($fila[0]){	
				$Contra=$fila[1]; $NoContra=$fila[2];
				$cons="select cup,nombre,grupo,tipo from salud.agenda,contratacionsalud.cups 
				where agenda.compania='$Compania[0]' and cups.compania='$Compania[0]' and numservicio=$NumServ and cup=codigo";
				$res=ExQuery($cons);
				$fila=ExFetch($res); $Cup=$fila[0]; $NomCup=$fila[1]; $Grupo=$fila[2]; $Tipo=$fila[3];
				$cons="select numservicio from salud.servicios where compania='$Compania[0]' order by numservicio desc";
				$res=ExQuery($cons);
				$fila=ExFetch($res); $AutoId=$fila[0]+1;
				
				$cons="select tiposervicio,tipousu,autorizac1,usuarioingreso,causaexterna,fechaing,nivelusu
				from salud.servicios where compania='$Compania[0]' and numservicio=$NumServ and cedula='$Paciente[1]'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$ban=0; $cont=1;
				$mes=$ND[mon]; $dia=$ND[mday]; $anio=$ND[year];		
				while($ban!=1)
				{
					$fechaProx = date("d/m/Y", mktime(0,0,0,$mes,$dia-$cont,$anio)); 
					$FProx=$fechaProx; $FProx=str_replace("/","-",$FProx);  $FProx=explode("-",$FProx); 
					$cons2="select fechaing from salud.servicios where compania='$Compania[0]' and fechaing<'$FProx[2]-$FProx[1]-$FProx[0] 23:59:59' 
					and cedula='$Paciente[1]'";
					//echo $cons2."<br>";
					$res2=ExQuery($cons2);
					$fila2=ExFetch($res2);
					if(!$fila2[0]){
						$ban=1; $FecIng=$FProx[2]."-".$FProx[1]."-".$FProx[0];
					}				
					$cont++;
				}	
				$MinsIng=explode(" ",$fila[5]);
				$cons2="update salud.servicios set estado='AN' where compania='$Compania[0]' and numservicio=$NumServ";
				$res2=ExQuery($cons2);
				//echo $cons2."<br>";
				$cons2="insert into salud.servicios (cedula,numservicio,tiposervicio,fechaing,fechaegr,tipousu,autorizac1,estado,compania
				,medicotte,usuarioingreso,causaexterna,nivelusu) values ('$Paciente[1]',$AutoId,'$NewAmb','$FecIng $MinsIng[1]'
				,'$FecIng $MinsIng[1]','$fila[1]','$fila[2]','AC','$Compania[0]','$usuario[1]','$fila[3]','$fila[4]','$fila[6]')";
				$res2=ExQuery($cons2);
				//echo $cons2;//252248
				$cons2="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";
				$res2=ExQuery($cons2); $fila2=ExFetch($res2); $AutoIdLiq=$fila2[0]+1;
				$cons3="select usuario,fechacrea,ambito,medicotte,fechafin,fechaini,nocarnet,tipousu,nivelusu
				,autorizac1,pagador,contrato,nocontrato,noliquidacion,numservicio,valorcopago,porsentajecopago,valordescuento,porsentajedesc
				,subtotal,total,tipocopago,clasecopago,cedula from facturacion.liquidacion where compania='$Compania[0]' and numservicio=$NumServ";
				$res3=ExQuery($cons3);
				$fila3=ExFetch($res3);
				if(!$fila3[15]){$fila3[15]="0";} if(!$fila3[16]){$fila3[16]="0";} if(!$fila3[17]){$fila3[17]="0";} if(!$fila3[18]){$fila3[18]="0";}
				$cons2="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechafin,fechaini,nocarnet,tipousu,nivelusu
				,autorizac1,pagador,contrato,nocontrato,noliquidacion,numservicio,valorcopago,porsentajecopago,valordescuento,porsentajedesc
				,subtotal,total,tipocopago,clasecopago,cedula) values ('$Compania[0]','$fila3[0]','$fila3[1]','$NewAmb','$usuario[1]'
				,'$FecIng','$FecIng','$fila3[6]','$fila3[7]','$fila3[8]','$fila3[9]','$Paga','$Contra','$NoContra',$AutoIdLiq,$AutoId,$fila3[15]
				,$fila3[16],$fila3[17],$fila3[18],$fila3[19],$fila3[20],'$fila3[21]','$fila3[22]','$Paciente[1]')";
				//echo $cons2."<br>";
				$res2=ExQuery($cons2);
				$cons4="select usuario,vrunidad,causaext,nofacturable from facturacion.detalleliquidacion where compania='$Compania[0]' 
				and noliquidacion=$fila3[13]and codigo='$Cup'";
				$res4=ExQuery($cons4);
				$fila4=ExFetch($res4);
				
				$cons2="insert into facturacion.detalleliquidacion 
				(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion,causaext,ambito,nofacturable) values 
				('$Compania[0]','$fila4[0]','$FecIng $MinsIng[1]','$Grupo','$Tipo','$Cup','$NomCup',1,$fila4[1],$fila4[1],$AutoIdLiq,'$fila4[2]'
				,1,$fila4[3])";
				//echo $cons2."<br>";
				$res2=ExQuery($cons2);
				$cons2="insert into salud.pagadorxservicios (compania,numservicio,entidad,contrato,nocontrato,fechaini,fechafin,usuariocre,fechacre)
				values 
				('$Compania[0]',$AutoId,'$Paga','$Contra','$NoContra','$FecIng','$ND[year]-$ND[mon]-$ND[mday]','$fila4[0]','$FecIng $MinsIng[1]')";
				$res2=ExQuery($cons2);
				//echo $cons2."<br>";?>
				<script lnguage="javascript">
				
					location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&SubFormato=<? echo $SubFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>'
				</script>
				<?
			}
			else{?>
				<script language="javascript">
					alert("No se ha encotrado un contrato configurado para la entidad de este paciente por lo cual no se puede realizar el cambio de servicio!!!");
				</script>
		<?	}
		}
		else
		{?>
			<script language="javascript">
				alert("No se ha configurado un proceso para este formato!!!");
			</script>
	<?	}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" > 
<br /><br /><br /><br /><br /><br /><br /><br />
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr align="center">
    	<td><strong>PARA PODER DILIGENCIAR ESTE FORMATO DEBE FINALIZAR EL ACTUAL SERVICIO,<BR />
        ¿DESEA FINALIZAR EL SERVICIO ACTUAL E INICIAR EL UN NUEVO SERVICIO ADECUADO PARA ESTE FORMATO?</strong></td>
    </tr>
    <TR align="center">
    	<td><input type="submit" name="Cambiar" value="Finalizar Servicio"/></td>
    </TR>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="SubFormato" value="<? echo $SubFormato?>" />
<input type="hidden" name="IdHistoOrigen" value="<? echo $IdHistoOrigen?>" />
<input type="hidden" name="SFTF" value="<? echo $SFTF?>" />
<input type="hidden" name="Formato" value="<? echo $Formato?>" />
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>" />
<input type="hidden" name="SoloUno" value="<? echo $SoloUno?>" />
<input type="hidden" name="NumServ" value="<? echo $NumServ?>" />
<input type="hidden" name="Paga" value="<? echo $Paga?>" />
<input type="hidden" name="PagaCont" value="<? echo $PagaCont?>" />
<input type="hidden" name="" value="<? echo $PagaNoCont?>" />
<input type="hidden" name="PagaCont" value="<? echo $AmbFormat?>" />
</form>
</body>
</html>