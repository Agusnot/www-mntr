<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
?>
<head>
    <title>
        Acta de recepcion tecnica
    </title>
</head>
<?
	$ND=getdate();
	if($Guardar)
	{	
		if($Editar)
		{
                    $cons = "Delete from Consumo.ActasMovimiento where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Comprobante ='$Comprobante'
                    and Numero='$Numero'";
                    $res = ExQuery($cons);
                    unset($Editar);
		}
		
		if($Item)
		{
                    while(list($cad,$val) = each($Item))
                    {
                        while( list($cad1,$val1) = each($val))
                        {
                            $dat=explode("-",$cad1);
                            $cons = "Insert into Consumo.ActasMovimiento
                            (Compania,AlmacenPpal,Comprobante,Numero,AutoId,Item,Cierre,Encabezado,PiedePagina,FechaActa,Laboratorio,Presentacion)
                            values('$Compania[0]','$AlmacenPpal','$Comprobante','$Numero',$dat[0],'$cad',0,'$Encabezado','$PiedePagina',
                            '$ND[year]-$ND[mon]-$ND[mday]','$dat[1]','$dat[2]')";
                            $res = ExQuery($cons);
                            $Editar[$cad1] = 1;
                            //echo $cons;
                        }
                    }
		}
                else
                {
                    $Itemxx ="NoItem";
                }
		
		if($Conforme)
		{
                    while(list($cad,$val) = each($Conforme))
                    {
                        $dat=explode("-",$cad);
                        if(!$Editar[$cad])
                        {
                            $cons = "Insert into Consumo.ActasMovimiento (Compania,AlmacenPpal,Comprobante,Numero,AutoId,Conforme,Cierre,Encabezado,PiedePagina,
                            FechaActa,Item,Laboratorio,Presentacion)
                            values('$Compania[0]','$AlmacenPpal','$Comprobante','$Numero',$dat[0],1,0,'$Encabezado','$PiedePagina',
                            '$ND[year]-$ND[mon]-$ND[mday]','$Itemxx','$dat[1]','$dat[2]')";
                            $res = ExQuery($cons);
                            $Editar[$cad] = 1;
                        }
                        else
                        {
                            $cons = "Update Consumo.ActasMovimiento set Conforme=1, Encabezado='$Encabezado', PiedePagina='$PiedePagina'
                            Where AutoId=$dat[0] and Laboratorio='$dat[1]' and Presentacion = '$dat[2]'
                            and Comprobante='$Comprobante' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
                            and Numero='$Numero'";
                            $res = ExQuery($cons);
                        }
                    }
		}
	}
        $cons = "Select Fecha,Numero,NoDocAfectado,DocAfectado,Cedula,PrimNom,SegNom,PrimApe,SegApe,Detalle,UsuarioCre,NoFactura
        from Consumo.Movimiento,Central.terceros Where Comprobante = '$Comprobante'
        and Numero = '$Numero' and AlmacenPpal = '$AlmacenPpal' and Movimiento.Compania = '$Compania[0]'
        and Terceros.Compania = '$Compania[0]' and Terceros.Identificacion = Movimiento.Cedula";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $FechaCompra = $fila[0]; $NumeroCompra=$fila[1];$NoDocAfectado=$fila[2];$DocAfectado=$fila[3];
        $IDProveedor = $fila[4]; $Proveedor = "$fila[5] $fila[6] $fila[7] $fila[8]";$Detalle = $fila[9];
        $UsuarioCompra = $fila[10]; $NoFactura = $fila[11];
	$cons = "Select AutoId,Item,Cierre,Encabezado,PiedePagina,Conforme,Laboratorio,Presentacion from Consumo.ActasMovimiento where
	Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Comprobante = '$Comprobante' and Numero='$Numero'";
        //echo $cons;
        $res = ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$Editar = 1;
		$DisCrear = "";
		$fila=ExFetch($res);
		if($fila[2]==1)
		{ ?> <script language="javascript">
			 //CerrarThis();
			 open("/Informes/Almacen/Formatos/ActaMovimiento.php?Anio=<? echo $Anio?>&DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $Numero;?>&Comprobante=<? echo $Comprobante;?>&AlmacenPpal=<? echo $AlmacenPpal?>",'','width=800,height=600,scrollbars=yes');
                         window.close();
             </script>
		<? }
		else
		{
			$res=ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$Item[$fila[1]]["$fila[0]-$fila[6]-$fila[7]"] = $fila[1];
				$Conforme["$fila[0]-$fila[6]-$fila[7]"] = $fila[5];
				$Encabezado = $fila[3];
				$PiedePagina = $fila[4];
			}
		}
	}
	else
	{ $DisCrear = " disabled ";}
	
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Marcar(Origen,Objeto)
	{
		if(Objeto.checked==1)
		{
			MarcarTodo(Origen);
			
		}
		else
		{
			QuitarTodo(Origen);
		}
	}

	function MarcarTodo(Origen)
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	{
			if(document.FORMA.elements[i].type == "checkbox")
			{
				if(Origen=="Conforme")
				{
					if(document.FORMA.elements[i].name.substr(0,8)=="Conforme")
					{
						document.FORMA.elements[i].checked=1;
					}
				}
				if(Origen=="Item")
				{
					if(document.FORMA.elements[i].name.substr(0,4)=="Item")
					{
						document.FORMA.elements[i].checked=1;
					}
				}
			} 
        	
		}
	}
	function QuitarTodo(Origen)
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	{
			if(document.FORMA.elements[i].type == "checkbox")
			{
				if(Origen=="Conforme")
				{
					if(document.FORMA.elements[i].name.substr(0,8)=="Conforme")
					{
						document.FORMA.elements[i].checked=0;
					}
				}
				if(Origen=="Item")
				{
					if(document.FORMA.elements[i].name.substr(0,4)=="Item")
					{
						document.FORMA.elements[i].checked=0;
					}
				}
			} 
        }
	}
</script>
<style type="text/css">
@media print {
    div,a {display:none}
    .ver {display:block}
    .nover {display:none}
}
</style>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="Comprobante" value="<? echo $Comprobante?>" />
<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="hidden" name="ImpActa" value="<? echo $ImpActa?>" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#000000" width="100%">
    <tr>
        <td rowspan ="2">
            <img src="/Imgs/Logo.jpg" width="100px"/>
        </td>
        <td colspan="3" valign="bottom"><font style="font : 15px Tahoma;font-weight:bold">
	<? echo $Compania[0]?><br /></font>
        </td>
    </tr>
    <tr>
            <td colspan ="3" valign="top"><? echo $Compania[1]?><br><font style="font : 12px Tahoma;">Acta de <? echo $Comprobante?></font>
            </td>
    </tr>
    <tr><td><br><br></td></tr>
        <tr><td bgcolor="#e5e5e5">Fecha y Hora:</td><td><? echo "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]"?></td>
        	<td bgcolor="#e5e5e5">Numero</td><td align="right"><b><? echo $Numero?></b></td></tr>
        <tr><td bgcolor="#e5e5e5">Usuario:</td><td><? echo $usuario[0]?></td>
        	</tr>
        <tr>
            <td bgcolor="#e5e5e5">Fecha de Compra</td><td><? echo $FechaCompra?></td>
            <td bgcolor="#e5e5e5"><? echo $DocAfectado?></td><td  align="right"><b><? echo $NoDocAfectado?></b></td>
        </tr>
        <tr>
            <td bgcolor ="#e5e5e5">Proveedor:</td><td><? echo "$Proveedor ($IDProveedor)"?></td>
            <td bgcolor ="#e5e5e5">Factura</td><td align="right"><b><? echo "$NoFactura"?></b></td>
        </tr>
</table>
<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5" width="100%">
    <tr><td colspan="3">
    <br><br>
        <?
        if(!$Editar)
        {
            $cons = "Select Encabezado,PiedePagina from Consumo.MensajeActas where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
            $res = ExQuery($cons); echo ExError();
            $fila = ExFetch($res);
            $Encabezado = $fila[0]; $PiedePagina = $fila[1];
        }
        ?>
        <!--<tr>
            <td colspan="3">
            <textarea name='Encabezado' rows='4' style='width:100%'><? echo $Encabezado; ?></textarea>
            </td>
        </tr>-->
        <tr>
            <td colspan="3">Productos relacionados:</td>
        </tr>
        <tr>
        	<td colspan="3">
            	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        		<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5">
                	<td rowspan="2">Nombre</td>
                        <td rowspan="2">Concentracion</td>
                        <td rowspan="2">Forma Farmaceutica</td>
                        <td rowspan="2">Lote</td>
                        <td rowspan="2">Vencimiento</td>
                        <td rowspan="2">Fabricante</td>
                        <td rowspan="2">Presentacion</td>
						<td rowspan="2">CUM</td>
                        <td rowspan="2">Reg.INVIMA</td>
                        <td rowspan="2">Cantidad</td>
                    <td>Conforme</td><td>Items Relacionados</td>
                </tr>
                <tr bgcolor="#e5e5e5"><td align="center"><input type="checkbox" title="Marcar Conformes" onClick="Marcar('Conforme',this)"></td>
                <td align="center"><input type="checkbox" title="Marcar Items" onClick="Marcar('Item',this)"></td></tr>
            	<?
                    $cons = "Select NombreProd1,UnidadMedida,Codproductos.Presentacion,CodProductos.Grupo,
                    Lotes.AutoId,Cantidad,Lote,Vence,Consumo.Lotes.Laboratorio,Lotes.RegInvima,Lotes.Presentacion, Consumo.cumsxproducto.cum
                    from Consumo.CodProductos,Consumo.Lotes
                    INNER JOIN consumo.cumsxproducto on Consumo.Lotes.laboratorio=consumo.cumsxproducto.laboratorio
                    and Consumo.Lotes.reginvima=consumo.cumsxproducto.reginvima and Consumo.Lotes.presentacion=consumo.cumsxproducto.presentacion
                    and Consumo.Lotes.autoid=consumo.cumsxproducto.autoid
                    where CodProductos.AutoId = Lotes.AutoId and
                    Lotes.Compania = '$Compania[0]' and CodProductos.Compania='$Compania[0]'
                    and Lotes.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
                    and Numero='$Numero' and CodProductos.Anio = $Anio";
                    $res = ExQuery($cons);
                    $Objetos = 0;
                    while($fila = ExFetch($res))
                    {
                        echo "<tr>
                        <td>$fila[0]</td>
                        <td>$fila[1]</td>
                        <td>$fila[2]</td>
                        <td>$fila[6]</td>
                        <td>$fila[7]</td>
                        <td>$fila[8]</td>
						<td>$fila[10]</td>
                        <td>$fila[11]</td>
                        <td>$fila[9]</td>
                        <td align='right'>$fila[5]</td>";
                        ?>
                        <input type="hidden" name="Laboratorio[<?echo $fila[4]?>]" value="<? echo $fila[8]?>" />
                        <input type="hidden" name="Presentacion[<?echo $fila[4]?>]" value="<? echo $fila[10]?>" />
                        <td align="center"><input type="checkbox" name="Conforme[<? echo "$fila[4]-$fila[8]-$fila[10]" ?>]" title="Conforme"
                        <? if($Conforme["$fila[4]-$fila[8]-$fila[10]"]) echo " checked "; ?> /></td><?
                        $cons1 = "Select Item from Consumo.ItemsxGrupo where Compania = '$Compania[0]' and
                        AlmacenPpal = '$AlmacenPpal' and Grupo = '$fila[3]' and Anio = $Anio";
                        $res1 = ExQuery($cons1);
                        echo "<td><table style='font : normal normal small-caps 10px Tahoma;' width='100%' border='0'>";
                        echo "<tr>";
                        while ($fila1 = ExFetch($res1))
                        {
                                echo "<td bgcolor='#e5e5e5'>$fila1[0]</td>";
                        }
                        echo "</tr>";
                        $res1 = ExQuery($cons1);
                        echo "<tr>";
                        while ($fila1 = ExFetch($res1))
                        {
                                ?><td align="center"><input type="checkbox" name="Item[<? echo $fila1[0]?>][<? echo "$fila[4]-$fila[8]-$fila[10]"?>]"
                            <? if($Item[$fila1[0]]["$fila[4]-$fila[8]-$fila[10]"]) { echo " checked ";} ?> /></td><?
                        }
                        echo "</table></td>";
                        $Objetos++;
                        ?>
                                        
                        <? $Objetos++; ?>
                        </tr>
                    <?
                    }
				?>
                </table>
            </td>
            <!--<tr>
        		<td colspan="3">
                	<textarea name='PiedePagina' rows='4' style='width:100%'><? echo $PiedePagina; ?></textarea>
				</td>
        	</tr>-->
            <tr>
            	<td colspan="3">
                	<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5" width="100%">
                    	<tr>
                        	<td><pre>
                            
                            </pre></td>
                        </tr>
                        <tr>
                        	<td>__________________________________________________</td><td width="5%">&nbsp;</td>
                        	<td>__________________________________________________</td>
                        </tr>
                        <tr>
                        	<td>Recibe <? echo $usuario[0]?></td><td width="5%">&nbsp;</td>
                        	<td>Entrega</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td colspan="3" align="right">
                	<input type="Hidden" name="Objetos" value="<? echo $Objetos?>" />
                	<input type="Hidden" name="Editar" value="<? echo $Editar?>" />
                	<!--<button type='submit' name='CerrarActa' <? echo $DisCrear; ?>><img title='Crear Acta Permanente' src='/Imgs/b_deltbl.png' ></button>-->
                        <div class="nover">
                        <?if(!$Editar)
                        {
                        ?>
                        <button type='submit' name='Guardar' style="width: 30px; height: 30px">
                            <img title='Guardar Acta Para Imprimir' src='/Imgs/b_save.png' ></button>
                        <?    
                        }?>
                        <button type='button' name='Guardar' onclick="window.print()" style="width: 30px; height: 30px">
                            <img title='Imprimir' src='/Imgs/b_print.png' ></button></div>
		</td>
            </tr>
    </table>
</form>
</body>