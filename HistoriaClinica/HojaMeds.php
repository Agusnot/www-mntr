<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND=getdate();
    /////////////////////////CARGO DEL USUARIO
    $cons = "Select cargo from salud.medicos Where COmpania = '$Compania[0]' and Usuario = '$usuario[1]'";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Cargo = $fila[0];
    ///////////////////////SERVICIOS DEL PACIENTE
    $cons = "Select NumServicio,TipoServicio from Salud.Servicios Where Compania='$Compania[0]'
    and Cedula='$Paciente[1]' and Estado = 'AC'";//echo $cons 31 ms;
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    //echo $cons;
    $NumServicio = $fila[0]; $Ambito = $fila[1];
    //print_r( $_SESSION );
    if(!$FechaIni)
    {
        $AnioI = $ND[year]; $MesI = $ND[mon]; $DiaI = $ND[mday];
    }
    else
    {
        $FechaI = explode("-",$FechaIni);
        $AnioI = $FechaI[0]; $MesI = $FechaI[1]; $DiaI = $FechaI[2];
    }
    if(!$FechaFin)
    {
        $AnioF = $ND[year]; $MesF = $ND[mon]; $DiaF = $ND[mday];
    }
    else
    {
        $FechaF = explode("-",$FechaFin);
        $AnioF = $FechaF[0]; $MesF = $FechaF[1]; $DiaF = $FechaF[2];
    }
    if(!$AlmacenPpal)
    {
        $cons = "Select AlmacenPpal,VoBoRM from Consumo.AlmacenesPpales Where Compania='$Compania[0]'
        and Ssfarmaceutico = 1";//echo $cons 31ms;
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $AlmacenPpal = $fila[0];
        $RVoBo[$fila[0]] = $fila[1];
    }
    /////////////////////////////////////
    if($VoBo)
    {
        while(list($cad,$val)=each($VoBo))
        {
            if($cad)
            {
                $Valores = explode("*",$cad);
                $cons = "Update Salud.RegistroMedicamentos set VoBo = '$usuario[0]', 
                FechaVoBo = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
                Where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and FechaCre = '$Valores[0]'
                and Cedula = '$Valores[1]' and AutoId = $Valores[2]";
                $res = ExQuery($cons);
            }
        }
    }
    /////////////////////////////////////
    $cons = "Select AutoidProd,ViaSuministro from Salud.Plantillamedicamentos
    Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
    and CedPaciente='$Paciente[1]' group by AutoidProd,ViaSuministro";//echo $cons 15ms;
    $res = ExQuery($cons);
    while($fila=ExFetch($res))
    {
        $ViAdmon[$fila[0]] = $fila[1];
    }
    ///////////////////////////////
    
    if($Medicamento){$Adcons = "and RegistroMedicamentos.Autoid=$Medicamento ";}
    $cons = "Select NoRegistroMedicamentos.Autoid,date(NoRegistroMedicamentos.FechaCre),Hora,Tipo,Cantidad,Nombre,
    NombreProd1,UnidadMedida,Presentacion,NoRegistroMedicamentos.FechaCre,Motivo
    from Salud.NoRegistroMedicamentos,Central.usuarios,Consumo.CodProductos
    Where NoRegistroMedicamentos.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
    and NoRegistroMedicamentos.AlmacenPpal = '$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
    and Usuarios.Usuario = NoRegistroMedicamentos.Usuariocre and CodProductos.Autoid = NoRegistroMedicamentos.Autoid
    and NoRegistroMedicamentos.cedula = '$Paciente[1]' 
    and date(NoRegistroMedicamentos.Fechacre)>='$AnioI-$MesI-$DiaI' and date(NoRegistroMedicamentos.FechaCre)<='$AnioF-$MesF-$DiaF'
    and Anio=$AnioF $Adcons order by NoRegistroMedicamentos.FechaCre,NoRegistroMedicamentos.Autoid";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $C++;
        $MedsNoRegistrados[$C] = array($fila[1],$fila[0],"$fila[6] $fila[7] $fila[8]",
            $fila[2],$fila[4],$fila[5],$fila[10]);
    }
    echo $cons = "Select RegistroMedicamentos.Autoid,date(RegistroMedicamentos.FechaCre),Hora,Tipo,Cantidad,Nombre,
    NombreProd1,UnidadMedida,Presentacion,RegistroMedicamentos.FechaCre,VoBo,FechaVoBo
    from Salud.RegistroMedicamentos,Central.usuarios,Consumo.CodProductos
    Where RegistroMedicamentos.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
    and RegistroMedicamentos.AlmacenPpal = '$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
    and Usuarios.Usuario = RegistroMedicamentos.Usuariocre and CodProductos.Autoid = RegistroMedicamentos.Autoid
    and RegistroMedicamentos.cedula = '$Paciente[1]' 
    and date(RegistroMedicamentos.Fechacre)>='$AnioI-$MesI-$DiaI' and date(RegistroMedicamentos.FechaCre)<='$AnioF-$MesF-$DiaF'
    and Anio=$AnioF $Adcons order by RegistroMedicamentos.FechaCre,RegistroMedicamentos.Autoid";
    //echo $cons 156ms;
    $res = ExQuery($cons);
    if(ExNumRows($res)==0){?><center><font color="red"><i>No se han registrado medicamentos</i></font></center><? }
    while($fila = ExFetch($res))
    {
        //$Registro[$fila[1]][$fila[0]][$fila[2]][$fila[3]] = array($fila[2],$fila[4],$fila[5],$fila[6]);
        $NomMed[$fila[0]] = "$fila[6] $fila[7] $fila[8]";
        //$Rowspan[$fila[1]][$fila[0]] = $Rowspan[$fila[1]][$fila[0]] + 1;
        $Total[$fila[0]] = $Total[$fila[0]] + $fila[4];
		/*if($Medicamento){$Adcons = "and RegistroMedicamentos.Autoid=$Medicamento ";}
		$consNB = "Select NoRegistroMedicamentos.Autoid,date(NoRegistroMedicamentos.FechaCre),Hora,Tipo,Cantidad,Nombre,
    	NombreProd1,UnidadMedida,Presentacion,NoRegistroMedicamentos.FechaCre,Motivo
	    from Salud.NoRegistroMedicamentos,Central.usuarios,Consumo.CodProductos
	    Where NoRegistroMedicamentos.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	    and NoRegistroMedicamentos.AlmacenPpal = '$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
	    and Usuarios.Usuario = NoRegistroMedicamentos.Usuariocre and CodProductos.Autoid = NoRegistroMedicamentos.Autoid
	    and NoRegistroMedicamentos.cedula = '$Paciente[1]' 
	    and date(NoRegistroMedicamentos.Fechacre)>='$AnioI-$MesI-$DiaI' and date(NoRegistroMedicamentos.FechaCre)<='$AnioF-$MesF-$DiaF'
	    and Anio>=$AnioI and Anio<=$AnioF $Adcons 
		and nombreprod1='$fila[6]' order by NoRegistroMedicamentos.FechaCre,NoRegistroMedicamentos.Autoid";
		echo "querynohay: ".$consNB."</br></br>";
		$resNB = ExQuery($consNB);
	    while($filaNB = ExFetch($resNB)){
			$Total[$fila[0]] = $Total[$fila[0]] + $fila[4] + $filaNB[12];
	    }*/
        $C++;
        $MedsRegistrados[$C] = array($fila[1],$fila[0],"$fila[6] $fila[7] $fila[8]",
        $ViAdmon[$fila[0]],$fila[2],$fila[4],$fila[5],$fila[3],$fila[9],$fila[10],$fila[11]);
	}
?>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
    <?
    if(!$Imprimir)
    {
    ?>
    <table bordercolor="#f1f1f1" border="1" style='font : normal normal small-caps 13px Tahoma;' align="center" cellspacing="0">
       <tr bgcolor="#e5e5e5">
           <td colspan="7" align="center">
                <input type="button" name="Nuevo" value="Registrar Medicamentos"
                 onclick="location.href='/HistoriaClinica/NewRegistroMedicamentos.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Paciente[1]?>&AlmacenPpal=<?echo $AlmacenPpal?>&NumSer=<? echo $NumServicio?>&Ambito=<? echo $Ambito?>&Pabellon=<? echo $Unidad?>&OrigenHC=1';"/>
            </td>
       </tr>
        <tr bgcolor="#e5e5e5" align="center" style="font-weight: bold">
            <td>Almacen Principal</td>
            <td colspan="4">Periodo</td>
            <td>Medicamento</td>
            <td rowspan="2">
                <button type="button" name="Imprimir_H" title="Imprimir Hoja de medicamentos"
                        onclick="open('/HistoriaClinica/Formatos_Fijos/HojaMeds.php?DatNameSID=<?echo $DatNameSID?>&Imprimir=1&Medicamento=<?echo $Medicamento?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>', '','width=850,height=500,scrollbars=yes')">
                    <img src="/Imgs/b_print.png">
                </button>
                <button type="button" name="Imprimir_T" title="Imprimir totales" style=" width: 25px; height: 25px"
                        onclick="document.getElementById('infSubTo').style.display='inline';
						         var ficha = document.getElementById('subTotales');
								 var ventimp = window.open('', 'popimpr');
								 ventimp.document.write( ficha.innerHTML );
								 ventimp.document.close();
								 ventimp.print( );
								 ventimp.close();
								 document.forms.FORMA.submit();">
                    <img src="/Imgs/b_sbrowse.png">
                </button>
				<button type="button" name="Imprimir_T" title="Imprimir totales" style=" width: 25px; height: 25px"
                        onclick="document.getElementById('infTo').style.display='inline';
						         var ficha = document.getElementById('Totales');
								 var ventimp = window.open('', 'popimpr');
								 ventimp.document.write( ficha.innerHTML );
								 ventimp.document.close();
								 ventimp.print( );
								 ventimp.close();
								 document.forms.FORMA.submit();">
                    <img src="/Imgs/auditoria.png">
                </button>
            </td>
       </tr>
       <tr>
            <td><select name="AlmacenPpal">
                <?
                $cons = "Select AlmacenPpal,VoBoRM from Consumo.AlmacenesPpales Where Compania='$Compania[0]'
                and Ssfarmaceutico = 1"; //echo $cons;
                $res = ExQuery($cons);
                while($fila = ExFetch($res))
                {
                    $RVoBo[$fila[0]] = $fila[1];
                    if($AlmacenPpal==$fila[0]){$Selected = " selected ";}else{$Selected="";}
                    ?><option <? echo $Selected?> value="<? echo $fila[0]?>" ><? echo $fila[0]?></option>
                <?
                }
                ?>
            </select></td>
            <td>
                <input type="text" name="FechaIni" readonly="readonly" size="6"
                onclick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd');"  value="<? echo "$AnioI-$MesI-$DiaI"; ?>"
                title="Doble click para confirmar la fecha"/>
            </td>
            <td>
                a
            </td>
            <td>
                <input type="text" name="FechaFin" readonly="readonly" size="6" 
                onclick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"  value="<? echo "$AnioF-$MesF-$DiaF"; ?>"
                title="Doble click para confirmar la fecha"/>
            </td>
            <td>
                <button type="button" onClick="FORMA.submit()" title="Confirmar Periodo">
                    <img src="/Imgs/b_check.png" />
                </button>
            </td>
            <td><select name="Medicamento" onChange="FORMA.submit()">
                <option value="">Mostrar todos</option>
                <?
                    $cons = "Select CodProductos.Autoid,NombreProd1,UnidadMedida,Presentacion from
                    Consumo.CodProductos,salud.RegistroMedicamentos
                    Where CodProductos.AlmacenPpal = '$AlmacenPpal' and RegistroMedicamentos.AlmacenPpal='$AlmacenPpal'
                    and CodProductos.Autoid = RegistroMedicamentos.Autoid and Anio =$AnioF
                    and date(RegistroMedicamentos.FechaCre)>='$AnioI-$MesI-$DiaI' and date(RegistroMedicamentos.FechaCre)<='$AnioF-$MesF-$DiaF'
                    and RegistroMedicamentos.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
                    and Cedula='$Paciente[1]' group by  CodProductos.Autoid,NombreProd1,UnidadMedida,Presentacion";//echo $cons;
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        if($Medicamento==$fila[0]){$Selected = " selected ";}else{$Selected="";}
                        ?><option <? echo $Selected?> value="<? echo $fila[0]?>" ><? echo "$fila[1] $fila[2] $fila[3]"?></option>
                    <?
                    }
                ?>
            </select></td>
       </tr>
    </table>
    <?
    }
    else
    {
    ?>
    <table bordercolor="#FFFFFF" border="1" style='font : normal normal small-caps 13px Tahoma; font-weight: bold' align="center" cellspacing="0">
        <tr><td align="center">
                <font size="4"><? echo $Compania[0]?></font><br>
                <font size="3"><? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]"?></font><br>
                <? echo "Hoja de registro de medicamentos
                <br>Desde: $AnioI-$MesI-$DiaI Hasta: $AnioF-$MesF-$DiaF";
                if($Medicamento)
                {
                    echo "<br>Filtrado por Medicamento: ".$NomMed[$Medicamento];
                }
                ?>
            </td></tr>
    </table>
    <?
    }
    if(!$Totales)
    {
    ?>
    <table bordercolor="#f1f1f1" border="1" style='font : normal normal small-caps 13px Tahoma;' align="center" cellspacing="0">
        <?
        if($MedsRegistrados)
        {
            foreach($MedsRegistrados as $RMedicamentos)
            {
                if($RMedicamentos[0]!=$FechaAnt)
                {
                    ?>
                    <tr>
                        <td colspan="6" align="right" bgcolor="<? echo $Estilo[1]?>" style=" font-weight: bold; color: white">
                            <?echo $RMedicamentos[0];?>
                        </td>
                    </tr>
                    <tr style="font-weight: bold" bgcolor="#e5e5e5">
                        <td>Medicamento</td><td>Via</td><td>Hora</td><td>Cantidad</td><td>Registra</td>
                        <?
                        if($RVoBo[$AlmacenPpal])
                        {
                            ?><td align="center">Visto Bueno <br><?echo $RVoBo[$AlmacenPpal]?></td><?
                        }
                        ?>
                    </tr>
                    <?
                }
                ?>
                <tr>
                    <td><? echo $RMedicamentos[2]?></td>
                    <td><? echo $RMedicamentos[3]?></td>
                    <? if($RMedicamentos[7]=="U"){$Color="red";}else{$Color="blue";}?>
                    <td style=" font-weight: bold; color: <?echo $Color?>"><? echo $RMedicamentos[4]?></td>
                    <td><? echo $RMedicamentos[5]?></td>
                    <td><? echo $RMedicamentos[6]?></td>
                    <?
                        if($RVoBo[$AlmacenPpal] && $Cargo==$RVoBo[$AlmacenPpal])
                        {
                            ?><td align="center"><?
                            if(!$RMedicamentos[9])
                            {
                                ?><button type="submit" 
                                    name="VoBo[<?echo $RMedicamentos[8]."*".$Paciente[1]."*".$RMedicamentos[1];?>]" title="Dar Visto bueno">
                                    <img src="/Imgs/b_check.png" />
                                </button> </td><?
                            }
                            else
                            {
                                ?>
                                <font color="green">
                                <b><i><?echo $RMedicamentos[9]?></i></b><br>
                                <i><?echo $RMedicamentos[10]?></i>
                                </font>
                                <?
                            }
                            
                        }
                    ?>
                </tr>
                <?
                $FechaAnt=$RMedicamentos[0];
            }//FIN FOR EACH
        }//Fin if($Registro)
        if($MedsNoRegistrados)
        {
            foreach($MedsNoRegistrados as $NRMedicamentos)
            {
                if($NRMedicamentos[0]!=$FechaAntX)
                {
                    ?>
                    <tr>
                        <td colspan="6" align="right" bgcolor="#FF0000" style=" font-weight: bold; color: white">
                            <?echo "NO REGISTRADOS EL $NRMedicamentos[0]";?>
                        </td>
                    </tr>
                    <tr style="font-weight: bold" bgcolor="#e5e5e5">
                        <td>Medicamento</td><td>Hora</td><td>Cantidad</td><td>Usuario</td><td colspan="2">Motivo</td>
                    </tr>
                    <?
                }
                ?>
                <tr>
                    <td><?echo $NRMedicamentos[2]?></td>
                    <td><?echo $NRMedicamentos[3]?></td>
                    <? if($NRMedicamentos[7]=="U"){$Color="red";}else{$Color="blue";}?>
                    <td style=" font-weight: bold; color: <?echo $Color?>"><? echo $NRMedicamentos[4]?></td>
                    <td><?echo $NRMedicamentos[5]?></td>
                    <td colspan="2"><?echo $NRMedicamentos[6]?></td>
                </tr>
                <?
                $FechaAntX=$NRMedicamentos[0];
            }//FIN FOR EACH
        }//Fin if($Registro)
        ?>
                
    </table>
    <?
    }
    //else
    //{
	$auditoria=$_GET['auditoria'];
        /*if($Total&&($auditoria==NULL))
        {
            ?>
            <table bordercolor="#f1f1f1" border="1" style='font : normal normal small-caps 13px Tahoma;' align="center" cellspacing="0">
                <tr bgcolor="#e5e5e5" style="font-weight: bold">
                    <td>Medicamento</td><td>Total Periodo</td>
                </tr>
                <?
                while(list($Autoid,$Tot) = each($Total))
                {
                    ?>
                    <tr>
                        <td><? echo $NomMed[$Autoid]?></td>
                        <td align="right"><? echo $Total[$Autoid];?></td>
                    </tr>
                    <?
                }// Fin while(list($Autoid,$Tot) = each($Total))
                ?>
            </table>
            <?
        }//Fin if($Total)*/
		if($Total&&($auditoria==NULL))
        {
            ?><br><div id="subTotales"><span id="infSubTo">
			<table bordercolor="#FFFFFF" border="1" style='font : normal normal small-caps 13px Tahoma; font-weight: bold' align="center" cellspacing="0">
            <tr><td align="center">
                <font size="5"><? echo $Compania[0]?></font><br>
                <font size="4"><? echo "<br>Hoja de registro de medicamentos</font>
                <br>Desde: $AnioI-$MesI-$DiaI Hasta: $AnioF-$MesF-$DiaF";
				echo "<br><br>Paciente: $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]"?><br>
                <?if($Medicamento)
                {
                    echo "<br>Filtrado por Medicamento: ".$NomMed[$Medicamento];
                }
                ?>
            </td></tr>
            </table><br></span>
            <div align="center"><b>MEDICAMENTOS REGISTRADOS</div>
			<table bordercolor="#f1f1f1" border="1" style='font : normal normal small-caps 13px Tahoma;' align="center" cellspacing="0">
                <tr bgcolor="#e5e5e5" style="font-weight: bold">
                    <td>Medicamento</td><td>Total Periodo</td>
                </tr>
                <?
                while(list($Autoid,$Tot) = each($Total))
                {$Reg[$Autoid]=$Total[$Autoid];
                    ?>
                    <tr>
                        <td><? echo $NomMed[$Autoid]?></td>
                        <td align="right"><? echo $Total[$Autoid];?></td>
                    </tr>
                    <?
                }// Fin while(list($Autoid,$Tot) = each($Total))
                ?>
            </table></div>
            <?
			?><br><div id="Totales"><span id="infTo">
			<table bordercolor="#FFFFFF" border="1" style='font : normal normal small-caps 13px Tahoma; font-weight: bold' align="center" cellspacing="0">
            <tr><td align="center">
                <font size="5"><? echo $Compania[0]?></font><br>
                <font size="4"><? echo "<br>Hoja de registro de medicamentos</font>
                <br>Desde: $AnioI-$MesI-$DiaI Hasta: $AnioF-$MesF-$DiaF";
				echo "<br><br>Paciente: $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]"?><br>
                <?if($Medicamento)
                {
                    echo "<br>Filtrado por Medicamento: ".$NomMed[$Medicamento];
                }
                ?>
            </td></tr>
            </table><br></span>
			<div align="center"><b>MEDICAMENTOS ORDENADOS</div>
            <table bordercolor="#f1f1f1" border="1" style='font : normal normal small-caps 13px Tahoma;' align="center" cellspacing="0">
                <tr bgcolor="#e5e5e5" style="font-weight: bold">
                    <td>Medicamento</td><!--<td>Total Periodo</td>--><td>Total</td>
                </tr>
                <?php $FI=explode('-', $FechaIni);
				  $FF=explode('-', $FechaFin);
    
	if($Medicamento){$Adcons = "and RegistroMedicamentos.Autoid=$Medicamento ";}
    $cons = "Select NoRegistroMedicamentos.Autoid,Tipo,sum(Cantidad),Nombre, NombreProd1,UnidadMedida,Presentacion
    from Salud.NoRegistroMedicamentos,Central.usuarios,Consumo.CodProductos
    Where NoRegistroMedicamentos.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
    and NoRegistroMedicamentos.AlmacenPpal = '$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
    and Usuarios.Usuario = NoRegistroMedicamentos.Usuariocre and CodProductos.Autoid = NoRegistroMedicamentos.Autoid
    and NoRegistroMedicamentos.cedula = '$Paciente[1]' 
    and date(NoRegistroMedicamentos.Fechacre)>='$FechaIni' and date(NoRegistroMedicamentos.FechaCre)<='$FechaFin'
    and Anio='".$FF[0]."' $Adcons 
	group by NoRegistroMedicamentos.Autoid,Tipo,Nombre, NombreProd1,UnidadMedida,Presentacion
	order by NoRegistroMedicamentos.Autoid ";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $C++;
        $MedsNoRegistradosGroup[$C] = array($fila[1],'',"$fila[4] $fila[5] $fila[6]",'',$fila[2],$fila[3],'');
    }
	
	

	
	$cons = "Select RegistroMedicamentos.Autoid,sum(Cantidad),Nombre,
    NombreProd1,UnidadMedida,Presentacion,VoBo,FechaVoBo
    from Salud.RegistroMedicamentos,Central.usuarios,Consumo.CodProductos
    Where RegistroMedicamentos.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
    and RegistroMedicamentos.AlmacenPpal = '$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
    and Usuarios.Usuario = RegistroMedicamentos.Usuariocre and CodProductos.Autoid = RegistroMedicamentos.Autoid
    and RegistroMedicamentos.cedula = '$Paciente[1]' 
    and date(RegistroMedicamentos.Fechacre)>='$FechaIni' and date(RegistroMedicamentos.FechaCre)<='$FechaFin'
    and Anio='".$FF[0]."' $Adcons 
	group by RegistroMedicamentos.Autoid,Nombre,
    NombreProd1,UnidadMedida,Presentacion,VoBo,FechaVoBo
	order by RegistroMedicamentos.Autoid";
    //echo $cons 156ms;
    $res = ExQuery($cons);
    if(ExNumRows($res)==0){?><center><font color="red"><i>No se han registrado medicamentos</i></font></center><? }
    while($fila = ExFetch($res))
    {
        $NomMedGroup[$fila[0]] = "$fila[3] $fila[4] $fila[5]";
        $TotalGroup[$fila[0]] = $Total[$fila[0]] + $fila[1];
		}
		$Control_=0;
				while(list($Autoid,$Tot) = each($TotalGroup))
                {$pas=false;
                     ?>
                    <tr>
                        <td><? 
						echo $NomMedGroup[$Autoid]?></td>
                        <!--<td align="right">--><? //echo $Total[$Autoid];?><!--</td>-->
						<td align="right"><?
					
					 if($MedsNoRegistradosGroup)
						foreach($MedsNoRegistradosGroup as $NRMedicamentos)
						if($NRMedicamentos[2]==$NomMedGroup[$Autoid]){
						   $Medicamento[]=$NomMedGroup[$Autoid];
						   $pas=true;
						   echo ($TotalGroup[$Autoid]+$NRMedicamentos[4]-$Reg[$Autoid]);}
				    if(!$pas)echo ($TotalGroup[$Autoid]-$Reg[$Autoid]);
						   ?>
						</td>
				    </tr><?
                $Control_++;}// Fin while(list($Autoid,$Tot) = each($Total))
				$Control=0;
				if($MedsNoRegistradosGroup)
				foreach($MedsNoRegistradosGroup as $NRMedicamentos)
            {
                if($NRMedicamentos[2]!=$Medicamento[$Control]){
                ?><tr><td align="left"><?echo $NRMedicamentos[2];?></td>
				<td align="right"><?echo $NRMedicamentos[4];?></td>
				<!--<td align="right"><?//echo $NRMedicamentos[4];?></td>--></tr><?}
			$Control++;}
                ?>
            </table></div>
            <?
        }//Fin if($Total)
        if(($Total)&&($auditoria==1))
        {
            ?>
            <table bordercolor="#f1f1f1" border="1" style='font : normal normal small-caps 13px Tahoma;' align="center" cellspacing="0">
                <tr bgcolor="#e5e5e5" style="font-weight: bold">
                    <td>Medicamento</td><td>Total Periodo</td>
                </tr>
                <?
                while(list($Autoid,$Tot) = each($Total))
                {
                    ?>
                    <tr>
                        <td><? echo $NomMed[$Autoid]?> carlos </td>
                        <td align="right"><? echo $Total[$Autoid]+$NRMedicamentos[4]?></td>
                    </tr>
                    <?
                }// Fin while(list($Autoid,$Tot) = each($Total))
                ?>
            </table>
            <?
        }//Fin if($Total)
    //}
    ?>
</form>
</body>
<script>document.getElementById('infTo').style.display='none';
        document.getElementById('infSubTo').style.display='none';</script>