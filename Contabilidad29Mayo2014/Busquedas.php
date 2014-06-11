<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>


<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>

<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="height:10px;" class="Tit1"><td><center>Asistente de B&uacute;squeda</center></td></tr>
<tr><td <? if($Reporteador){?> valign="top"<?}?>>
<?
	if(!$Tipo)
	{
		echo "<center><em>Haga clic sobre un par&aacute;metro de b&uacute;squeda</em>";
	}
	else
	{
		if($Tipo=="CambiaTerceroComp")
		{	
			$cons="Update Contabilidad.TmpMovimiento set Identificacion='$Cedula' where NUMREG='$NUMREG'";
			$res=ExQuery($cons);echo ExError();
			?>
            <script language="javascript">
				parent.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<? echo $Comprobante?>&NUMREG=<? echo $NUMREG?>';
			</script>
            <?
		}
		if($Tipo=="Identificacion"){
		$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros where Identificacion='$Identificacion' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
		echo "Criterio <strong>$Identificacion</strong><br>";
		echo "Registros Encontrados (" . ExNumRows($res) . ")";
		$fila=ExFetch($res);
		echo "<li>".strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")."</li>";
			echo "<br>";?>
		<a onclick="open('NuevoTercero.php?DatNameSID=<? echo $DatNameSID?>&Cerrar=1','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
		<script language="JavaScript">
		parent.document.FORMA.Tercero.value="<?echo strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")?>";
		parent.document.FORMA.Identificacion.value="<?echo $fila[4]?>";
		parent.document.FORMA.Detalle.focus();
		</script>
<?
		}
		
		if($Tipo=="Nombre")
		{
			$cons="Select * from Central.Terceros where Identificacion = '99999999999-0' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				$cons="Insert into Central.Terceros (PrimApe,SegApe,PrimNom,SegNom,Identificacion,Compania) values ('VARIOS','','','','99999999999-0','$Compania[0]')";
				$res=ExQuery($cons);
				echo ExError($res);
			}
			
			$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where (PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' ||SegNom) ilike '%$Nombre%' and Terceros.Compania='$Compania[0]' Order By PrimApe,SegApe,PrimNom,SegNom";
			$res=ExQuery($cons);echo ExError($res);
			echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				if(ExNumRows($res)==1){?><script language="javascript">location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Identificacion&Identificacion=<?echo $fila[0]?>'</script><?}
				echo "<li><a href='Busquedas.php?DatNameSID=$DatNameSID&Tipo=Identificacion&Identificacion=$fila[0]'>".strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]")."</a></li>";
			}
			echo "<br>";?>
			<a onclick="open('NuevoTercero.php?DatNameSID=<? echo $DatNameSID?>&Cerrar=1','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
<?		}
		if($Tipo=="PlanCuentas")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,CC,Descripcion,Tipo)
		{
			parent.frames.NuevoMovimiento.document.FORMA.Cuenta.value=CuentaConta;
			parent.frames.NuevoMovimiento.document.FORMA.Cuenta.focus();

			if(!CC){CC="0";}
			parent.frames.NuevoMovimiento.document.FORMA.Movimiento.value=Tipo;
			parent.frames.NuevoMovimiento.document.FORMA.ValeCC.value=CC;
			if(CC=="on"){parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=false;}
			else{parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=1;}
			parent.frames.TotMovimientos.document.FORMA.Descripcion.value=Descripcion;
			if(Naturaleza=="Debito"){parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="";}
			if(Naturaleza=="Credito"){parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="";}

		}
		function PonerMovimiento(Tipo,Naturaleza,CC,Descripcion)
		{
			if(!CC){CC="0";}
			parent.frames.NuevoMovimiento.document.FORMA.Movimiento.value=Tipo;
			parent.frames.NuevoMovimiento.document.FORMA.ValeCC.value=CC;
			if(CC=="on"){parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=false;}
			else{parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=1;}
			parent.frames.TotMovimientos.document.FORMA.Descripcion.value=Descripcion;
			ValDiferencia=parent.frames.TotMovimientos.document.FORMA.Diferencia.value;
			ValorDif="";
			for(x=0;x<=ValDiferencia.length;x=x+1)
			{
				if(ValDiferencia.substr(x,1)!=",")
				{

					ValorDif=ValorDif + ValDiferencia.substr(x,1);
				}
			}
			if(!ValorDif){$ValorDif=0;}
			if(Naturaleza=="Debito"){if(parent.frames.NuevoMovimiento.document.FORMA.EstadoMod.value==0 && ValorDif<0){parent.frames.NuevoMovimiento.document.FORMA.Haber.value=0;parent.frames.NuevoMovimiento.document.FORMA.Debe.value=Math.abs(ValorDif);}parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="";}
			if(Naturaleza=="Credito"){if(parent.frames.NuevoMovimiento.document.FORMA.EstadoMod.value==0 && ValorDif>0){parent.frames.NuevoMovimiento.document.FORMA.Debe.value=0;parent.frames.NuevoMovimiento.document.FORMA.Haber.value=Math.abs(ValorDif);}parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="";}
			
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if(!$Cuenta){exit;}
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza,CentroCostos from Contabilidad.PlanCuentas 
			where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[2]=="Titulo")
				{
					echo "<a style='font-size:10px;color:black'>$fila[0] - $fila[1]</a><br>";
				}
				else
				{
					?><a style='font-size:10px;' href='#' 
                	<? 
					if($NoMovimiento)
					{
						if($Frame){$F="frames.$Frame.";}
						if(!$ID && $ID != "0")
						{
							?>onclick="parent.<? echo $F?>document.FORMA.<? echo $ObjCuenta?>.value=<? echo $fila[0]?>"<? 
						}
						else
						{
							?>onclick="parent.<? echo $F?>document.getElementById('<? echo $ObjCuenta;?>' + '[' + '<? echo $ID;?>' + ']').value=<? echo $fila[0];?>"<? 
						}
						
					}
					else
					{
						?>onclick="PonerCuenta('<? echo $fila[0]?>','<? echo $fila[3]?>','<? echo $fila[4]?>','<? echo $fila[1]?>','<? echo $fila[2]?>')"<?
					}
					?>
                    >
					<? echo "$fila[0] - $fila[1]"?></a><br><? ;
				}
			}
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza,CentroCostos from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$Anio";
			$res=ExQuery($cons);
			$fila=ExFetch($res);$Movimiento=$fila[2];
			if(ExNumRows($res)==1){
			?>
			<a href='#' onclick="open('SaldosxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>','','width=400,height=300,scrollbars=yes')">Ver Saldos</a><br>
			<a href='#' onclick="open('MovimientoxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>','','width=800,height=300,scrollbars=yes')">Ver Movimientos</a><br><?
			if(!$NoMovimiento)
			{
				?><script language="JavaScript">PonerMovimiento('<?echo $Movimiento?>','<?echo $fila[3]?>','<?echo $fila[4]?>','<? echo $fila[1]?>')</script><?
			}
			if($fila[2]=="Titulo")
			{?>
				<a href='#' onclick="open('DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Nuevo=Nuevo&Cerrar=1','','width=400,height=300,scrollbars=yes')">Nueva Cuenta</a><br>
<?			}}
			else{?><script language="JavaScript">PonerMovimiento('0')</script><? }
			
		}

		if($Tipo=="CCG")
		{
	?>
			<script language="JavaScript">
			function PonerCentro(Codigo)
			{
				<? if($Frame)$F="$Frame.";?>
				parent.frames.<? echo $F?>document.FORMA.CC.value=Codigo;
				parent.frames.<? echo $F?>document.FORMA.CC.focus();
				//parent.frames.document.body.scrollTop = '0';
				
			}
			</script>
	<?
				echo "B&uacute;squeda por Centros de Costo<br>";
				echo "Criterio <strong>$Centro</strong><br>";
				$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto WHERE Compania='$Compania[0]' and Anio=$Anio and Codigo like '$Centro%' Order By Codigo";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[2]=="Detalle"){?><a style='font-size:11px;' href='#' onclick="PonerCentro('<? echo $fila[0]?>')"><? }
					echo ("$fila[0] - $fila[1]")?></a><br>
					<? 
				}
		}
		if($Tipo=="CC")
		{
?>
		<script language="JavaScript">
		function PonerCentro(Codigo)
		{
			parent.frames.NuevoMovimiento.document.FORMA.CC.value=Codigo;
			parent.frames.NuevoMovimiento.document.FORMA.CC.focus();
			
		}
		</script>
<?
			echo "B&uacute;squeda por Centros de Costo<br>";
			echo "Criterio <strong>$Centro</strong><br>";
				$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto WHERE Compania='$Compania[0]' and Anio=$Anio Order By Codigo";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[2]=="Detalle"){?><a style='font-size:11px;' href='#' onclick="PonerCentro('<? echo $fila[0]?>')"><? }
				echo ("$fila[0] - $fila[1]")?></a><br>
				<? 
			}
		}
		if($Tipo=="Departamentos")
		{
			$cons="Select Departamento from Central.Departamentos where Departamento ilike '$Departamento%'";

			$res=ExQuery($cons);echo ExError($res);
			echo "B&uacute;squeda por Departamentos<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Departamento.value='<?echo $fila[0]?>';parent.document.FORMA.ValDepto.value=1;parent.document.FORMA.Municipio.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		
		
		if($Tipo=="Actividad")
		{
			$cons="Select  codigo, descripcion from Contabilidad.ActividadesEconomicas where codigo ilike '$Actividad%'";

			$res=ExQuery($cons);echo ExError($res);
			echo "B&uacute;squeda por Actividad Economica<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Actividad.value='<?echo $fila[0];?>';parent.document.FORMA.ValActividad.value=1;"><?echo strtoupper("$fila[0] - $fila[1]")?></a></li>
<?			}
		}
		
		
		if($Tipo=="Municipio")
		{

			$cons="Select Codigo from Central.Departamentos where Departamento='$Departamento'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$CodDepto=$fila[0];

			$cons="Select Municipio from Central.Municipios where Departamento='$CodDepto' and Municipio ilike '$Municipio%'";


			$res=ExQuery($cons);echo ExError($res);
			echo "B&uacute;squeda por Municipio<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Municipio.value='<?echo $fila[0]?>';parent.document.FORMA.ValMpo.value=1;parent.document.FORMA.TipoTercero.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Direccion")
		{
			$cons="Select Convencion,Detalle from Contabilidad.ConvDirecciones";

			$res=ExQuery($cons);echo ExError($res);
			echo "Convencion de Direcciones<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Direccion.value=parent.document.FORMA.Direccion.value + '<?echo "$fila[0] "?>';parent.document.FORMA.Direccion.focus();"><?echo strtoupper($fila[0])."  (".$fila[1].")"?></a></li>
<?			}
		}

		if($Tipo=="TercerosxReportes")
		{?>
		<script language="JavaScript">
		function PonerTercero(Nombre,Objeto)
		{
			parent.document.getElementById(Objeto).value=Nombre;
			parent.document.getElementById(Objeto).focus();
		}
		</script>
<?
			$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros 
			where (PrimApe|| ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom) ilike '$Tercero%' and Terceros.Compania='$Compania[0]' Order By PrimApe,SegApe,PrimNom,SegNom";
			$res=ExQuery($cons);echo ExError($res);
			echo "Terceros existentes<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				?><li><a style='font-size:10px;' href='#' onclick="PonerTercero('<? echo "$fila[4]"?>','<? echo $Objeto?>')"><? echo "$fila[0] $fila[1] $fila[2] $fila[3]"?></a><br><?
			}
		}


		if($Tipo=="TercerosxTodos")
		{
			$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros 
			where PrimApe ilike '%$PrimApe%' and  SegApe ilike '%$SegApe%' and PrimNom ilike '%$PrimNom%' and SegNom ilike '%$SegNom%' and Terceros.Compania='$Compania[0]' Order By PrimApe,SegApe,PrimNom,SegNom";
			$res=ExQuery($cons);echo ExError($res);
			echo "Terceros existentes<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				echo "<li style='font-size:9px;'>$fila[0] $fila[1] $fila[2] $fila[3] - $fila[4]</li>";
			}
		}
		if($Tipo=="Bancos")
		{
			$cons="Select Nombre from Contabilidad.PlanCuentas where Banco=1 and Nombre ilike '$Banco%' and Compania='$Compania[0]' and Anio=$Anio";

			$res=ExQuery($cons);echo ExError($res);
			echo "Bancos<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Banco.value='<?echo "$fila[0]"?>';parent.document.FORMA.Banco.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Bancos1")
		{
			$cons="Select Nombre from Contabilidad.PlanCuentas where Banco=1 and Nombre ilike '$Banco%' and Compania='$Compania[0]' and Anio=$Anio";

			$res=ExQuery($cons);echo ExError($res);
			echo "Bancos<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.BancoRecRec.value='<?echo "$fila[0]"?>';parent.document.FORMA.BancoRecRec.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Cheque")
		{
			$cons="Select Cuenta from Contabilidad.PlanCuentas where Nombre='$Banco' and Compania='$Compania[0]' and Anio=$Anio";
			$res=ExQuery($cons);echo ExError($res);
			$fila=ExFetch($res);
			$CuentaBanco=$fila[0];
			
			$cons="Select NoCheque from Contabilidad.Movimiento where Cuenta='$CuentaBanco' AND NoCheque!=0 and Compania='$Compania[0]' Order By Numero Desc;";
			$res=ExQuery($cons);echo ExError($res);
			echo "<strong>Cheque n&uacute;mero</strong><br>";
			if(ExNumRows($res)>0)
			{
				$fila=ExFetch($res);
				$NoCheque=$fila[0]+1;
				echo $NoCheque;
			?>
				<script language="JavaScript">
					parent.document.FORMA.NumCheque.value=<?echo $NoCheque?>
				</script>
			<? }
			else{echo "<em>Banco aun no ha generado cheques</em>";}
		}

		if($Tipo=="PlanCuentasGr")
		{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza)
		{
			parent.document.FORMA.Cuenta.value=CuentaConta;
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<?echo $fila[0]?>,'<?echo $fila[3]?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}

		if($Tipo=="PlanCuentasDetalle")
		{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,Objeto)
		{
			parent.document.getElementById(Objeto).value=CuentaConta;
			parent.document.getElementById("Val"+Objeto).value=1;
			parent.document.getElementById(Objeto).focus();
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if($Cuenta){
				$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[2]=="Titulo")
					{
						 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
					}
					else
					{
						?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<? echo $fila[0]?>,'<? echo $fila[3]?>','<? echo $Objeto?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?
					}
				}
			}
		}
		if($Tipo=="PlanCuentasTodas")
		{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,Objeto)
		{
			parent.document.getElementById(Objeto).value=CuentaConta;
			parent.document.getElementById(Objeto).focus();
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if($Bancos)
			{
				$condAdc=" and Banco=1";$Cuenta=1;
			}
			if($Cuenta){
				$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio $condAdc Order By Cuenta";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
						?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<? echo $fila[0]?>,'<? echo $fila[3]?>','<? echo $Objeto?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?

				}
			}
		}

		if($Tipo=="CodigoExogena")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(CuentaE)
		{
			parent.document.FORMA.Codigo.value=CuentaE;
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre from Contabilidad.CodigosExogena where Cuenta ilike '$Codigo%' Order By Cuenta";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				?><a style='font-size:10px;' href='#' onclick="PonerCuenta('<?echo $fila[0]?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}

		if($Tipo=="CuentasCierre")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(Objeto,CuentaConta,Tipo)
		{
			if(Objeto=="Ingresos"){parent.document.FORMA.Ingresos.value=CuentaConta;parent.document.FORMA.TipoIngresos.value=Tipo;}
			if(Objeto=="Gastos"){parent.document.FORMA.Gastos.value=CuentaConta;parent.document.FORMA.TipoGastos.value=Tipo;}
			if(Objeto=="Utilidad"){parent.document.FORMA.Utilidad.value=CuentaConta;parent.document.FORMA.TipoUtilidad.value=Tipo;}
			if(Objeto=="Perdida"){parent.document.FORMA.Perdida.value=CuentaConta;parent.document.FORMA.TipoPerdida.value=Tipo;}
			if(Objeto=="Costos"){parent.document.FORMA.Costos.value=CuentaConta;parent.document.FORMA.TipoCostos.value=Tipo;}
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
			$res=ExQuery($cons);

			while($fila=ExFetch($res))
			{
				?><a style='font-size:10px;' href='#' onclick="PonerCuenta('<?echo $Objeto?>',<?echo $fila[0]?>,'<?echo $fila[2]?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}

		if($Tipo=="Comprobante")
		{
			$cons="Select Comprobante from Contabilidad.Comprobantes where Comprobante ilike '$Comprobante%' and Compania='$Compania[0]'";

			$res=ExQuery($cons);echo ExError($res);
			echo "Comprobantes<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				if(!$ObjComprobante)
                {
                	?><li><a href="#" onclick="parent.document.FORMA.Comprobante.value='<? echo "$fila[0]"?>';parent.document.FORMA.Banco.focus();">
                	<? echo strtoupper($fila[0])?></a></li><?
                }
				else
				{
					?><li><a href="#" onclick="parent.document.FORMA.<? echo $ObjComprobante?>.value='<? echo "$fila[0]"?>';">
                	<? echo strtoupper($fila[0])?></a></li><?
				}
			}
		}

		
		if($Tipo=="RevisaCierre")
		{
			$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio and Modulo='$Modulo'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{?>
				<script language="JavaScript">
					parent.document.FORMA.MesInvalido.value=1;
				</script>
			<?}
			else
			{?>
				<script language="JavaScript">
					parent.document.FORMA.MesInvalido.value=0;
				</script>
			<?}
			
		}
		
		if($Tipo=="HabilitarComprobantes")
		{
			$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio and Modulo='$Modulo'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=true;
				</script>
				
			<?}
			else
			{?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=false;
				</script>
<?			}
		}
		if($Tipo=="MaxDias")
		{
			$cons="Select NumDias from Central.Meses where Numero=$Mes";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			?>
			<script language="javascript">
            parent.document.FORMA.NoMaxDias.value="<? echo $fila[0]?>";
            </script>
<?		}

		if($Tipo=="PlanoHR")
		{?>
        
  		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,Objeto)
		{
			parent.document.getElementById(Objeto).value=CuentaConta;
			parent.document.getElementById(Objeto).focus();
		}
		</script>
      
<?        
			echo "Criterio <strong>$Parte</strong><br>";
			if($Parte){
				$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Parte%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[2]=="Titulo")
					{
						 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
					}
					else
					{
						?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<? echo $fila[0]?>,'<? echo $fila[3]?>','<? echo $Objeto?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?
					}
				}
			}
			else
			{
				$Opciones=array('Tercero','Centro de Costos','Doc Soporte','Detalle');
				foreach($Opciones as $MM)
				{?>
					<a style='font-size:10px;' href='#' onclick="PonerCuenta('<? echo $MM?>','<? echo $MM?>','<? echo $Objeto?>')"><? echo "$MM"?></a><br>
<?				}
			}
		}

	}
?>
</td></tr>
</table>