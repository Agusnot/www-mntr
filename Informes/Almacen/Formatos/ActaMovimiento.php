<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
        $cons="Select * from Consumo.AlmacenesPpales Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and SSFarmaceutico=1";
        $res = ExQuery($cons);
        if(ExNumRows($res)>0){$SSF=1;}
	$cons = "Select AutoId,Item,Conforme,Cierre,Encabezado,PiedePagina from Consumo.ActasMovimiento where
	Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Comprobante = '$Comprobante' and Numero='$Numero'";
	$res=ExQuery($cons);
	while($fila = ExFetch($res))
	{
            $Item[$fila[1]][$fila[0]] = $fila[1];
            $Conforme[$fila[0]] = $fila[2];
            $Encabezado = $fila[4];
            $PiedePagina = $fila[5];
	}
        if($SSF)
        {
            $cons = "Select AutoId,Lote,Vence,Cantidad from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
            and Numero='$Numero' and Cerrado=1 and TMPCOD is NULL";
            $res = ExQuery($cons);
            while($fila=ExFetch($res))
            {
                $Lotes[$c]=array($fila[0],$fila[1],$fila[2],$fila[3]);
                $c++;
            }
        }

?>
<head>
	<title><? echo $Sistema[$NoSistema]?></title>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
	<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5" width="100%">
    	<tr align="center">
        	<td colspan="3"><font style="font : 15px Tahoma;font-weight:bold">
				<? echo $Compania[0]?><br /></font>
             </td>
        </tr>
        <tr align="center">
        	<td colspan="3"><font style="font : 12px Tahoma;">Acta de <? echo $Comprobante?></font></td>
        </tr>
        <tr><td colspan="3">
        <pre>
        
        </pre>
        </td></tr>
        <tr><td width="16%" bgcolor="#e5e5e5">Fecha y Hora:</td><td><? echo "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]"?></td>
        	<td align="center" width="15%" bgcolor="#e5e5e5">No. Acta</td></tr>
        <tr><td bgcolor="#e5e5e5">Usuario:</td><td><? echo $usuario[0]?></td>
        	<td align="center"><? echo $Numero?></td></tr>
        <tr><td colspan="3">
        <pre>
        
        </pre>
        <tr>
        	<td colspan="3">
            	<? echo $Encabezado; ?>
			</td>
        </tr>
        <tr>
        	<td colspan="3">Productos relacionados:</td>
        </tr>
        <tr>
        	<td colspan="3">
            	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        		<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5">
                	<td>Nombre del Producto</td><td>Items Relacionados</td><td>Conforme</td>
                        <? if($SSF){ ?><td width="10%">Lote</td><td width="10%">Vence</td><? }?><td>Cantidad</td>
                </tr>
            	<?
                    $cons = "Select distinct(CodProductos.AutoId),NombreProd1,UnidadMedida,
                    Presentacion,CodProductos.Grupo,Movimiento.AutoId,cantidad from Consumo.CodProductos,Consumo.Movimiento
                    where CodProductos.AutoId = Movimiento.AutoId and
                    Movimiento.Compania = '$Compania[0]' and CodProductos.Compania='$Compania[0]'
                    and Movimiento.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero'
                    and Movimiento.Anio = $Anio and CodProductos.Anio=$Anio
                    and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.AlmacenPpal='$AlmacenPpal' order by Movimiento.AutoId";
                    $res = ExQuery($cons);
                    $Objetos = 0;
                    while($fila = ExFetch($res))
                    {
                        echo "<tr><td>$fila[1] $fila[2] $fila[3]</td>";
                        $cons1 = "Select Item from Consumo.ItemsxGrupo where Compania = '$Compania[0]' and
                        AlmacenPpal = '$AlmacenPpal' and Grupo = '$fila[4]' and Anio=$Anio";
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
                            if($Item[$fila1[0]][$fila[5]])
                            {
                                ?>
                                <td align="center" valign="middle"><img border="0" src="/Imgs/b_check.png"/></td>
                                <?
                            }
                            else
                            {
                                echo "<td>&nbsp;</td>";
                            }
                        }
                        echo "</table></td>";
                        if($Conforme[$fila[5]])
                        {
                            ?><td align="center"><img src="/Imgs/b_check.png"></td><?
                            if($SSF)
                            {echo "<td>&nbsp;</td><td>&nbsp;</td>";}    
                        }
                        else{echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";}
                        echo "<td align='right'>".number_format($fila[6],2)."</td>";
                        if($SSF)
                        {
                            $TotCant=0;
                            if($Lotes)
                            {
                                foreach ($Lotes as $Lote)
                                {
                                    if($Lote[0]==$fila[5])
                                    {
                                        echo "<tr><td></td><td></td><td></td><td>$Lote[1]</td><td>$Lote[2]</td>
                                        <td align='right'>".number_format($Lote[3],2)."</td></tr>";
                                        $TotCant = $TotCant + $Lote[3];
                                    }
                                }    
                            }
                            if($TotCant < $fila[6])
                            {
                                echo "<tr><td></td><td></td><td></td><td>Sin lote</td><td>Sin vencimiento</td><td align='right'>".number_format($fila[6]-$TotCant,2)."</td></tr>";
                            }    
                        }
                    }
                    ?>
                </table>
            </td>
            <tr>
        		<td colspan="3">
                	<? echo $PiedePagina; ?>
				</td>
        	</tr>
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
                        	<td>Nombre y Firma</td><td width="5%">&nbsp;</td>
                        	<td>Nombre y Firma</td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
</form>
</body>