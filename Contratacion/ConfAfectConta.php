<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	
	if($BtnGuardar)
	{
		$cons="select cuentacaja,cuentar from salud.afectacioncontable where compania='$Compania[0]' and anio=$Anio";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0){
			$cons="update salud.afectacioncontable set cuentacaja='$CuentaCaja',nomcuent='$NomCuenta',cuentar='$CuentaR',nomcuentar='$NomCuentaR',compcont='$CompIngresos'
			where compania='$Compania[0]' and anio=$Anio";
		}
		else{
			$cons="insert into salud.afectacioncontable (compania,anio,cuentacaja,nomcuent,cuentar,nomcuentar,compcont) values
			('$Compania[0]',$Anio,'$CuentaCaja','$NomCuenta','$CuentaR','$NomCuentaR','$CompIngresos')";			
		}		
		$res=ExQuery($cons);
	}
	
	$cons="select cuentacaja,nomcuent,cuentar,nomcuentar,compcont from salud.afectacioncontable where compania='$Compania[0]' and anio=$Anio";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(ExNumRows($res)>0){$CuentaCont="1"; $CuentaContR="1";}
  	$CuentaCaja=$fila[0]; $NomCuenta=$fila[1]; $CuentaR=$fila[2]; $NomCuentaR=$fila[3]; $CompIngresos=$fila[4]
?>	

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='1px';
		document.getElementById('Busquedas').style.left='42%';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function Validar()
	{
		if(document.FORMA.CuentaCont.value==""){alert("Debe seleccionar una Cuenta Contable!!!");return false;}
		if(document.FORMA.CuentaContR.value==""){alert("Debe seleccionar una Cuenta de Depositos!!!");return false;}
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg" onFocus="Ocultar();">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="CuentaCont" value="<? echo $CuentaCont?>"/>
<input type="hidden" name="CuentaContR" value="<? echo $CuentaContR?>"/>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' cellpadding="2">	               
	<tr>
		<td colspan="3" align="left">Año: 
        	<select name="Anio">
            <?	$cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
				$res = ExQuery($cons);
				while($fila=ExFetch($res))					
				{
					if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
				}?>
            </select>
        </td>
	</tr>
    <tr onfocus="Ocultar();">
		<td colspan="3" align="center" style='font-weight:bold;' bgcolor="#e5e5e5" onfocus="Ocultar();">CONFIGURACI&Oacute;N AFECTACI&Oacute;N CONTABLE</td>
	</tr>
    <tr align="center" style='font-weight:bold;' bgcolor="#e5e5e5">
    	<td  onfocus="Ocultar();">Cuenta de Caja</td>
		<td><input type="text" name="CuentaCaja" value="<? echo $CuentaCaja?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=CuentaR&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaCaja&Objeto1=NomCuenta&Objeto2=CuentaCont&SigObjeto=CuentaR&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
             onChange="document.FORMA.CuentaCont.value='';document.FORMA.NomCuenta.value=''" size="10"/>
       	</td>
        <td><input type="text" name="NomCuenta" value="<? echo $NomCuenta?>" style="border:thin" onFocus="Ocultar();" readonly/></td>
	</tr>
    <tr align="center" style='font-weight:bold;' bgcolor="#e5e5e5">
    	<td  onfocus="Ocultar();">Cuenta de Depositos</td>
		<td><input type="text" name="CuentaR" value="<? echo $CuentaCaja?>"
			onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaR&Objeto1=NomCuentaR&Objeto2=CuentaContR&SigObjeto=BtnGuardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
			onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=CuentaR&Objeto1=NomCuentaR&Objeto2=CuentaContR&SigObjeto=BtnGuardar&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
             onChange="document.FORMA.CuentaContR.value='';document.FORMA.NomCuentaR.value=''" size="10"/>
       	</td>
        <td><input type="text" name="NomCuentaR" value="<? echo $NomCuentaR?>" style="border:thin" onFocus="Ocultar();" readonly/></td>
	</tr>
    <tr>
    	<td style='font-weight:bold;' bgcolor="#e5e5e5">Comprobante de Caja</td>
        <td colspan="2">
        <?	$cons="select comprobante from contabilidad.comprobantes where compania='$Compania[0]' and tipocomprobant='Ingreso'";
			$res=ExQuery($cons);?>
        	<select name="CompIngresos">
            <?	while($fila=ExFetch($res)){
					if($fila[0]==$CompIngresos){?>
						<option value='<? echo $fila[0]?>' selected><? echo $fila[0]?></option>                        
				<?	}
					else{?>
						<option value='<? echo $fila[0]?>'><? echo $fila[0]?></option>                        
				<?	}
				}?>
            </select>
        </td>
    </tr>
    <tr align="center">
    	<td colspan="3">
        	<input type="submit" name="BtnGuardar" value="Guardar">
        </td>
    </tr>
</table>

</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0" height="400"></iframe>
</body>
</html>