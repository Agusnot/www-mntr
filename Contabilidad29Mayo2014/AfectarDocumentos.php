<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?

	if($Registrar)
	{

		$cons="Select AutoId from Contabilidad.TmpMovimiento where NumReg='$NUMREG' Order By AutoId Desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$IdOt=$fila[0]+1;

		$AutoId=$fila[0]+1;

		if($ComprobSel)
		{
			while (list($val,$cad) = each ($ComprobSel)) 
			{
				$cons1="Select Naturaleza from Contabilidad.PlanCuentas where Cuenta='$ValorCuenta[$cad]' and Compania='$Compania[0]' and Anio=$Anio";
				$res1=ExQuery($cons1);
				$fila1=ExFetch($res1);
				if($fila1[0]=="Debito"){$Debitos=0;$Creditos=$Valor[$cad];$TotHaber=$TotHaber+$Creditos;}
				else{$Debitos=$Valor[$cad];$Creditos=0;$TotDebe=$TotDebe+$Debitos;}
				$ValorTotal=$ValorTotal+$Valor[$cad];

				$AutoId++;
				$cons="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
				values('$NUMREG',$AutoId,'$Comprobante',$ValorCuenta[$cad],'$TerceroCuenta[$cad]',$Debitos,$Creditos,'000','$NoDocumento[$cad]','$Compania[0]','$Detalle')";
				$res=ExQuery($cons);echo ExError($res);
				$cons2="Select Concepto,Cuenta,Opera from Contabilidad.ConceptosAfectacion where lower(Comprobante)=lower('$Comprobante')
				 and CuentaBase='$ValorCuenta[$cad]' and Compania='$Compania[0]' and Anio=$Anio";
				$res2=ExQuery($cons2);
				$ConceptoBajar=$DetalleDoc[$cad];
				while($fila2=ExFetch($res2))
				{
					$Debitos=0;$Creditos=0;
					$NomCampo=str_replace(" ","",$fila2[0]);
					$VrCampo=${$NomCampo}[$cad];
					if($VrCampo>0){
					if($fila2[2]=="+"){$ValorTotal=$ValorTotal+$VrCampo;}
					elseif($fila2[2]=="-"){$ValorTotal=$ValorTotal-$VrCampo;}
					$cons1="Select Naturaleza from Contabilidad.PlanCuentas where Cuenta='$fila2[1]' and Compania='$Compania[0]' and Anio=$Anio";
					$res1=ExQuery($cons1);
					$fila1=ExFetch($res1);
					if($fila1[0]=="Debito"){$Debitos=$VrCampo;$Creditos=0;}
					else{$Debitos=0;$Creditos=$VrCampo;}

					$AutoId++;
					$cons="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
					values('$NUMREG',$AutoId,'$Comprobante',$fila2[1],'$Tercero','$Debitos','$Creditos','000','$NoDocumento[$cad]','$Compania[0]','$Detalle')";
					$res=ExQuery($cons);echo ExError($res);
					}
				}
			}

			$cons1="Select TipoComprobant from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			if($fila1[0]=="Ingreso"){$Naturaleza="Debito";}
			else{$Naturaleza="Credito";}
	
			if($Naturaleza=="Debito"){$Debitos=$ValorTotal;$Creditos=0;}
			else{$Debitos=0;$Creditos=$ValorTotal;}

			$cons="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
			values('$NUMREG',$IdOt,'$Comprobante',$CuentaCruzar,'$Tercero',$Debitos,$Creditos,'000','0','$Compania[0]','$Detalle')";
			$res=ExQuery($cons);echo ExError($res);

		}
		?>
		<script language="JavaScript">
			parent.document.FORMA.ValidacionCruce.value=1;
			parent.document.FORMA.Detalle.value="<? echo $ConceptoBajar?>";
			parent.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Tercero?>';
			CerrarThis();
		</script>
<?	}

?>
<title>Afectar Comprobante</title>
<script language="javascript" src="/Funciones.js"></script>
<script language="JavaScript">
	function Marcar()
	{
		if(document.FORMA.Marcacion.checked==1){MarcarTodo();}
		else{QuitarTodo();}
	}

	function MarcarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
		{
	        document.FORMA.elements[i].checked=1 ;
			document.FORMA1.VrMarcadoOct.value=document.FORMA.TotCartera.value;
			document.FORMA1.VrMarcado.value=formatCurrency(parseInt(document.FORMA1.VrMarcadoOct.value));
		}
		
	}
	function QuitarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
		{
	        document.FORMA.elements[i].checked=0;
			document.FORMA1.VrMarcadoOct.value=0;
			document.FORMA1.VrMarcado.value=formatCurrency(parseInt(document.FORMA1.VrMarcadoOct.value));
		}
	}
function formatCurrency(num) { 
num = num.toString().replace(/$|,/g,''); 
if(isNaN(num)) 
num = "0"; 
sign = (num == (num = Math.abs(num))); 
num = Math.floor(num*100+0.50000000001); 
cents = num%100; 
num = Math.floor(num/100).toString(); 
if(cents<10) 
cents = "0" + cents; 
for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) 
num = num.substring(0,num.length-(4*i+3))+','+ 
num.substring(num.length-(4*i+3)); 
return (((sign)?'':'-') + '' + num + '.' + cents); 

}

	function Totales(Id,Objeto,AuxVr,VrIni)
	{
		VrInicial=parseInt(document.FORMA1.VrMarcadoOct.value);
		VrInc=parseInt(document.getElementById("Valor["+Id+"]").value);
		if(AuxVr != 0)
		{
			if(parseInt(document.getElementById("Valor["+Id+"]").value) <= VrIni)
			{
				VrInc = parseInt(document.getElementById("Valor["+Id+"]").value) - parseInt(document.FORMA1.AuxValor.value);
			}
			else
			{VrInc = 0;document.getElementById("Valor["+Id+"]").value = document.FORMA1.AuxValor.value;}
		}
		if(Objeto==true)
		{
			VrFinal=VrInicial+VrInc;
		}
		else
		{
			VrFinal=VrInicial-VrInc;
		}
		document.FORMA1.VrMarcadoOct.value=VrFinal;
		document.FORMA1.VrMarcado.value=formatCurrency(parseInt(document.FORMA1.VrMarcadoOct.value));
		
	}
</script>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body>
<form name="FORMA1" method="post">
<table border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center"><td colspan="2">Fecha</td><td colspan="2">Rango</td><td>Total Marc</td><td></td></tr>
<tr>
<td><input type="Text" name="FechaI" style="width:80px;" value="<?echo $FechaI?>"></td>
<td><input type="Text" name="FechaF" style="width:80px;" value="<?echo $FechaF?>"></td>
<td><input type="Text" name="RangoI" style="width:80px;" value="<?echo $RangoI?>"></td>
<td><input type="Text" name="RangoF" style="width:80px;" value="<?echo $RangoF?>"></td>
<td bgcolor="<?echo $Estilo[1]?>"><input type="text" name="VrMarcado" style="width:110px;background:transparent; border:0px;color:white; font-weight:bold; text-align:right" value="0" readonly></td>
<input type="hidden" name="VrMarcadoOct" style="width:80px;" value="0" readonly>
<input type="Hidden" name="AuxValor">
<td><input type="Submit" name="Buscar" value="Buscar"></td>
</tr>
</table>

<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="Tercero" value="<?echo $Tercero?>">
<input type="Hidden" name="CuentaCruzar" value="<?echo $CuentaCruzar?>">
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Banco" value="<?echo $Banco?>">
<input type="Hidden" name="Detalle" value="<?echo $Detalle?>">

</form>

<form name="FORMA" method="post">
<table border="1" width="100%" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
<td><input type="Checkbox" onClick="Marcar()" name="Marcacion"></td>
<td>Documento</td><td>Numero</td><td>Fecha</td><td>Concepto</td><td>Valor</td>
<?
	if($FechaI && $FechaF){$Rangos="and Fecha>='$FechaI' and Fecha<='$FechaF'";}
	if($RangoI && $RangoF){$Rangos="and DocSoporte>='$RangoI' and DocSoporte<='$RangoF'";}
	$TotCartera=0;
	$cons="Select CruzarCon,Movimiento,Cuenta,CuentaCruzar,Varios from Contabilidad.CruzarComprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$CondComp="(";
	while($fila=ExFetch($res))
	{
		$Movimiento=$fila[1];
		if($Movimiento=="Debe"){$Movimiento2="Haber";}
		elseif($Movimiento=="Haber"){$Movimiento2="Debe";}
		$CompPosibles=$CompPosibles." Or Comprobante='$fila[0]'";
		$CuentPosibles=$CuentPosibles."Or Cuenta ilike '$fila[2]%'";

		$CondComp=$CondComp."(Comprobante='$fila[0]' and Cuenta like '$fila[2]%') Or ";
		$CuentaCruzar=$fila[3];
		if($fila[4]==1){$Varios=1;}
	}
	$CondComp=substr($CondComp,0,strlen($CondComp)-4);
	$CondComp=$CondComp.")";


	$CompPosibles=substr($CompPosibles,3,strlen($CompPosibles));
	$CompPosibles="(".$CompPosibles.")";
	$CuentPosibles=substr($CuentPosibles,2,strlen($CuentPosibles));
	$CuentPosibles="(".$CuentPosibles.")";
	
	if($Varios==1)
	{
		$CondVarios="Or Identificacion='99999999999-0'";
	}

	$cons20="Select sum($Movimiento2),DocSoporte,Cuenta from Contabilidad.Movimiento 
	where (Identificacion='$Tercero' $CondVarios) and Compania='$Compania[0]' and (Numero!='$Numero') and $CuentPosibles and Estado='AC'
	Group By DocSoporte,Cuenta having sum($Movimiento2)>0 Order By DocSoporte";

	$res20=ExQuery($cons20);
	while($fila20=ExFetch($res20))
	{
		$TotPagos[$fila20[2]][$fila20[1]]=$Pagos[$fila20[2]][$fila20[1]]+$fila20[0];
	}	

	$cons19="Select Fecha,Numero,Comprobante,0,DocSoporte,Detalle,Cuenta,DiasVencimiento,AutoId from Contabilidad.Movimiento 
	where (Identificacion='$Tercero' $CondVarios) $Rangos and $CondComp and Movimiento.Compania='$Compania[0]' and DocSoporte!='0' and Estado='AC' 
	Group By Fecha,Numero,Comprobante,Cuenta,DocSoporte,DiasVencimiento,AutoId,Detalle Order By DocSoporte,Fecha";
	$res19=ExQuery($cons19);
	while($fila19=ExFetch($res19))
	{
		$DatosFac[$fila19[6]][$fila19[4]]=array($fila19[0],$fila19[1],$fila19[2],$fila19[3],$fila19[4],$fila19[5],$fila19[6],$fila19[7],$fila19[8]);
	}	

	$cons1="Select 0,0,0,sum($Movimiento),DocSoporte,0,Cuenta,0,0,Identificacion from Contabilidad.Movimiento 
	where (Identificacion='$Tercero' $CondVarios) $Rangos and $CondComp and Movimiento.Compania='$Compania[0]' and DocSoporte!='0' and Estado='AC' 
	Group By Cuenta,DocSoporte,Identificacion Order By DocSoporte";

	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		$Pagos=$TotPagos[$fila1[6]][$fila1[4]];
		$fila1[3]=$fila1[3]-$Pagos;
		

		$i++;
		$cons2="Select Concepto,Cuenta,Opera from Contabilidad.ConceptosAfectacion where Comprobante='$Comprobante' and Compania='$Compania[0]' 
		and CuentaBase='$fila1[6]' and Anio=$Anio";
		if($i==1){
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
			echo "<td>$fila2[0]</td>";
		}}
		
		$FD=strtotime($fila1[0]);
		$DD=getdate();
		$FD1=strtotime("+$fila1[7] days",$FD);
		$FD2=getdate($FD1);

		$Date2="$FD2[year]-$FD2[mon]-$FD2[mday]";
		$Date1="$DD[year]-$DD[mon]-$DD[mday]";

		$s = strtotime($Date1)-strtotime($Date2);
		$d = intval($s/86400);
		if($d==0 || $fila1[7]=="0"){$Msj="CTE";}
		else{$Msj=$d;}
		if($fila1[3]>0){?>
		<input type="Hidden" name="ValorCuenta[<? echo $i?>]" value="<? echo $fila1[6]?>">
		<tr><td><center>
        <input type="Checkbox" name="ComprobSel[<? echo $i?>]" id="ComprobSel_<? echo $i?>" value="<? echo $i?>" 
        onClick="Totales(<? echo $i?>,this.checked,0,0)"></td>
        <td><? echo $DatosFac[$fila1[6]][$fila1[4]][2]?></td><td><? echo $fila1[4]?></td><td><? echo $DatosFac[$fila1[6]][$fila1[4]][0]?></td>
        <td><? echo $DatosFac[$fila1[6]][$fila1[4]][5]?></td><td align='right'><input id="Valor[<? echo $i?>]" name="Valor[<? echo $i?>]" type="Text" 
        style='width:70px; text-align:right' onChange="if(ComprobSel_<? echo $i?>.checked==true){Totales(<? echo $i?>,true,this.value,<? echo $fila1[3]?>);}
        else{if(this.value><? echo $fila1[3]?>){this.value='<? echo $fila1[3]?>'}}" 
         value="<? echo $fila1[3]?>"
         onFocus="document.FORMA1.AuxValor.value = this.value"></td>
		<input type="Hidden" name="NoDocumento[<? echo $i?>]" value="<? echo $fila1[4]?>">
		<input type="Hidden" name="TerceroCuenta[<? echo $i?>]" value="<? echo $fila1[9]?>">
		<input type="Hidden" name="DetalleDoc[<? echo $i?>]" value="<? echo $DatosFac[$fila1[6]][$fila1[4]][5]?>">
<?		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
			$fila2[0]=str_replace(" ","",$fila2[0]);
			echo "<td align='right'><input type='Text' name='$fila2[0][$i]' value='0' style='width:60px;'></td>";
		}}
		if($fila1[3]>0){
		$TotCartera=$TotCartera+$fila1[3];}
	}?>

		<input type="hidden" name="TotCartera" value="<? echo $TotCartera?>">

<tr bgcolor="#e6e6e6" align="right" style="font-weight:bold"><td colspan="5">TOTAL CARTERA</td><td align="right"><? echo number_format($TotCartera,2)?></td>
<?

	if($CuentaCruzar=="Bancos")
	{
		$cons="Select Cuenta from Contabilidad.PlanCuentas where Nombre='$Banco' and Compania='$Compania[0]' and Anio=$Anio";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$CuentaCruzar=$fila[0];
	}

?>
</table>
<br>
<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="Tercero" value="<?echo $Tercero?>">
<input type="Hidden" name="CuentaCruzar" value="<?echo $CuentaCruzar?>">
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Banco" value="<?echo $Banco?>">
<input type="Hidden" name="Detalle" value="<?echo $Detalle?>">
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
<input type="Submit" name="Registrar" value="Registrar">
<input type="button" value="Cerrar" onClick="CerrarThis()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
