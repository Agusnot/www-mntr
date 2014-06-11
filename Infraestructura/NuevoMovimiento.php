<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("FuncionesUnload.php");
	@require_once ("xajax/xajax_core/xajax.inc.php");
	$obj = new xajax(); 
	$obj->registerFunction("Clear_Table");
	$obj->registerFunction("Modify_Table");
	$obj->processRequest(); 
	
	$ND = getdate();
	if($Tipo == "Orden Compra"){ $NumeroTipo = "NumeroOrdenCompra";}
	if($Tipo == "Compras")
    { 
        $NumeroTipo = "NumeroCompra";
        $cons = "Select compcontable from Infraestructura.Numeracion 
        Where Compania = '$Compania[0]' and Anio = $Anio and Tipo='Compras'";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $ComprobanteContable = $fila[0];
    }
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons = "Select $NumeroTipo from Infraestructura.CodElementos Where Compania='$Compania[0]' and $NumeroTipo IS NOT NULL
                        and SUBSTR(CAST($NumeroTipo as character varying),0,5) like '$Anio'
                        order by $NumeroTipo Desc";
			$res = ExQuery($cons);
			if(ExNumRows($res) == 0)
			{
				$cons1 = "Select NumInicial From Infraestructura.Numeracion Where Compania='$Compania[0]' and Anio=$Anio and Tipo = '$Tipo'";
				$res1 = ExQuery($cons1);
				if(ExNumRows($res1)>0)
				{
					$fila1 = ExFetch($res1);
					$Numero = $Anio.$fila1[0];	
				}
			}
			else
			{
				$fila = ExFetch($res);
				$Numero = $fila[0] + 1;
			}		
		}
		
		if($Tipo=="Orden Compra")
		{
			$cons = "Update Infraestructura.CodElementos set Cedula = '$Identificacion', Detalle = '$Detalle', FechaOrdenCompra = '$Anio-$Mes-$Dia',
			EstadoOrdenCompra = 'Solicitado', EstadoOrdenCompraX = '$usuario[0]', TMPCOD='', NumeroOrdenCompra = '$Numero' 
			Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Tipo='Orden Compra'";	
		}
		if($Tipo=="Compras")
		{
			$cons = "Update Infraestructura.CodElementos set TMPCOD = '', VrFactura=$TotFactura, NoFactura='$NoFactura'
			Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Tipo='Compras' and
			Codigo IS NULL";
			$res = ExQuery($cons);
			
			if(!$Edit)
			{
				$cons = "Select CentroCostos,Responsable,SubUbicacion From Infraestructura.Ubicaciones Where Compania='$Compania[0]' and XDefecto=1";
				$res = ExQuery($cons);
				if(ExNumRows($res)>0)
				{
					$fila = ExFetch($res);
					$cons1 = "Select AutoId From Infraestructura.CodElementos Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Tipo='$Tipo'";
					$res1 = ExQuery($cons1);
					
					while($fila1 = ExFetch($res1))
					{
						$cons2 = "Insert into Infraestructura.Ubicaciones(Compania,CentroCostos,Responsable,FechaIni,AutoId,UsuarioCrea,FechaCrea,SubUbicacion,Clase)
						values('$Compania[0]','$fila[0]','$fila[1]','$Anio-$Mes-$Dia',$fila1[0],'$usuario[0]',
						'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[2]','Devolutivos')";	
						$res2 = ExQuery($cons2);
					}
					
				}	
			}
			
			$cons = "Update Infraestructura.CostosAdicionales set Numero='$Numero',TMPCOD='' Where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
			$res = ExQuery($cons);
			$cons = "Select Sum(Valor) from Infraestructura.CostosAdicionales Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' group by TMPCOD";
			$res = ExQuery($cons);
			if($fila = ExFetch($res))
			{
				$cons1 = "Select Sum(CostosCompras) from Infraestructura.CodElementos Where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
				$res1 = ExQuery($cons1);
				$fila1 = ExFetch($res1); $TotalCompra = $fila1[0];
				
				$cons1 = "Select AutoId,CostoCompras from Infrsestructura.CodElementos Where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
				$res1 = ExQuery($cons1);
				while($fila1 = ExFetch($res1))
				{
					$cons2="Update Infraestructura.CodElementos set CostoAdicional=($TotalCompra/$fila1[1])*$fila[0] 
					Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and AutoId=$fila1[0]";
					$res2 = ExQuery($cons);	
				}	
			}
			
            if($ComprobanteContable!="" || $ComprobanteContable)
            {
                $NumeroComprobante = ConsecutivoComp($ComprobanteContable,$Anio,'Contabilidad');
                $consX = "Select SUM(CostoInicial+VrIva),Grupo 
                from Infraestructura.CodElementos 
                Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Tipo='$Tipo' group by Grupo";
                $resX = ExQuery($consX);
                while($filaX = ExFetch($resX))
                {
                    $consXX = "Select CtaGrupo,CtaProveedor from Infraestructura.GruposdeElementos
                    Where COmpania = '$Compania[0]' and Grupo = '$filaX[1]'";
                    $resXX = ExQuery($consXX);
                    $filaXX = ExFetch($resXX);
                    $consXXX = "Insert into Contabilidad.Movimiento
                    (Autoid,Fecha,Comprobante,Numero,Identificacion,Detalle,
                    Cuenta,Debe,Haber,cc,DocSoporte,Compania,UsuarioCre,FechaCre,
                    Estado,FechaDocumento,Anio) values
                    (1,'$Anio-$Mes-$Dia','$ComprobanteContable','$NumeroComprobante','$Identificacion','$Detalle',
                    '$filaXX[0]',$filaX[0],0,'000','$NoFactura','$Compania[0]','$usuario[0]',
                    '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$Anio-$Mes-$Dia',2011)";
                    //echo $consXXX."<br>";
                    $resXXX = ExQuery($consXXX);
                    $consXXX = "Insert into Contabilidad.Movimiento
                    (Autoid,Fecha,Comprobante,Numero,Identificacion,Detalle,
                    Cuenta,Debe,Haber,cc,DocSoporte,Compania,UsuarioCre,FechaCre,
                    Estado,FechaDocumento,Anio) values
                    (2,'$Anio-$Mes-$Dia','$ComprobanteContable','$NumeroComprobante','$Identificacion','$Detalle',
                    '$filaXX[1]',0,$filaX[0],'000','$NoFactura','$Compania[0]','$usuario[0]',
                    '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$Anio-$Mes-$Dia',2011)";
                    //echo $consXXX."<br>";
                    $resXXX = ExQuery($consXXX);
                }
            }
            //exit;
			$cons = "Update Infraestructura.CodElementos set Cedula = '$Identificacion', 
            Detalle = '$Detalle', FechaCompra = '$Anio-$Mes-$Dia', Tipo = 'Compras',
			EstadoCompras = 'Ingresado', NumeroCompra='$Numero', EstadoComprasX = '$usuario[0]', 
            TMPCOD='', VrFactura = $TotFactura, NoFactura = '$NoFactura',
			UVE = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',UUVE = '$usuario[0]',
            CompContable = '$ComprobanteContable', NoCompContable = '$NumeroComprobante'
			Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Tipo='$Tipo'";		
		}
		if($Numero)
		{
			$res = ExQuery($cons);
            ?><script language="javascript">
				<? if($Tipo=="Compras"){$Tipo="Compra";}?>
                                open("/Informes/Infraestructura/Formatos/<? echo str_replace(" ","",$Tipo);?>.php?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $Numero?>",'','width=800,height=600,scrollbars=yes');
				<? if($Tipo=="Compra"){$Tipo="Compras";}?>
                                location.href="Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Clase=<? echo $Clase?>&MesI=<? echo $Mes?>&AnioI=<? echo $Anio?>";
			</script><?		
		}
		else
		{
			$MostrarAlerta = 1;	
		}
	}
	if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if($Edit)
	{
		$cons = "Update Infraestructura.CodElementos set TMPCOD = '$TMPCOD' Where Compania = '$Compania[0]' and Tipo = '$Tipo' and $NumeroTipo = '$Numero'";
		$res = ExQuery($cons);
		$cons = "Update Infraestructura.CostosAdicionales set TMPCOD='$TMPCOD' Where Compania='$Compania[0]' and Numero='$Numero'";
		$res = ExQuery($cons);
		$cons = "Select NoFactura,VrFactura From Infraestructura.CodElementos Where Compania = '$Compania[0]' and NumeroCompra='$Numero' and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$TotFactura=$fila[1];
		$NoFactura=$fila[0];
	}
	if(!$Numero)
	{
		$cons = "Select $NumeroTipo from Infraestructura.CodElementos Where Compania='$Compania[0]' and $NumeroTipo IS NOT NULL
                and SUBSTR(CAST($NumeroTipo as character varying),0,5) like '$Anio'
		order by $NumeroTipo Desc";
		$res = ExQuery($cons);
                if(ExNumRows($res) == 0)
		{
			$cons1 = "Select NumInicial From Infraestructura.Numeracion Where Compania='$Compania[0]' and Anio=$Anio and Tipo = '$Tipo'";
			$res1 = ExQuery($cons1);
			if(ExNumRows($res1)>0)
			{
				$fila1 = ExFetch($res1);
				$Numero = $Anio.$fila1[0];
			}
		}
		else
		{
			$fila = ExFetch($res);
			$Numero = $fila[0] + 1;
		}	
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("../xajax");?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
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
	function Validar()
	{
		if(document.FORMA.Dia.value==""){alert("Ingrese la fecha de manera correcta");return false;}
		if(document.FORMA.Tercero.value==""){alert("Tercero no Ingresado");return false;}
		if(document.FORMA.Identificacion.value==""){alert("Identificacion no Ingresada");return false;}
		if(document.FORMA.Detalle.value==""){alert("Escriba un Detalle para el Movimiento");return false;}
		if(document.FORMA.Tipo.value=="Compras")
		{
			if(document.FORMA.TotFactura.value==""){alert("Ingrese el Valor Total de la Factura");return false;}
			if(document.FORMA.NoFactura.value==""){alert("Ingrese el Numero de la Factura");return false;}
			if(document.FORMA.TotFactura.value != "0")
			{
                            var VrIng = parseInt(document.FORMA.TotFactura.value)+parseInt(document.FORMA.CostAd.value);
                            var VrFac = parseInt(document.FORMA.Factura.value)+parseInt(document.FORMA.CostAd.value);
                            //alert(VrIng+" --- "+VrFac);
                            if( VrIng > VrFac + 1000)
                            {alert("El Valor de la Factura no Coincide");return false;}
                            else
                            {if(VrIng < VrFac-1000){alert("El Valor de la Factura no Coincide");return false;}}
			}
		}
	}
	function AbrirOrdenCompra()
	{
		frames.FrameOpener.location.href="OrdenesCompra.php?DatNameSID=<? echo $DatNameSID?>&Identificacion="+document.FORMA.Identificacion.value+"&Tipo=<? echo $Tipo?>&TMPCOD=<? echo $TMPCOD?>&Clase=<? echo $Clase?>&Anio="+document.FORMA.Anio.value+"&Fecha="+document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}
	function AbrirCostosAdicionales(TMPCOD)
	{
		frames.FrameOpener.location.href="CostosAdicionales.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD="+TMPCOD;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}
</script>
</head>
<?  $CamposOC="Codigo,FechaAdquisicion,Serie,Estado,Cedula,Detalle,FechaOrdenCompra,EstadoOrdenCompra";
	$CamposC="FechaCompra,EstadoCompras,NumeroCompra,VrFactura,NoFactura,EstadoComprasx,CostoAdicional";
	$CamposM="Codigo,FechaAdquisicion,Serie,Estado,DepDesde,CostoCompras";
	$Valores="NULL,NULL,NULL,NULL,NULL,NULL";
?>
<body background="/Imgs/Fondo.jpg" 
onUnload="if(document.FORMA.NoEliminar.value == '')
{<? if($Tipo=="Orden Compra"){?>xajax_Clear_Table('Infraestructura.CodElementos','<? echo $TMPCOD?>','<? echo $CamposOC?>');<? }
    if($Tipo=="Compras"){?>
    	xajax_Clear_Table('Infraestructura.CostosAdicionales','<? echo $TMPCOD?>','Numero');
        xajax_Modify_Table('Infraestructura.CodElementos','<? echo $TMPCOD;?>','<? echo $CamposC;?>','<? echo $CamposM;?>','<? echo $Valores;?>');<? }?>}">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Clase" value="<? echo $Clase?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="Clase" value="<? echo $Clase;?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>" />
<input type="hidden" name="NumeroTipo" value="<? echo $NumeroTipo?>" />
<input type="hidden" name="NoEliminar" />
<input type="hidden" name="Factura" />
<table border="0">
<tr><td>
<table border="1" width="750" bordercolor="<? echo $Estilo[1]?>" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr style="color:<? echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<? echo $Estilo[1]?>">
    	<td colspan="4">Nuevo Movimiento</td>
    </tr>
	<tr>
    	<td>Fecha</td>
		<td><input type="Text" name="Anio" style="width:40px;" onFocus="Ocultar()" readonly="yes" value="<? echo $Anio?>">
		<?
			$cons="Select * from Central.UsuariosxModulos where Usuario='$usuario[1]' and Modulo='Administrador'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{
			?>
				<select name="Mes" style="width:40px" onFocus="Ocultar()">
			<?
				for($i=1;$i<=12;$i++)
				{
					if($i==$Mes){echo "<option selected value='$i'>$i</option>";}
					else{echo "<option value='$i'>$i</option>";}
				}
			?>
				</select>
			<?
			}
            else
            {
			?>
				<input type="Text" name="Mes" readonly="yes" style="width:20px" maxlength="2" onFocus="Ocultar()" value="<? echo $Mes?>">
			<?
			}
		if(!$Dia){$Dia=$ND[mday];}
		if($Dia<10 && !$Edit){$Dia="0".$Dia;}
		if(!$FechaDocumento){$FechaDocumento="$Anio-$Mes-$Dia";}
		?>
		<input type="Text" name="Dia" maxlength="2" onFocus="Ocultar()" style="width:20px;" value="<?echo $Dia?>">
		</td>
		<td>Numero</td>
		<td><input type="Text" name="Numero" onFocus="Ocultar()" readonly
        	style="width:170px;font-size:16px;color:blue;border:0px;font-weight:bold" value="<? echo $Numero?>"></td>
	<tr>
	<td>Tercero</td>
		<td><input type="Text" name="Tercero" value="<? echo $Tercero;?>" style="width:250px;" 
        		onKeyUp="xLetra(this);Mostrar();Identificacion.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value"
                onKeyDown="xLetra(this)"/>
               </td>
		<td>Cedula</td>
		<td><input type="Text" value="<? echo $Identificacion?>" style="width:230px;" name="Identificacion" 
        onchange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Identificacion&Identificacion='+this.value"
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
	 
	<tr>
		<td>Detalle</td>
		<td colspan="3"><input type="Text" value="<? echo $Detalle?>" name="Detalle" style="width:100%;" 
        onfocus="Ocultar();"
		onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
    </tr>
    <?
    if($Tipo == "Compras")
	{?><tr><td colspan="4" align="center"><input type="button" value="Orden de Compra" onClick="AbrirOrdenCompra()" style="width:110px" />
    <input type="button" name="ValorAd" value="Costos Adicionales" onClick="AbrirCostosAdicionales('<? echo $TMPCOD?>')" style="width:120px" />
    <input type="text" readonly name="CostAd" style="text-align:right" size="6" />
    <strong><font color="#0000FF">Valor Factura : </font></strong><input type="text" name="TotFactura" style="width:100px;" value="<? echo $TotFactura?>" 
																onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
	<strong><font color="#0000FF">No Factura : </font></strong><input type="text" name="NoFactura" style="width:100px;" value="<? echo $NoFactura?>"
															onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" />
    </td></tr>
	<? }?>
</table>
</td></tr>
<tr><td>
<iframe id="NuevoMovimiento" height="350" frameborder="0" width="100%" scrolling="auto"
src="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Clase=<? echo $Clase?>&TMPCOD=<? echo $TMPCOD ?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>&Editar=<? echo $Editar?>">
</iframe><br>
</td></tr>
<tr><td><center>
	<input type="submit" name="Guardar" value="Guardar Registro" onClick="NoEliminar.value='1'" />
    <input type="button" name="Cancelar" value="Cancelar" onClick="location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Clase=<? echo $Clase?>'" />
</center></td></tr>
</table>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</form>
</body>
<?
	if($MostrarAlerta)
	{
	?>
	<script language="javascript">alert("No esta configurada la Numeracion");</script>
	<?	
	}
?>
</html>