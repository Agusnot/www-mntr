<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("ObtenerSaldos.php");
	$ND=getdate();
?>
<body background="/Imgs/Fondo.jpg">
<?
	if(!$Anio){$Anio=$ND[year];}
	$Fecha="$ND[year]-$ND[mon]-$ND[mday]";
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$ND[year]-$ND[mon]-01");
	$VrEntradas=Entradas($Anio,$AlmacenPrincipal,"$Anio-$ND[mon]-01",$Fecha);
	$VrSalidas=Salidas($Anio,$AlmacenPrincipal,"$Anio-$ND[mon]-01",$Fecha);
    $VrDevoluciones=Devoluciones($Anio,$AlmacenPrincipal,"$Anio-$ND[mon]-01",$Fecha);
	//print_r($VrEntradas);
    if($Codigo || $Medicamento)
	{
		$cons = "Select TipoProducto from ContratacionSalud.TiposdeProdXFormulacion where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
		and Formulacion='$Formulacion'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			$TipoPro[$fila[0]] = $fila[0];
		}
		
		$cons0 ="Select NoContrato,Entidad,contrato
				from Salud.PagadorXServicios,Salud.Servicios 
				where PagadorXServicios.NumServicio = Servicios.NumServicio and
				PagadorXServicios.Compania='$Compania[0]' and Servicios.Compania='$Compania[0]' 
				 and Servicios.cedula='$Paciente[1]' and FechaIni<='$Fecha' and 
				(FechaFin>='$Fecha' or FechaFin is NULL)";//echo $cons0;
        if($Paquete)
        {
            $cons0 = "Select Numero,Entidad,Contrato from ContratacionSalud.Contratos 
            Where Entidad = '$Entidad' and Numero = '$NoContrato' and Contrato = '$Contrato' LIMIT 1";//echo $cons0;
        }
		$res0 = ExQuery($cons0);		
		//echo $cons0;
		if(ExNumRows($res0)>0)
		{
			$fila0 = ExFetch($res0);
            $NoContrato = $fila0[0]; $Entidad = $fila0[1]; $Contrato = $fila0[2];
            if($Paquete){$fila0[0]=$NoContrato;$fila0[2]=$Contrato;$fila0[1]=$Entidad;}
			$cons1 = "Select PlanServMeds from ContratacionSalud.Contratos where Numero='$fila0[0]' and contrato='$fila0[2]' and Entidad='$fila0[1]' and Compania='$Compania[0]'";
			$res1 = ExQuery($cons1);
			//echo $cons1."<br>";
			if(ExNumRows($res1)>0)
			{
				$fila1 = ExFetch($res1);
				$cons2 = "Select Codigo from ContratacionSalud.MedsxPlanServic where AutoId='$fila1[0]' and Compania='$Compania[0]'";
				$res2 = ExQuery($cons2);
				//echo $cons2;
				if(ExNumRows($res2)>0)
				{
					while($fila2 = ExFetch($res2))
					{	
						$MedicamentoS[$fila2[0]] = "$fila2[0]";
						//echo $MedicamentoS[$fila2[0]]." <BR>";
					}
				}
				else
				{ $Titulo = "No Existen Medicamentos relacionados para el Paciente";
					
				}
			}
			else
			{ $Titulo = "No Existen Planes de Servicios que relacionen el Medicamento para el Paciente";
			
			}
		}
		else
		{ $Titulo = "No Existen Contratos que relacionen el Medicamento para el Paciente";
		
		}
		
		$Medicamento = str_replace(" ", "%", $Medicamento);
		$cons = "Select AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion,CodProductos.TipoProducto,POS
		from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion 
		where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and
		CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$AlmacenPpal' and Anio=$ND[year] and Codigo1 like '$Codigo%'
		and ( NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion) ilike '$Medicamento%' and Formulacion = '$Formulacion' 
		order by ( NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion) asc";
		
		$cons = "Select AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion,CodProductos.TipoProducto,POS
		from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion 
		where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and
		CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$AlmacenPpal' and Anio=$ND[year] and Codigo1 like '$Codigo%'
		and ( NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion) ilike '$Medicamento%' and consumo.codproductos.estado='AC'
		group by AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion,CodProductos.TipoProducto,POS
		order by ( NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion) asc";
		//echo $cons;
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			?><table border="1" bordercolor="<? echo $Estilo[1]?>" width="100%" style='font : normal normal small-caps 13px Tahoma;'>
				<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    				<td width="10%">Codigo</td><td width="68">Medicamento</td><td width="10%">Existencias</td><td width="12%"></td></tr>
    			</tr>
            <? 	while($fila = ExFetch($res))
				{
					if($TipoPro[$fila[5]])
					{ 
						if($fila[6]){
							$P="POS";
						}else{
							$P="NO POS";
						}
                        //echo $VrSaldoIni[$fila[0]][0]."----|".$VrEntradas[$fila[0]][0]."---".$VrSalidas[$fila[0]][0]."----".$VrDevoluciones[$fila[0]][0]."<br>";
						$CantExistencias=$VrSaldoIni[$fila[0]][0]+$VrEntradas[$fila[0]][0]-$VrSalidas[$fila[0]][0]+$VrDevoluciones[$fila[0]][0];
						//echo $$CantExistencias;
						//echo $fila[1]."<br>";
						// Se comenta para permitir que se acepten todas los medicamentos independientemente si estén o no en un plan de servicios del paciente
						//if($MedicamentoS[$fila[1]])
						//{ 
							?>
							<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
                            onclick="
                            parent.parent.document.FORMA.Medicamento.value='<? echo "$fila[2] $fila[3] $fila[4]"?>'
							<? if($Formulacion!='Urgentes'){?>
							parent.parent.document.FORMA.Medicamentoss.value='<? echo $fila[4]?>';
							<? }?>
                            parent.parent.document.FORMA.AutoIdProd.value='<? echo $fila[0]?>';
                            parent.parent.document.FORMA.AlmacenPpal.value='<? echo $AlmacenPpal?>';
                            parent.parent.document.FORMA.POS.value='<? echo $fila[6]?>';
							parent.parent.document.FORMA.submit();
                            ">
								<? echo "<td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4]</td>";
						/*}
						else
						{
							if($Titulo)
							{
								echo "<tr title='El Producto no puede ser seleccionado, $Titulo'><td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4]</td>";
							}
							else
							{
								echo "<tr title='Este Producto no pertenece al plan de Servicios relacionado con el paciente'>
								<td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4]</td>";
							}
						}*/
						echo "<td align='right'>".number_format($CantExistencias,2)."</td><td>$P</td></tr>";
					}
				} ?>	
			</table><? 
		}
		else
		{
			echo "<center><font color='red'><em>No Existen Registros Coincidentes</em></font></center>";
		}
	}?>
</body>    
