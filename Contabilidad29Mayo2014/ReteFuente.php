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
	$cons98="Select tipocomprobant from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res98=ExQuery($cons98);
	$fila98=ExFetch($res98);
	$TipoComprobante=$fila98[0];

	if($Guardar && $ClaseRetencion)
	{
	
		while (list($val,$cad) = each ($ClaseRetencion)) 
		{

			$cons="Select AutoId from Contabilidad.TmpMovimiento where NumReg='$NUMREG' Order By AutoId Desc";
			$res=ExQuery($cons);echo ExError($res);
			$fila=ExFetch($res);
			$AutoId=$fila[0]+1;

			if($PorcRet[$val]==0)
			{
				$Creditos=$Base[$val];
			}
			else
			{
				$Creditos=((($Base[$val]*$PorcBase[$val])/100)*$PorcRet[$val])/100;
			}

			$Debitos=0;

			if($PorcIVA[$val]>0)
			{
				$Base[$val]=(100/$PorcIVA[$val])*$Base[$val];
			}
			if($Base[$val]>=$Minimo[$val]){

			$Debitos=round($Debitos);
			$Creditos=round($Creditos);


			if($TipoComprobante=="Egreso")
			{
				$cons="Select Cuenta,AutoId,Haber from Contabilidad.TmpMovimiento where NumReg='$NUMREG' and Haber>0 and Cuenta not ilike '2%'";
			}
			else
			{
				$cons="Select Cuenta,AutoId,Haber from Contabilidad.TmpMovimiento where NumReg='$NUMREG' and Haber>0";
				$DocSoporte=$DSoporte;
			}
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$VrHaber=$fila[2]-$Creditos;
			$CtaHaber=$fila[0];
			$IdHaber=$fila[1];
			
			$cons="Update Contabilidad.TmpMovimiento set Haber=$VrHaber where NumReg='$NUMREG' and Cuenta='$CtaHaber' and AutoId=$IdHaber";
			$res=ExQuery($cons);
			echo ExError($res);

			$cons="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle,Base,ConceptoRte,PorcRetenido)
			values('$NUMREG',$AutoId,'$Comprobante',$CuentaRet[$val],'$Tercero',$Debitos,$Creditos,'000','$DocSoporte','$Compania[0]','$Detalle',$Base[$val],'$NomRete[$val]','$PorcRet[$val]')";
			$res=ExQuery($cons);echo ExError($res);
			}
			
			?>
			<script language="JavaScript">
				CerrarThis();
				parent.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Tercero?>';
			</script>
<?		}
	}
?>
<title>Retencion en la Fuente</title>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body>
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
        document.FORMA.elements[i].checked=1 
	}
	function QuitarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=0
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
document.FORMA.BasexMoney.value= (((sign)?'':'-') + '$' + num + '.' + cents); 

}

</script>
<script language="JavaScript">
	function validar(VrBase,VrMinimo)
	{
		if(VrBase<VrMinimo){alert("Valor por debajo del minimo establecido");return false;}
	}
</script>
<script language='javascript' src="/Funciones.js"></script>
<form name="FORMA" method="post" onSubmit="return validar()">
<table border="1" width="100%" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr><td bgcolor="<?echo $Estilo[1]?>"><font color="#ffffff"><strong>Base Gravable</strong></font></td><td colspan="4">
<input type="Text" name="BaseGravableGral" onKeyUp="xNumero(this);formatCurrency(this.value)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"><img src="/Imgs/flecha_der.gif">
<input type="text" name="BasexMoney" readonly style="border:0px;font-weight:bold">
</td></tr>
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center"><td></td><td>Concepto</td><td>Porcentaje</td><td>IVA</td><td>Base</td></tr>
<?
	$cons1="Select TipoRetencion from Contabilidad.BasesRetencion where Compania='$Compania[0]' and Anio=$Anio Group By TipoRetencion";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		echo "<tr><td colspan=5 align='center' bgcolor='#e5e5e5'><strong>$fila1[0]</td></tr>";
		$cons="Select Concepto,Porcentaje,Base,Cuenta,MontoMinimo,IVA from Contabilidad.BasesRetencion where Compania='$Compania[0]' and Anio=$Anio and TipoRetencion='$fila1[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$i++;
			if($fila[1]==0){$Base=$fila[2];}
			
			echo "<tr><td><input type='Checkbox' name='ClaseRetencion[$i]' id='ClaseRetencion_$i' onclick='if(this.checked){if(!Base_$i.value || Base_$i.value==0){Base_$i.value=BaseGravableGral.value;}}else{Base_$i.value=0;}'></td><td>$fila[0]</td><td align='right'>$fila[1] %</td>";
	
			echo "<td><input type='Text' name='PorcIVA[$i]' value='$fila[5]' style='width:30px;'></td>";
			echo "<td><center><input type='Text' name='Base[$i]' value='$Base' Id='Base_$i' style='width:90px;'></td>";
			echo "<input type='Hidden' name='NomRete[$i]' value='$fila[0]'>";
			echo "<input type='Hidden' name='PorcBase[$i]' value=$fila[2]>";
			echo "<input type='Hidden' name='PorcRet[$i]' value=$fila[1]>";
			echo "<input type='Hidden' name='CuentaRet[$i]' value=$fila[3]>";
			echo "<input type='Hidden' name='Minimo[$i]' value=$fila[4]>";$Base="";
		}
	}
	echo "<input type='Hidden' name='Comprobante' value='$Comprobante'>";
	echo "<input type='Hidden' name='Tercero' value='$Tercero'>";
	echo "<input type='Hidden' name='Detalle' value='$Detalle'>";
	echo "<input type='Hidden' name='NUMREG' value=$NUMREG>";
	echo "<input type='Hidden' name='Anio' value=$Anio>";

?>

</table><br>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="Hidden" name="DocSoporte" value="<? echo $DocSoporte?>">
<input type="Submit" name="Guardar" value="Registrar">
<input type="button" value="Cancelar" onClick="CerrarThis()">
</form>
</body>