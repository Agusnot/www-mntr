<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($DiasTr){$DiasTr=1;}else{$DiasTr='';}
		$Operacion=str_replace("$","\\$",$_POST["Operacion"]);
		//echo $Operacion;
		if(!$Editar)
		{			
			$cons="select codigo from nomina.conceptosliquidacion where movimiento='$Movimiento' and tipovinculacion='$TipoVinculacion' order by codigo desc";
			$res=ExQuery($cons);$fila=ExFetch($res);
			if($fila){$Codigo=$fila[0]+1;}else{$Codigo=1;}			
			//$ban1=ExNumRows($res1);
			$cons="select codigo from nomina.conceptosliquidacion where movimiento='$Movimiento' and concepto='$Concepto' and tipovinculacion='$TipoVinculacion'";
			$res2=ExQuery($cons);
			$ban2=ExNumRows($res2);
			/*if($ban1>0)
			{
				?><script language="javascript">alert("El Codigo del concepto que desea ingresar ya existe!!!");</script><?		
			}*/
			if($ban2>0)
			{
				?><script language="javascript">alert("El Nombre del concepto que desea ingresar ya existe!!!");</script><?		
			}
			if($ban2==0)
			{
				$cons="insert into nomina.conceptosliquidacion (compania,concepto,tipoconcepto,opera,arrastracon,movimiento,claseconcepto,codigo,detconcepto,tipovinculacion,novedad,diastr,xccdb,ctadebe,xcccr,ctacredito,varpila) values('$Compania[0]','$Concepto','$TipConcepto','$Operacion','$VieneCon','$Movimiento','$ClaseConcepto','$Codigo','$DetConcepto','$TipoVinculacion','$Novedad','$DiasTr','$CheckDebito','$Debito','$CheckCredito','$Credito','$Variable')";
			//echo $cons;
				$res=ExQuery($cons); 
				?><script language="javascript">location.href="ConfigConceptos.php?DatNameSID=<? echo $DatNameSID?>&Movimiento=<? echo $Movimiento?>&TipoVinculacion=<? echo $TipoVinculacion?>";</script><?
			}
		}
		else
		{
			$cons="update nomina.conceptosliquidacion set concepto='$Concepto',detconcepto='$DetConcepto',tipoconcepto='$TipConcepto',claseconcepto='$ClaseConcepto',opera='$Operacion',arrastracon='$VieneCon',novedad='$Novedad', DiasTr='$DiasTr',xccdb='$CheckDebito',ctadebe='$Debito',xcccr='$CheckCredito',ctacredito='$Credito',varpila='$Variable' where codigo='$Codigo' and movimiento='$Movimiento' and tipovinculacion='$TipoVinculacion'";
//			echo $cons;
			$res=ExQuery($cons);
			?><script language="javascript">location.href="ConfigConceptos.php?DatNameSID=<? echo $DatNameSID?>&Movimiento=<? echo $Movimiento?>&TipoVinculacion=<? echo $TipoVinculacion ?>";</script><?
		}
	}
	if($Editar)
	{
		$cons="select codigo,concepto,detconcepto,tipoconcepto,claseconcepto,Movimiento,opera,arrastracon,tipovinculacion,diastr,xccdb,ctadebe,xcccr,ctacredito,novedad,varpila from nomina.conceptosliquidacion where codigo='$Codigo' and movimiento='$Movimiento' and concepto='$Concepto' and tipovinculacion='$TipoVinculacion'";
		$res=ExQuery($cons);
		//echo $cons;
		$fila=ExFetch($res);
		if(!$Codigo){$Codigo=$fila[0];}
		if(!$Concepto){$Concepto=$fila[1];}		
		if(!$DetConcepto){$DetConcepto=$fila[2];}
		if(!$TipConcepto){$TipConcepto=$fila[3];}
		if(!$ClaseConcepto){$ClaseConcepto=$fila[4];}
		if(!$Movimiento){$Movimiento=$fila[5];}
		if(!$Operacion){$Operacion=$fila[6];}
		if(!$VieneCon){$VieneCon=$fila[7];}
		if(!$TipoVinculacion){$TipoVinculacion=$fila[8];}
		if(!$DiasTr){$DiasTr=$fila[9];}
		if(!$CheckDebito){$CheckDebito=$fila[10];}
		if(!$Debito){$Debito=$fila[11];}
		if(!$CheckCredito){$CheckCredito=$fila[12];}
		if(!$Credito){$Credito=$fila[13];}
		if(!$Novedad){$Novedad=$fila[14];}
		if(!$Variable){$Variable=$fila[15];}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
  // alert("");
   //if(document.FORMA.Codigo.value==''){alert('Por favor ingrese el Codigo del Concepto!!!');return false;}
   if(document.FORMA.Concepto.value==''){alert('Por favor ingrese el Nombre del Concepto!!!');return false;}
   if(document.FORMA.TipConcepto.value==''){alert('Por favor ingrese el Tipo del Concepto!!!');return false;}
   if(document.FORMA.ClaseConcepto.value==''){alert('Por favor ingrese la Clase del Concepto!!!');return false;}   
}
function CuentasxCC(Valor,Naturaleza,Concepto,Movimiento)
	{
//		alert(Movimiento);
		document.Info.location.href="CuentasXCC.php?DatNameSID=<? echo $DatNameSID?>&Valor="+Valor.value+"&Naturaleza="+Naturaleza+"&Concepto="+Concepto+"&Movimiento="+Movimiento;
//		document.getElementById('Info').style.position='absolute';
//		document.getElementById('Info').style.top='10px';
//		document.getElementById('Info').style.right='10px';
//		document.getElementById('Info').style.display='';
//		document.getElementById('Info').style.width='300px';
//		document.getElementById('Info').style.height='450px';
	}
function AsistBusqueda(Valor,Cuenta)
	{
		document.FrameOpener.location.href="AsistenteCxC.php?DatNameSID=<? echo $DatNameSID?>&Valor="+Valor.value+"&Cuenta="+Cuenta;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='10px';
		document.getElementById('FrameOpener').style.right='10px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='300px';
		document.getElementById('FrameOpener').style.height='450px';
	}
function Ocultar()
	{

		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='0';
		document.getElementById('FrameOpener').style.height='0';
	}	
</script>
</head>
<body>
<body background="/Imgs/Fondo.jpg" onFocus="Ocultar();">
<form name="FORMA" method="post" onSubmit="return Validar()">
<!--<input type="hidden" name="Movimiento" value="<? echo $Movimiento?>">-->
<input type="hidden" name="TipoVinculacion" value="<? echo $TipoVinculacion?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Editar" value="<? echo $Editar?>">
<input type="hidden" name="Codigo" value="<? echo $Codigo?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"/>
<tr>
	<td colspan="4" bgcolor="#666699" style="color:white" align="center">NUEVO CONCEPTO</td>
</tr>
<tr>
   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >CONCEPTO</td>
    <td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold" align="center" >DETALLE CONCEPTO</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >TIPO DE CONCEPTO</td>
</tr>
<tr>
	<td><input type="text" name="Concepto" value="<? echo $Concepto?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  onFocus="Ocultar();"/></td>
    <td colspan="2"><input type="text" name="DetConcepto" value="<? echo $DetConcepto?>" onKeyPress="xLetra(this)" onKeyDown="xLetra(this)" style="width:100%" onFocus="Ocultar();"/></td>
    <td><select name="TipConcepto" onChange="FORMA.submit();" style="width:155px" onFocus="Ocultar();">
            <option ></option>
                    <?
					$cons = "select codigo,tipoconcepto from nomina.tiposconcepto order by tipoconcepto";
					$resultado = ExQuery($cons);
					while ($fila = ExFetch($resultado))
					{                        
						 if($fila[1]==$TipConcepto)
						 {
							 echo "<option value='$fila[1]' selected>$fila[1]</option>"; 
						 }
						 else{echo "<option value='$fila[1]'>$fila[1]</option>";}						 
					}
	?>
            </select></td>
</tr>
<tr>
		
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >CLASE CONCEPTO</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >MOVIMIENTO</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">TIPO VINCULACION</td>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">NOVEDAD</td>
</tr>
<tr>
	
    <td><select name="ClaseConcepto" onChange="FORMA.submit();" style="width:155px" onFocus="Ocultar();">
            <option ></option>
                    <?	
					if($Movimiento!='Devengados')
					{
						$cons = "select codigo,claseconcepto from nomina.clasesconcepto order by claseconcepto";
                    	$resultado = ExQuery($cons);
                    	while ($fila = ExFetch($resultado))
						{                        
							 if($fila[1]==$ClaseConcepto)
							 {
								 echo "<option value='$fila[1]' selected>$fila[1]</option>"; 
							 }
							 else{echo "<option value='$fila[1]'>$fila[1]</option>";}						 
						}
					}
					else
					{
						$cons = "select codigo,claseconcepto from nomina.clasesconcepto where claseconcepto!='AutoRegistro' order by claseconcepto";
                    	$resultado = ExQuery($cons);
                    	while ($fila = ExFetch($resultado))
						{                        
							 if($fila[1]==$ClaseConcepto)
							 {
								 echo "<option value='$fila[1]' selected>$fila[1]</option>"; 
							 }
							 else{echo "<option value='$fila[1]'>$fila[1]</option>";}						 
						}
					}
				?>
            </select></td>
    <td align="center">
    <input type="text" name="Movimiento" value="<? echo $Movimiento?>" readonly onFocus="Ocultar();"/>
    </td>
    <td><input type="text" name="TipoVinculacion" value="<? echo $TipoVinculacion?>" readonly />
    <td><select name="Novedad" onChange="FORMA.submit();" style="width:100%" onFocus="Ocultar();" <? if($ClaseConcepto!='Dias'){ echo "disabled";}?>>
    	<option></option>
    	<option value="Incapacidades" <? if($Novedad=="Incapacidades"){echo "selected";}?>>Incapacidades</option>
        <option value="Licencias" <? if($Novedad=="Licencias"){echo "selected";}?>>Licencias</option>
       	<option value="Suspensiones" <? if($Novedad=="Suspensiones"){echo "selected";}?>>Suspensiones</option>
        <option value="Vacaciones" <? if($Novedad=="Vacaciones"){echo "selected";}?>>Vacaciones</option>
        </select>
    </td>
</tr>
<tr>
	
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">VIENE CON</td>
   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="3">FORMULA</td>
</tr>
<tr>
	
    <td><select name="VieneCon" onChange="FORMA.submit();" style="width:100%" onFocus="Ocultar();" <? if($TipConcepto!='Formula'){echo "disabled";}?>>
    	<option></option>
        <?
                    $cons = "select concepto,detconcepto from nomina.conceptosliquidacion where tipovinculacion='$TipoVinculacion'  and (claseconcepto='Dias' or claseconcepto='Cantidad') order by concepto";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$VieneCon)
						 {
							 echo "<option value='$fila[0]' selected>$fila[0] - $fila[1]</option>"; 
						 }
						 else{echo "<option value='$fila[0]'>$fila[0] - $fila[1]</option>";}						 
                    }
				?>
        </select>
    </td>
    <? //					echo $cons;
?>
   	<td colspan="3"><input type="text" name="Operacion" value="<? echo $Operacion?>" style="width:100%" onFocus="Ocultar();" <? if($TipConcepto!='Formula'){echo "disabled";}?>/></td>
</tr>
<? 	$cons="select concepto,diastr from nomina.conceptosliquidacion where diastr='1' and tipovinculacion='$TipoVinculacion'";
	$res=ExQuery($cons);
	$ConDias=ExNumRows($res);
	$filacon=ExFetch($res);	
	$DiasTr=$filacon[1];
	if(!$ConDias==1||$filacon[0]==$Concepto)
	{
		?>
		<tr>
			<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">Dias Trabajados : <input type="checkbox" name="DiasTr" <? if($DiasTr){echo "checked";}?> onFocus="Ocultar();">
			</td>
		</tr>
<?	} 
//echo $Movimiento." --> ".$Concepto."<br>";       
	 ?>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">DEBITOS</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">CREDITOS</td>
</tr>
<tr>
	<td align="right" colspan="2"><input type="text" name="Debito" value="<? echo $Debito?>" style="width:100%" onFocus="AsistBusqueda(this,'Debito')" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsistBusqueda(this,'Debito')"/></td>
    <td align="right" colspan="2"><input type="text" name="Credito" value="<? echo $Credito?>" style="width:100%" onFocus="AsistBusqueda(this,'Credito')" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsistBusqueda(this,'Credito')"/></td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="checkbox" name="CheckDebito" value="1" <? if($CheckDebito=="1"){ echo "checked";}?> onClick="if(this.checked){document.FORMA.Debito.disabled=true;
    document.FORMA.Debito.value='DEBITOS X CENTRO DE COSTOS';
    document.FORMA.CCD.disabled=false;
    }else{
    document.FORMA.Debito.disabled=false;
    document.FORMA.Debito.value=''
    document.FORMA.CCD.disabled=true;}" onFocus="Ocultar();" ><font size="-4">X Centro de Costo</font>
    <button name="CCD" onClick="CuentasxCC(this,'Debito',document.FORMA.Concepto.value,document.FORMA.Movimiento.value)" ><img src="/Imgs/s_process.png"></button>
    </td>
    
	<td colspan="2" align="center"><input type="checkbox" name="CheckCredito" value="1" <? if($CheckCredito=="1"){ echo "checked";}?> onClick="if(this.checked){document.FORMA.Credito.disabled=true;
    document.FORMA.Credito.value='CREDITOS X CENTRO DE COSTOS';    
    document.FORMA.CCC.disabled=false;
    }else{
    document.FORMA.Credito.disabled=false;
    document.FORMA.Credito.value='';
    document.FORMA.CCC.disabled=true;}" onFocus="Ocultar();" ><font size="-4">X Centro de Costo</font>
    <button name="CCC" onClick="CuentasxCC(this,'Credito',document.FORMA.Concepto.value,document.FORMA.Movimiento.value)" onFocus=""><img src="/Imgs/s_process.png"></button>
    </td>    
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Variables de Pila
    </td>
	<td colspan="3">
    <select name="Variable">
    <option></option>
    	<?
		$cons = "select variable from nomina.variables where compania='$Compania[0]' order by variable";
		$resultado = ExQuery($cons);
		while ($fila = ExFetch($resultado))
		{                        
			 if($fila[0]==$Variable)
			 {
				 echo "<option value='$fila[0]' selected>$fila[0]</option>"; 
			 }
			 else{echo "<option value='$fila[0]'>$fila[0]</option>";}						 
		}
		?>
    </select>
    </td>
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar" >
<input type="button" value="Cancelar" onClick="location.href='ConfigConceptos.php?DatNameSID=<? echo $DatNameSID?>&Movimiento=<? echo $Movimiento;?>&TipoVinculacion=<? echo $TipoVinculacion?>'"></center>
</form>
<script language="javascript">
if(document.FORMA.CheckDebito.checked){document.FORMA.CCD.disabled=false; document.FORMA.Debito.disable=true;}
else{document.FORMA.CCD.disabled=true;}
if(document.FORMA.CheckCredito.checked){document.FORMA.CCC.disabled=false; document.FORMA.Credito.disable=true;}
else{document.FORMA.CCC.disabled=true;}
</script>
<table align="center" width="50%">
<tr>
	<td colspan="2">
    <iframe id="Info" name="Info" frameborder="0" width="100%" height="200px">
    </iframe>
	</td>
</tr>
</table>
</body>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" ></iframe>
</html>