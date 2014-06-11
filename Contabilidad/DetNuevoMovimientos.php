<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	/*print_r($_GET);
	print_r($_POST);*/
	if(!$DocSoporte){$DocSoporte="0";}

	if(!$Debe){$Debe=0;}
	if(!$Haber){$Haber=0;}

	$cons="Select TipoComprobant,CompPresupuesto from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$TipoComp=$fila[0];

	if($Modificar)
	{
		$cons="Select NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle,Base,ConceptoRte,PorcRetenido,FechaConciliado 
		from Contabilidad.TmpMovimiento
		where NumReg='$NUMREG' and Cuenta='$Cuenta' and AutoId=$AutoId";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		$Cuenta=$fila['cuenta'];$Debe=$fila['debe'];
		$Haber=$fila['haber'];$Tercero=$fila['identificacion'];
		$CC=$fila['cc'];$DocSoporte=$fila['docsoporte'];
		$Detalle=$fila['detalle'];$Comprobante=$fila['comprobante'];
		$Base=$fila['base'];
		$ConceptoRte=$fila['conceptorte'];
		$PorcRetenido=$fila['porcretenido'];
		$FechaConciliado=$fila['fechaconciliado'];
		$cons="Delete from Contabilidad.TmpMovimiento where NumReg='$NUMREG' and Cuenta='$Cuenta' and AutoId=$AutoId";
		$res=ExQuery($cons);
		$ValMovimiento=1;
?>
		<script language="JavaScript">
			parent.document.FORMA.Guardar.disabled=true;
			parent.document.FORMA.Acarrear.disabled=true;
		</script>
<?
	}
	if($Eliminar)
	{
		$cons="Delete from Contabilidad.TmpMovimiento where NumReg='$NUMREG' and Cuenta='$Cuenta' and AutoId=$AutoId";
		$res=ExQuery($cons);
		$Guardar=1;$NoInsert=1;
	}
	
	if($Guardar)
	{
		if(!$NoInsert)
		{
			if(!$CC){$CC="000";}

			if(!$AutoId){
			$cons="Select AutoId from Contabilidad.TmpMovimiento where NumReg='$NUMREG' Order By AutoId Desc";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$AutoId=$fila[0]+1;}

			$cons="Select * from Central.Terceros where Identificacion='$Tercero' and Terceros.Compania='$Compania[0]'";

			$res=ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				echo "<em>Tercero NO existe. Registro Abortado</em><br>";
			}
			else
			{
				if(!$Base){$Base=0;}if(!$PorcRetenido){$PorcRetenido=0;}
				$cons="Insert into Contabilidad.TmpMovimiento (NumReg,AutoId,Comprobante,Identificacion,Cuenta,Debe,Haber,CC,DocSoporte,Compania,Detalle,Base,ConceptoRte,PorcRetenido)
				values('$NUMREG',$AutoId,'$Comprobante','$Tercero','$Cuenta',$Debe,$Haber,'$CC','$DocSoporte','$Compania[0]','$Detalle','$Base','$ConceptoRte','$PorcRetenido')";
$Base="";$ConceptoRte="";$PorcRetenido="";
				$res=ExQuery($cons);
				$ErrorGen=ExError();
				if(eregi(" llave duplicada ",$ErrorGen))
				{
					echo "<em>No puede duplicar la cuenta en un mismo asiento</em>";
				}
			}
			$Modificar=0;
		}
		

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.TmpMovimiento where NumReg='$NUMREG'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$TotDebe=$fila[0];$TotHaber=$fila[1];$Dif=round($TotDebe,2)-round($TotHaber,2);
		if($Dif==0 && ($TotHaber || $TotDebe))
		{?>
			<script language="JavaScript">
				parent.document.FORMA.Guardar.disabled=false;
				parent.document.FORMA.Acarrear.disabled=false;
			</script>
<?		}
		else
		{?>
			<script language="JavaScript">
				parent.document.FORMA.Guardar.disabled=true;
				parent.document.FORMA.Acarrear.disabled=true;
			</script>
<?		}
		?>
		<script language="JavaScript">
			parent.frames.TotMovimientos.document.FORMA.TotDebitos.value="<?echo number_format($TotDebe,2)?>";
			parent.frames.TotMovimientos.document.FORMA.TotCreditos.value="<?echo number_format($TotHaber,2)?>";
			parent.frames.TotMovimientos.document.FORMA.Diferencia.value="<?echo number_format($Dif,2)?>";
		</script>
<?		
		$AutoId="";
		$Cuenta="";$Debe="0";
		$Haber="0";$ValMovimiento=0;$CC="000";
	}
?>
	<html>
		<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
		</head>
		
		<body onLoad="document.FORMA.Cuenta.focus()" onFocus="Ocultar()">
			<script language="JavaScript">
				function Validar()
				{
				//	alert(document.FORMA.DocSoporte.value);
					if(document.FORMA.Haber.value>0)
					{
						if((!document.FORMA.DocSoporte.value || document.FORMA.DocSoporte.value=="" || document.FORMA.DocSoporte.value=="0") && (document.FORMA.TipoComp.value=="Cuentas x Pagar")){alert("Debe registrar un documento de referencia para los documentos de cuentas por pagar");document.FORMA.DocSoporte.focus();return false;}
					}
					if(document.FORMA.Debe.value>0)
					{
						if((!document.FORMA.DocSoporte.value || document.FORMA.DocSoporte.value=="" || document.FORMA.DocSoporte.value=="0") && (document.FORMA.TipoComp.value=="Facturas")){alert("Debe registrar un documento de referencia para los documentos de Facturacion");document.FORMA.DocSoporte.focus();return false;}
					}

					if(document.FORMA.Debe.value!=0 && document.FORMA.Haber.value!=0){alert("No puede registrar movimiento en ambas partidas");return false;}
					if(document.FORMA.Movimiento.value!="Detalle"){alert("Cuenta no permite movimiento");document.FORMA.Cuenta.focus();return false;}
					if(document.FORMA.Debe.value==0 && document.FORMA.Haber.value==0){alert("Debe ingresar el valor del movimiento");document.FORMA.Debe.focus();return false;}
					if(document.FORMA.ValeCC.value=="on" && (document.FORMA.CC.value=="000" || document.FORMA.CC.value=="" || document.FORMA.CC.value=="0")){alert("Cuenta requiere centro de costos");document.FORMA.CC.focus();return false;}
					if(document.FORMA.Tercero.value==""){alert("Se requiere tercero");document.FORMA.Tercero.focus();return false;}
				}
			</script>
			<script language='javascript' src="/Funciones.js"></script>
			<form name="FORMA" onSubmit="return Validar()">
				<table  width="100%" class="tabla2" style="font-size:11px"  <?php echo $borderTabla2Mentor ; echo $bordercolorTabla2Mentor ; echo $cellspacingTabla2Mentor ; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<td class="encabezado2Horizontal">ID</td>
						<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
						<td class="encabezado2Horizontal">DEBITO</td>
						<td class="encabezado2Horizontal">CREDITO</td>
						<td class="encabezado2Horizontal">TERCERO</td>
						<td class="encabezado2Horizontal">CC</td>
						<td class="encabezado2Horizontal">DOC</td>
					</tr>
			<?
				$cons="Select Cuenta,Debe,Haber,Identificacion,CC,DocSoporte,Detalle,AutoId,FechaConciliado from Contabilidad.TmpMovimiento where NumReg='$NUMREG' Order By AutoId";
				$cons;
				$res=ExQuery($cons);echo ExError($res);
				while($fila=ExFetch($res))
				{
					if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
					else{$BG="";$Fondo=1;}

					if($fila[8]==0){
					echo "<td><a href='DetNuevoMovimientos.php?DatNameSID=$DatNameSID&Modificar=1&Anio=$Anio&Cuenta=$fila[0]&NUMREG=$NUMREG&AutoId=$fila[7]&Comprobante=$Comprobante&Tercero=$Tercero&DocSoporte=$DocSoporte&Detalle=$Detalle''><img src='/Imgs/b_edit.png' border=0></a></td>";
					echo "<td><a href='DetNuevoMovimientos.php?DatNameSID=$DatNameSID&Eliminar=1&Anio=$Anio&Cuenta=$fila[0]&NUMREG=$NUMREG&AutoId=$fila[7]&Comprobante=$Comprobante&Tercero=$Tercero&DocSoporte=$DocSoporte&Detalle=$Detalle'><img src='/Imgs/b_drop.png' border=0></a></td>";}
					else{echo "<td colspan=2><em><font color='blue'>Concil</font></em></td>";}
					echo "</tr>";
				}
			?>
			<tr>
			<script language="JavaScript">
				function Mostrar()
				{
					parent.document.getElementById('Busquedas').style.position='absolute';
					parent.document.getElementById('Busquedas').style.top='50px';
					parent.document.getElementById('Busquedas').style.right='10px';
					parent.document.getElementById('Busquedas').style.display='';
				}
				function Ocultar()
				{
					parent.document.getElementById('Busquedas').style.display='none';
				}
				function BuscarTerceros()
				{

					parent.frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo=Tercero&NuevoMovimiento=1';
					parent.document.getElementById('FrameOpener').style.position='absolute';
					parent.document.getElementById('FrameOpener').style.top='50px';
					parent.document.getElementById('FrameOpener').style.left='15px';
					parent.document.getElementById('FrameOpener').style.display='';
					parent.document.getElementById('FrameOpener').style.width='690';
					parent.document.getElementById('FrameOpener').style.height='390';
				}
			</script>

			<td colspan="2"><input type="Text" id="Cuenta" name="Cuenta" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" value="<?echo $Cuenta?>" style="width:100px;" onFocus="if(!Tercero.value){Tercero.value=parent.document.FORMA.Identificacion.value;}Mostrar();parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio='+parent.document.FORMA.Anio.value+'&Tipo=PlanCuentas&Cuenta='+this.value" onKeyUp="xNumero(this);parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio='+parent.document.FORMA.Anio.value+'&Tipo=PlanCuentas&Cuenta='+this.value"></td>
			<td><input type="text" name="Debe" onFocus="Ocultar()" style="width:90px;" value="<?echo $Debe?>" onKeyUp="xNumero(this);;" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
			<td><input type="text" name="Haber" onFocus="Ocultar()" style="width:90px;" value="<?echo $Haber?>" onKeyUp="xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
			<td><input type="text" name="Tercero" readonly onFocus="Ocultar()" style="width:90px;" value="<? echo $Tercero?>">
			<input type='Button' value='...' onClick="BuscarTerceros()" class="boton1Envio"></td>
			</td>
			<td><input type="text" name="CC" readonly onfocus="parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CC&Centro='+this.value+'&Anio='+parent.document.FORMA.Anio.value;Mostrar()" disabled style="width:59px;" onClick="parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CC&Centro='+this.value+'&Anio='+parent.document.FORMA.Anio.value" onfocus="parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CC&Centro='+this.value" onkeyup="parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CC&Centro='+this.value+'&Anio='+parent.document.FORMA.Anio.value" value="<?echo $CC?>"></td>
			<td><input type="text" onFocus="Ocultar()" name="DocSoporte" style="width:90px;" value="<?echo $DocSoporte?>" onKeyUp="xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
			<td><input type="Hidden" onFocus="Ocultar()" name="Detalle" style="width:100px;" value="<?echo $Detalle?>"></td>
			<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
			<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
			<input type="Hidden" name="Movimiento" value="<?echo $ValMovimiento?>">
			<input type="Hidden" name="ValeCC" value="0">
			<input type="Hidden" name="AutoId" value="<?echo $AutoId?>">

			<input type="Hidden" name="Base" value="<?echo $Base?>">
			<input type="Hidden" name="ConceptoRte" value="<?echo $ConceptoRte?>">
			<input type="Hidden" name="PorcRetenido" value="<?echo $PorcRetenido?>">
			<input type="Hidden" name="EstadoMod" value="<? echo $Modificar?>">
			<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
			<input type="hidden" name="Anio" value="<? echo $Anio?>">
			<input type="hidden" name="TipoComp" value="<? echo $TipoComp?>">
			<td><input type="Submit" name="Guardar" class="boton1Envio" value="G"></td>
			</tr>
			</table>
			</form>
		</body>
	</html>	