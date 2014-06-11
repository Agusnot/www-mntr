<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        //UM:26-04-2011
	if($Guardar)
	{
            if($Aprobar)
            {
                while(list($cad,$val) = each($Aprobar))
                {
                    $Upt = "";
                    if($val == "Aprobar"){ $Upt = " Estado = 'Aprobado'";}
                    if($val == "Rechazar"){ $Upt = " Estado = 'Rechazado'";}
                    if($Upt)
                    {
                        $cons = "Update Infraestructura.Traslados set $Upt,
                        UsuarioAR = '$usuario[0]', FechaAR='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
                        Where Compania='$Compania[0]' and Numero = '$cad'";
                        $res = ExQuery($cons);		
                    }
                }
            }
	}
	if($Reversar)
	{
		while(list($cad,$val)=each($Reversar))
		{
			$Valores = explode(",",$cad);
			$cons = "Update Infraestructura.Traslados set Estado='Solicitado', UsuarioAR='', FechaAR=NULL Where Compania='$Compania[0]'
			and Numero='$Valores[0]' and CCDestino='$Valores[2]'";
			$res = ExQuery($cons);
		}
	}
	if($Ejecutar)
	{
		while(list($cad,$val) = each($Ejecutar))
		{
			$Valores = explode(",",$cad);
			//$Valores[0]->Numero, $Valores[1]->Cedula, $Valores[2]->CCDestino
			$cons = "Update Infraestructura.Traslados set Estado='Ejecutado' Where Compania='$Compania[0]' and Numero='$Valores[0]'";
			$res = ExQuery($cons);
			$cons = "Select AutoId from InfraEstructura.Traslados Where Compania='$Compania[0]' and Numero = '$Valores[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$cons1 = "Update Infraestructura.Ubicaciones set FechaFin = '$ND[year]-$ND[mon]-$ND[mday]' Where AutoId = $fila[0]
				and FechaFin is NULL and Compania='$Compania[0]'";
				$res1 = ExQuery($cons1);
				$cons1 = "Insert into InfraEstructura.Ubicaciones (Compania,CentroCostos,Responsable,FechaIni,AutoId,UsuarioCrea,FechaCrea,SubUbicacion)
				values('$Compania[0]','$Valores[2]','$Valores[1]','$ND[year]-$ND[mon]-$ND[mday]',$fila[0],
				'$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','".$SubUbD[$Valores[2]]."')";
				$res1 = ExQuery($cons1);	
			}
		}		
	}
	if($Tipo == "Aprobar"){ $EstadoTipo = " and Traslados.Estado != 'Ejecutado' "; $Tit = "APROBACION";}
	if($Tipo == "Ejecutar"){ $EstadoTipo = " and Traslados.Estado = 'Aprobado'"; $Tit = "EJECUCION";}
	$cons = "Select FechaSolicita,Numero,Traslados.Cedula,PrimApe,SegApe,PrimNom,SegNom,
	CCDestino,CentrosCosto.CentroCostos,Traslados.Estado,SubUbicacionDestino
	From InfraEstructura.Traslados, InfraEstructura.CodElementos, Central.CentrosCosto, Central.Terceros Where
	Traslados.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]'
	and Traslados.AutoId = CodElementos.AutoId and Traslados.Cedula = Terceros.Identificacion and CentrosCosto.Codigo = Traslados.CCDestino
	and CentrosCosto.Anio = $ND[year] and Terceros.Compania='$Compania[0]' and Traslados.Estado != 'ANULADO' $EstadoTipo 
	Group by Numero,FechaSolicita,Traslados.Cedula,primape,segape,primnom,segnom,ccdestino,CentrosCosto.centrocostos,Traslados.Estado,SubUbicacionDestino
        order by Numero";
        //echo $cons;
	$res = ExQuery($cons);
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="6"><? echo $Tit;?> DE TRASLADO</td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Fecha</td><td>Numero</td><td>Tercero</td><td>Centro Costos Destino</td><td>Accion</td>
        <?
        	while($fila = ExFetch($res))
			{
				?><input type="hidden" name="SubUbD[<? echo $fila[7]?>]" value="<? echo $fila[10]?>" /><?
				echo "<tr><td align='right'>$fila[0]</td>";
				?> <td align='right' onMouseOver="this.bgColor='#AAD4FF'" style="cursor:hand" title="Ver Acta"
                onClick="open('/Informes/Infraestructura/Formatos/Traslados.php?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $fila[1]?>','','width=600,height=600,scrollbars=yes');"
				onmouseout="this.bgColor='#FFFFFF'">
				<? echo"$fila[1]</td><td>$fila[2] - $fila[3] $fila[4] $fila[5] $fila[6]</td>
				<td>$fila[7] - $fila[8] ($fila[10])</td>";
				if($Tipo == "Aprobar")
				{
					if($fila[9]=="Solicitado")
					{
					?>
                        <td>
                        <select name="Aprobar[<? echo $fila[1]?>]" style="width:100%">
                            <option></option>
                            <option value="Aprobar" title="Aprobar">Aprobar</option>
                            <option value="Rechazar" title="Rechazar">Rechazar</option>
                        </select></td>
                    <?		
					}
					else
					{
					?>
                        <td align="center">
                        <button type="submit" name="Reversar[<? echo $fila[1];?>,<? echo $fila[2];?>,<? echo $fila[7];?>]"
                        title="Reversar <? if($fila[9]=="Aprobado"){ echo " Aprobacion";}else{ echo " Rechazo";}?>"><img src="/Imgs/b_drop.png" /></button>
                        </td>
                    <?	
					}
				}
				if($Tipo == "Ejecutar")
				{
				?>
				<td align="center">
                <button type="submit" name="Ejecutar[<? echo $fila[1];?>,<? echo $fila[2];?>,<? echo $fila[7];?>]" 
                title="Ejecutar"><img src="/Imgs/b_check.png" /></button>
                </td>
				<?	
				}
					
			}
		?>
    </tr>
</table>
<?
	if($Tipo == "Aprobar")
	{
	?>
	<input type="submit" name="Guardar" value="Guardar" />
	<?	
	}
?>
</form>
</body>