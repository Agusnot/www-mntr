<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	include("GeneraValoresEjecucion.php");
	$TipoVig=$ClaseVigencia;
	$ND=getdate();
?>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>

<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="height:10px;" class="Tit1"><td><center>Asistente de B&uacute;squeda</td></tr>
<tr><td>
<?
	if(!$Tipo)
	{
		echo "<center><em>Haga clic sobre un par&aacute;metro de b&uacute;squeda</em>";
	}
	else
	{
		if($Tipo=="CodCGR")
		{
			$cons9="Select clase from central.compania where Nombre='$Compania[0]'";
			$res9=ExQuery($cons9);
			$fila9=ExFetch($res9);
			$Clase=$fila9[0];
			?>
				<script language="javascript">
					function PonerCodCGR(CodCGR,NombreCGR)
					{
						parent.frames.document.FORMA.CodCGR.value = CodCGR;
						parent.frames.document.FORMA.NombreCGR.value = NombreCGR;
						parent.frames.Ocultar();
						parent.frames.document.FORMA.RecursoCGR.focus();												
						//parent.frames.document.FORMA.submit();						
					}
				</script>
			<?
			echo "Busqueda Por Codigo CGR";
			echo "<br>Criterio <b>$CodCGR</b><br>";
			$cons = "Select Codigo,Descripcion from Presupuesto.CodigosCGR where Tipo='$TipoCGR' and Codigo like'$CodCGR%' and Clase='$Clase' order by Codigo";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?> <a href="#" onclick="PonerCodCGR('<? echo $fila[0]?>','<? echo $fila[1]?>')"> <?
				echo "$fila[0] - $fila[1]<br></a>";
			}
		}
		if($Tipo=="Generico1")
		{
			?>
				<script language="javascript">
					function PonerValor(Valor, Valor1)
					{
						parent.frames.document.FORMA.<? echo $Objeto?>.value = Valor;
						parent.frames.document.FORMA.<? echo $Objeto1?>.value = Valor1;//ver si se utiliza en otras busquedas la FORMA
						parent.frames.Ocultar();
						parent.frames.document.FORMA.<? echo $SigObjeto?>.focus();
						
					}
				</script>
			<?
			echo "Busqueda por $TipoG";
			echo "<br>Criterio <b>$Valor</b><br>";
			$cons = "Select ".$Valor1.",".$Valor2." from ".$Tabla." where ".$Valor1." like '".$Valor."%' order by $Valor1";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?> <a href="#" onclick="PonerValor('<? echo $fila[0]?>','<? echo $fila[1]?>')"> <?
				echo "$fila[0] - $fila[1]<br></a>";
			}
		}
		
		if($Tipo=="Identificacion"){
		$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros where Identificacion='$Identificacion' and Terceros.Compania='$Compania[0]'";
		$res=ExQuery($cons);
		echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
		echo "Criterio <strong>$Identificacion</strong><br>";
		echo "Registros Encontrados (" . ExNumRows($res) . ")";
		$fila=ExFetch($res);

		$cons2="Update Presupuesto.TmpMovimiento set Identificacion='$fila[4]' where NumReg='$NumReg'";
		$res2=ExQuery($cons2);
		echo "<li>".strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")."</li>";
			echo "<br>";?>
		<script language="JavaScript">
		parent.frames.NuevoMovimiento.location.href=parent.frames.NuevoMovimiento.location.href + '&NoInsert=1';
		parent.document.FORMA.Tercero.value="<?echo strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")?>";
		parent.document.FORMA.Identificacion.value="<?echo $fila[4]?>";
		parent.document.FORMA.Detalle.focus();
		</script>
<?
		}
		
		if($Tipo=="Nombre")
		{
			$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom ilike '%$Nombre%' and Terceros.Compania='$Compania[0]' Order By PrimApe,SegApe,PrimNom,SegNom";
			$res=ExQuery($cons);
			echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				if(ExNumRows($res)==1){?><script language="javascript">location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Identificacion&Identificacion=<?echo $fila[0]?>&NumReg=<?echo $NumReg?>'</script><?}
				echo "<li><a href='Busquedas.php?DatNameSID=$DatNameSID&Tipo=Identificacion&Identificacion=$fila[0]&NumReg=$NumReg'>".strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]")."</a></li>";
			}
			echo "<br>";?>
			<a onclick="open('/Contabilidad/NuevoTercero.php?DatNameSID=<? echo $DatNameSID?>&Cerrar=1&NUMREG=<?echo $NumReg?>','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
<?		}
		if($Tipo=="PlanCuentas")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,Descripcion,Tipo,SaldoCta)
		{
			if(!Descripcion){Descripcion="No Seleccionada";}
			if(!SaldoCta){SaldoCta=0;}
			parent.frames.NuevoMovimiento.document.FORMA.Cuenta.value=CuentaConta;
			parent.frames.NuevoMovimiento.document.FORMA.Cuenta.focus();

			parent.frames.NuevoMovimiento.document.FORMA.Movimiento.value=Tipo;
			parent.frames.TotMovimientos.document.FORMA.Descripcion.value=Descripcion;
			parent.frames.TotMovimientos.document.FORMA.Saldo.value=SaldoCta;

		}
		function PonerMovimiento(Tipo,Naturaleza,Descripcion,SaldoCta)
		{
			if(!Descripcion){Descripcion="No Seleccionada";}
			if(!SaldoCta){SaldoCta=0;}
			parent.frames.NuevoMovimiento.document.FORMA.Movimiento.value=Tipo;
			parent.frames.TotMovimientos.document.FORMA.Descripcion.value=Descripcion;			
			parent.frames.TotMovimientos.document.FORMA.Saldo.value=SaldoCta;
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			;
			$MesIni=1;$MesFin=$Mes;
			$ApropIni=GeneraApropiacion();

			$Adiciones=GeneraValor("Adicion","Ambos",1);
			$Reducciones=GeneraValor("Reduccion","Ambos",1);
			$Creditos=GeneraValor("Traslado","Credito",1);
			$CCreditos=GeneraValor("Traslado","ContraCredito",1);
			$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;

			$DisponibilidadesAnt=GeneraValor("Disponibilidad","Credito",2);
			$DisminucDispoAnt=GeneraValor("Disminucion a disponibilidad","ContraCredito",2);
			$TotDispoAnt=$DisponibilidadesAnt-$DisminucDispoAnt;

			$DispoPeriodo=GeneraValor("Disponibilidad","Credito",3);
			$DismPeriodo=GeneraValor("Disminucion a disponibilidad","ContraCredito",3);
			$TotDispoPeriodo=$DispoPeriodo-$DismPeriodo;

			$TotDisponibilidades=$TotDispoAnt+$TotDispoPeriodo;
			$SaldoDisponible=$ApropDef-$TotDisponibilidades;

			if($TipoCom=="Disponibilidad" || $TipoCom=="Compromiso presupuestal" || $TipoCom=="Obligacion presupuestal" || $TipoCom=="Egreso presupuestal" || $TipoCom=="Traslado"){$CondAdc9=" and Cuenta NOT ilike '1%'";}

			if(!$Vigencia){$Vigencia="Actual";}
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Presupuesto.PlanCuentas where Cuenta ilike '$Cuenta%'
			and Vigencia='$Vigencia' and ClaseVigencia='".$ClaseVigencia."'
			 $CondAdc9 and Compania='$Compania[0]' and Anio=$Anio 
			 Order By Cuenta";

			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[2]=="Titulo"){echo "<a style='font-size:10px;color:black'>$fila[0] - $fila[1]</a><br>";}
				else{?><a style='font-size:10px;' href='#' onclick="PonerCuenta('<?echo $fila[0]?>','<?echo $fila[3]?>','<?echo $fila[1]?>','<?echo $fila[2]?>','<?echo $fila[2]?>','<?echo $SaldoDisponible?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?;}
			}

			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Presupuesto.PlanCuentas where Cuenta='$Cuenta' 
			and Compania='$Compania[0]' and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);$Movimiento=$fila[2];
			if(ExNumRows($res)==1){
			?>
			<a href='#' onclick="open('SaldosxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>','','width=400,height=300,scrollbars=yes')">Ver Saldos</a><br>
			<a href='#' onclick="open('MovimientoxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>','','width=800,height=300,scrollbars=yes')">Ver Movimientos</a><br><?
			?><script language="JavaScript">PonerMovimiento('<?echo $Movimiento?>','<?echo $fila[3]?>','<?echo $fila[1]?>','<?echo $SaldoDisponible?>')</script><?
			if($fila[2]=="Titulo")
			{?>
				<a href='#' onclick="open('DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Nuevo=Nuevo&Cerrar=1','','width=400,height=300,scrollbars=yes')">Nueva Cuenta</a><br>
<?			}}
			else{?><script language="JavaScript">PonerMovimiento('0')</script><?}
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
			$cons="Select Codigo,CentroCostos from Presupuesto.CentrosCosto WHERE Compania='$Compania[0]' Order By Codigo";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{?>
				<a style='font-size:11px;' href='#' onclick="PonerCentro('<?echo $fila[0]?>')"><?echo strtoupper("$fila[0] - $fila[1]")?></a><br>
<?			}
		}
		if($Tipo=="Departamentos")
		{
			$cons="Select Departamento from Presupuesto.Departamentos where Departamento ilike '$Departamento%'";

			$res=ExQuery($cons);
			echo "B&uacute;squeda por Departamentos<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Departamento.value='<?echo $fila[0]?>';parent.document.FORMA.ValDepto.value=1;parent.document.FORMA.Municipio.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Municipio")
		{

			$cons="Select Codigo from Presupuesto.Departamentos where Departamento='$Departamento'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$CodDepto=$fila[0];

			$cons="Select Municipio from Presupuesto.Municipios where Departamento='$CodDepto' and Municipio ilike '$Municipio%'";


			$res=ExQuery($cons);
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
			$cons="Select Convencion,Detalle from Presupuesto.ConvDirecciones";

			$res=ExQuery($cons);
			echo "Convencion de Direcciones<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Direccion.value=parent.document.FORMA.Direccion.value + '<?echo "$fila[0] "?>';parent.document.FORMA.Direccion.focus();"><?echo strtoupper($fila[0])."  (".$fila[1].")"?></a></li>
<?			}
		}
		if($Tipo=="TercerosxTodos")
		{
			$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros 
			where PrimApe ilike '%$PrimApe%' and  SegApe ilike '%$SegApe%' and PrimNom ilike '%$PrimNom%' and SegNom ilike '%$SegNom%' and Terceros.Compania='$Compania[0]' Order By PrimApe,SegApe,PrimNom,SegNom";
			$res=ExQuery($cons);
			echo "Terceros existentes<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				echo "<li style='font-size:9px;'>$fila[0] $fila[1] $fila[2] $fila[3] - $fila[4]</li>";
			}
		}
		if($Tipo=="Bancos")
		{
			$cons="Select Nombre from Presupuesto.PlanCuentas where Banco=1 and Nombre ilike '$Banco%' and Compania='$Compania[0]'";

			$res=ExQuery($cons);
			echo "Bancos<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Banco.value='<?echo "$fila[0]"?>';parent.document.FORMA.Banco.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Cheque")
		{
			$cons="Select Cuenta from Presupuesto.PlanCuentas where Nombre='$Banco' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$CuentaBanco=$fila[0];
			
			$cons="Select NoCheque from Presupuesto.Movimiento where Cuenta=$CuentaBanco AND NoCheque!=0 and Compania='$Compania[0]' Order By Numero Desc;";
			$res=ExQuery($cons);
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
			<?}
			else{echo "<em>Banco aun no ha generado cheques</em>";}
		}

		if($Tipo=="PlanCuentasGr")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza)
		{
			parent.document.FORMA.Cuenta.value=CuentaConta;
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Presupuesto.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' Order By Cuenta";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<?echo $fila[0]?>,'<?echo $fila[3]?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}
		if($Tipo=="Comprobante")
		{
			$cons="Select Comprobante from Presupuesto.Comprobantes where Comprobante ilike '$Comprobante%' and Compania='$Compania[0]'";

			$res=ExQuery($cons);
			echo "Comprobantes<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Comprobante.value='<?echo "$fila[0]"?>';parent.document.FORMA.Banco.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
	}
?>
</td></tr>
</table>