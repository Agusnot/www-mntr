<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$ND = getdate();
	include("Funciones.php");
	$cons = "Select Nombre from Central.Usuarios Where Cedula='$Responsable'";
	$res = ExQuery($cons);
	$fila=ExFetch($res);
	$Nombre = $fila[0];
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
    $cons="Select Mantenimiento.AutoId,Codigo,Nombre,Caracteristicas,Modelo,Marca,HoraIni,Duracion,DetalleSolicitud
    from Infraestructura.Mantenimiento, Infraestructura.CodElementos
    Where Mantenimiento.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and Mantenimiento.AutoId=CodElementos.AutoId
    and Agendado=1 and Encargado='$Responsable' and FechAgenda='$Fecha'";
    $res = ExQuery($cons);
    while($fila=ExFetch($res))
    {
        $HoraAgenda=explode(":",$fila[6]);
        for($i=$HoraAgenda[0];$i<=$HoraAgenda[0]+($fila[7]);$i++)
        {
            if($i==$HoraAgenda[0]){$li=$HoraAgenda[1];}else{$li=0;}
            if($i==$HoraAgenda[0]+($fila[7])){$ls=$HoraAgenda[1]+10;}else{$ls=60;}
            for($j=$li;$j<$ls;$j+=10)
            {
                if($j==0){$j="00";}
                $Elemento[$i.":".$j]=array($fila[0],$fila[1],"$fila[2] $fila[3] $fila[4] $fila[5]",$fila[6],$fila[7],$fila[8]);
                //echo "Elemento[$i:$j]=$fila[0],$fila[1],\"$fila[2] $fila[3] $fila[4] $fila[5]\",$fila[6],$fila[7],$fila[8]";
            }
        }
    }
?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td colspan="3">Responsable: <? echo $Nombre?></td></tr>
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td colspan="3">Grupo: <? echo $Grupo?></td></tr>
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td colspan="3">Fecha: <? echo $Fecha?></td></tr>
   	<? if(!$Elemento)
	{
	?><tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Hora</td><td>&nbsp;</td></tr><?	
	}
	else
	{
	?><tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Hora</td><td>Elemento</td><td>Observaciones</td></tr><?	
	}
    $m=0;
	if($Fecha<"$ND[year]-$ND[mon]-$ND[mday]"){$NoPuede=" title='No puede programar mantenimiento para dias anteriores a la fecha actual'";}
	for($h=7;$h<18;$h++)
	{
		
            for($m=0;$m<60;$m+=10)
            {
                if($m==0){$m="00";}
                if(!$Elemento[$h.":".$m])
                {
                    if($Fecha=="$ND[year]-$ND[mon]-$ND[mday]")
                    {
                        if($h<$ND[hours])
                        {
                                $NoPuede=" title='No puede programar mantenimiento para horas anteriores a la hora actual'";
                        }
                        else
                        {
                            if($m<$ND[minutes])
                            {
                                $NoPuede=" title='No puede programar mantenimiento para horas anteriores a la hora actual'";
                            }
                            else
                            {
                                unset($NoPuede);
                            }
                        }
                    }
                    if($m==0){$m="00";}
                    ?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" <? echo $NoPuede?>
                    <? if(!$NoPuede){?>
                    onclick="parent.location.href='Elementos.php?DatNameSID=<? echo $DatNameSID?>&Clase=Devolutivos&Origen=Agenda&Fecha=<? echo $Fecha?>&Grupo=<? echo $Grupo?>&Responsable=<? echo $Responsable;?>&H=<? echo $h?>&M=<? echo $m?>'"
                    <? }?>
        >
                        <td style="font-weight:bold" align="center"><? echo "$h:$m";?></td><td colspan="2" align="center">-Sin Asignar-</td></tr><?
                }
                else
                {
                    if($Elemento[$h.":".$m]!=$E)
                    {
                            if($m==0){$m="00";}
                            ?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" >
                            <td style="font-weight:bold" align="center"><? echo "$h:$m - ".($h+$Elemento[$h.":".$m][4]).":$m";?></td>
        <td align="center"><? echo $Elemento[$h.":".$m][1]." - ".$Elemento[$h.":".$m][2]?></td>
        <td title="<? echo $Elemento[$h.":".$m][5]?>"><? echo substr($Elemento[$h.":".$m][5],0,20)?></td></tr><?
                            $E = $Elemento[$h.":".$m];
                    }
                }
            }
	}
	?>
</table>
</form>
</body>