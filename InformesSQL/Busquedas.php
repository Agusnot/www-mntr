<?
	session_start();
	include("Funciones.php");
?>

<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>

<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="height:10px;" class="Tit1"><td><center>Asistente de Búsqueda</td></tr>
<tr><td>
<?
	if(!$Tipo)
	{
		echo "<center><em>Haga clic sobre un parámetro de búsqueda</em>";
	}
	else
	{
		if($Tipo=="Cedula"){
		$cons="Select PrimApe,SegApe,PrimNom,SegNom,Cedula from Central.Terceros where Cedula='$Cedula' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		echo "Búsqueda por identificación de tercero<br>";
		echo "Criterio <strong>$Cedula</strong><br>";
		echo "Registros Encontrados (" . ExNumRows($res) . ")";
		$fila=ExFetch($res);
		echo "<li>".strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")."</li>";
			echo "<br>";?>
		<a onclick="open('NuevoTercero.php?Cerrar=1','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
		<script language="JavaScript">
		parent.document.FORMA.Tercero.value="<?echo strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")?>";
		parent.document.FORMA.Cedula.value="<?echo $fila[4]?>";
		parent.document.FORMA.Detalle.focus();
		</script>
<?
		}
		
		if($Tipo=="Modulo")
		{
			?>
			<script language="javascript">
            	function PonerModulo(Mod)
				{
					parent.frames.document.FORMA.Modulo.value = Mod;
					parent.frames.document.FORMA.EModulo.value = 1;
					parent.frames.document.FORMA.NombreInforme.focus();
					//parent.frames.Productos.document.FORMA.Cantidad.focus();
				}
            </script>
			<?
			echo "Búsqueda por Modulos<br>";
			echo "Criterio <strong>$Modulo</strong><br>";
			$cons = "Select Perfil,UsuariosxModulos.Madre from Central.AccesoxModulos,Central.UsuariosxModulos 
			where Perfil = Modulo and Nivel > 0 and Ruta <> '' and Usuario = '$usuario[1]' and Modulo LIKE '$Modulo%'";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();	
			while($fila=ExFetch($res))
			{
			?><a href="#" onclick="PonerModulo('<? echo "$fila[0] - $fila[1]"?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?	
			}
		}
		if($Tipo=="Unidad")
		{
			?>
            <script language="javascript">
			function PonerUnidadMedida(Objeto,UM,Tipo)
				{
					parent.document.Forma.EUniMed.value=1;
					parent.document.getElementById("<? echo $Objeto?>").value=UM;
					parent.document.getElementById("<? echo $Objeto?>").focus();
					parent.document.Forma.Presentacion.focus();
				}
			</script>
		<?  
			echo "Búsqueda por Unidad de Medida<br>";
			echo "Criterio <strong>$UnidadMedida</strong><br>";
			$cons="Select Unidad from Consumo.UnidadMedida WHERE 
			Unidad like '$UnidadMedida%'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
			?><a href="#" onclick="PonerUnidadMedida('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
			}
			?><input type="button" name="Crear" value="Nueva Unidad" onclick="frames.parent.NuevoElemento('../NuevoRegistro.php?Tabla=Consumo.UnidadMedida&VienedeOtro=1&Objeto=<? echo $Objeto?>')" /><?		
		}
		if($Tipo=="Presentacion")
		{
			?>
            <script language="javascript">
			function PonerPresentacion(Objeto,Pres,Tipo)
				{
					parent.document.Forma.EPresentacion.value=1;
					parent.document.getElementById("<? echo $Objeto?>").value=Pres;
					parent.document.Forma.TipoPro.focus();
				}
			</script>
		<?  
			echo "Búsqueda por Presentacion<br>";
			echo "Criterio <strong>$Presentacion</strong><br>";
			$cons="Select Presentacion from Consumo.PresentacionProductos WHERE 
			Presentacion like '$Presentacion%'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
			?><a href="#" onclick="PonerPresentacion('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
			}
			?><input type="button" name="Crear" value="Nueva Presentacion" 
            onclick="frames.parent.NuevoElemento('../NuevoRegistro.php?Tabla=Consumo.PresentacionProductos&VienedeOtro=1&Objeto=<? echo $Objeto?>')" /><?		
		}
		if($Tipo=="TipoProducto")
		{
			?>
            <script language="javascript">
			function PonerTipoProducto(Objeto,TP,Tipo)
				{
					parent.document.Forma.ETipoPro.value=1;
					parent.document.getElementById("<? echo $Objeto?>").value=TP;
					parent.document.Forma.Grupo.focus();
				}
			</script>
		<?  
			echo "Búsqueda por Tipo de Producto<br>";
			echo "Criterio <strong>$TipoProducto</strong><br>";
			$cons="Select TipoProducto from Consumo.TiposProducto WHERE 
			TipoProducto like '$TipoProducto%' and AlmacenPpal = '$AlmacenPpal' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
			?><a href="#" onclick="PonerTipoProducto('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
			}
			?><input type="button" name="Crear" value="Nuevo Tipo de Producto" onclick="frames.parent.NuevoElemento('NewConfigConsumo.php?VienedeOtro=1&Objeto=<? echo $Objeto?>&Tabla=TiposProducto&Campo=TipoProducto&AlmacenPpal=<? echo $AlmacenPpal?>')" /><?		
		}
		if($Tipo=="Bodega")
		{
			?>
            <script language="javascript">
			function PonerBodega(Objeto,Bod,Tipo)
				{
					parent.document.Forma.EBodega.value=1;
					parent.document.getElementById("<? echo $Objeto?>").value=Bod;
					parent.document.Forma.Estante.focus();
				}
			</script>
		<?  
			echo "Búsqueda por Bodegas<br>";
			echo "Criterio <strong>$Bodega</strong><br>";
			$cons="Select Bodega from Consumo.Bodegas WHERE 
			Bodega like '$Bodega%' and AlmacenPpal = '$AlmacenPpal' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
			?><a href="#" onclick="PonerBodega('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
			}
			?><input type="button" name="Crear" value="Nueva Bodega" onclick="frames.parent.NuevoElemento('NewConfigConsumo.php?VienedeOtro=1&Objeto=<? echo $Objeto?>&Tabla=Bodegas&Campo=Bodega&AlmacenPpal=<? echo $AlmacenPpal?>')" /><?		
		}
		if($Tipo=="NombreProducto")
		{
			?>
			<script language="javascript">
            	function PonerProducto(Auto,Cod,Pro)
				{
					parent.frames.Productos.document.FORMA.AutoId.value = Auto;
					parent.frames.Productos.document.FORMA.Codigo.value = Cod;
					parent.frames.Productos.document.FORMA.Nombre.value = Pro;
					parent.frames.Productos.document.FORMA.Cantidad.focus();
				}
            </script>
			<?
			echo "Búsqueda por Nombre de Producto<br>";
			echo "Criterio <strong>$NomProducto</strong><br>";
			$cons="Select  AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion  from Consumo.CodProductos WHERE 
			Compania='$Compania[0]' and concat(NombreProd1,' ',UnidadMedida,' ',Presentacion) LIKE '$NomProducto%' and AlmacenPpal='$AlmacenPpal'";
			$res=ExQuery($cons);echo ExError();	
			while($fila=ExFetch($res))
			{
			?><a href="#" onclick="PonerProducto('<? echo $fila[0]?>','<? echo $fila[1]?>','<? echo "$fila[2] $fila[3] $fila[4]" ?>')"><? echo "$fila[2] $fila[3] $fila[4]"?></a><br><?	
			}
		}
		if($Tipo=="CodProducto")
		{
			echo "Búsqueda por Codigo de Producto<br>";
			echo "Criterio <strong>$Codigo</strong><br>";
			$cons="Select Codigo1,NombreProd1,Presentacion,UnidadMedida,AutoId,VrIva,Min,Max,Grupo from Consumo.CodProductos WHERE 
			Compania='$Compania[0]' and Codigo1 = '$Codigo' and AlmacenPpal='$AlmacenPpal' Order By Codigo1";
			$res=ExQuery($cons);echo ExError();
			if(ExNumRows($res)==1)
			{
				$fila=ExFetch($res);

				$cons20="Select ReteFte,ReteICA from Consumo.Grupos where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and Grupo='$fila[8]'";
				$res20=ExQuery($cons20);
				$fila20=ExFetch($res20);

				
				$Minimo=$fila[6];$Maximo=$fila[7];

				$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$Fecha);
				$SumVrExistencias=$VrSaldoIni[$fila[4]][1];
				$SumCantExistencias=$VrSaldoIni[$fila[4]][0];
				$PromedioPond=$SumVrExistencias/$SumCantExistencias;
				
				$cons2="Select ValorVenta from Consumo.TarifasxProducto where AutoId=$fila[4] and AlmacenPpal='$AlmacenPpal' 
				and Compania='$Compania[0]' and Tarifario='$Tarifario' and FechaIni<='$Fecha' and FechaFin>='$Fecha'";


				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				?>
				<script language="javascript">
					parent.frames.NuevoMovimiento.document.FORMA.AutoId.value='<? echo $fila[4]?>';
					parent.frames.NuevoMovimiento.document.FORMA.Codigo.value='<? echo $fila[0]?>';
					parent.frames.NuevoMovimiento.document.FORMA.Nombre.value='<? echo "$fila[1] $fila[2] $fila[3]"?>';
					if(parent.document.FORMA.Tipo.value=="Entradas" || parent.document.FORMA.Tipo.value=="Orden de Compra" || parent.document.FORMA.Tipo.value=="Remisiones")
					{
						if(parent.frames.NuevoMovimiento.document.FORMA.Editar.value!=1){
						parent.frames.NuevoMovimiento.document.FORMA.VrCosto.value='0';
						parent.frames.NuevoMovimiento.document.FORMA.VrVenta.value='0';}
					}
					else
					{
						parent.frames.NuevoMovimiento.document.FORMA.VrCosto.value='<? echo $PromedioPond?>';
						parent.frames.NuevoMovimiento.document.FORMA.VrVenta.value='<?echo $fila2[0]?>';
					}
					parent.frames.NuevoMovimiento.document.FORMA.PorcIVA.value='<? echo $fila[5]?>';
					parent.frames.NuevoMovimiento.document.FORMA.PorcICA.value='<? echo $fila20[1]?>';
					parent.frames.NuevoMovimiento.document.FORMA.PorcReteFte.value='<? echo $fila20[0]?>';

					parent.frames.NuevoMovimiento.document.FORMA.Existencias.value='<?echo $SumCantExistencias?>';
					parent.frames.NuevoMovimiento.document.FORMA.Minimo.value='<?echo $Minimo?>';
					parent.frames.NuevoMovimiento.document.FORMA.Maximo.value='<?echo $Maximo?>';
					
					parent.frames.TotMovimientos.document.FORMA.Existencias.value='<?echo $SumCantExistencias?>';
					parent.frames.TotMovimientos.document.FORMA.Min.value='<?echo $Minimo?>';
					parent.frames.TotMovimientos.document.FORMA.Max.value='<?echo $Maximo?>';
					parent.frames.NuevoMovimiento.document.FORMA.Cantidad.focus();
				</script>				
			<?
				echo "$fila[0] $fila[1] $fila[2] $fila[3]<br>";
			}
			else
			{
				?>
				<script language="javascript">
					parent.frames.NuevoMovimiento.document.FORMA.Nombre.value='';
				</script>
				<?
			}				
		}

		if($Tipo=="RevisaCierre")
		{
			$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio";
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
			<? }
			
		}
		
		if($Tipo=="HabilitarComprobantes")
		{
			$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=true;
				</script>
				
			<?}
			else
			{ ?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=false;
				</script>
<?			}
		}
		if($Tipo=="PlanCuentas")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(Objeto,CuentaConta,Tipo)
		{
			parent.document.getElementById("<? echo $Objeto?>").value=CuentaConta;
			parent.document.getElementById("<? echo $Objeto?>").focus();
		}
		</script>
<?
			$ND=getdate();
			$Anio=$ND[year];
			echo "Búsqueda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta like '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res))
			{
				echo "<font style='font-size:10px;'>";
				if($fila[2]=="Detalle"){
					
				?><a href='#' onclick="PonerCuenta('<?echo $Objeto?>',<?echo $fila[0]?>,'<?echo $fila[2]?>')"><?}echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}
	}
?>
</td></tr>
</table>