<?php
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND=getdate();
    //UM:27-04-2011
    if($ND[mon]<10){$Mes = "0".$ND[mon];}else{$Mes = $ND[mon];}
    if($ND[mday]<10){$Dia = "0".$ND[mday];}else{$Dia = $ND[mday];}
    $Anio = $ND[year];
    $Fecha = "$ND[year]-$Mes-$Dia";
    $Hoy = $ND[wday];
    $AmbitoAux = explode("-",$Ambito);
    $cons = "Select Cedula,AutoId,Hora,NumServicio,NumOrden,IdEscritura,date(FechaCre),SUM(Cantidad)
    from Salud.NoRegistroMedicamentos Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
    and Cedula = '$Ced'
    Group by Cedula,AutoId,Hora,NumServicio,NumOrden,IdEscritura,date(FechaCre)";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $NoRegistrados[$fila[0]][$fila[1]][$fila[4]][$fila[5]][$fila[2]] = array($fila[6],$fila[7]);//echo "LALA";
    }
    //print_r($NoRegistrados);
    $cons = "Select Paciente,AutoId,Hora,Tipo,Cantidad,NumOrden,IdEscritura,Fecha
    from Salud.HoraCantidadxMedicamento Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
    and Paciente = '$Ced' and Estado='AC' order by Hora,IdEscritura desc";
    $res = ExQuery($cons);
    while($fila=ExFetch($res))
    {
        //echo "".$NoRegistrados[$fila[0]][$fila[1]][$fila[5]][$fila[6]][$fila[2]][0]."==$ND[year]-$Mes-$Dia<br>";
        if($NoRegistrados[$fila[0]][$fila[1]][$fila[5]][$fila[6]][$fila[2]][0]=="$ND[year]-$Mes-$Dia")
            {$fila[4]=$fila[4]-$NoRegistrados[$fila[0]][$fila[1]][$fila[5]][$fila[6]][$fila[2]][1];}
        $Medicamento[$fila[0]][$fila[1]][$fila[5]][$fila[6]][$fila[2]] = array($fila[4],$fila[3],$fila[7]);
		// medicamento: paciente,autoid,numorden,idescritura,hora,cantidad,tipo,fecha
        //echo "$fila[0]\\$fila[1]\\$fila[2]\\$fila[3]\\cantidad: $fila[4]\\$fila[5]\\$fila[6]<br>";
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    if($CantidadReg)
    {
        if(!$NumOrden){$NumOrden="NULL";}
        if(!$IdEscritura){$IdEscritura = "NULL";}
        $cons="insert into salud.registromedicamentos
        (compania,almacenppal,numservicio,cedula,autoid,usuariocre,fechacre,hora,cantidad,Tipo,NumOrden,IdEscritura)
        values ('$Compania[0]','$AlmacenPpal',$NumSer,'$Ced',$AutoId,'$usuario[1]',
        '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$Hora,$CantidadReg,'$Tipo',$NumOrden,$IdEscritura)";
        $res = ExQuery($cons); echo ExError();
    }
    ////////////////////////////MEDICAMENTOS REGISTRADOS///////////////////////////////////////////////
    $cons = "Select Cedula,AutoId,FechaCre,Hora,Cantidad,Tipo,NumOrden,IdEscritura
    from Salud.RegistroMedicamentos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
    AND Cedula = '$Ced'";
    $res = ExQuery($cons);
    while($fila=ExFetch($res))
    {
        $FechaDesp = substr($fila[2],0,10);
        if($FechaDesp == $Fecha)
        {
            $TotRegistro[$fila[0]][$fila[1]] += $fila[4];
            $Registro[$fila[0]][$fila[1]][$fila[6]][$fila[7]][$fila[3]] = 1;
        }
    }

    $cons = "Select AutoId,NombreProd1,UnidadMedida,Presentacion,Grupo
    from Consumo.CodProductos
    Where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Anio=$Anio and Estado='AC'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
            $NombreProd[$fila[0]] = "$fila[1] $fila[2] $fila[3]";
            $Grupo[$fila[0]] = $fila[4];
    }
    $cons = "Select Identificacion,primApe,SegApe,PrimNom,SegNom
    from Central.Terceros
    Where Compania='$Compania[0]'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
            $NombreTercero[$fila[0]] = "$fila[1] $fila[2] $fila[3] $fila[4]";
    }
    
    // 2014-03-20
    // Se cambia $Hoy[wday] por $Hoy
    switch($Hoy)
    {
        case 0: $adconsDIA = " and domingo = 1 ";break;
        case 1: $adconsDIA = " and lunes = 1 ";break;
        case 2: $adconsDIA = " and martes = 1 ";break;
        case 3: $adconsDIA = " and miercoles = 1 ";break;
        case 4: $adconsDIA = " and jueves = 1 ";break;
        case 5: $adconsDIA = " and viernes = 1 ";break;
        case 6: $adconsDIA = " and sabado = 1 ";break;
    }
    if($Pabellon)
    {
        $adconsPABELLON = " and Pabellon = '$Pabellon' ";
    }

    //Indefinidos////////////////////////////////////////////////////
    /*
    $cons = "Select cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos
    Where PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    $adconsDIA and fechaini <= '$Fecha' and fechafin is null
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $adconsPABELLON and Ambito = '$AmbitoAux[0]' and PacientesxPabellones.NumServicio = PlantillaMedicamentos.NumServicio and
    PacientesxPabellones.NumServicio = Servicios.NumServicio and PacientesxPabellones.Cedula = Servicios.Cedula and
    PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and PacientesxPabellones.Estado = 'AC' and PacientesxPabellones.FechaI<='$Fecha'
    and PacientesxPabellones.Compania = '$Compania[0]'
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC' and PlantillaMedicamentos.CedPaciente='$Ced'";*/
    
    // 2014-03-20
    // Para cruzar con consumo.movimiento y mostrar solo medicamentos despachados
    $cons = "Select cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos,consumo.movimiento
    Where movimiento.autoid=plantillamedicamentos.autoidprod AND movimiento.fecha = '$ND[year]-$ND[mon]-$ND[mday]' AND movimiento.numservicio=plantillamedicamentos.numservicio and movimiento.comprobante='Salidas por Plantilla' AND PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    $adconsDIA and fechaini <= '$Fecha' and fechafin is null
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $adconsPABELLON and Ambito = '$AmbitoAux[0]' and PacientesxPabellones.NumServicio = PlantillaMedicamentos.NumServicio and
    PacientesxPabellones.NumServicio = Servicios.NumServicio and PacientesxPabellones.Cedula = Servicios.Cedula and
    PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and PacientesxPabellones.Estado = 'AC' and PacientesxPabellones.FechaI<='$Fecha'
    and PacientesxPabellones.Compania = '$Compania[0]'
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC' and PlantillaMedicamentos.CedPaciente='$Ced'";
    $res = ExQuery($cons);
    //echo $cons;
    while($fila = ExFetch($res))
    {
        //echo "Indefinidos:$fila[0]\\$fila[1]\\$fila[2]\\$fila[3]\\$fila[4]\\$fila[5]\\<br>";
        $Plantilla[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = array($fila[2]);
    }
    /////////////////////////////////////////////////////////////////////////
    //Definidos////////////////////////////////////////////////////
    /*$cons = "Select cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos
    Where PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    $adconsDIA and fechaini <= '$Fecha' and fechafin >= '$Fecha'
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $adconsPABELLON and Ambito = '$AmbitoAux[0]' and PacientesxPabellones.NumServicio = PlantillaMedicamentos.NumServicio and
    PacientesxPabellones.NumServicio = Servicios.NumServicio and PacientesxPabellones.Cedula = Servicios.Cedula and
    PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and PacientesxPabellones.Estado = 'AC' and PacientesxPabellones.FechaI<='$Fecha'
    and PacientesxPabellones.Compania = '$Compania[0]'
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC' and PlantillaMedicamentos.CedPaciente='$Ced'";*/
    
    // 2014-03-20
    // Para cruzar con consumo.movimiento y mostrar solo medicamentos despachados
    $cons = "Select cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos,consumo.movimiento
    Where movimiento.autoid=plantillamedicamentos.autoidprod AND movimiento.fecha = '$ND[year]-$ND[mon]-$ND[mday]' AND movimiento.numservicio=plantillamedicamentos.numservicio and movimiento.comprobante='Salidas por Plantilla' AND PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    $adconsDIA and fechaini <= '$Fecha' and fechafin >= '$Fecha'
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $adconsPABELLON and Ambito = '$AmbitoAux[0]' and PacientesxPabellones.NumServicio = PlantillaMedicamentos.NumServicio and
    PacientesxPabellones.NumServicio = Servicios.NumServicio and PacientesxPabellones.Cedula = Servicios.Cedula and
    PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and PacientesxPabellones.Estado = 'AC' and PacientesxPabellones.FechaI<='$Fecha'
    and PacientesxPabellones.Compania = '$Compania[0]'
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC' and PlantillaMedicamentos.CedPaciente='$Ced'";
    $res = ExQuery($cons);
    //echo $cons;
    while($fila = ExFetch($res))
    {
        $Plantilla[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = array($fila[2]);
        //echo "Definidos:$fila[0]\\$fila[1]\\$fila[2]\\$fila[3]\\$fila[4]\\$fila[5]\\<br>";
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Calendario////////////////////////////////////////////////////////
    /*$cons = "Select PlantillaMedicamentos.cedpaciente,PlantillaMedicamentos.AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos,salud.CalendarioxMedicamento
    Where PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $adconsPABELLON and Ambito = '$AmbitoAux[0]' and PacientesxPabellones.NumServicio = PlantillaMedicamentos.NumServicio and
    PacientesxPabellones.NumServicio = Servicios.NumServicio and PacientesxPabellones.Cedula = Servicios.Cedula and
    PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and PacientesxPabellones.Estado = 'AC' and PacientesxPabellones.FechaI<='$Fecha'
    and PacientesxPabellones.Compania = '$Compania[0]'
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC'
    and CalendarioxMedicamento.Compania='$Compania[0]' and CalendarioxMedicamento.AlmacenPpal='$AlmacenPpal'
    and PlantillaMedicamentos.AutoIdProd = CalendarioxMedicamento.AutoIdProd and PlantillaMedicamentos.CedPaciente = CalendarioxMedicamento.CedPaciente
    and CodProductos.AutoId = CalendarioxMedicamento.AutoIdProd and CalendarioxMedicamento.Fecha = '$Fecha' and PlantillaMedicamentos.CedPaciente='$Ced'";*/
    
    // 2014-03-20
    // Para cruzar con consumo.movimiento y mostrar solo medicamentos despachados
    $cons = "Select PlantillaMedicamentos.cedpaciente,PlantillaMedicamentos.AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos,salud.CalendarioxMedicamento,consumo.movimiento
    Where movimiento.autoid=plantillamedicamentos.autoidprod AND movimiento.fecha = '$ND[year]-$ND[mon]-$ND[mday]' AND movimiento.numservicio=plantillamedicamentos.numservicio and movimiento.comprobante='Salidas por Plantilla' AND PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $adconsPABELLON and Ambito = '$AmbitoAux[0]' and PacientesxPabellones.NumServicio = PlantillaMedicamentos.NumServicio and
    PacientesxPabellones.NumServicio = Servicios.NumServicio and PacientesxPabellones.Cedula = Servicios.Cedula and
    PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and PacientesxPabellones.Estado = 'AC' and PacientesxPabellones.FechaI<='$Fecha'
    and PacientesxPabellones.Compania = '$Compania[0]'
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC'
    and CalendarioxMedicamento.Compania='$Compania[0]' and CalendarioxMedicamento.AlmacenPpal='$AlmacenPpal'
    and PlantillaMedicamentos.AutoIdProd = CalendarioxMedicamento.AutoIdProd and PlantillaMedicamentos.CedPaciente = CalendarioxMedicamento.CedPaciente
    and CodProductos.AutoId = CalendarioxMedicamento.AutoIdProd and CalendarioxMedicamento.Fecha = '$Fecha' and PlantillaMedicamentos.CedPaciente='$Ced'";
    //echo $cons;
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $Plantilla[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = array($fila[2]);
        //echo "Calendario:$fila[0]\\$fila[1]\\$fila[2]\\$fila[3]\\$fila[4]\\$fila[5]\\<br>";
    }
    ////////////////////////////////////////////////
    /////Urgentes
    $cons = "Select * from Salud.Ambitos Where Ambito = '$AmbitoAux[0]' and Urgencias = 1";
    $res = ExQuery($cons);
    if(ExNumRows($res)==0)
    {
        $Adcons1 = "$adconsPABELLON and Ambito = '$AmbitoAux[0]' and PacientesxPabellones.NumServicio = PlantillaMedicamentos.NumServicio and
        PacientesxPabellones.NumServicio = Servicios.NumServicio and PacientesxPabellones.Cedula = Servicios.Cedula and
        PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and PacientesxPabellones.Estado = 'AC' and PacientesxPabellones.FechaI<='$Fecha'
        and PacientesxPabellones.Compania = '$Compania[0]'";
    }
    /*$cons = "Select cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura,dosisunica,posologia,FechaIni
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos
    Where PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    and TipoMedicamento='Medicamento Urgente' and fechaformula>='$Fecha'
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $Adcons1
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC' and PlantillaMedicamentos.CedPaciente='$Ced'
	group by cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura,dosisunica,posologia,FechaIni
	Order by PlantillaMedicamentos.IdEscritura desc";*/
    
    // 2014-03-20
    // Para cruzar con consumo.movimiento y mostrar solo medicamentos despachados
    $cons = "Select cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura,dosisunica,posologia,FechaIni
    from salud.plantillamedicamentos,Salud.Servicios,Salud.PacientesxPabellones,Consumo.CodProductos,consumo.movimiento
    Where movimiento.autoid=plantillamedicamentos.autoidprod AND movimiento.fecha = '$ND[year]-$ND[mon]-$ND[mday]' AND movimiento.numservicio=plantillamedicamentos.numservicio and movimiento.comprobante='Salidas Urgentes' AND PlantillaMedicamentos.AlmacenPpal = '$AlmacenPpal' and PlantillaMedicamentos.Compania='$Compania[0]' and PlantillaMedicamentos.Estado = 'AC'
    and TipoMedicamento='Medicamento Urgente' and fechaformula>='$Fecha'
    and PlantillaMedicamentos.NumServicio = Servicios.NumServicio and Servicios.Cedula = PlantillaMedicamentos.CedPaciente and Servicios.Estado = 'AC'
    and TipoServicio = '$AmbitoAux[0]' and Servicios.Compania='$Compania[0]'
    $Adcons1
    and CodProductos.AlmacenPpal='$AlmacenPpal' and CodProductos.AutoId = PlantillaMedicamentos.AutoIdProd and CodProductos.Compania='$Compania[0]'
    and CodProductos.Estado='AC' and PlantillaMedicamentos.CedPaciente='$Ced'
	group by cedpaciente,AutoIdProd,PlantillaMedicamentos.NumServicio,
    CantDiaria,PlantillaMedicamentos.NumOrden,PlantillaMedicamentos.IdEscritura,dosisunica,posologia,FechaIni
	Order by PlantillaMedicamentos.IdEscritura desc";
    $res = ExQuery($cons);
    //echo $cons;
    while($fila = ExFetch($res))
    {
//        echo "Urgentes:$fila[0]\\$fila[1]\\$fila[2]\\$fila[3]\\$fila[4]\\$fila[5]\\**$fila[6]**$fila[7]<br>";
        if($fila[6] || $fila[8]=="$Fecha")
        {
			// plantilla: cedula,autoid,numorden,idescritura,numservicio,dosisunica,posologia
            $Plantilla[$fila[0]][$fila[1]][$fila[4]][$fila[5]] = array($fila[2],$fila[6],$fila[7]);
			//echo "Urgentes:$fila[0]\\$fila[1]\\$fila[2]\\$fila[3]\\$fila[4]\\$fila[5]\\**$fila[6]**$fila[7]<br>";
        }
    }
    ?>
<script language="javascript">
    function AbrirMedNR()
    {
        frames.FrameOpener.location.href='MedsNR.php?DatNameSID=<? echo $DatNameSID;?>&Anio=<? echo $Anio?>&AutoId=<? echo $AutoId?>&AlmacenPpal=<? echo $AlmacenPpal?>';
        document.getElementById('FrameOpener').style.position='absolute';
        document.getElementById('FrameOpener').style.top='80px';
        document.getElementById('FrameOpener').style.left='180px';
        document.getElementById('FrameOpener').style.display='';
        document.getElementById('FrameOpener').style.width='700';
        document.getElementById('FrameOpener').style.height='350';
    }
    function AbrirNoReg(AutoId,Hora,Cantidad,NumServicio,NumOrden,IdEscritura,Medicamento)
    {
        frames.FrameOpener.location.href='MedsNoReg.php?Medicamento='+Medicamento+'&Hora='+Hora+'&Cantidad='+Cantidad+'&NumServicio='+NumServicio+'&NumOrden='+NumOrden+'&IdEscritura='+IdEscritura+'&DatNameSID=<? echo $DatNameSID;?>&Anio=<? echo $Anio?>&AutoId='+AutoId+'&AlmacenPpal=<? echo $AlmacenPpal?>';
        document.getElementById('FrameOpener').style.position='absolute';
        document.getElementById('FrameOpener').style.top='80px';
        document.getElementById('FrameOpener').style.left='180px';
        document.getElementById('FrameOpener').style.display='';
        document.getElementById('FrameOpener').style.width='350';
        document.getElementById('FrameOpener').style.height='220';
    }
</script>
<body background="/Imgs/Fondo.jpg">
    <?php
    if($Plantilla)
    {
        while(list($Identificacion,$Plantilla1)=each($Plantilla))
        {
            //echo "factura: ".$Identificacion." y nombre comprobante contable: ".$Plantilla1[0][0][0][0][0][0][0]."</BR>";
            //$consDesp = "SELECT * FROM Consumo.Movimiento WHERE Cedula = '$Paciente[1]' AND fecha = '$ND[year]-$ND[mon]-$ND[mday]' AND UPPER(comprobante) = 'SALIDAS POR PLANTILLA'";
            //$resDesp = ExQuery($consDesp);
            //if(ExNumRows($resDesp) > 0){
                        
            ?>
            
            <table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
                <tr bgcolor="#e5e5e5">
                    <td colspan="25" align="center" style=" font-weight: bold">
                        <font color="blue">REGISTRO DE MEDICAMENTOS</font><br><br>
                        <?php echo $NombreTercero[$Ced]?>
                    </td>
                </tr>
            <?php
            while(list($AutoId,$Plantilla2) = each($Plantilla1))
            {
                ?>
                <tr><td align="center"><?php echo $NombreProd[$AutoId]?>
                    </td><td>
                <table bordercolor="#f5f5f5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center"><tr>
                <?php
                while(list($NumOrden,$Plantilla3) = each($Plantilla2))
                {
                    while(list($IdEscritura,$Plantilla4)=each($Plantilla3))
                    {
                       //echo "$NumOrden--$IdEscritura<br>";
                       //echo "????".$Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura].'<br>';
					   //echo "$Identificacion \\ $AutoId\\$NumOrden\\$IdEscritura<br>";
                       if($Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura])
                       {
                           while(list($Hora,$Medicamento1)= each($Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura]))
                           {
                               unset($AdTag);
                               if($Medicamento1[1]=="U"){ $AdTag=" Title='Medicamento URGENTE!' style='color:red; font-weight:bold'";}
                               else{$AdTag=" Title='Medicamento programado' style='color:blue; font-weight:bold'";}
                               if($Plantilla4[1] || $Medicamento1[2]=="$Fecha" || $Medicamento1[1]!="U")
                               {
                                    echo "<td $AdTag align='center' width='60px'> $Hora:00 </td>";
                               }
                           }
                       }
                    }
                }
                reset($Plantilla2);
                reset($Medicamento);
                ?>
                    <td>Total Registrado</td>
                </tr>
                <tr>
                <?php
                while(list($NumOrden,$P3)=each($Plantilla2))
                {
                    while(list($IdEscritura,$P4)=each($P3))
                    {
                        if($Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura])
                        {
							// medicamento: paciente,autoid,numorden,idescritura,hora,cantidad,tipo,fecha
							//echo "$fila[0]\\$fila[1]\\$fila[2]\\$fila[3]\\cantidad: $fila[4]\\$fila[5]\\$fila[6]<br>";
							
                            reset($Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura]);
                            while(list($Hora,$M1)=each($Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura]))
                            {
								//echo "$Identificacion \\ $AutoId\\$NumOrden\\$IdEscritura<br>";
								
                                if($P4[1])
                                {
									 if(!$Registro[$Identificacion][$AutoId][$NumOrden][$IdEscritura][$Hora] && $M1[0]>0)
                                        {
                                                                             if($usuario[3]!="JEFE DE ENFERMERIA"){
                                                                                $fecha_actual = date("Y-m-d H:i:s");
                                                                                $time_actual = strtotime($fecha_actual);

                                                                                // fecha para crear el intervalo ya que de la bd solo sale la hora
                                                                                $fechass = date("Y-m-d");
                                                                                $timeac = strtotime($fechass);

                                                                                $timean = strtotime($fechass." ".$Hora.":00:00"." -15 minutes");
                                                                                $timede = strtotime($fechass." ".$Hora.":00:00"." +60 minutes");

                                                                                // es posible registrar el medicamento en el intervalo de 15 minutos antes y 60 después de la hora definida
                                                                                $inhabilitaboton = " disabled";
                                                                                if($time_actual>=$timean && $time_actual<=$timede){
                                                                                        $inhabilitaboton = "";
                                                                                }
                                                                                
                                                                                //echo $time_actual." ".$timean." ".$timede;
                                                                            }

                                                                            if($usuario[3]=="JEFE DE ENFERMERIA"){
                                                                                $fecha_actual = date("Y-m-d H:i:s");
                                                                                $time_actual = strtotime($fecha_actual." +60 minutes");
                                                                                $time_actual24 = strtotime($fecha_actual." -24 hours");

                                                                                // fecha para crear el intervalo ya que de la bd solo sale la hora
                                                                                $fechass = date("Y-m-d");
                                                                                $timemed = strtotime($fechass." ".$Hora.":00:00");

                                                                                // es posible registrar el medicamento en el intervalo de 15 minutos antes y 60 después de la hora definida
                                                                                $inhabilitaboton = " disabled";
                                                                                if($timemed>=$timeactual24 && $timemed<=$time_actual){
                                                                                        $inhabilitaboton = "";
                                                                                }
                                                                            }
										?>
										<td align="center">
										<button style=" width: 60px; height: 50px" title="<?php echo "($P4[2]):REGISTRAR SIMINISTRO"?>"
										name="CantidadReg[<?php echo $AutoId?>][<?php echo $NumOrden?>][<?php echo $IdEscritura?>][<?php echo $Hora?>][<?php echo $M1[0]?>]"
										onClick="location.href='NewRegistroMedicamentos.php?OrigenHC=<?php echo $OrigenHC?>&DatNameSID=<?php echo $DatNameSID?>&Ced=<?php echo $Ced?>&AlmacenPpal=<?php echo $AlmacenPpal?>&AutoId=<?php echo $AutoId?>&NumSer=<?php echo $P4[0]?>&Ambito=<?php echo $Ambito?>&Pabellon=<?php echo $Pabellon?>&Hora=<?php echo $Hora?>&CantidadReg=<?php echo $M1[0]?>&Tipo=<?php echo $M1[1]?>&NumOrden=<?php echo $NumOrden?>&IdEscritura=<?php echo $IdEscritura?>'" <?php echo $inhabilitaboton; ?>>
											<img src="/Imgs/b_check.png" />
										</button>
										</td>
										<?php
									}else
                                        {
                                            ?>
                                            <td align="center"><font size="5" style=" font-weight: bold">
                                                <?php echo $M1[0];?>
                                            </font></td>
                                            <?php
                                        }
                                }
                                else
                                {
                                    if($Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura][$Hora][2]=="$Fecha"
                                       || $Medicamento[$Identificacion][$AutoId][$NumOrden][$IdEscritura][$Hora][1]!="U")
                                    {
                                        //echo "$Registro--$Identificacion--$AutoId--$NumOrden--$IdEscritura--$Hora.";
                                        if(!$Registro[$Identificacion][$AutoId][$NumOrden][$IdEscritura][$Hora] && $M1[0]>0)
                                        {
                                            //$fecha_actual = date("H");
                                            //$time_actual = strtotime($fecha_actual);
                                            
                                            //$inhabilitaboton = "";
                                            /*if($fecha_actual!=$Hora){
                                                $inhabilitaboton = " disabled";
                                            }*/
                                            
                                            if($usuario[3]!="JEFE DE ENFERMERIA"){
                                                $fecha_actual = date("Y-m-d H:i:s");
                                                $time_actual = strtotime($fecha_actual);

                                                // fecha para crear el intervalo ya que de la bd solo sale la hora
                                                $fechass = date("Y-m-d");
                                                $timeac = strtotime($fechass);

                                                $timean = strtotime($fechass." ".$Hora.":00:00"." -15 minutes");
                                                $timede = strtotime($fechass." ".$Hora.":00:00"." +60 minutes");

                                                // es posible registrar el medicamento en el intervalo de 15 minutos antes y 60 después de la hora definida
                                                $inhabilitaboton = " disabled";
                                                if($time_actual>=$timean && $time_actual<=$timede){
                                                        $inhabilitaboton = "";
                                                }
                                            }
                                            
                                            if($usuario[3]=="JEFE DE ENFERMERIA"){
                                                $fecha_actual = date("Y-m-d H:i:s");
                                                $time_actual = strtotime($fecha_actual." +60 minutes");
                                                $time_actual24 = strtotime($fecha_actual." -24 hours");

                                                // fecha para crear el intervalo ya que de la bd solo sale la hora
                                                $fechass = date("Y-m-d");
                                                $timemed = strtotime($fechass." ".$Hora.":00:00");

                                                // es posible registrar el medicamento en el intervalo de 15 minutos antes y 60 después de la hora definida
                                                $inhabilitaboton = " disabled";
                                                if($timemed>=$timeactual24 && $timemed<=$time_actual){
                                                        $inhabilitaboton = "";
                                                }
                                            }
                                            
                                            ?>
                                            <td align="center">
                                            <button style=" width: 60px; height: 50px"
                                            name="CantidadReg[<?php echo $AutoId?>][<?php echo $NumOrden?>][<?php echo $IdEscritura?>][<?php echo $Hora?>][<?php echo $M1[0]?>] "
                                            onClick="location.href='NewRegistroMedicamentos.php?OrigenHC=<?echo $OrigenHC?>&DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&AlmacenPpal=<? echo $AlmacenPpal?>&AutoId=<? echo $AutoId?>&NumSer=<? echo $P4[0]?>&Ambito=<? echo $Ambito?>&Pabellon=<? echo $Pabellon?>&Hora=<? echo $Hora?>&CantidadReg=<? echo $M1[0]?>&Tipo=<? echo $M1[1]?>&NumOrden=<? echo $NumOrden?>&IdEscritura=<? echo $IdEscritura?>'" <?php echo $inhabilitaboton; ?>>
                                            <?php
                                            echo $M1[0];
                                            ?>
                                            </button>
                                                <br>
                                                <a title="NO REGISTRAR o REGISTRAR PARCIALMENTE" href="#" style="<?php if($inhabilitaboton!=""){echo "visibility:hidden;";}; ?>"
                                                   onclick="AbrirNoReg('<?echo $AutoId?>','<?echo $Hora?>','<?echo $M1[0]?>','<?echo $P4[0]?>','<?echo $NumOrden?>','<?echo $IdEscritura?>','<?echo $NombreProd[$AutoId]?>')">
                                                No Registrar
                                                </a>
                                            </td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td align="center"><font size="5" style=" font-weight: bold">
                                                <?php echo $M1[0];?>
                                            </font></td>
                                            <?php
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
                <td>
                    <input type="text" name="DespachadoMov[<? echo $Identificacion?>][<? echo $AutoId?>] " readonly
                    title="Este Registro" style=" font-size: 20;"
                    value="<? echo $TotRegistro[$Identificacion][$AutoId]?>" style="width:60px; text-align: right" size="4"/>
                </td>
                </tr>
                </table></td></tr>
                <?php
            }
            ?>
            </table>
            <?php
        //}
        }
    }
?>
<div align="center">
    <?php
    if($OrigenHC)
    {
        ?>
        <center>
            <input type="button" name="MedNOR" value="Medicamento NO Relacionado"
                   onclick="AbrirMedNR()"/>
        </center>
        <?
    }
    ?>
    <input type="button" value="Regresar"
    onClick="
    <?if($OrigenHC){echo "location.href='/HistoriaClinica/Formatos_Fijos/HojaMeds.php?DatNameSID=$DatNameSID'";}
    else{echo "location.href='RegistroMedicamentos.php?DatNameSID=$DatNameSID&AlmacenPpal=$AlmacenPpal&Pabellon=$Pabellon&Ambito=$Ambito#$Ced'";}?>
    ">
</div>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>