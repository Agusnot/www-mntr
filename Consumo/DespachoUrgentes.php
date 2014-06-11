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
Usuario='$usuario[1]' and SSFarmaceutico = 1";

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
if($Descarta)
{
    //[$Cedula][$Autoid][$NumOrden][$IdEscritura]
    while(list($Cedula,$Descarta1) = each($Descarta))
    {
        while(list($Autoid,$Descarta2) = each($Descarta1))
        {
            while(list($NumOrden,$Descarta3) = each($Descarta2))
            {
                while(list($IdEscritura,$Descarta4) = each($Descarta3))
                {
                    $cons = "Update salud.Plantillamedicamentos set Descartado = 1, Descartadox='$usuario[1]'
                    Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and CedPaciente = '$Cedula'
                    and AutoidProd=$Autoid and NumOrden = $NumOrden and IdEscritura = $IdEscritura";
                    $res = ExQuery($cons);
                }
            }
        }
        unset ($Cedula,$Autoid,$NumOrden,$IdEscritura);
    }
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
if($Despachar)
{
    if($Despacha)
    {
        $cons = "Select Entidad,Autoid,ValorVenta from ContratacionSalud.Contratos, Consumo.TarifasxProducto
        where Contratos.PlanTarifaMeds = TarifasxProducto.Tarifario and Contratos.Compania='$Compania[0]'
        and TarifasxProducto.Compania='$Compania[0]'
        and Contratos.FechaIni<='$ND[year]-$ND[mon]-01' and Contratos.FechaFin>='$ND[year]-$ND[mon]-$ND[mday]'
        and TarifasxProducto.FechaIni<='$ND[year]-$ND[mon]-01'
        and TarifasxProducto.FechaFin>='$ND[year]-$ND[mon]-$ND[mday]' and Anio=$ND[year] and Estado = 'AC'";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            $Tarifas[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2]);
        }
        $Comprobante = 'Salidas Urgentes';
        //$CC = '000';
        //$cons = "Select Codigo from central.CentrosCosto Where Compania='$Compania[0]'
        //and Tipo='Detalle' and CentroCostos ilike '%Urgencias%' and Anio=$ND[year]";
        //$res = ExQuery($cons);
        //$fila = ExFetch($res);
        //if($fila[0]){$CC = $fila[0];}
        while(list($Cedula,$Despacha1)=each($Despacha))
        {
            $consxx = "Select Cedula,Pabellon,Ambito from Salud.PacientesxPabellones
            Where Compania = '$Compania[0]' and Cedula='$Cedula' order by Estado asc, Fechai Desc LIMIT 1";
            $resxx = ExQuery($consxx);
            if(ExNumRows($resxx)>0)
            {
                $filaxx = ExFetch($resxx);
                $consxx1 = "Select CentroCosto from Salud.Pabellones
                Where Compania = '$Compania[0]' and Pabellon = '$filaxx[1]'";
                $resxx1 = ExQuery($consxx1);
                if(ExNumRows($resxx1)>0)
                {
                    $filaxx1 = ExFetch($resxx1);
                    $CC = $filaxx1[0];$TipoServ = $filaxx[2];$UnidadHosp = $filaxx[1];
                }
                else
                {
                    $consxx1 = "Select CentroCostos,Ambito from Salud.Ambitos
                    Where Compania = '$Compania[0]' and Ambito = '$filaxx[2]'";
                    $resxx1 = ExQuery($consxx1);
                    $filaxx1 = ExFetch($resxx1);
                    if($filaxx1[0]){$CC = $filaxx1[0];$TipoServ = $filaxx1[1];$UnidadHosp = "";}
                    else{$CC = "000";}
                }
            }
            else
            {
                $consxx = "Select TipoServicio,CentroCostos 
                from Salud.Servicios,Salud.Ambitos
                Where Servicios.Compania = '$Compania[0]' and Ambitos.Compania='$Compania[0]'
                and TipoServicio = Ambito
                and Cedula = '$Cedula' order by Estado asc, FechaIng Desc LIMIT 1";
                $resxx = ExQuery($consxx);
                $filaxx = ExFetch($resxx);
                if($filaxx[0]){$CC=$filaxx[1];$TipoServ = $filaxx[0];$UnidadHosp = "";}
                else{$CC="000";}
            }
            //echo "$Cedula --- $CC --- $TipoServ --- $UnidadHosp<br>";
            $Numero=ConsecutivoComp($Comprobante,$ND[year],"Consumo");
            while(list($Autoid,$Despacha2) = each($Despacha1))
            {
                if($Control[$Autoid]=="Si")
                {
                    if(!$NumeroControl[$Cedula][$Autoid] || $NumeroControl[$Cedula][$Autoid]=="NULL")
                    {
                        if(!$IniControl)
                        {
                            $cons = "Select NumeroControlados from Consumo.Movimiento
                            Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
                            and NumeroControlados is not NULL
                            order by NumeroControlados desc";
                            $res = ExQuery($cons);
                            $fila = ExFetch($res);
                            $NumeroControl[$Cedula][$Autoid] = $fila[0] + 1;
                        }
                        else
                        {
                            $NumeroControl[$Cedula][$Autoid] = $IniControl;
                            unset($IniControl);
                        }
                    }
                }
                else
                {
                    $NumeroControl[$Cedula][$Autoid]="NULL";
                }
                $CantFinal=$VrSaldoIni[$Autoid][0]+$VrEntradas[$Autoid][0]-$VrSalidas[$Autoid][0]+$VrDevoluciones[$Autoid][0];
                $VrFinal=$VrSaldoIni[$Autoid][1]+$VrEntradas[$Autoid][1]-$VrSalidas[$Autoid][1]+$VrDevoluciones[$Autoid][1];
                if($CantFinal>0){$VrCosto = $VrFinal/$CantFinal;}
                else{$VrCosto = 0;}
                $VrVenta = $Tarifas[$Asegurador[$Cedula]][$Autoid][2];
                if(!$VrVenta){$VrVenta = 0;}
                while(list($NumOrden,$Despacha3) = each($Despacha2))
                {
                    while(list($IdEscritura,$C) = each($Despacha3))
                    {
                        //echo $Medicamento[$Autoid]."__Pendiente:".$PendientesDespacho[$Autoid]."__Existencias:$CantFinal<br>";
                        //echo $CantFinal-$EsteDespacho[$Autoid]."ooooooooo".$Servicio[$Cedula]."iiiiiii$Cedula<br>";
                        //else
                        //{
                            //echo "---$Cantidad <= ($CantFinal-$EsteDespacho[$Autoid])<br>";
                            //////////////
                            reset($Lote);
                            unset($EsteDesp);
                            $EsteDesp = $Cantidad[$Cedula][$Autoid][$NumOrden][$IdEscritura];//Cantidad
                            //echo "Lote para Autoid:".$Despacho[$i][1]."<br>";
                            if(!$Lote[$Autoid])
                            {
                                $cons = "";
                                $Lote[$Autoid]=array(0,0,0);
                            }
                            foreach($Lote[$Autoid] as $CUM)
                            {
                                if($CUM[0]>0)
                                {
                                    if($CUM[0] >= $EsteDesp)
                                    {
                                        $CUM[0] = $CUM[0] - $EsteDesp;
                                        $EsteDesp = 0;
                                        $CantidadX = $Cantidad[$Cedula][$Autoid][$NumOrden][$IdEscritura];
                                        $do_break = 1;
                                    }
                                    if($CUM[0]<$EsteDesp)
                                    {
                                        $EsteDesp = $EsteDesp - $CUM[0];
                                        $CUM[0] = 0;
                                        $CantidadX = $EsteDesp;
                                        $do_break = 0;
                                    }
                                    $consxx = "Update Consumo.Lotes set Salidas = Salidas + $CantidadX
                                    Where Laboratorio = '$CUM[2]' and Presentacion = '$CUM[3]'
                                    and Numero = '$CUM[4]' and Compania = '$Compania[0]' 
                                    and AlmacenPpal = '$AlmacenPpal' and Autoid = $Autoid";
                                    $resxx = ExQuery($consxx);

                                    if($CantidadX <= ($CantFinal-$EsteDespacho[$Autoid]))
                                    {
                                        if(!$Servicio[$Cedula]){$Servicio[$Cedula]=-1;}
                                        if(!$NumOrden || $NumOrden==""){$NumOrden="NULL";}
                                        if(!$IdEscritura || $IdEscritura==""){$IdEscritura="NULL";}
                                        $TotCosto = $CantidadX * $VrCosto;
                                        $TotVenta = $CantidadX * $VrVenta;
                                        $cons = "Insert into Consumo.Movimiento (Compania,AlmacenPpal,Fecha,
                                        Comprobante,TipoComprobante,Numero,Cedula,Detalle,Autoid,UsuarioCre,
                                        FechaCre,Estado,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIva,
                                        VrIva,CentroCosto,Anio,numservicio,Grupo,NumOrden,IdEscritura,NumeroControlados,CUM)
                                        values('$Compania[0]','$AlmacenPpal','$ND[year]-$ND[mon]-$ND[mday]',
                                        '$Comprobante','Salidas','$Numero','$Cedula',
                                        'Despacho de Medicamentos Urgentes',
                                        $Autoid,'$usuario[1]',
                                        '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                                        'AC',".$CantidadX.",
                                        $VrCosto,$TotCosto,$VrVenta,$TotVenta,0,0,'$CC',$ND[year],
                                        $Servicio[$Cedula],'".$Grupo[$Autoid]."',$NumOrden,$IdEscritura,".$NumeroControl[$Cedula][$Autoid].",'$CUM[1]')";
                                        //echo $cons."<br><br>";
                                        $res = ExQuery($cons);
                                        //$PendientesDespacho[$Autoid] = $PendientesDespacho[$Autoid] - $Cantidad;
                                        $EsteDespacho[$Autoid] = $EsteDespacho[$Autoid] + $CantidadX;
                                    }
                                    else
                                    {
                                        $Mensaje = $Mensaje."No se pudo despachar ".$Medicamento[$Autoid]." para ".$Paciente[$Cedula]."\\n\\n";
                                    }
                                    if($do_break == 1){break;}
                                }
                                if($EsteDesp==0){break;}
                            }
                            //////////////
                            
                        //}
                    }
                }
            }
        }
    }
    if($Mensaje)
    {
        echo "<script language='javascript'>alert(\"$Mensaje Verificar existencias y/o Servicios activos\");</script>";
    }
}
$VrSaldoIni=SaldosIniciales($ND[year],$AlmacenPpal,"$ND[year]-01-01");
$VrEntradas=Entradas($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
$VrSalidas=Salidas($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
$VrDevoluciones = Devoluciones($ND[year],$AlmacenPpal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]");
if($ND[mon]<10){$Mes = "0".$ND[mon];}else{$Mes = $ND[mon];}
if($ND[mday]<10){$Dia = "0".$ND[mday];}else{$Dia = $ND[mday];}

/*if($VerDespachosUrgentes)
$cons = "Select cedula,Movimiento.Autoid,Numero,fecha,numorden,idescritura,cantidad,control,numerocontrolados
from consumo.movimiento,consumo.codproductos where Movimiento.Compania='$Compania[0]'
and codproductos.Compania='$Compania[0]' and Codproductos.AlmacenPpal='$AlmacenPpal'
and Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Autoid = CodProductos.Autoid
and Movimiento.Estado = 'AC' and Comprobante = 'Salidas Urgentes' and consumo.codproductos.anio='$ND[year]' order by fecha asc";
else*/
$cons = "Select cedula,Movimiento.Autoid,Numero,fecha,numorden,idescritura,cantidad,control,numerocontrolados
from consumo.movimiento,consumo.codproductos where Movimiento.Compania='$Compania[0]'
and codproductos.Compania='$Compania[0]' and Codproductos.AlmacenPpal='$AlmacenPpal'
and Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Autoid = CodProductos.Autoid
and Movimiento.Estado = 'AC' and Comprobante = 'Salidas Urgentes' order by fecha asc";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $TotalPlantilla[$fila[0]][$fila[1]]=$fila[6];
    $Despachados[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $Despachados[$fila[0]][$fila[1]][$fila[4]][$fila[5]] + $fila[6];
    $Fecha[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = $fila[3];
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
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="Javascript">
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
    function RevisarInconsistencias(AutoId,AlmacenPpal,Medicamento,e)
    {
        y = e.clientY;
		sT = document.body.scrollTop;
        frames.FrameOpener.location.href='InconsistenciasCUM.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal='+AlmacenPpal+'&AutoId='+AutoId+'&Medicamento='+Medicamento;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y/2)+sT;;
		document.getElementById('FrameOpener').style.left='8px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='600';
    }
    function ListaDespachos(AlmacenPpal)
    {
        open("/Informes/Almacen/Reportes/DespachosUrgentes.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal="+AlmacenPpal,"","width=800,height=600,scrollbars=yes")
    }
    function ValidarCant(IDCantidad,IDOrden)
    {
        var cantidad = parseInt(document.getElementById(IDOrden).value)-parseInt(document.getElementById(IDCantidad).value);
        if(cantidad<0){alert("Valor invalido");document.getElementById(IDCantidad).value = document.getElementById(IDOrden).value;}
    }
    function H_D(objeto,Cedula,lon)
    {
        //alert(lon + 9);
        cadcomp = "Despacha["+Cedula+"]";
        //alert(cadcomp);
        if(objeto.checked==true)
        {
            for (i=0;i<document.FORMA.elements.length;i++)
            {
                if(document.FORMA.elements[i].type == "checkbox")
                {
                    if(document.FORMA.elements[i].name.substr(0,lon+10)==cadcomp)
                    {
                        document.FORMA.elements[i].checked = true;
                    }
                }
            }
        }
        else
        {
            for (i=0;i<document.FORMA.elements.length;i++)
            {
                if(document.FORMA.elements[i].type == "checkbox")
                {
                    if(document.FORMA.elements[i].name.substr(0,lon+10)==cadcomp)
                    {
                        document.FORMA.elements[i].checked = false;
                    }
                }
            }
        }
    }
    function Info(Evento,Dato,Div)
        {
            var PosMouseX,PosMouseY;
            PosMouseX=Evento.clientX;
            PosMouseY=Evento.clientY;
            //--
            var ajusteX, ajusteY;
            if( self.pageYOffset ) {
              ajusteX = self.pageXOffset;
              ajusteY = self.pageYOffset;
            } else if( document.documentElement && document.documentElement.scrollTop ) {
              ajusteX = document.documentElement.scrollLeft;
              ajusteY = document.documentElement.scrollTop;
            } else if( document.body ) {
              ajusteX = document.body.scrollLeft;
              ajusteY = document.body.scrollTop;
            }
            var leftOffset;
            if(Div=="Mensaje"){leftOffset = ajusteX + (PosMouseX )-100;}
            else{leftOffset = ajusteX + (PosMouseX )-200;}
            var topOffset = ajusteY + (PosMouseY )-30 ;
            //alert(Div+"Msj");
            var Msjforma=Div+"Msj";
            document.getElementById(Msjforma).value=Dato;
            document.getElementById(Div).style.top = topOffset + "px";
            document.getElementById(Div).style.left = leftOffset + "px";
            document.getElementById(Div).style.display = "block";
            if(Dato==""){document.getElementById(Msjforma).style.background="none";}
        }
        function AbrirTarjetaMeds(AlmacenPpal)
	{
            var Fecha = document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
            var Ambito = document.FORMA.Ambito.value;
            var Pabellon = document.FORMA.Pabellon.value;
            open("/Informes/Almacen/Reportes/TarjetaMeds.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal="+AlmacenPpal+"&Fecha="+Fecha+"&Ambito="+Ambito+"&Pabellon="+Pabellon,"","width=700,height=500,scrollbars=yes")
	}
	function VerOrden(Cedula,Formato,Tipo,Numero,Urgentes,Despachados)
	{
            open("/Informes/Almacen/Reportes/"+Formato+"?Urgentes="+Urgentes+"&Despachados="+Despachados+"&Tipo="+Tipo+"&DatNameSID=<? echo $DatNameSID?>&Cedula="+Cedula+"&AlmacenPpal=<?echo $AlmacenPpal?>&Urgente=1&Numero="+Numero,"","width=700,height=500,scrollbars=yes")
	}
	function ValidarCantidad(CedPaciente,IDProd,ValAnt,Medicamento)
	{
            var Cantidad = parseFloat(document.getElementById("AutoIdProd[" + CedPaciente + "][" + IDProd + "]").value);
            var ExCorte = parseFloat(document.getElementById("Exist[" + IDProd + "]").value);
            var ExAnual = parseFloat(document.getElementById("ExistAnu[" + IDProd + "]").value);

            if(Cantidad>parseFloat(document.getElementById("Exist[" + IDProd + "]").value))
            {
                if(ExCorte-Cantidad>0)
                {
                    if(ExAnual-Cantidad>0){document.getElementById("AutoIdProd[" + CedPaciente + "][" + IDProd + "]").value= ValAnt;}
                    else
                    {
                        alert("El Valor que intenta despachar para "+Medicamento+" es invalido sobrepasa la existencia Anual");
                        document.getElementById("AutoIdProd[" + CedPaciente + "][" + IDProd + "]").value= "";
                    }
                }
                else
                {
                    alert("El Valor que intenta despachar para "+Medicamento+" es invalido");
                    document.getElementById("AutoIdProd[" + CedPaciente + "][" + IDProd + "]").value= "";
                }
            }
            if((Cantidad>parseFloat(ValAnt)))
            {
                alert("El Valor que intenta despachar para "+Medicamento+" es mayor que el de la Orden Medica");
                document.getElementById("AutoIdProd[" + CedPaciente + "][" + IDProd + "]").value= ValAnt;
            }
	}
	
	function AbrirDespachosUrgentes(option)
	{
            open("/Informes/Almacen/Reportes/ConsoDespachosUrg.php?DatNameSID=<? echo $DatNameSID?>&VerDespachosUrgentes="+option,"","width=800,height=600,scrollbars=yes")
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form  name="FORMA" method="post">
    <table style='font : normal normal small-caps 12px Tahoma;' border="0">
        <tr bgcolor="#e5e5e5" style="font-weight:bold">
            <td colspan ="3" align ="center"><b>Almacen Principal</b>
                <select name="AlmacenPpal" onChange="FORMA.submit();">
                <?
                    $cons = "Select UsuariosxAlmacenes.AlmacenPpal from Consumo.UsuariosxAlmacenes, Consumo.AlmacenesPpales
                    where UsuariosxAlmacenes.AlmacenPpal = AlmacenesPpales.AlmacenPpal and  AlmacenesPpales.Compania='$Compania[0]' and
                    Usuario='$usuario[1]' and SSFarmaceutico = 1";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                        else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                    }
                ?>
                </select>
            </td>
    </tr>
    </table>
    <!--<div style =" width: 700px" align="center">-->
    <input type="submit" name="Despachar" value="Despachar Medicamentos NO Programados" style =" width: 300px"
           onclick ="this.style.visibility = 'hidden';"/>
    <input type="submit" name="VerDespachosUrgentes" value="Ver Medicamentos NO Programados Despachos Hoy" onClick="AbrirDespachosUrgentes('true')" style =" width: 350px"
           onclick ="this.style.visibility = 'hidden';"/>
    <?
    if($Plantilla)
    {
        while(list($Cedula,$Plantilla1) = each($Plantilla))
        {
            if($Controlados[$Cedula])
            {
                $AdtdC="<button type=\"button\" name=\"Ver Formula CON\"
                    style=\"background-color:cadetblue;width: 25px; height: 25px;\"
                    onmouseover=\"Info(event,'Ver formula de medicamentos de control','Mensaje');MensajeMsj.style.width='400px';\"
                    onmouseout=\"document.getElementById('Mensaje').style.display='none';MensajeMsj.style.width='150px';\"
                    onClick=\"VerOrden('$Cedula','$Formula[$AlmacenPpal]','Si','','')\">
                    <img src=\"/Imgs/b_sbrowse.png\" />
                </button>";
            }
            else{unset($AdtdC);}
            if($NOControlados[$Cedula])
            {
                $AdtdNC="<button type=\"button\" name=\"Ver Formula NC\"
                        style=\"background-color:#e5e5e5;width: 25px; height: 25px;\"
                        onmouseover=\"Info(event,'Ver formula de medicamentos','Mensaje');MensajeMsj.style.width='400px';\"
                        onmouseout=\"document.getElementById('Mensaje').style.display='none';MensajeMsj.style.width='150px';\"
                        onClick=\"VerOrden('$Cedula','FormulaGenerica.php','No','','')\">
                        <img src=\"/Imgs/b_sbrowse.png\" />
                        </button>";
            }
            else{unset($AdtdNC);}
            $TABLA="
            <table style='font : normal normal small-caps 12px Tahoma;' border=\"1\" bordercolor =\"#e5e5e5\" width =\" 700px\">
                <tr>
                    <td bgcolor =\"#e5e5e5\" style=\" font-weight: bold\" colspan=\"5\" align=\"left\">
                    ".$Paciente[$Cedula]." - $Cedula
                    </td>
                    <td align='right' bgcolor='#e5e5e5'>
                    $AdtdC $AdtdNC
                    </td>
                </tr>
                <tr bgcolor =\"#e5e5e5\">
                    <td><input type=\"checkbox\" title=\"Habilitar/Deshabilitar el despacho para este paciente\"
                               name=\"Hab_Des[$Cedula]\" id =\"Hab_Des[$Cedula]\"
                               onclick=\"H_D(this,'$Cedula',".strlen($Cedula).")\"/>
                    </td><td>Fecha</td><td>Medicamento</td><td>Cantidad</td><td>Existencias</td><td>Descartar</td>
                </tr>";
                $C = 0;
                while(list($Autoid,$Plantilla2) = each($Plantilla1))
                {
                    if(!$PendientesDespacho[$Autoid])
                    {
                        $PendientesDespacho[$Autoid] = $TotalOrden[$Autoid] - $TotalDespacho[$Autoid];
                        //echo $Medicamento[$Autoid]."Pendientes:".$PendientesDespacho[$Autoid]."__Despachados:".$TotalDespacho[$Autoid]."
                        //    __En Plantilla:".$TotalOrden[$Autoid]."<br><br>";
                        ?>
                        <input type="hidden" name="PendientesDespacho[<? echo $Autoid?>]" value="<? echo $PendientesDespacho[$Autoid]?>" />
                        <?
                    }
                    $CantFinal=$VrSaldoIni[$Autoid][0]+$VrEntradas[$Autoid][0]-$VrSalidas[$Autoid][0]+$VrDevoluciones[$Autoid][0];
                    while(list($NumOrden,$Plantilla3) = each($Plantilla2))
                    {
                        while(list($IdEscritura,$Plantilla4) = each($Plantilla3))
                        {
                            if(($Plantilla4[0]-$Despachados[$Cedula][$Autoid][$NumOrden][$IdEscritura])>0 && $Plantilla4[4]=="AC")
                            {
                                if($Redondear[$AlmacenPpal])
                                {
                                    $Plantilla4[0] = ceil($Plantilla4[0]);
                                }
                                if(!$Lote[$Autoid])
                                {
                                    $BotonAlerta = "<img src='/Imgs/b_tblops.png' title='Revisar Inconsistencias' style=\"cursor:hand\"
                                    onclick=\"if(confirm('Desea revisar inconsistencias para ".$Medicamento[$Autoid]."?'))
                                    {RevisarInconsistencias('$Autoid','$AlmacenPpal','".$Medicamento[$Autoid]."',event)}\"\" />";
                                    $ColorAlerta = "#FF0000";
                                    $TdAlerta = " bgcolor=#FF0000 syle='color:white; font-weight:bold'
                                        title='INCONSISTENCIAS EN CONFIGURACION DE CUMS, PRESIONE LA HERRAMIENTA PARA REVISAR'";
                                }
                                else
                                {
                                    $BotonAlerta = "";
                                    $ColorAlerta = "";
                                    $TdAlerta = "";
                                }
                                $TABLA = $TABLA."
                                <tr onMouseOver=\"this.bgColor='#AAD4FF'\" onMouseOut=\"this.bgColor='$ColorAlerta'\"
                                    title =\"Orden Por:$Plantilla4[2]\">
                                    <td $TdAlerta>
                                        <input type=\"checkbox\" value=\"$Plantilla4[0]\"
                                               name=\"Despacha[$Cedula][$Autoid][$NumOrden][$IdEscritura]\"
                                               id=\"Despacha[$Cedula][$Autoid][$NumOrden][$IdEscritura]\"/>
                                    </td>
                                    <td $TdAlerta>$Plantilla4[1]</td>
                                    <td $TdAlerta>".$Medicamento[$Autoid]." Via: $Plantilla4[5] - $Plantilla4[6]
                                        <br><font color=\"blue\">$Plantilla4[7]</font>
                                        <br><font color=\"red\"><i>$Plantilla4[8]</i></font>
                                    </td>
                                    <td align=\"right\" $TdAlerta>
                                        <input type=\"text\" size=\"1\" maxleght=\"3\" style=\"text-align:right\"
                                        onKeyUp=\"xNumero(this)\" onKeyDown=\"xNumero(this)\"
                                        onBlur=\"campoNumero(this);
                                        ValidarCant('C$Cedula$Autoid$NumOrden$IdEscritura','O$Cedula$Autoid$NumOrden$IdEscritura')\"
                                        name=Cantidad[$Cedula][$Autoid][$NumOrden][$IdEscritura]
                                        value=\"".($Plantilla4[0]-$Despachados[$Cedula][$Autoid][$NumOrden][$IdEscritura])."\"
                                        id=\"C$Cedula$Autoid$NumOrden$IdEscritura\">
                                        <input type=\"hidden\"
                                        name=CantidadOrden[$Cedula][$Autoid][$NumOrden][$IdEscritura] 
                                        value=\"".($Plantilla4[0]-$Despachados[$Cedula][$Autoid][$NumOrden][$IdEscritura])."\"
                                        id=\"O$Cedula$Autoid$NumOrden$IdEscritura\">
                                    </td>
                                    <td align=\"right\" $TdAlerta>".number_format($CantFinal,2)."</td>
                                    <td align=\"right\" $TdAlerta>
                                        <button type=\"submit\" name=\"Descarta[$Cedula][$Autoid][$NumOrden][$IdEscritura]\"
                                        style=\"border=0\" title=\"Descartar\">
                                            <img src=\"/Imgs/b_drop.png\" />
                                        </button>
                                        $BotonAlerta
                                    </td>
                                </tr>";
                                $C++;
                            }

                        }
                    }
                }
                $TABLA = $TABLA."</table>";
                if($C>0)
                {
                    echo $TABLA;
                }
            //}
        }
    }
    ?>
<div id='Mensaje' name='Mensaje' style='position: absolute; width:auto;
     height:auto; display: none; background:#FFFFFF;'>
<input type="text" name="MensajeMsj" value="Mensaje de prueba" readonly
       style=" color:#666699; border-style: ridge;
       background:none;
       font:normal small-caps 12px Tahoma;
       font-weight:bold;
       text-align:center;
       width:150px; border-color:#666699"/>
</div>
</form>
<?
    $_SESSION["TarjetaMeds"]=$TotalPlantilla;
?>
</body>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>