<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND=getdate();
    $AutoId = $Medicamento;
    if($AutoId){$AdConsAutoId=" and CodProductos.Autoid = $AutoId";$AdConsAutoId2 = " and AutoId = $AutoId ";}
    $FechaIni="$Anio-$MesIni-$DiaIni";
    $FechaFin="$Anio-$MesFin-$DiaFin";
    if($Origen)
    {
        $DatosFecha = explode("-",$Fecha);
        $Anio = $DatosFecha[0]; $MesIni = $DatosFecha[1]; $DiaIni = $DatosFecha[1];
        $FechaIni = $Fecha;
        $FechaFin = $Fecha;
        if($Pacientes)
        {
            $Pacientes = $Pacientes."|";
            $Pacientes = str_replace("|","'",$Pacientes);
            $AdconsPaciente1 = " and Servicios.Cedula in ($Pacientes)";
            $AdconsPaciente2 = " and Plantillamedicamentos.CedPaciente in ($Pacientes)";
            $AdconsPaciente3 = " and Movimiento.Cedula in ($Pacientes)";
        }
    }
    //////////////////////////DATOS DEL PRESTADOR DEL SERVICIO////////////////////////////
    $cons = "Select Municipios.Municipio,Departamentos.Departamento,CodSGSSS
    from central.Compania,Central.Departamentos,Central.Municipios
    Where Nombre='$Compania[0]' and Departamentos.Codigo = Compania.Departamento
    and Municipios.CodMpo = Compania.Municipio and Municipios.Departamento = Departamentos.Codigo";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Municipio = $fila[0];$Departamento = $fila[1]; $CodSGSSS = $fila[2];
    //////////////////////////FIN DATOS DEL PRESTADOR DEL SERVICIO////////////////////////////
    /////////////////////////CONSULTA PARA VER LA PLANTILLA DE MEDICAMENTOS////////////////
    $cons = "Select CedPaciente,AutoidProd,Detalle,FechaIni,Fechafin,ViaSuministro,Posologia,CantDiaria
    from Salud.PlantillaMedicamentos,Consumo.CodProductos Where Autoid=AutoidProd and Control='Si'
    $AdConsAutoId2 $AdconsPaciente2";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        //$POS = explode("-",$fila[6]);
        //unset($Posologia);
        //foreach($POS as $P){$Posologia = $Posologia."<br>$P";}
        if($fila[3]>=$FechaIni){$F_I = $fila[3];}else{$F_I = $FechaIni;}
        if($fila[4])
        {
            if($fila[4]<=$FechaFin){$F_F = $fila[4];}else{$F_F = $FechaFin;}
            $AdConsFechaFin = " and date(Fecha) <= '$F_F'";
        }
        else{$F_F = $FechaFin;}
        $cons1 = "Select NumServicio,NumOrden,IdEscritura from Salud.OrdenesMedicas Where Compania='$Compania[0]'
        and Cedula = '$fila[0]' and Detalle = '$fila[2]' and
        (date(Fecha)>='$F_F'or date(Fecha)<= '$F_F')";//echo $cons1;OK!!
        $res1 = ExQuery($cons1);
        while($fila1 = ExFetch($res1))
        {
            $Prescripcion[$fila[0]][$fila[1]][$fila1[0]][$fila1[1]][$fila1[2]] = array($fila[5],$fila[6],$fila[7]);
            //echo "[$fila[0]][$fila[1]][$fila1[0]][$fila1[1]][$fila1[2]]=$fila[5],$fila[6],$fila[7]<br>";
        }
    }
    ////////////////////////FIN CONSULTA PARA VER LA PLANTILLA DE MEDICAMENTOS//////////
    //////////////////////////CONSULTA PARA DATOS DE LA ENTIDAD Y DATOS CLINICOS////////////////
    $cons = "Select Servicios.Cedula,PrimApe,Entidad,CodigoSGSSS,Regimen,Contrato,NoContrato,
    Usuarios.Nombre,Usuarios.Cedula,DxServ,Diagnostico,RM,Cargo
    From Salud.Servicios,Salud.PagadorxServicios,Central.Terceros,Central.Usuarios,Salud.CIE,Salud.Medicos
    Where Servicios.Compania='$Compania[0]' and PagadorxServicios.Compania='$Compania[0]'
    and PagadorxServicios.NumServicio = Servicios.NumServicio and Terceros.Identificacion = PagadorxServicios.Entidad
    and Usuarios.Usuario = Servicios.MedicoTte and Servicios.DxServ = Cie.Codigo
    and Medicos.Usuario = Usuarios.Usuario $AdconsPaciente1";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $Entidad[$fila[0]] = array($fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);
        $DatosClinicos[$fila[0]] = array($fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12]);
    }
    //////////////////////////FIN CONSULTA PARA DATOS DE LA ENTIDAD Y DATOS CLINICOS////////////////
    /////////////////CONSULTA PARA VERIFICAR DESPACHO DE MEDICAMENTOS//////////////////
    $cons = "Select Comprobante,Numero,NumeroControlados,NumServicio,NumOrden,IdEscritura,Cedula,
    PrimNom,SegNom,PrimApe,SegApe,TipoUsu,Nivelusu,Fecha,
    CodProductos.Codigo1,NombreProd1,UnidadMedida,Presentacion,Cantidad,CodProductos.Autoid
    from Consumo.Movimiento,Central.Terceros,Consumo.CodProductos
    Where Terceros.Compania = '$Compania[0]' and Movimiento.Compania='$Compania[0]'
    and Terceros.Identificacion = Movimiento.Cedula and Movimiento.Autoid = CodProductos.Autoid
    and CodProductos.Control = 'Si' and CodProductos.Estado = 'AC' and Movimiento.Estado = 'AC'
    and Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Anio = $Anio and Fechadespacho >= '$FechaIni'
    and Fechadespacho <='$FechaFin' and TipoComprobante = 'Salidas' and CodProductos.Anio = $Anio 
    $AdConsAutoId $AdconsPaciente3
    order by NombreProd1,UnidadMedida,Presentacion,Fecha,
    primNom,SegNom,PrimApe,SegApe,
    NumeroControlados,NumServicio,NumOrden,IdEscritura";
    ///////////////// FIN CONSULTA PARA VERIFICAR DESPACHO DE MEDICAMENTOS//////////////////
    $res = ExQuery($cons);
    ?>
<html>
<head>
    <title>Formulas para medicamentos de control</title>
    <style>
        P {page-break-after: always}
        @media print {
            div,a {display:none}
            .ver {display:block}
            .nover {display:none}
        }
    </style>
</head>
<body>
    <?
    while($fila = ExFetch($res))
    {
        if($MedicamentoAnt != "$fila[15] $fila[16] $fila[17]")
        {
            if($MedicamentoAnt)
            {
                ?>
                <div align="center" class="nover"
                     style=" background-color: <? echo $Estilo[1]?>; width:100%;
                     font-size: 20px; color: white; font-weight:bold;">
                     <? echo "FORMULAS: $CantFormulas, TOTAL ORDENADO: $CantFormulada, TOTAL DESPACHADO: $CantDespachada"; ?></div><br>
                <?
                unset($CantFormulas,$CantFormulada,$CantDespachada);
            }
            ?>
                <div align="center" class="nover"
                     style=" background-color: <? echo $Estilo[1]?>; width:100%;
                     font-size: 20px; color: white; font-weight:bold;"><? echo "$fila[15] $fila[16] $fila[17]"; ?></div>
            <?
        }
        ?>
        <!--TABLA PRINCIPAL-->
        <table border="0" bordercolor="#e5e5e5" width="70%"
               style='font : normal normal small-caps 11px Tahoma; border-style: outset; border-width: thin'>
            <tr valign="bottom" align="center">
                <td rowspan="3" valign="middle" width="10%">
                    <img src="/Imgs/Logo.jpg" width="80px" />
                </td>
                <td style =" font-size: 20px; font-weight: bold"><? echo $Compania[0]?></td>
                <td bgcolor="#e5e5e5" width="20%"><b><? echo $fila[0]?></b></td>
            </tr>
            <tr valign="middle" align="center">
                <td><? echo $Compania[1]?><br>
                <? echo $Compania[2]." TEL:".$Compania[3]?><br>
                <? echo "$Municipio - $Departamento";?><br>
                Codigo SGSSS <? echo $CodSGSSS;?></td>
                <td rowspan="2" style =" font-size: 15px; font-weight: bold" valign="top">
                    <?
                    if($fila[2]){echo "$fila[2]-$fila[3].$fila[4].$fila[5]";}
                    else{echo "$fila[1]";}
                    ?>
                </td>
            </tr>
            <tr>
                <td align="center"><b>Prescripcion de medicamentos de Control</b></td>
            </tr>
            <tr>
                <td colspan="3">
                    <!--TABLA DATOS DEL CONTRATO-->
                    <table border="0" style='font : normal normal small-caps 9px Tahoma;' width="100%">
                        <tr bgcolor="#e5e5e5" style=" font-weight: bold">
                            <td colspan ="4" ALIGN="CENTER">ASEGURADOR</td>
                        </tr>
                        <tr>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">ENTIDAD</td>
                            <td><? echo strtoupper($Entidad[$fila[6]][0]);?></td>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">NIT</td>
                            <td><? echo $Entidad[$fila[6]][1];?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">CODIGO SGSSS</td>
                            <td><? echo $Entidad[$fila[6]][2]?>&nbsp;</td>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">REGIMEN</td>
                            <td><? echo $Entidad[$fila[6]][3]?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">CONTRATO</td>
                            <td><? echo $Entidad[$fila[6]][4]?>&nbsp;</td>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">No. CONTRATO</td>
                            <td><? echo $Entidad[$fila[6]][5]?>&nbsp;</td>
                        </tr>
                    <!--</table>-->
                    <!--FIN TABLA DATOS DEL CONTRATO-->
                    <!--DATOS DEL PACIENTE-->
                    <!--<table border="0" bordercolor="green" style='font : normal normal small-caps 11px Tahoma;' width="100%">-->
                        <tr bgcolor="#e5e5e5" style=" font-weight: bold">
                            <td colspan ="4" ALIGN="CENTER">PACIENTE</td>
                        </tr>
                        <tr>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">PACIENTE</td>
                            <td><? echo strtoupper("$fila[7] $fila[8] $fila[9] $fila[10]");?></td>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">IDENTIFICACION</td>
                            <td><? echo $fila[6];?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">TIPO DE USUARIO</td>
                            <td><? echo strtoupper("$fila[11]");?></td>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">NIVEL DE USUARIO</td>
                            <td><? echo strtoupper("$fila[12]");?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">MEDICO TRATANTE</td>
                            <td><? echo $DatosClinicos[$fila[6]][0]?></td>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">IDENTIFICACION</td>
                            <td><? echo $DatosClinicos[$fila[6]][1]?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#e5e5e5" style=" font-weight: bold" width="10%">DIAGNOSTICO</td>
                            <td colspan="3"><? echo $DatosClinicos[$fila[6]][2]."-".$DatosClinicos[$fila[6]][3]?></td>
                        </tr>
                    </table>
                    <!--FIN DATOS DEL PACIENTE-->
                    <!--DATOS MEDICAMENTOS-->
                    <table border="1" bordercolor="e5e5e5" style='font : normal normal small-caps 9px Tahoma;'
                           cellspacing="1">
                        <tr bgcolor="#e5e5e5" style=" font-weight: bold">
                            <td colspan ="8" ALIGN="CENTER">MEDICAMENTO</td>
                        </tr>
                        <tr>
                            <td>Fecha de Formula</td>
                            <td><? echo $fila[13]?></td>
                            <td colspan="4">&nbsp;</td>
                            <td>Vigencia de la Formula</td>
                            <td><? if($fila[2]){echo "24 HORAS";}else{echo "30 DIAS";}?></td>
                        </tr>
                        <tr bgcolor="#e5e5e5" style=" font-weight: bold" align="center">
                            <td rowspan="2" width="5%">Codigo</td>
                            <td rowspan="2" width="5%">Nombre</td>
                            <td rowspan="2" width="5%">Concentracion</td>
                            <td rowspan="2" width="5%">Forma Farmaceutica</td>
                            <td rowspan="2" width="5%">Via</td>
                            <td rowspan="2">Dosis</td>
                            <td colspan="2">Cantidad</td>
                        </tr>
                        <tr bgcolor="#e5e5e5" style=" font-weight: bold" align="center">
                            <td width="5%">Formulada</td><td width="5%">Despachada</td>
                        </tr>
                        <tr align="center">
                            <td><? echo $fila[14]?></td>
                            <td><? echo $fila[15]?></td>
                            <td><? echo $fila[16]?></td>
                            <td><? echo $fila[17]?></td>
                            <td><? echo $Prescripcion[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][0];
                            //echo "[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][0]";?></td>
                            <td><?echo $Prescripcion[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][1];
                            //echo "[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][1]";?></td>
                            <td><?
                            if($fila[2])
                            {
                                echo $Prescripcion[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][2]."  ";
                                $CantMed1 = NumerosxLet($Prescripcion[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][2]);
                                //echo stripos($CantMed1,"pesos");
                                $CantMed1 = substr($CantMed1,0,stripos($CantMed1,"pesos"));
                                if(eregi(".5",$Prescripcion[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][2]))
                                {
                                    $CantMed1 = $CantMed1." + Media";
                                }
                                echo "($CantMed1)";$CF = $Prescripcion[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][2];
                            }
                            else
                            {
                                echo $fila[18]."  ";
                                $CantMed2 = NumerosxLet($fila[18]);
                                $CantMed2 = substr($CantMed2,0,stripos($CantMed2,"pesos"));
                                echo "($CantMed2)";$CF = $fila[18];
                            }

                            //echo "[$fila[6]][$fila[19]][$fila[3]][$fila[4]][$fila[5]][2]";?></td>
                            <td><? echo $fila[18]."  ";
                            $CantMed2 = NumerosxLet($fila[18]);
                            $CantMed2 = substr($CantMed2,0,stripos($CantMed2,"pesos"));
                            echo "($CantMed2)";$CD = $fila[18];?></td>
                        </tr>
                    </table>
                    <table border="0" bordercolor="#000000" style='font : normal normal small-caps 11px Tahoma;' width="100%">
                        <tr>
                            <td align="Center">
                                <img width="150px" height="40px" src="/Firmas/<?echo $DatosClinicos[$fila[6]][1].".GIF";?>" /><br>
                                ___________________________________________________<br>
                                <? echo $DatosClinicos[$fila[6]][0];?><br>
                                <? echo $DatosClinicos[$fila[6]][5]." RM:".$DatosClinicos[$fila[6]][4];?>
                            </td>
                        </tr>
                    </table>
                    <!--FIN DATOS MEDICAMENTOS-->
                </td>
            </tr>
        </table>
        <!--FIN TABLA PRINCIPAL-->
        <P></P><br>
        <?
        $MedicamentoAnt = "$fila[15] $fila[16] $fila[17]";
        $CantFormulas = $CantFormulas + 1;
        $CantFormulada = $CantFormulada + $CF;
        $CantDespachada = $CantDespachada + $CD;
    }
    ?>
    <div align="center" class="nover"
         style=" background-color: <? echo $Estilo[1]?>; width:100%;
         font-size: 20px; color: white; font-weight:bold;">
         <? echo "FORMULAS: $CantFormulas, TOTAL ORDENADO: $CantFormulada, TOTAL DESPACHADO: $CantDespachada"; ?></div><br>
    <?
    unset($CantFormulas,$CantFormulada,$CantDespachada);
?>
</body>
</html>