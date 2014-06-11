<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Fecha)
	{
		$FechaDato=explode("-",$Fecha);
		$Anio = $FechaDato[0];
		$Mes = $FechaDato[1];
		$dia = $FechaDato[2];	
	}
	if(!$Grupo)
	{
		$cons = "Select Grupo from Infraestructura.GruposdeElementos Where Compania='$Compania[0]' order by Grupo";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Grupo = $fila[0];	
	}
	if(!$Anio){$Anio=$ND[year];}
	if(!$Mes){$Mes=$ND[mon];}
	if(!$dia){$dia=$ND[mday];}
	
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table align="center">
<tr><td>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
    	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Grupo</td>
        	<td align="left"><select name="Grupo" onchange="FORMA.submit()">
            <?
            	$cons = "Select Grupo from Infraestructura.GruposdeElementos Where Compania='$Compania[0]'
                and Clase = 'Devolutivos' and Anio=$Anio order by Grupo";
                $res = ExQuery($cons);
                while($fila=ExFetch($res))
                {
                        if($Grupo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                        else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }
            ?>
            </select></td>
        </tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Responsable</td>
        	<td align="left"><select name="Responsable" onchange="FORMA.submit()"><option></option>
            <?
            	$cons = "Select Nombre,Cedula from Central.Usuarios,Infraestructura.ResponsablesMantenimiento
                Where Compania='$Compania[0]' and ResponsablesMantenimiento.Usuario = Usuarios.Cedula and
                GrupoElementos ilike '$Grupo' Group by Nombre,Cedula order by Nombre";
                $res = ExQuery($cons);
                while($fila=ExFetch($res))
                {
                        if($Responsable==$fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
                        else{echo "<option value='$fila[1]'>$fila[0]</option>";}
                }
            ?>
            </select></td>
        </tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">A&ntilde;o</td>
        	<td align="left"><select name="Anio" onChange="Fecha.value='';document.FORMA.submit();">
             <? $cons="select anio from central.anios where compania='$Compania[0]' order by anio";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
				if($Anio==$fila[0]){ echo "<option value='$fila[0]' selected>$fila[0]</option>";}
				else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>	
        	</select></td>
        </tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Mes</td>
        	<td align="left"><select name="Mes" onChange="Fecha.value='';document.FORMA.submit();">
            <? $cons="select numero,mes from central.meses";
            $res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
            	if($Mes==$fila[0]){ echo "<option value='$fila[0]' selected>$fila[1]</option>";}
                else{ echo "<option value='$fila[0]'>$fila[1]</option>";}
            }?>
            </select></td>
        </tr>
    </table> 
</td>
<td>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>D</td><td>L</td><td>M</td><td>M</td><td>J</td><td>V</td><td>S</td></tr>
<tr>
<?
	$nd=0;
	$fec = $Mes . '/01/' . $Anio;
	$hora = getdate(strtotime($fec));	
	for($i=0;$i<=$hora[wday]-1;$i++)
	{
		echo "<td>&nbsp;</td>";
	}
	$cons = "Select NumDias from Central.Meses Where Numero = $Mes";
	$res = ExQuery($cons);
	$fila=ExFetch($res);
	$NumDias = $fila[0];
	$D = $hora[wday];
	for($i=1;$i<=$NumDias;$i++)
	{
		if($i==$dia){$BgColor = "#e5e5e5";}
		else{$BgColor = "";}
		if($D==0){echo "<td style='font-weight:bold'>$i</td>";}
		else{?><td bgcolor="<? echo $BgColor?>" onMouseOver="this.bgColor='#AAD4FF'" 
        onMouseOut="this.bgColor='<? echo $BgColor?>'" style="cursor:hand" onclick="dia.value='<? echo $i?>';Fecha.value='';FORMA.submit();"><? echo $i?></td> <? }
		$D++;
		if($D==7){$D=0;echo "</tr>";}
	}
?>
</table>
</td></tr>
</table>
<input type="hidden" name="dia" value="<? echo $dia?>" />
<input type="hidden" name="Fecha" value="<? echo $Fecha?>" />
</form>
<iframe frameborder="0" id="AgendaDia" 
<? if($Responsable){?>src="AgendaDia.php?DatNameSID=<? echo $DatNameSID?>&Grupo=<? echo $Grupo?>&Fecha=<? echo "$Anio-$Mes-$dia"?>&Responsable=<? echo $Responsable?>"<? }
else{?>src="about:blank"<? }?> width="100%" height="250px"></iframe>
</body>