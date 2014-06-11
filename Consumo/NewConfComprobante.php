<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		if ($Costo=="on"){$Costo="SI";}else{$Costo="NO";}
		if ($Venta=="on"){$Venta="SI";}else{$Venta="NO";}
		if ($IVA=="on"){$IVA="SI";}else{$IVA="NO";}
		if ($Descto=="on"){$Descto="SI";}else{$Descto="NO";}
		if ($ReteFte=="on"){$ReteFte="SI";}else{$ReteFte="NO";}
		if ($ICA=="on"){$ICA="SI";}else{$ICA="NO";}
		if ($OrdenCompra=="on"){$OrdenCompra="SI";}else{$OrdenCompra="NO";}
		if(!$ComprobanteContable){$ComprobanteContable="NULL";}else{$ComprobanteContable="'$ComprobanteContable'";}
                if($RVoBo=="on"){$RVoBo=1;}else{$RVoBo=0;}
		if(!$DesvioTotal){$DesvioTotal=0;}
                if($Presup=="on"){$Presup=1;}else{$Presup=0;$CompPresup="";}
		if(!$Editar)
		{
                    $cons = "Select * from Consumo.Comprobantes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante'";
                    $res = ExQuery($cons);
                    if(ExNumRows($res)>0){$MensajeInserta="El comprobante ya se encuentra configurado";}
                    $cons = "Insert into Consumo.Comprobantes
                    (Compania,AlmacenPpal,Comprobante,NumeroInicial,Tipo,Costo,Venta,IVA,Descto,ReteFte,ICA,
                     ComprobanteContable,CtaTipoVenta,ExigeOC,Formato,Mensaje1,Mensaje2,DesvioTotal,RequiereVoBo,presupuesto,comprobantepresup)
                    values
                    ('$Compania[0]','$AlmacenPpal','$Comprobante','$NumeroInicial','$Tipo','$Costo','$Venta','$IVA','$Descto','$ReteFte','$ICA',".$ComprobanteContable.",'$CtaTipoVenta',
                    '$OrdenCompra','$Formato','$Mensaje1','$Mensaje2',$DesvioTotal,$RVoBo,$Presup,'$CompPresup')";
		}
		else
		{
			$cons = "Update Consumo.Comprobantes set Comprobante='$Comprobante',NumeroInicial='$NumeroInicial',Tipo='$Tipo',Costo='$Costo',
			Venta='$Venta',IVA='$IVA',Descto='$Descto',ReteFte='$ReteFte',ICA='$ICA', ComprobanteContable=".$ComprobanteContable.", CtaTipoVenta='$CtaTipoVenta',
			ExigeOC='$OrdenCompra',Formato='$Formato',Mensaje1='$Mensaje1',Mensaje2='$Mensaje2',DesvioTotal=$DesvioTotal, RequiereVoBo=$RVoBo,
                        Presupuesto = $Presup, ComprobantePresup = '$CompPresup'
			where Comprobante='$Comprobantex' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";	
		}
		if(!$MensajeInserta){$res=ExQuery($cons);}
		?>
		<script language="javascript">
                    <?if($MensajeInserta)
                    {
                        ?>alert("<? echo $MensajeInserta;?>");<?
                    }?>
                    location.href="ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal;?>";
                </script>
		<?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		var b=0;
		if(document.FORMA.Comprobante.value==""){alert("Debe llenar el campo Nombre Comprobante");b=1;}
		else{if(document.FORMA.NumeroInicial.value==""){alert("Debe llenar el Numero Inicial Para el Comprobante");	b=1;}}
		if(document.FORMA.Presup.checked == true)
                {
                    if(document.FORMA.CompPresup.value == ""){alert("Debe seleccionar un comprobante presupuestal"); b = 1;}
                }
                if(b==1) return false;

	}
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='50px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	Ocultar()
</script>
<body background="/Imgs/Fondo.jpg">
<?
	if($Editar)
	{
		$cons1="Select Comprobante,NumeroInicial,Tipo,Costo,Venta,IVA,Descto,ReteFte,ICA,ComprobanteContable,CtaTipoVenta,
		ExigeOC,Formato,Mensaje1,Mensaje2,DesvioTotal,RequiereVoBo,Presupuesto,comprobantepresup
		from Consumo.Comprobantes where Comprobante='$Comprobante' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";	
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$comprobante=$fila1[0];
		$NumeroInicial=$fila1[1];
		$Tipo=$fila1[2];
		if($fila1[3]=="SI"){$ChecCosto=" checked ";}
		if($fila1[4]=="SI"){$ChecVenta=" checked ";}
		if($fila1[5]=="SI"){$ChecIVA=" checked ";}
		if($fila1[6]=="SI"){$ChecDto=" checked ";}
		if($fila1[7]=="SI"){$ChecRteFte=" checked ";}
		if($fila1[8]=="SI"){$ChecICA=" checked ";}
		$ComprobanteContable = $fila1[9];
		$CtaTipoVenta = $fila1[10];
		if($fila1[11]=="SI"){$ChecOrdendeCompra=" checked ";}
		$Formato=$fila1[12];
		$Mensaje1=$fila1[13];
		$Mensaje2=$fila1[14];
		$DesvioTotal=$fila1[15];
                if($fila1[16]==1){$ChkRVoBo = " checked ";}
                if($fila1[17]==1){$ChkPresup = " checked "; $CompPresup = $fila1[18];}
        }
?>
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="Tabla" value="<? echo $Tabla; ?>"  />
<input type="Hidden" name="Campo" value="<? echo $Campo; ?>"  />
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal; ?>"  />
<input type="Hidden" name="Comprobantex" value="<? echo $Comprobante; ?>"  />
<input type="Hidden" name="Editar" value="<? echo $Editar; ?>"  />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="6" align="center"><? echo $AlmacenPpal;?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Nombre Comprobante:</td><td colspan="5">
        <input type="text" onFocus="Ocultar()" name="Comprobante" value="<? echo $Comprobante;?>" maxlength="30" style="width:100%" 
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
    </tr>
    <tr>
       		<td bgcolor="#e5e5e5">Numero Inicial:</td>
            <td><input type="text" onFocus="Ocultar()" name="NumeroInicial" value="<? echo $NumeroInicial;?>" maxlength="6" size="6" 
            onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            <td bgcolor="#e5e5e5">Tipo de Comprobante:</td>
            <td colspan="2"><select onFocus="Ocultar()" name="Tipo" style="width:100%">
            <?
            	$cons = "Select Tipo from Consumo.TiposComprobante";
				$res = ExQuery($cons);
				echo ExError();
				while ($fila = ExFetch($res))
				{
					if($Tipo==$fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else { echo "<option value='$fila[0]'>$fila[0]</option>"; }
				}
			?>
            </select>
            </td>
            <td bgcolor="#e5e5e5">Requiere VoBo <input type="checkbox" name="RVoBo" <? echo $ChkRVoBo;?> onFocus="Ocultar()"></td>
	</tr>
    <tr bgcolor="#e5e5e5">
    	<td width="16.667%">Costo <input type="checkbox" onFocus="Ocultar()" name="Costo" <? echo " $ChecCosto"; ?> /></td>
        <td width="16.667%">Venta <input type="checkbox" onFocus="Ocultar()" name="Venta" <? echo " $ChecVenta"; ?> /></td>
        <td width="16.667%">IVA <input type="checkbox" onFocus="Ocultar()" name="IVA" <? echo " $ChecIVA"; ?> /></td>
        <td width="16.667%">Descuento <input type="checkbox" onFocus="Ocultar()" name="Descto" <? echo "$ChecDto"; ?> /></td>
        <td width="16.667%">ReteFuente <input type="checkbox" onFocus="Ocultar()" name="ReteFte" <? echo "$ChecRteFte"; ?> /></td>
        <td width="16.667%">ICA <input type="checkbox" onFocus="Ocultar()" name="ICA" <? echo "$ChecICA"; ?> /></td>
    </tr>
    <tr>
        <td bgcolor="#e5e5e5">Presupuesto <input type="checkbox" name="Presup" <? echo $ChkPresup ?> onFocus="Ocultar()" onclick="if(this.checked==true){CompPresup.disabled=false;}
                                                                                else{CompPresup.disabled=true;}"></td>
        <td bgcolor="#e5e5e5">Comprobante Presupuestal</td>
        <td><select name="CompPresup" <? if(!$ChkPresup){ echo " disabled ";}?> onFocus="Ocultar()"><option></option>
            <?
                $cons = "Select comprobante from Presupuesto.Comprobantes Where Compania='$Compania[0]'
                and TipoComprobant='Compromiso presupuestal'";
                $res = ExQuery($cons);
                while($fila = ExFetch($res))
                {
                    if($CompPresup==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }
            ?>
            </select></td>
            <td align="right" bgcolor="#e5e5e5" colspan="2">Comprobante Contable: </td>
            <td >
            <select onFocus="Ocultar()" name="ComprobanteContable" style="width:100%;"><option value=""></option>
            	<?
                    $cons = "Select Comprobante from Contabilidad.Comprobantes where Compania = '$Compania[0]'";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                            if($ComprobanteContable==$fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                            else { echo "<option value='$fila[0]'>$fila[0]</option>"; }
                    }
                ?>
            </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" align="right">Cuenta Tipo Venta:</td>
        <td><input type="text" 
        onFocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $ND[year]?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaTipoVenta'" 
		onkeyup="xNumero(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $ND[year]?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaTipoVenta';"
        name="CtaTipoVenta" value="<? echo $CtaTipoVenta;?>"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
        <td bgcolor="#e5e5e5" align="right">Ajustar Sobre:</td>
        <td><input type="text" name="DesvioTotal" value="<? echo $DesvioTotal?>" onFocus="Ocultar()"
        	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
        <td bgcolor="#e5e5e5" align="right">Formato:</td>
        <td>
            <select name="Formato">
        <?
                $RutaRoot=$_SERVER['DOCUMENT_ROOT'];
            $midir=opendir("$RutaRoot/Informes/Almacen/Formatos/");
                while($files=readdir($midir))
            {
                        $ext=substr($files,-3);
                        if (!is_dir($files) && ($ext=="php"))
                        $files="Formatos/".$files;
                        if($files!="." && $files!=".."){
                        if($files==$Formato){echo "<option selected value='$files'>$files</option>";}
                        else{echo "<option value='$files'>$files</option>";}}
              }
        ?>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" colspan="6" align="center">Requiere Orden de Compra/Solicitud de Consumo<input type="checkbox" onFocus="Ocultar()" <? echo "$ChecOrdendeCompra"?> name="OrdenCompra"></td>
        
        
    </tr>
    <tr>
    	<td colspan="6" bgcolor="#e5e5e5" align="center">MENSAJE 1</td>
    </tr>
    <tr>
    	<td colspan="6"><textarea name="Mensaje1" onFocus="Ocultar()" rows="2" style="width:100%" 
        			onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Mensaje1?></textarea>
    </tr>
    <tr>
    	<td colspan="6" bgcolor="#e5e5e5" align="center">MENSAJE 2</td>
    </tr>
    <tr>
    	<td colspan="6"><textarea name="Mensaje2" onFocus="Ocultar()" rows="2" style="width:100%" 
        			onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Mensaje2?></textarea>
    </tr>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>'" />
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>