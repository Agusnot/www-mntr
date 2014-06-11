<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if(!$FechaIni){$FechaIni = "$ND[year]-$ND[mon]-$ND[mday]";}
if(!$FechaFin){$FechaFin = "$ND[year]-$ND[mon]-$ND[mday]";}
if(!$Verx){$Verx="Pacientes";}
?>
<script language="javascript">
    function Cambiar(Valor)
    {
        if(Valor=="Medicamentos")
        {
            document.FORMA.Campo_Cedula.style.width="0";
            document.FORMA.Campo_Cedula.style.visibility = "collapse";
            document.FORMA.Cedula.style.width="0";
            document.FORMA.Cedula.style.visibility = "collapse";
        }
    }
</script>
<form name="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<?echo $DatNameSID?>" />
    <input type="hidden" name="AlmacenPpal" value="<?echo $AlmacenPpal?>" />
    <table style='font : normal normal small-caps 12px Tahoma;' border="0">
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>
                Ver por:
            </td>
            <td>
                <select Name="Verx" onchange="Cambiar(this.value)">
                    <option <?if($Verx=="Pacientes"){echo " selected ";}?> value="Pacientes">Paciente</option>
                    <option <?if($Verx=="Medicamentos"){echo " selected ";}?> value="Medicamentos">Medicamentos</option>
                </select>
            </td>
            <td><input type="text" name="Campo_Cedula" size="4" value="Cedula:"
                       style="background: #e5e5e5; font-weight: bold; border: 0;"/></td>
            <td>
                <input type="text" name="Cedula" size="8"/>
            </td>
            <td><input type="text" name="Campo_Detallado" size="4" value="Detalle"
                       style="background: #e5e5e5; font-weight: bold; border: 0; width: 0"/>
                <input type="checkbox" name="Detalledo"/>
            </td>
            
            <td>Desde:</td>
            <td>
                <input type="text" name="FechaIni" value="<?echo "$FechaIni"?>" size="8" />
            </td>
            <td>Hasta:</td>
            <td>
                <input type="text" name="FechaFin" value="<?echo "$FechaFin"?>" size="8" />
            </td>
            <td>
                <input type="submit" name="Ver" value="Ver" />
            </td>
        </tr>
    </table>
    <?
    if($Verx=="Pacientes")
    {
        if($Cedula){$Ad_Cons=" and Cedula = '$Cedula'";}
        $cons = "Select Movimiento.Autoid,Fecha,Cedula,Numero,NombreProd1,
        UnidadMedida,Presentacion,Codigo1,Movimiento.UsuarioCre,
        PrimNom,SegNom,PrimApe,SegApe,Cantidad
        from Consumo.Movimiento,Consumo.CodProductos,Central.Terceros
        Where Movimiento.Compania='$Compania[0]' and CodProductos.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]'
        and CodProductos.AlmacenPpal = '$AlmacenPpal' and Movimiento.AlmacenPpal = '$AlmacenPpal'
        and CodProductos.Autoid = Movimiento.Autoid and Cedula = Identificacion
        and Fecha>='$FechaIni' and Fecha <='$FechaFin' $Ad_Cons
        and Comprobante='Salidas Urgentes' order by Cedula,Fecha,NombreProd1,UnidadMedida,Presentacion";
        $res = ExQuery($cons);
        ?>
        <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"><?
        while($fila=ExFetch($res))
        {
            if($fila[2]!=$CedAnt)
            {
                ?>
                <tr bgcolor="#e5e5e5" style="font-weight: bold">
                    <td colspan="6"><?echo "$fila[9] $fila[10] $fila[11] $fila[12] ($fila[2])";?></td>
                </tr>
                <tr bgcolor="#e5e5e5">
                    <td>Codigo</td><td>Numero</td><td>Medicamento</td><td>Cantidad</td>
                    <td>Fecha</td><td>Usuario</td>
                </tr>
                <?
            }
            ?>
            <tr>
                <td><? echo $fila[7]?></td><td><? echo $fila[3]?></td>
                <td><? echo "$fila[4] $fila[5] $fila[6]"?></td><td><? echo $fila[13]?></td>
                <td><? echo $fila[1] ?></td><td><? echo$fila[8]?></td>
            </tr>
            <?
            $CedAnt = $fila[2];
        }
        ?>
        </table><?    
    }
    else
    {
        $cons = "Select Movimiento.Autoid,Fecha,Cedula,Numero,NombreProd1,
        UnidadMedida,Presentacion,Codigo1,Movimiento.UsuarioCre,
        PrimNom,SegNom,PrimApe,SegApe,Cantidad
        from Consumo.Movimiento,Consumo.CodProductos,Central.Terceros
        Where Movimiento.Compania='$Compania[0]' and CodProductos.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]'
        and CodProductos.AlmacenPpal = '$AlmacenPpal' and Movimiento.AlmacenPpal = '$AlmacenPpal'
        and CodProductos.Autoid = Movimiento.Autoid and Cedula = Identificacion
        and Fecha>='$FechaIni' and Fecha <='$FechaFin' $Ad_Cons
        and Comprobante='Salidas Urgentes' order by Cedula,Fecha,NombreProd1,UnidadMedida,Presentacion";
    }
    
?></form>