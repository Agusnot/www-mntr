<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
include("ObtenerSaldos.php");
$ND = getdate();
//UM:27-04-2011
//UM:24-04-2011
$cons = "Select UsuariosxAlmacenes.AlmacenPpal,FormatoFormula,IniControlados,Redondear
from Consumo.UsuariosxAlmacenes, Consumo.AlmacenesPpales
where UsuariosxAlmacenes.AlmacenPpal = AlmacenesPpales.AlmacenPpal and  AlmacenesPpales.Compania='$Compania[0]' and
Usuario='$usuario[0]' and SSFarmaceutico = 1";
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    if(!$AlmacenPpal){$AlmacenPpal=$fila[0];}
    $Formula[$fila[0]]=$fila[1];
    $Inicial[$fila[0]]=$fila[2];
    $Redondear[$fila[0]] = $fila[3];
}
$cons = "Select NumeroControlados from Consumo.Movimiento Where Compania='$Compania[0]'
and AlmacenPpal='$AlmacenPpal' and NumeroControlados IS NOT NULL";
$res = ExQuery($cons);
if(ExNumRows($res)==0){$IniControl = $Inicial[$AlmacenPpal];}
$VrSaldoIni=SaldosIniciales($ND[year],$AlmacenPpal,"$ND[year]-01-01");
$VrEntradas=Entradas($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
$VrSalidas=Salidas($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
$VrDevoluciones = Devoluciones($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
$cons = "Select Cedula,Entidad,Servicios.NumServicio,PrimNom,SegNom,PrimApe,SegApe
from Salud.Servicios,Salud.PagadorxServicios,Central.Terceros Where
Servicios.Compania='$Compania[0]' and PagadorxServicios.Compania='$Compania[0]'
and Servicios.NumServicio = PagadorxServicios.NumServicio and FechaIni <='$ND[year]-$ND[mon]-$ND[mday]'
and Terceros.Identificacion = Servicios.Cedula
order by fechaIni Desc";
//echo $cons;
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    $Asegurador[$fila[0]] = $fila[1];
    $Servicio[$fila[0]] = $fila[2];
    $Paciente[$fila[0]] = "$fila[3] $fila[4] $fila[5] $fila[6]";
}
$cons = "Select Autoid,Nombreprod1,UnidadMedida,Presentacion,Control,Grupo from Consumo.CodProductos Where Compania = '$Compania[0]'
and AlmacenPpal = '$AlmacenPpal' and Anio=$ND[year] and Estado = 'AC'";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Medicamento[$fila[0]] = "$fila[1] $fila[2] $fila[3]";
    $Control[$fila[0]]=$fila[4];
    $Grupo[$fila[0]] = $fila[5];
}

$cons = "Select CumsxProducto.AutoId,CUM,Cantidad,
Lotes.Laboratorio,Lotes.Presentacion,Numero,Salidas
from Consumo.CumsxProducto,Consumo.Lotes
Where Lotes.Laboratorio = CumsXProducto.Laboratorio
and Lotes.Presentacion = CumsXProducto.Presentacion
and Lotes.Autoid = CumsXProducto.Autoid
and (Salidas < Cantidad or Salidas is NULL)
order by Autoid,Vence Desc";//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    //echo "$fila[0]....$fila[1]....$fila[2]<br>";
    $C[$fila[0]] = $C[$fila[0]] + 1;
    $Lote[$fila[0]][$C[$fila[0]]] = array($fila[2]-$fila[6],$fila[1],$fila[3],$fila[4],$fila[5]);
}

$VrSaldoIni=SaldosIniciales($ND[year],$AlmacenPpal,"$ND[year]-01-01");
$VrEntradas=Entradas($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
$VrSalidas=Salidas($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
$VrDevoluciones = Devoluciones($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
if($ND[mon]<10){$Mes = "0".$ND[mon];}else{$Mes = $ND[mon];}
if($ND[mday]<10){$Dia = "0".$ND[mday];}else{$Dia = $ND[mday];}

$cons = "Select cedula,Movimiento.Autoid,Numero,fecha,numorden,idescritura,cantidad,control,numerocontrolados,numero
from consumo.movimiento,consumo.codproductos where Movimiento.Compania='$Compania[0]'
and codproductos.Compania='$Compania[0]' and Codproductos.AlmacenPpal='$AlmacenPpal'
and Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Autoid = CodProductos.Autoid
and Movimiento.Estado = 'AC' and Comprobante = 'Salidas Urgentes' and consumo.codproductos.anio='$ND[year]' order by fecha asc";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $TotalPlantilla[$fila[0]][$fila[1]]=$fila[6];
    $Despachados[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $Despachados[$fila[0]][$fila[1]][$fila[4]][$fila[5]] + $fila[6];
    $Fecha[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $fila[3];
	$Numero[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $fila[9];
    $TotalDespacho[$fila[1]] = $TotalDespacho[$fila[1]] + $fila[6];
    $D[$fila[3]][$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $fila[6];
    //echo "$fila[3]==$ND[year]-$Mes-$Dia";
    if($fila[3]=="$ND[year]-$Mes-$Dia")
    {
        if($fila[7]=="Si"){$DC[$fila[0]]=$fila[8];}
        if($fila[7]=="No"){$DNC[$fila[0]]=$fila[2];}
    }
}
$cons = "Select cedpaciente,AutoidProd,plantillamedicamentos.numorden,
plantillamedicamentos.idescritura,cantdiaria,fechaini,Usuarios.Nombre,
plantillamedicamentos.numservicio,plantillamedicamentos.Estado,plantillamedicamentos.Detalle,
Plantillamedicamentos.ViaSuministro,Plantillamedicamentos.Posologia,
Plantillamedicamentos.Justificacion,Plantillamedicamentos.Notas
from salud.plantillamedicamentos,central.usuarios,salud.ordenesmedicas
Where ordenesmedicas.compania='$Compania[0]'
and ordenesmedicas.numservicio=plantillamedicamentos.numservicio and ordenesmedicas.numorden=plantillamedicamentos.numorden
and ordenesmedicas.idescritura=plantillamedicamentos.idescritura and
Plantillamedicamentos.usuario = usuarios.usuario and plantillamedicamentos.compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal'
and TipoMedicamento = 'Medicamento Urgente' and Descartado is NULL order by cedpaciente"; //echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    //echo "$fila[0]/$fila[1]/$fila[2]/$fila[3]/$fila[4]/$fila[5]/$fila[6]/$fila[7]/$fila[8]/$fila[9]<br>";
    if(!$Despachados[$fila[0]][$fila[1]][$fila[2]][$fila[3]])
    {
        if($Control[$fila[1]]=="Si"){$Controlados[$fila[0]]=$Controlados[$fila[0]]+1;}
        else{$NOControlados[$fila[0]]=$NOControlados[$fila[0]]+1;}
    }
    $Plantilla[$fila[0]][$fila[1]][$fila[2]][$fila[3]] = array($fila[4],$fila[5],$fila[6],$fila[7],
                                                        $fila[8],$fila[10],$fila[11],$fila[12],$fila[13],$fila[9]);
    $TotalOrden[$fila[1]] = $TotalOrden[$fila[1]] + $fila[4];
    $TotalPlantilla[$fila[0]][$fila[1]] = $fila[4];
}
$Comprobante="Salidas Urgentes";
$AnioI="$ND[year]";
?>
<script language="Javascript">
	function VerOrden(Cedula,Formato,Tipo,Numero,Urgentes,Despachados)
	{
            open("/Informes/Almacen/Reportes/"+Formato+"?Urgentes="+Urgentes+"&Despachados="+Despachados+"&Tipo="+Tipo+"&DatNameSID=<? echo $DatNameSID?>&Cedula="+Cedula+"&AlmacenPpal=<?echo $AlmacenPpal?>&Urgente=1&Numero="+Numero,"","width=700,height=500,scrollbars=yes")
	}
	function VerImprimible(Numero,Comprobante,AlmacenPpal,NoFactura)
	{
		<? 
			$cons000 = "Select Formato from Consumo.Comprobantes where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Comprobante = '$Comprobante'";
			$res000 = ExQuery($cons000);
			$fila000 = ExFetch($res000);
			$Archivo = $fila000[0];
		?>
		open("/Informes/Almacen/Formatos/Venta.php?DatNameSID=<? echo $DatNameSID?>&NoFactura="+NoFactura+"&Numero="+Numero+"&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $AnioI?>","","width=700,height=500,scrollbars=yes")
	}
</script>
<body background="/Imgs/Fondo.jpg">
    <?
    if($ND[mon]<10){$Mes = "0".$ND[mon];}else{$Mes = $ND[mon];}
    if($ND[mday]<10){$Dia = "0".$ND[mday];}else{$Dia = $ND[mday];}
    if($VerDespachosUrgentes)
	if($D["$ND[year]-$Mes-$Dia"])
    {
    ?>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor ="#e5e5e5" width =" 700px" align="center">
        <tr bgcolor="<? echo $Estilo[1]?>" style="color:#FFFFFF" style=" font-weight: bold">
            <td align="center" colspan="2">MEDICAMENTOS URGENTES DESPACHADOS HOY</td>
        </tr>
        <?
        while(list($Cedula,$Despachados1) = each($Despachados))
        {
            if($D["$ND[year]-$Mes-$Dia"][$Cedula])
            {
                ?>
                <tr bgcolor ="#e5e5e5" style =" font-weight: bold" align="left">
                    <td><? echo strtoupper($Paciente[$Cedula])." - $Cedula"?></td>
				<td align="right">
				<?
                if($DC[$Cedula])
                {?>
                    <button type="button" name="Ver Formula CON"
                        style="background-color:cadetblue;width: 25px; height: 25px;"
                        onmouseover="Info(event,'Ver formula de medicamentos de control','Mensaje');MensajeMsj.style.width='400px';"
                        onmouseout="document.getElementById('Mensaje').style.display='none';MensajeMsj.style.width='150px';"
                        onClick="VerOrden('<?echo $Cedula?>','<? echo $Formula[$AlmacenPpal]?>','Si','<? echo $DC[$Cedula]?>','1')">
                        <img src="/Imgs/b_sbrowse.png" />
                    </button>
                <?}
                if($DNC[$Cedula])
                {?>
                    <button type="button" name="Ver Formula CON"
                        style="background-color:#e5e5e5;width: 25px; height: 25px;"
                        onmouseover="Info(event,'Ver formula de medicamentos','Mensaje');MensajeMsj.style.width='400px';"
                        onmouseout="document.getElementById('Mensaje').style.display='none';MensajeMsj.style.width='150px';"
                        onClick="VerOrden('<? echo $Cedula ?>','FormulaGenerica.php','No','<? echo $DNC[$Cedula]?>','1')">
                        <img src="/Imgs/b_sbrowse.png" />
                    </button>
                <?}
                ?></td></tr><?
                while(list($Autoid,$Despachados2) = each($Despachados1))
                {
                    if(!$Encabezado[$Cedula])
                    {
                        $Encabezado[$Cedula]=1;
                    ?>
                        <tr bgcolor="#e5e5e5">
                            <td>Medicamento</td><td>Cantidad</td>
                        </tr>
                    <?
                    }
                    while(list($NumOrden,$Despachados3) = each($Despachados2))
                    {
                        while(list($IdEscritura,$Cantidad) = each($Despachados3))
                        {
                            //echo $Fecha[$Cedula][$Autoid][$NumOrden][$IdEscritura]."====$ND[year]-$Mes-$Dia<br>";
                            //if($Fecha[$Cedula][$Autoid][$NumOrden][$IdEscritura]=="$ND[year]-$Mes-$Dia")
                            if($VerDespachosUrgentes)
							{if($Fecha[$Cedula][$Autoid][$NumOrden][$IdEscritura]=="$ND[year]-$Mes-$Dia"){
                            ?>
                            <tr>                            
							<td><? echo $Medicamento[$Autoid]?> <img style="cursor:hand;" border="0" onClick="VerImprimible('<? echo $Numero[$Cedula][$Autoid][$NumOrden][$IdEscritura]?>','<? echo $Comprobante ?>','<? echo $AlmacenPpal?>','<? echo $fila[12]?>')" title="Ver la Versión imprimible" src="/Imgs/b_print.png" /></td>
                                <td align="right"><? echo number_format($Cantidad,2)?></td>
                            </tr>
                            <?
                            }}
                        }
                    }
                }
            }
        }
        ?>
    </table>
    <?
    }
    ?>
