<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if($Guardar)
{
    $consxx = "";
    if($Asignar)
    {
        $cons = "Select IdPaquete from ContratacionSalud.PaquetesxContratos
        Where Compania = '$Compania[0]' order by IdPaquete desc LIMIT 1";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $Paq = $fila[0] + 1;
        while(list($cad,$val)=each($Asignar))
        {
            $Asignar_Paquete = explode("|$DatNameSID|",$cad);
            //--------ENTIDAD------------------CONTRATO------------------NoCOntrato
            //echo "$Asignar_Paquete[0]-----$Asignar_Paquete[1]-----$Asignar_Paquete[2]";
            $cons1 = "Insert into ContratacionSalud.PaquetesxContratos values 
            ('$Compania[0]',$Paq,'$Asignar_Paquete[1]','$Asignar_Paquete[2]','$Paquete',
            '$Asignar_Paquete[0]','$usuario[1]',
            '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
            '$usuario[1]',
            '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
            $res1 = ExQuery($cons1);
            
            $cons1 = "Insert into ContratacionSalud.ItemsxPaquete
            (
            Select Compania,$Paq,'$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
            Codigo,Tipo,Finalidad,Detalle,Justificacion,AlmacenPpal,Cantidad,ViaSumnistro,Nota,Posologia,
            '$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',TipoFinalidad
            from ContratacionSalud.ItemsxPaquete Where IdPaq = $IdPaquete
            )";
            $res1 = ExQuery($cons1);
            $Paq++;
        }
    }
}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<?echo $DatNameSID?>" />
    <input type="hidden" name="IdPaquete" value="<?echo $IdPaquete?>" />
    <?
    $cons = "Select Entidad,Contrato,Numero,PrimApe,SegApe,PrimNom,SegNom
    from COntratacionSalud.COntratos, Central.Terceros
    Where Contratos.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]'
    and Entidad = Identificacion and Contrato != '$Contrato' and Numero != '$NoContrato'
    order by Entidad, Contrato,Numero";
    $res = ExQuery($cons);
    ?>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
        <tr bgcolor="#e5e5e5">
            <td colspan="3" align="center">
                <?echo "Paquete: $Paquete <br>ENTIDAD $NomEntidad<br>CONTRATO: $Contrato<br>No.CONTRATO: $NoContrato";?>
                <br>
                <input type="submit" name="Guardar" value="Guardar" />
                <input type="button" name="Cancelar" value="Cancelar" />
            </td>
        </tr>
        <?
        while($fila = ExFetch($res))
        {
            if($fila[0]!=$EntAnt)
            {
                ?>
                <tr bgcolor="#e5e5e5" style="font-weight: bold">
                    <td colspan="3"><? echo "$fila[3] $fila[4] $fila[5] $fila[6]";?></td>
                </tr>
                <tr bgcolor="#e5e5e5">
                    <td>Contrato</td><td>No.Contrato</td><td>Asignar Paquete</td>
                </tr>
                <?
            }
            ?>
                <tr>
                    <td><?echo $fila[1]?></td>
                    <td><?echo $fila[2]?></td>
                    <td>
                        <input type="checkbox" name="Asignar[<?echo "$fila[0]|$DatNameSID|$fila[1]|$DatNameSID|$fila[2]"?>]" />
                    </td>
                </tr>
            <?
            $EntAnt = $fila[0];
        }
        ?>
    </table>
</form>
</body>