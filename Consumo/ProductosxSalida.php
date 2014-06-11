<? 
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$cons = "Select NoDocAfectado,AutoId,Numero,Cantidad from Consumo.Movimiento Where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Estado = 'AC'
and NoDocAfectado='$Numero' and Comprobante = 'Devoluciones' and TipoComprobante='Devoluciones'";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
    $Devoluciones[$fila[0]][$fila[1]]=array($fila[2],$fila[3]);
}
if($Guardar)
{
    while(list($cad,$val)=each($Devolucion))
    {
        if($val)
        {
            $cons = "Select * from Consumo.TmpMovimiento Where TMPCOD='$TMPCOD' 
            and NoDocAfectado='$Numero' and DocAfectado='$Comprobante'
            and AutoId=$cad";
            $res = ExQuery($cons);
            if(ExNumRows($res)>0){$Editar = 1;}else{unset($Editar);}
            if(!$NS[$cad]){$NS[$cad]=0;}
            if($Editar)
            {
                $cons = "Update Consumo.TmpMovimiento set Cantidad = $val 
                Where TMPCOD='$TMPCOD' and NoDocAfectado='$Numero' and DocAfectado='$Comprobante'
                and AutoId=$cad ";
            }
            else
            {
                $cons = "Insert into Consumo.TmpMovimiento (TMPCOD,AutoId,Cantidad,NoDocAfectado,DocAfectado,CentroCosto,NumServicio) 
                values ('$TMPCOD',$cad,$val,'$Numero','$Comprobante','".$CC[$cad]."',".$NS[$cad].")";
            }
            //echo $cons;
            $res = ExQuery($cons);
			?>
			<script language="javascript">
            	parent.document.FORMA.submit();
			</script>
			<?
        }
    }
}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
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
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="TMPCOD" value ="<? echo $TMPCOD?>" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Comprobante" value="<? echo $Comprobante?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="Cedula" value="<? echo $Cedula?>" />
<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<?
    $cons = "Select AutoId,Cantidad from Consumo.TmpMovimiento Where TMPCOD='$TMPCOD' and NoDocAfectado='$Numero' and DocAfectado='$Comprobante'";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $Devolucion[$fila[0]]=$fila[1];
    }
?>
<div align="right">
    <button type="button" title="Cerrar" onclick="CerrarThis()">
        Cerrar
    </button>
    <button type="button" title="Volver" onclick="parent.CargarSalidas('<? echo $Cedula?>','<? echo $AlmacenPpal?>','<? echo $Anio?>','<? echo $Numero?>')">
        Volver
    </button>
    <button type="submit" title="Guardar" name="Guardar" onclick="parent.CargarNuevaDevolucion('<? echo $Cedula?>','<? echo $AlmacenPpal?>','<? echo $Anio?>','<? echo $Numero?>')">
        Guardar
    </button>
</div>
    <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' width="100%">
        <tr bgcolor="<? echo $Estilo[1]?>" style="color: #FFFFFF; font-weight: bold">
            <td colspan="3" align="center">Salida <? echo $Numero?></td>
        </tr>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>Producto</td><td>Salida</td><td>Devolucion</td>
        </tr>
        <?
        $cons = "Select Movimiento.Autoid,NombreProd1,UnidadMedida,Presentacion,Cantidad,CentroCosto,NumServicio
        from Consumo.Movimiento,Consumo.CodProductos
        Where Movimiento.AutoId = CodProductos.AutoId and CodProductos.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]'
        and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Anio=$Anio 
        and Comprobante='$Comprobante' and Numero='$Numero'";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            //echo $fila[5]."-----".$fila[6]."<br>";
            if($Devoluciones[$Numero][$fila[0]]){ $TitDevolucion="Afectado previamente por devolucion ".$Devoluciones[$Numero][$fila[0]][0];}
        ?>
            <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="<? echo $TitDevolucion;?>">
                <td><? echo "$fila[1] $fila[2] $fila[3]"?></td>
                <td align="right"><? echo number_format($fila[4]-$Devoluciones[$Numero][$fila[0]][1],2)?></td>
                <td>
                    <input type="text" name="Devolucion[<? echo $fila[0]?>]" value="<?echo $Devolucion[$fila[0]]?>" size="3" style=" text-align: right"
                    onkeyup="xNumero(this)" onkeydown="xNumero(this)"
                    onBlur="
                        if(parseInt(this.value)>parseInt(<? echo $fila[4]-$Devoluciones[$Numero][$fila[0]][1]?>)){this.value='';};
                        if(parseInt(this.value)<0){this.value='';};
                        campoNumero(this);"/>
                    <input type="hidden" name="CC[<?echo $fila[0]?>]" value="<?echo $fila[5]?>" />
                    <input type="hidden" name="NS[<?echo $fila[0]?>]" value="<?echo $fila[6]?>" />
                </td>
            </tr>
        <?}
        ?>
    </table>
</form>
</body>