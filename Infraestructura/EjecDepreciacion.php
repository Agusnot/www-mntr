<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND = getdate();
    if(!$Anio){$Anio = $ND[year];}
    if(!$Mes){$Mes = $ND[mon];}
    if(!$Tipo){$Tipo="Devolutivos";}
    if(!$Dia){$Dia=1;}
    ////////////////////////
    $cons = "Select AutoId,Codigo,VrDepreciacion,DepEjecutadas from Infraestructura.Depreciaciones Where Compania='$Compania[0]' and FechaDepreciacion<='$Anio-$Mes-$Dia'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $DepEjecutada["$fila[0]$fila[1]"] = $DepEjecutada["$fila[0]$fila[1]"] + $fila[2];
        $Cuotas["$fila[0]$fila[1]"] = $Cuotas["$fila[0]$fila[1]"] + $fila[3];
    }

    $cons = "Select GruposdeElementos.Grupo,CentroCostos,Cuenta,DepreciAcumulada from Infraestructura.CuentasDepxCC,Infraestructura.GruposdeElementos
    Where CuentasDepxCC.Compania='$Compania[0]' and CuentasDepxCC.Grupo = GruposdeElementos.Grupo and CuentasDepxCC.Anio=$Anio and
    GruposdeElementos.Anio=$Anio";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $CuentaCC[$fila[0]][$fila[1]] = $fila[2];
        $CuentaGrupo[$fila[0]] = $fila[3];
        if($Tipo=="Activos Fijos")
        {
            $CCGrupo[$fila[0]] = $fila[1];
        }
    }

    if($Tipo == "Devolutivos")
    {
        $AdCampo = ",CentroCostos";
        $AdFrom = " ,Infraestructura.Ubicaciones";
        $AdWhere = " and Ubicaciones.Compania='$Compania[0]' and CodElementos.AutoId = Ubicaciones.AutoId
        and FechaFin is NULL";
    }

    $cons = "Select CodElementos.AutoId,Codigo,Grupo$AdCampo from Infraestructura.CodElementos$AdFrom
        Where CodElementos.Compania='$Compania[0]' $AdWhere and CodElementos.Clase='$Tipo'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $GrupoProducto["$fila[0]|$fila[1]"] = $fila[2];
        if($Tipo=="Devolutivos")
        {
            $Ubicacion["$fila[0]|$fila[1]"] = $fila[3];
        }
        if($Tipo == "Activos Fijos")
        {
            $Ubicacion["$fila[0]|$fila[1]"] = $CCGrupo[$fila[2]];
        }
    }
    
    ////////////////////////
    if($DepreciarIn)
    {
        while(list($AutoidCod,$val)=each($DepreciarIn))
        {
            if($val)
            {
                //echo "Grupo: ".$GrupoProducto[$AutoidCod]."<br>";
                //echo "Ubicacion: ".$Ubicacion[$AutoidCod]."<br>";
                //echo "Cuenta: ".$Cuenta[$GrupoProducto[$AutoidCod]][$Ubicacion[$AutoidCod]]."<br>";
                //break;
                $props = explode("|",$AutoidCod);
                //$AniosDep = ObtenEdad($DepD[$AutoidCod]); $MesesDep = ObtenMesesEnEdad($DepD[$AutoidCod]);
                //$QPen = ($AniosDep*12) + $MesesDep - $QEje[$AutoidCod];
                $FechaDep = getdate(strtotime($DepD[$AutoidCod]));
                unset($TPen);
                if($Anio>$FechaDep[year])
                {
                    if($Mes>$FechaDep[mon]){$Tpen =(($Anio-$FechaDep[year])*12)+$Mes-$FechaDep[mon];}
                    else{$TPen=(($Anio-$FechaDep[year]-1)*12)+12-$FechaDep[mon]+$Mes;}
                }
                else
                {
                    if($Anio==$FechaDep[year])
                    {
                        if($Mes>$FechaDep[mon]){$TPen = $Mes-$FechaDep[mon];}
                    }
                    else{$TPen = 0;}

                }
                $QPen = $TPen - $Cuotas["$props[0]$props[1]"] + 1;
                $VrDep = $QPen * $Qota[$AutoidCod];
                if(($Saldo[$AutoidCod]-$VrDep)<0){$VrDep = $Saldo[$AutoidCod];}
                if($QPen>0)
                {
                    if($Ubicacion[$AutoidCod])
                    {
                        //if($CuentaCC[$GrupoProducto[$AutoidCod]][$Ubicacion[$AutoidCod]] && $CuentaGrupo[$GrupoProducto[$AutoidCod]])
                        //{
                            $Numero=ConsecutivoComp($ComprobanteCont,$Anio,"Contabilidad");

                            $cons = "Insert into Infraestructura.Depreciaciones (Compania,AutoId,Codigo,FechaDepreciacion,VrDepreciacion,
                            UsuarioEjecuta,DepEjecutadas,FechaEjecucion,ComprobanteCont,Numero)
                            values ('$Compania[0]',$props[0],'$props[1]','$Anio-$Mes-$Dia',$VrDep,'$usuario[0]',$QPen,'$ND[year]-$ND[mon]-$ND[mday]','$ComprobanteCont','$Numero')";
                            $res = ExQuery($cons);
                            $Tercero = substr($Compania[1],4,strlen($Compania[1]));
                            $cons = "Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,
                            Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,FechaDocumento,Anio,CC,DocSoporte)
                            values(1,'$Anio-$Mes-$Dia','$ComprobanteCont','$Numero','$Tercero',
                            'Depreciacion ".$NomElemento[$AutoidCod]."','".$CuentaGrupo[$GrupoProducto[$AutoidCod]]."',0,$VrDep,'$Compania[0]','$usuario[0]',
                            '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$Anio-$Mes-$Dia',$Anio,'000','0')";
                            $res = ExQuery($cons);

                            $cons = "Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,
                            Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,FechaDocumento,Anio,CC,DocSoporte)
                            values(2,'$Anio-$Mes-$Dia','$ComprobanteCont','$Numero','$Tercero',
                            'Depreciacion ".$NomElemento[$AutoidCod]."','".$CuentaCC[$GrupoProducto[$AutoidCod]][$Ubicacion[$AutoidCod]]."',$VrDep,0,'$Compania[0]','$usuario[0]',
                            '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC','$Anio-$Mes-$Dia',$Anio,'".$Ubicacion[$AutoidCod]."','0')";
                            $res = ExQuery($cons);
                        //}
                    }
                }
                $HacerSubmit=1;
            }
        }
                
    }
    if(!$Inf){$Inf = 0;}
    if(!$Sup){$Sup = 10;}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Dep(AutoIdCod)
	{
            if(document.FORMA.ComprobanteCont.value != "")
            {
                document.getElementById("DepreciarIn["+AutoIdCod+"]").value = 1;
            }
            else
            {
                alert("Escoja o configure un comprobante contable para la depreciacion");
            }
        }
        function MarcarDep()
        {
            if(document.FORMA.ComprobanteCont.value != "")
            {
                for (i=0;i<document.FORMA.elements.length;i++)
                {
                    if(document.FORMA.elements[i].type == "hidden")
                    {
                        if(document.FORMA.elements[i].id.substr(0, 11)=="DepreciarIn")
                            {
                                document.FORMA.elements[i].value = 1;
                            }
                    }
                }
            }
            else
            {
                alert("Escoja o configure un comprobante contable para la depreciacion");
            }
        }
</script>
<form name="FORMA" method="post">
    <input type="hidden" name="Inf" value="<? echo $Inf?>" />
    <input type="hidden" name="Sup" value="<? echo $Sup?>" />
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="<? echo $Estilo[1]?>">
        <tr bgcolor="#e5e5e5" align="center" style=" font-weight: bold"><td>A&ntilde;o</td><td>Mes</td><td>Dia</td><td>Tipo</td><td>Grupo</td>
            <td>Comprobante Contable</td></tr>
        <tr>
            <td>
                <select name="Anio" onchange="FORMA.submit()">
                    <?
                    $cons = "Select Anio from Central.Anios Where Compania='$Compania[0]' order by Anio";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        echo "<option value='$fila[0]'";
                        if($Anio==$fila[0]){echo " selected ";}
                        echo ">$fila[0]</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="Mes" onchange=";FORMA.submit();">
                    <?
                    $cons = "Select Numero,Mes,NumDias from Central.Meses order by Numero";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        echo "<option value='$fila[0]'";
                        if($Mes==$fila[0]){echo " selected ";}
                        echo ">$fila[1]</option>";
                        $Dias[$fila[0]]=$fila[2];
                    }
                    if(!$Dia){$Dia = $Dias[$Mes];}
                    ?>
                </select>
            </td>
            <td>
                <input type="hidden" name="DiasLimit" value="<? echo $Dias[$Mes];?>" />
                <input type="text" name="Dia" value="<? echo $Dia?>" size="2" maxlength="2" style=" text-align: right"
                onkeyup="xNumero(this)" onkeydown="xNumero(this)" onblur="if(parseInt(this.value)>parseInt(DiasLimit.value)){this.value='';};campoNumero(this);"/>
            </td>
            <td>
                <select name="Tipo" onchange="FORMA.submit()">
                    <option <? if($Tipo=="Devolutivos"){echo " selected ";}?> value="Devolutivos">Devolutivos</option>
                    <option <? if($Tipo=="Activos Fijos"){echo " selected ";}?> value="Activos Fijos">Activos Fijos</option>
                </select>
            </td>
            <td>
                <select name="Grupo" onchange="Inf.value='0';Sup.value='10';FORMA.submit();"><option></option>
                    <?
                    $cons = "Select Grupo from Infraestructura.GruposDeElementos Where Compania = '$Compania[0]' and Anio=$Anio and Clase='$Tipo' order by grupo";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        echo "<option value='$fila[0]'";
                        if($fila[0]==$Grupo){echo "selected";}
                        echo ">$fila[0]</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="ComprobanteCont"><option></option>
                    <?
                    $cons = "Select Comprobante from Contabilidad.Comprobantes Where Compania='$Compania[0]' and Depreciacion = 1";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        echo "<option value='$fila[0]'";
                        if($fila[0]==$ComprobanteCont){echo "selected";}
                        echo ">$fila[0]</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <div align="right">
        <input type="button" name="DepTodo" value="Depreciar todo" onclick="MarcarDep();FORMA.submit();" />
    </div>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr bgcolor="#e5e5e5" align="center" style=" font-weight: bold">
            <td>Codigo</td><td>Nombre</td><td>Valor Inicial</td><td>Dep. Acumulada</td><td>Saldo</td><td>Valor Cuota</td><td>Cuotas Restantes</td></tr>
        <tr align="center">
            <td><input type="text" size="6" name="ConsCodigo" onkeyup="xLetra(this)" onkeyDown="xLetra(this)" value="<? echo $ConsCodigo?>"
                       onblur="Inf.value='0';Sup.value='10';"></td>
            <td><input type="text" size="38" name="ConsNombre" onkeyup="xLetra(this)" onkeyDown="xLetra(this)" value="<? echo $ConsNombre?>"
                       onblur="Inf.value='0';Sup.value='10';"></td>
            <td><input type="text" size="10" name="ConsVrIni" onkeyup="xNumero(this)" onkeydown="xNumero(this)" onblur="campoNumero(this);Inf.value='0';Sup.value='10';"
                       value="<? echo $ConsVrIni?>" style=" text-align: right"></td>
            <td><input type="text" size="10" name="ConsDepAc" onkeyup="xNumero(this)" onkeydown="xNumero(this)" onblur="campoNumero(this);Inf.value='0';Sup.value='10';"
                       value="<? echo $ConsDepAc?>" style=" text-align: right"></td>
            <td><input type="text" size="10" name="ConsSaldo" onkeyup="xNumero(this)" onkeydown="xNumero(this)" onblur="campoNumero(this);Inf.value='0';Sup.value='10';"
                       value="<? echo $ConsSaldo?>" style=" text-align: right"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
                <button type="submit" name="Buscar" title="Buscar">
                    <img src="/Imgs/b_search.png" />
                </button>
            </td>
        </tr>
            <?
            if($ConsCodigo){$AdConsCodigo = " and Codigo ilike '$ConsCodigo%'";}
            if($ConsNombre){$AdConsNombre = " and (Nombre || '' || Caracteristicas) ilike '%$ConsNombre%'";}
            if($ConsVrIni){$AdConsVrIni = " and CostoInicial = $ConsVrIni";}
            if($ConsDepAc){$AdConsDepAc = " and DepAcumulada >= $ConsDepAc";}
            if($ConsSaldo){$AdConsSaldo = " and (CostoInicial-DepAcumulada) >= $ConsSaldo";}
            if($Grupo){$AdConsGrupo = " and Grupo ilike '$Grupo%' ";}
            
            $cons = "Select AutoId,Codigo,Nombre,Caracteristicas,CostoInicial,DepAcumulada,
            DepDesde,depreciaren,depreciardurante,Grupo,AxICostoIni
            from Infraestructura.CodElementos Where Compania='$Compania[0]'
            and FechaAdquisicion <= '$Anio-$Mes-$Dia' and DepDesde <= '$Anio-$Mes-$Dia' and Clase='$Tipo' and Tipo not in('Baja','Orden Compra')
            and (EstadoCompras is NULL or EstadoCompras='Ingresado') and Eliminado is NULL and (CostoInicial-DepAcumulada)>0
            $AdConsGrupo$AdConsCodigo$AdConsNombre$AdConsDepAc$AdConsSaldo$AdConsVrIni
            order by Codigo";
            $res = ExQuery($cons);
            $Total = ExNumRows($res);
            $cont = 0;
            while($fila=ExFetch($res))
            {
                $DepAcumulada = $fila[5] + $DepEjecutada["$fila[0]$fila[1]"];
                $Saldo = $fila[4] - $DepAcumulada;
                if($fila[7]==anios){$CuotasTotales = $fila[8]*12;}
                else{$CuotasTotales = $fila[8];}
                if($CuotasTotales == 0){$CuotasTotales = 1;}
                $VrCuota = ($fila[4]+$fila[10])/$CuotasTotales;
                $CuotasRestantes = $CuotasTotales - $Cuotas["$fila[0]$fila[1]"];
                $FechaDep = getdate(strtotime($fila[6]));
                unset($TPen);
                if($Anio>$FechaDep[year])
                {
                    if($Mes>$FechaDep[mon]){$Tpen =(($Anio-$FechaDep[year])*12)+$Mes-$FechaDep[mon];}
                    else{$TPen=(($Anio-$FechaDep[year]-1)*12)+12-$FechaDep[mon]+$Mes;}
                }
                else
                {
                    if($Anio==$FechaDep[year])
                    {
                            if($Mes>$FechaDep[mon]){$TPen = $Mes-$FechaDep[mon];}
                    }
                    else{$TPen = 0;}
					
                }
                $QPen = $TPen - $Cuotas["$fila[0]$fila[1]"] + 1;
                //echo "$QPen---".$Cuotas["$fila[0]$fila[1]"]."<br>";
                if($QPen>0)
                {
                ?>
                <a name="<? echo "$fila[0]|$fila[1]";?>">
                <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
                    <td><? echo $fila[1]?></td><td><? echo "$fila[2] $fila[3]"?></td><td align="right"><? echo number_format($fila[4],2); ?></td>
                    <td align="right"><? echo number_format($DepAcumulada,2);?></td><td align="right"><? echo number_format($Saldo,2);?></td>
                    <td align="right"><? echo number_format($VrCuota,2);?></td><td align="right"><? echo $CuotasRestantes;?></td>
                    <td><button type="button" onclick="Dep('<? echo "$fila[0]|$fila[1]"?>');FORMA.submit()"
                    name="Depreciar[<? echo "$fila[0]|$fila[1]"?>]" title="Depreciar este elemento">
                        <image src="/Imgs/s_process.png" />
                        </button></td>
                </tr>
                </a>
                <?
                }
                    
                
            ?>
            <input type="hidden" name="Saldo[<? echo "$fila[0]|$fila[1]"?>]" value="<? echo $Saldo?>" />
            <input type="hidden" id="DepreciarIn[<? echo "$fila[0]|$fila[1]";?>]" name="DepreciarIn[<? echo "$fila[0]|$fila[1]";?>]" />
            <input type="hidden" name="Qota[<?echo "$fila[0]|$fila[1]";?>]" value="<? echo $VrCuota;?>" />
            <input type="hidden" name="DepD[<? echo "$fila[0]|$fila[1]";?>]" value="<? echo $fila[6];?>" />
            <input type="hidden" name="QEje[<? echo "$fila[0]|$fila[1]";?>]" value="<? echo $Cuotas["$fila[0]$fila[1]"];?>" />
            <input type="hidden" name="NomElemento[<? echo "$fila[0]|$fila[1]"?>]" value="<? echo "$fila[2] $fila[3]";?>" />
            <?
            $cont++;
            }
            ?>
    </table>
</form>
<?
if($HacerSubmit)
{
    unset($HacerSubmit);
    ?>
    <script language="javascript">
        document.FORMA.submit();
    </script>
    <?
}
?>