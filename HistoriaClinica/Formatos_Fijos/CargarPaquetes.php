<?
include("Funciones.php");
if($DatNameSID){session_name("$DatNameSID");}
session_start();
$ND = getdate();
$cons = "Select TipoServicio from Salud.Servicios 
Where Compania = '$Compania[0]' and NumServicio = $NumServicio LIMIT 1";
$res = ExQuery($cons);
$fila = ExFetch($res);
$TipoServicio = $fila[0];
if($Cargar)
{
    $cons = "Select Codigo,Finalidad,Detalle,Justificacion,AlmacenPpal,Cantidad,ViaSumnistro,Nota,Posologia,TipoFinalidad,Tipo
    from COntratacionSalud.ItemsxPaquete
    Where Compania = '$Compania[0]' and IdPaq = $Paquete order by Tipo";//echo $cons;exit;
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        if($fila[10]=="Medicamentos")
        {
            $cons_Insert = "Insert into Salud.PlantillaMedicamentos
            (Compania,AlmacenPpal,AutoidProd,Usuario,FechaFormula,CedPaciente,
            FechaIni,FechaFin,CantDiaria,ViaSuministro,Justificacion,Notas,Estado,
            NumServicio,Detalle,TipoMedicamento,Posologia,NumOrden,IdEscritura,DosisUnica)
            values
            ('$Compania[0]','$fila[4]',$fila[0],'$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',
            '$ND[year]-$ND[mon]-$ND[mday]','$ND[year]-$ND[mon]-$ND[mday]',$fila[5],'$fila[6]','$fila[3]','$fila[7]','AC',
            $NumServicio,'$fila[2]','Medicamento Urgente','$fila[8]',$NoOrden,$IdEscritura,0)";
            $res_Insert = ExQuery($cons_Insert);
            
            $cons_Insert = "Insert into Salud.OrdenesMedicas
            (Compania,Fecha,Cedula,
            NumServicio,Detalle,IdEscritura,NumOrden,Usuario,TipoOrden,
            Estado,Acarreo,Posologia,DosisUnica,ViaSumin)
            values
            ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',
            $NumServicio,'$fila[2]',$IdEscritura,$NoOrden,'$usuario[1]','Medicamento Urgente',
            'AC',0,'$fila[8]',0,'$fila[6]')";
            $res_Insert = ExQuery($cons_Insert);
            
            $cons_Insert = "Insert into salud.HoraCantidadxMedicamento
            (Compania,AlmacenPPal,AutoId,NoFormula,Hora,Cantidad,
            Nota,Paciente,Tipo,Fecha,Estado,NumOrden,IdEscritura,Via)
            values
            ('$Compania[0]','$fila[4]',$fila[0],1,$ND[hours],$fila[5],
            '$fila[7]','$Paciente[1]','U','$ND[year]-$ND[mon]-$ND[mday]','AC',$NoOrden,$IdEscritura,'$fila[6]')";
            $res_Insert = ExQuery($cons_Insert);
            $NoOrden++;
        }
        else
        {
            $cons_Insert = "Insert into salud.PlantillaProcedimientos
            (Compania,usuario,Cedula,FechaIni,FechaFin,CUP,
            AmbitoReal,FinProced,Detalle,NumServicio,Estado,Justificacion,
            Observaciones,NumProcedimiento,Diagnostico,CausaExterna,TipoDx,Externo,IdEscritura,NumOrden)
            values
            ('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$ND[year]-$ND[mon]-$ND[mday]','$fila[0]',
            '$TipoServicio',$fila[1],'$fila[2]',$NumServicio,'AC','$fila[3]',
            '$fila[7]',1,'$CodDiagnostico1','13','$TipoDx',1,$IdEscritura,$NoOrden)";
            $res_Insert = ExQuery($cons_Insert);
            
            $cons_Insert = "Insert into Salud.OrdenesMedicas
            (Compania,Fecha,Cedula,
            NumServicio,Detalle,IdEscritura,NumOrden,Usuario,TipoOrden,
            Estado,Acarreo,Posologia,DosisUnica,ViaSumin)
            values
            ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',
            $NumServicio,'$fila[2]',$IdEscritura,$NoOrden,'$usuario[1]','Procedimiento',
            'AC',0,'$fila[8]',0,'$fila[6]')";
            $res_Insert = ExQuery($cons_Insert);
            $NoOrden++;
        }
        ?><script language="javascript">
             frames.parent.location.href="NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";
        </script><?
    }
}
?>
<script language="javascript">
    function ValidaDiagnostico2(Objeto1,Objeto2)
	{		
		frames.FrameOpener2.location.href="ValidaDiagnostico2.php?DatNameSID=<? echo $DatNameSID?>&NameCod="+Objeto1.name+"&NameNom="+Objeto2.name;
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top='60px';
		document.getElementById('FrameOpener2').style.left='0px';
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='593px';
		document.getElementById('FrameOpener2').style.height='300px';
	}
    function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?
if($Procedimiento){$AdTipo = " and Tipo = 'CUP'";$TipoPaq="CUPS";}
else{$AdTipo = " and Tipo = 'Medicamentos'";$TipoPaq="Medicamento";}
$cons = "Select Paquete,IdPaquete 
from contratacionsalud.paquetesxcontratos,ContratacionSalud.ItemsxPaquete 
Where IdPaq = IdPaquete and Codigo = '$Codigo' $AdTipo
and Entidad='$Entidad' and NoContrato='$NoContrato' and Contrato like '$Contrato%'";
$res = ExQuery($cons);//echo $cons;exit;
if(ExNumRows($res)==0)
{
    ?>
    <script language="javascript">
        CerrarThis();
        parent.document.FORMA.submit();
    </script>
    <?
}
else
{
    ?>
    <script language="javascript">
        if(!confirm("El <?echo $TipoPaq?> esta ligado a uno o mas paquetes. Desea Ligar los paquetes?"))
        {
            CerrarThis();
            parent.document.FORMA.submit();
        }
    </script>
    <form name="FORMA" method="post">
        <center>
            <input type="submit" name="Cargar" value="Cargar" />
            <input type="button" name="Cancelar" value="Cancelar"
                   onclick="CerrarThis();
                    parent.document.FORMA.submit();"/>
        </center>
        <table border="1" bordercolor="#e5e5e5" width="90%" align="center" style='font : normal normal small-caps 13px Tahoma;'>
            <tr>
                <td colspan="7" align="center" style="font-weight:bold" bgcolor="#e5e5e5">Diagnostico</td>
            </tr>
            <tr>
                <td align="left" colspan="7" style="font-weight:bold">
                    Codigo <input style="width:100" type="text" readonly name="CodDiagnostico1" 
                    onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  
                    onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" 
                    onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>" />
                    Nombre <input type="text" style="width:280px" name="NomDiagnostico1" readonly 
                    onFocus="ValidaDiagnostico2(CodDiagnostico1,this)" 
                    onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" 
                    onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>" />
                </td>
            </tr>
            <?  
            $consxx="select tipodiagnost,codigo from salud.tiposdiagnostico where compania='$Compania[0]'";
            $resxx=ExQuery($consxx);?>
            <tr>
                <td colspan="7" align="center" style="font-weight:bold">Tipo de Diagnostico
                    <select name="TipoDx"><?
                        while($filaxx=ExFetch($resxx)){
                            if($TipoDx==$fila[1]){
                                echo "<option value='$filaxx[1]' selected>$filaxx[0]</option>";
                            }
                            else{
                                echo "<option value='$filaxx[1]'>$filaxx[0]</option>";
                            }			
                        }
                ?>	</select>
                </td>
            </tr>
            <tr bgcolor="#e5e5e5" style=" font-weight: bold">
                <td width="5%">Cargar</td><td colspan="2">Paquete</td>
            </tr>
            <?
            while($fila = ExFetch($res))
            {
                ?>
                <tr bgcolor="#e5e5e5">
                    <td align="center"><input type="radio" name="Paquete" value="<?echo $fila[1]?>" /></td>
                    <td colspan="2"><? echo $fila[0]?></td>
                </tr>
                <tr>
                    <td>Codigo</td><td>Detalle</td><td>Cantidad</td>
                </tr>
                <?
                $cons1 = "Select Codigo,Detalle,Cantidad,ViaSumnistro
                from ContratacionSalud.ItemsXPaquete Where COmpania='$Compania[0]'
                and IdPaq = $fila[1] order by Tipo,Detalle";
                $res1 = ExQuery($cons1);
                while($fila1 = ExFetch($res1))
                {
                    if($fila1[0]=="$Codigo"){$BgColor=" bgcolor=#009933 ";}else{$BgColor="";}
                    ?>
                    <tr <? echo $BgColor?> id="<?echo $fila[0]?>">
                        <td><? echo $fila1[0]?></td>
                        <td><? echo "$fila1[1] $fila1[3]";?></td>
                        <td><? echo $fila1[2];?></td>
                    </tr>
                    <?
                }
            }
            ?>
        </table>
    </form>
    <?
}
?>
<iframe scrolling="no" id="FrameOpener2" name="FrameOpene2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>