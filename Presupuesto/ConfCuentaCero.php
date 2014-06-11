<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	$CompContable="Cuentas cero";	
	if($Eliminar){
		$cons="delete from presupuesto.crucecuentascero where CtaPresupuestal ilike '$CtaPresupuestal%' and crucecuentascero.anio='$Anio' and crucecuentascero.vigencia='$Vigencia' and 
		crucecuentascero.Clasevigencia='$ClaseVigencia' and crucecuentascero.compania='$Compania[0]'";		
		$res=ExQuery($cons);
		$Eliminar="";
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar(){
		if(document.FORMA.CuentaDB.value=="")
		{
			if(document.FORMA.CuentaDBAux.value=="")
			{
				alert("Debe digitar la cuenta DB!!!");return false;
			}
		}
	}
	function BuscarCuenta(TB) 
	{ 
		frames.FrameOpener.location.href="BusquedaCuenta.php?DatNameSID=<? echo $DatNameSID?>&Tabla="+TB;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='160';
		document.getElementById('FrameOpener').style.left='10';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='400';
	}	
	function Retira()
	{
		document.FORMA.Eliminar.value="1";
		document.FORMA.submit();
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table  border="1" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:13;font-style:<? echo $Estilo[10]?>" align="center">
<? 	
$cons="select ctapresupuestal,nombre from presupuesto.crucecuentascero,presupuesto.plancuentas where CtaPresupuestal ilike '$CtaPresupuestal%' and crucecuentascero.anio='$Anio' and crucecuentascero.vigencia='$Vigencia' and crucecuentascero.Clasevigencia='$ClaseVigencia'
and crucecuentascero.compania='$Compania[0]' and plancuentas.compania='$Compania[0]' and plancuentas.cuenta=crucecuentascero.CtaPresupuestal 
group by ctapresupuestal,nombre
order by ctapresupuestal,nombre";
$res=ExQuery($cons);
//echo $cons;
if(ExNumRows($res)>0)
{
?>	<tr align="center"><td colspan="3"><input type="button" value="Retirar" onClick="if(confirm('Esta seguro de retirar este regestro!!!')){Retira('');}" /></td></tr><?
	while($fila=ExFetch($res))
	{
		$cons2="select tipocomppresupuestal,ctadebe,ctahaber,autoid from presupuesto.crucecuentascero 
		where CtaPresupuestal='$fila[0]' and crucecuentascero.anio='$Anio' and crucecuentascero.vigencia='$Vigencia' and 
		crucecuentascero.Clasevigencia='$ClaseVigencia' and crucecuentascero.compania='$Compania[0]' group by tipocomppresupuestal,ctadebe,ctahaber,autoid 
		order by autoid";
		$res2=ExQuery($cons2);	
	?> 	<tr bgcolor="#666699" style="color:white;font-weight:bold">
			<td align="center" colspan="3">Amarre Existente: <? echo $fila[0]?> - <? echo $fila[1]?></td>
		</tr>
		<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
			<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
		</tr> <?
		while($fila2=ExFetch($res2)){
		?>	<tr align="center"><td><strong><? echo $fila2[0]?></strong></td><td><? echo $fila2[1]?></td><td><? echo $fila2[2]?></td></tr><?
		}
	}
}
else
{
	$Cta=substr($CtaPresupuestal,0,1);
	if(!$CuentaDB)
	{	?>
    	<tr bgcolor="#666699" style="color:white;font-weight:bold">
			<td colspan="4" align="center">Nuevo Amarre <? echo $CtaPresupuestal?> - <? echo $NomCuenta?></td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="#666699" style="color:white;" align="center"><strong>Comprobante:</strong> Apropiacion Inicial</td>
		</tr>    
		<tr bgcolor="#666699" style="color:white;" align="center">
			<td>Cuenta DB</td><td>Cuenta HB</td>
		</tr>    	
    <?		//echo $ClaseVigencia;
		if($Cta==1){
			$TablaBusq="ingresos";
		}
		else{
			if($ClaseVigencia=="Reservas")
			{
					$TablaBusq="reservas";
			}
			else{
				if($ClaseVigencia=="CxP")
					{
						$TablaBusq="ctasxpagar";
					}
					else{
					//tabla vigencia
						$TablaBusq="vigencia";
				}
			}
		}?>
        <tr align="center">
      		<td><input type="text" readonly="readonly" onClick="BuscarCuenta('<? echo $TablaBusq?>')" onFocus="BuscarCuenta('<? echo $TablaBusq?>')" name="CuentaDB" style="width:80px"/></td>
      		<td><input type="submit" value="Generar"></td>
   		</tr>
<?	}
	else
	{		
		if($Cta==1){	
			
			$cons="select codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codrecaudado,nomrecaudado,naturecaudado
			from presupuesto.ingresos where codaprobado='$CuentaDB'";					
			$res=ExQuery($cons);			
			//echo $cons;			
			$fila=ExFetch($res);
			$Aprobado=$fila[0];
			$xEjecutar=$fila[3];
			$Recaudado=$fila[6];
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";
				//echo $cons3;
				$res3=ExQuery($cons3);				
			}			
			$Titulo1=substr($fila[0],0,1);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
			//echo "$cons2<br>";
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				//echo "$cons11<br>";
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo "$cons3<br>";
				$res3=ExQuery($cons3);							
			}	
			$Titulo2=substr($fila[0],0,2);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo $cons3;
				$res3=ExQuery($cons3);							
			}
			$Titulo3=substr($fila[0],0,4);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo $cons3;
				$res3=ExQuery($cons3);							
			}
				
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$Anio";
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
				//echo $cons3;
				$res3=ExQuery($cons3);
			}	
			$Titulo1=substr($fila[3],0,1);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				//echo "$cons11<br>";
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo "$cons3<br>";
				$res3=ExQuery($cons3);							
			}	
			$Titulo2=substr($fila[3],0,2);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo $cons3;
				$res3=ExQuery($cons3);							
			}
			$Titulo3=substr($fila[3],0,4);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo $cons3;
				$res3=ExQuery($cons3);							
			}
						
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$Anio";
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
				//echo $cons3;
				$res3=ExQuery($cons3);
			}	
			$Titulo1=substr($fila[6],0,1);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				//echo "$cons11<br>";
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo "$cons3<br>";
				$res3=ExQuery($cons3);							
			}	
			$Titulo2=substr($fila[6],0,2);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo $cons3;
				$res3=ExQuery($cons3);							
			}
			$Titulo3=substr($fila[6],0,4);
			$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
			//echo $cons2;
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)<=0)				
			{
				$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
				$res11=ExQuery($cons11); $fila11=ExFetch($res11);
				$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
				($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
				//echo $cons3;
				$res3=ExQuery($cons3);							
			}				 			
						
			$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
			$res3=ExQuery($cons3); $fila3=ExFetch($res3);
			$Autoid=$fila3[0]+1;			
			$cons2="select cuenta,nombre from presupuesto.plancuentas 
			where cuenta like'$CtaPresupuestal%' and vigencia='$Vigencia' and clasevigencia='$ClaseVigencia' and anio='$Anio' and tipo='Detalle' group by cuenta,nombre";
			$res2=ExQuery($cons2);	echo ExError();
			//echo $cons2;
			//Ingresos---Algoritmo
			while($fila2=ExFetch($res2)){	
		?>     	<tr bgcolor="#666699" style="color:white;font-weight:bold">
					<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
				</tr>
				<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
					<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
				</tr> <?							
				//Apropiacion Inicial--->Aprobado-xEjecutar				
				$cons3="insert into presupuesto.crucecuentascero (
				anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
				('$Anio','$Compania[0]','$CompContable','Apropiacion inicial','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
				$res3=ExQuery($cons3);
				//echo "$cons3<br>";
			?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
				$Autoid++;
				//Adicion--->Aprobado-xEjecutar
				$cons3="insert into presupuesto.crucecuentascero 
				(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
				('$Anio','$Compania[0]','$CompContable','Adicion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
				$res3=ExQuery($cons3);
				//echo "$cons3<br>";
			?>	<tr align="center"><td><strong>Adicion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
				$Autoid++;
				//Reduccion--->xEjecutar-Aprobado
				$cons3="insert into presupuesto.crucecuentascero 
				(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
				('$Anio','$Compania[0]','$CompContable','Reduccion','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
				$res3=ExQuery($cons3);
				$Autoid++;
				//echo "$cons3<br>";
			?>	<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
				//Ingreso presupuestal-->xEjecutar-Aprobado
				$cons3="insert into presupuesto.crucecuentascero 
				(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
				('$Anio','$Compania[0]','$CompContable','Ingreso presupuestal','$xEjecutar','$Recaudado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
				$res3=ExQuery($cons3);
				//echo "$cons3<br>";
				$Autoid++;
			?>	<tr align="center"><td><strong>Ingreso Presupuestal</strong></td><td><? echo $xEjecutar?></td><td><? echo $Recaudado?></td></tr><?
				//Diminucion de Ingreso Presupuestal-->Recaudado-xEjecutar
				$cons3="insert into presupuesto.crucecuentascero 
				(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
				('$Anio','$Compania[0]','$CompContable','Disminucion a ingreso presupuestal','$Recaudado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
				$res3=ExQuery($cons3);
				//echo "$cons3<br>";
			?>	<tr align="center"><td><strong>Disminucion a Ingreso Presupuestal</strong></td><td><? echo $Recaudado?></td><td><? echo $xEjecutar?></td></tr><?
				$Autoid++;				
			}	
		}
		else{
			if($ClaseVigencia=="Reservas")
			{				
				$cons="select codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codobligaciones,nomobligaciones,natuobligaciones,codpagado,nompagado,natupagado
				from presupuesto.reservas where codaprobado='$CuentaDB'";			
				$res=ExQuery($cons);			
				//echo $cons;			
				$fila=ExFetch($res);
				$Aprobado=$fila[0];
				$xEjecutar=$fila[3];
				$Obligaciones=$fila[6];
				$Pagado=$fila[9];
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";
					//echo $cons3;
					$res3=ExQuery($cons3);				
				}
				$Titulo1=substr($fila[0],0,1);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					//echo "$cons11<br>";
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);							
				}	
				$Titulo2=substr($fila[0],0,2);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}
				$Titulo3=substr($fila[0],0,4);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}		
					
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$Anio";
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
					//echo $cons3;
					$res3=ExQuery($cons3);
				}
				$Titulo1=substr($fila[3],0,1);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					//echo "$cons11<br>";
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);							
				}	
				$Titulo2=substr($fila[3],0,2);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}
				$Titulo3=substr($fila[3],0,4);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}
				
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$Anio";
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
					//echo $cons3;
					$res3=ExQuery($cons3);
				}
				$Titulo1=substr($fila[6],0,1);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					//echo "$cons11<br>";
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);							
				}	
				$Titulo2=substr($fila[6],0,2);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}
				$Titulo3=substr($fila[6],0,4);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}
		
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[9]' and Compania='$Compania[0]' and anio=$Anio";
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila[9]','$fila[10]','$fila[11]','Detalle')";
					//echo $cons3;
					$res3=ExQuery($cons3);
				}				 				
				$Titulo1=substr($fila[9],0,1);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					//echo "$cons11<br>";
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);							
				}	
					$Titulo2=substr($fila[9],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}
				$Titulo3=substr($fila[9],0,4);
				$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
				//echo $cons2;
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)<=0)				
				{
					$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
					$res11=ExQuery($cons11); $fila11=ExFetch($res11);
					$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
					($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
					//echo $cons3;
					$res3=ExQuery($cons3);							
				}
		
				$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
				$res3=ExQuery($cons3); $fila3=ExFetch($res3);
				$Autoid=$fila3[0]+1;			
				$cons2="select cuenta,nombre from presupuesto.plancuentas 
				where cuenta like'$CtaPresupuestal%' and vigencia='$Vigencia' and clasevigencia='$ClaseVigencia' and anio='$Anio' and tipo='Detalle' group by cuenta,nombre";
				$res2=ExQuery($cons2);	echo ExError();
				//Reservas---Algoritmo
				while($fila2=ExFetch($res2)){
			?>     	<tr bgcolor="#666699" style="color:white;font-weight:bold">
						<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
					</tr>
					<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
						<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
					</tr> <?
					//Apropiacion Inicial--->Aprobado-xEjecutar
					$cons3="insert into presupuesto.crucecuentascero (anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					('$Anio','$Compania[0]','$CompContable','Apropiacion inicial','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
					//echo "$cons3<br>";					
					$res3=ExQuery($cons3);
					$Autoid++;
				?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
					//Reduccion--->xEjecutar-Aprobado
					$cons3="insert into presupuesto.crucecuentascero (anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values					('$Anio','$Compania[0]','$CompContable','Reduccion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);
					$Autoid++;
				?>	<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
					//Obligacion presupuestal--->Obligaciones-xEjecutar
					$cons3="insert into presupuesto.crucecuentascero (anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					('$Anio','$Compania[0]','$CompContable','Obligacion presupuestal','$Obligaciones','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);
					$Autoid++;
				?>	<tr align="center"><td><strong>Obligacion Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $xEjecutar?></td></tr><?
					//Disminucion a obligacion presupuestal-->xEjecutar-Aprobado
					$cons3="insert into presupuesto.crucecuentascero (anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					('$Anio','$Compania[0]','$CompContable','Disminucion a obligacion presupuestal','$xEjecutar','$Obligaciones','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);
					$Autoid++;
				?>	<tr align="center"><td><strong>Disminucion a Obligacion Presupuestal</strong></td><td><? echo $xEjecutar?></td><td><? echo $Obligaciones?></td></tr><?
					//Egreso Presupuestal-->Pagado-Obligaciones
					$cons3="insert into presupuesto.crucecuentascero (anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					('$Anio','$Compania[0]','$CompContable','Egreso presupuestal','$Pagado','$Obligaciones','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);
					$Autoid++;
				?>	<tr align="center"><td><strong>Egreso Presupuestal</strong></td><td><? echo $Pagado?></td><td><? echo $Obligaciones?></td></tr><?
					//Disminucion a Egreso Presupuestal--->Obligaciones-Pagado
					$cons3="insert into presupuesto.crucecuentascero (anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					('$Anio','$Compania[0]','$CompContable','Disminucion a egreso presupuestal','$Obligaciones','$Pagado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
					//echo "$cons3<br>";
					$res3=ExQuery($cons3);
					$Autoid++;
				?>	<tr align="center"><td><strong>Disminucion a Egreso Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $Pagado?></td></tr><?
				}
			}
			else{			
				if($ClaseVigencia=="CxP")
				{
					$cons="select 	
					codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codpagado,nompagado,natupagado
					from presupuesto.ctasxpagar where codaprobado='$CuentaDB'";			
					$res=ExQuery($cons);			
					//echo $cons;			
					$fila=ExFetch($res);
					$Aprobado=$fila[0];
					$xEjecutar=$fila[3];					
					$Pagado=$fila[6];
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);				
					}
					$Titulo1=substr($fila[0],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[0],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[0],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
						
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$Anio";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}
					$Titulo1=substr($fila[3],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[3],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[3],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$Anio";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}									 			
					$Titulo1=substr($fila[6],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[6],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[6],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}		
								
					$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
					$res3=ExQuery($cons3); $fila3=ExFetch($res3);
					$Autoid=$fila3[0]+1;			
					$cons2="select cuenta,nombre from presupuesto.plancuentas 
					where cuenta like'$CtaPresupuestal%' and vigencia='$Vigencia' and clasevigencia='$ClaseVigencia' and anio='$Anio' and tipo='Detalle' group by cuenta,nombre";
					$res2=ExQuery($cons2);	echo ExError();
					//Ctas x Pagar
					while($fila2=ExFetch($res2)){
					?> 	<tr bgcolor="#666699" style="color:white;font-weight:bold">
							<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
						</tr>
						<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
							<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
						</tr> <?						
						//Apropiacion Inicial--->xEjecutar-Aprobado
						$cons3="insert into presupuesto.crucecuentascero 						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Apropiacion inicial','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);
						$Autoid++;
					?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
						//Reduccion--->Aprobado-xEjecutar
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Reduccion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";						
						//echo "$cons3<br>";	
						$res3=ExQuery($cons3);
						$Autoid++;
					?>	<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
						//Egreso presupuestal--->Pagado-xEjecutar	
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values	
						('$Anio','$Compania[0]','$CompContable','Egreso presupuestal','$Pagado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";										
					?>	<tr align="center"><td><strong>Egreso Presupuestal</strong></td><td><? echo $Pagado?></td><td><? echo $xEjecutar?></td></tr><?
						//Diminucion a egreso presupuestal-->Recaudado-xEjecutar						
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Disminucion a egreso presupuestal','$xEjecutar','$Pagado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Diminucion a Egreso Presupuestall</strong></td><td><? echo $xEjecutar?></td><td><? echo $Pagado?></td></tr><?
					}
				}
				else{					
					$cons="select codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codcomprometido,nomcomprometido,natucomprometido,
					codpagado,nompagado,natupagado,codobligaciones,nomobligaciones,natuobligaciones
					from presupuesto.vigencia where codaprobado='$CuentaDB'";			
					$res=ExQuery($cons);			
					//echo $cons;			
					$fila=ExFetch($res);
					$Aprobado=$fila[0];
					$xEjecutar=$fila[3];					
					$Comprometido=$fila[6];
					$Pagado=$fila[12];
					$Obligaciones=$fila[9];
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);				
					}
					$Titulo1=substr($fila[0],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[0],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[0],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
						
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$Anio";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}
					$Titulo1=substr($fila[3],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[3],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[3],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}	
						
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$Anio";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}	
					$Titulo1=substr($fila[6],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[6],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[6],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
													 			
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[9]' and Compania='$Compania[0]' and anio=$Anio";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[9]','$fila[10]','$fila[11]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}	
					$Titulo1=substr($fila[9],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[9],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[9],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[12]' and Compania='$Compania[0]' and anio=$Anio";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila[12]','$fila[13]','$fila[14]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}	
					$Titulo1=substr($fila[12],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[12],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[12],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$Anio";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($Anio,'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
							
					$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
					$res3=ExQuery($cons3); $fila3=ExFetch($res3);
					$Autoid=$fila3[0]+1;			
					$cons2="select cuenta,nombre from presupuesto.plancuentas 
					where cuenta like'$CtaPresupuestal%' and vigencia='$Vigencia' and clasevigencia='$ClaseVigencia' and anio='$Anio' and tipo='Detalle' group by cuenta,nombre";
					$res2=ExQuery($cons2);	echo ExError();
					//Vigencia
					while($fila2=ExFetch($res2)){
					?> 	<tr bgcolor="#666699" style="color:white;font-weight:bold">
							<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
						</tr>
						<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
							<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
						</tr> <?						
						//Apropiacion Inicial--->xEjecutar-Aprobado
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Apropiacion inicial','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
						//Adicion ---> xEjecutar-Aprobado
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Adicion','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Adicion</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
						//Reduccion--->Aprobado-xEjecutar
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Reduccion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";						
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";		
					?>	<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
						//Compromiso presupuestal--->
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values	
						('$Anio','$Compania[0]','$CompContable','Compromiso presupuestal','$Comprometido','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";	
					?>	<tr align="center"><td><strong>Compromiso Presupuestal</strong></td><td><? echo $Comprometido?></td><td><? echo $xEjecutar?></td></tr><?
						//Disminucion a compromiso--->xEjecutar-Comprometido
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Disminucion a compromiso','$xEjecutar','$Comprometido','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";	
					?>	<tr align="center"><td><strong>Disminucion a compromiso</strong></td><td><? echo $xEjecutar?></td><td><? echo $Comprometido?></td></tr><?
						//Obligacion presupuestal--->Obligaciones-Comprometido
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Obligacion presupuestal','$Obligaciones','$Comprometido','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";																					
					?>	<tr align="center"><td><strong>Obligacion Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $Comprometido?></td></tr><?
						//Disminucion a obligacion Presupuestal-->Comprometido-Obligaciones						
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Disminucion a obligacion presupuestal','$Comprometido','$Obligaciones','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Disminucion a Obligacion Presupuestal</strong></td><td><? echo $Comprometido?></td><td><? echo $Obligaciones?></td></tr><?
						//Egreso presupuestal-->Pagado-Obligaciones						
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Egreso presupuestal','$Pagado','$Obligaciones','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Egreso Presupuestal</strong></td><td><? echo $Pagado?></td><td><? echo $Obligaciones?></td></tr><?
						//Disminucion a egreso presupuestal-->Comprometido-Obligaciones						
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Disminucion a egreso presupuestal','$Obligaciones','$Pagado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','0')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Disminucion a Egreso Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $Pagado?></td></tr><?
						//Traslados (Contra Credito)-->Aprobado-xEjecutar
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Traslado','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','Contra Credito')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Traslado (Contra Credito)</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
						//Traslados (Credito)--->xEjecutar-Aprobado
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						('$Anio','$Compania[0]','$CompContable','Traslado','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$Vigencia','$ClaseVigencia','Credito')";
						$res3=ExQuery($cons3);
						$Autoid++;
						//echo "$cons3<br>";
					?>	<tr align="center"><td><strong>Traslado (Credito)</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
					}
				}
			}
		}
	}
}?>	
</table>
<input type="hidden" name="CuentaDBAux" value="<? echo $CuentaDB?>" />
<input type="hidden" name="CtaPresupuestal" value="<? echo $CtaPresupuestal?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="ClaseVigencia" value="<? echo $ClaseVigencia?>" />
<input type="hidden" name="Vigencia" value="<? echo $Vigencia?>" />
<input type="hidden" name="Eliminar" value="<? echo $Eliminar?>"/>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
