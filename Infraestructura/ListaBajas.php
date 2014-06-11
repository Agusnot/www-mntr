<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
        if($Elim)
	{
		$cons = "Update InfraEstructura.Bajas set Estado='ANULADO' Where Compania='$Compania[0]' and Numero='$Numero'";
		$res = ExQuery($cons);
		unset($Numero,$Elim);
		//echo $cons;	
	}
	$ND = getdate();
	if(!$MesI){$MesI=$MesTrabajo;}if(!$AnioI){$AnioI=$ND[year];}
	$cons="Select Mes,NumDias from Central.Meses where Numero=$MesI";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	echo ExError();$UltDia=$fila[1];
	$cons = "";
?>
<script language="javascript" src="/Funciones.js"></script>
<?
	if($DiaI){ $conFecha = " and Fecha='$AnioI-$MesI-$DiaI' ";}
	if($Numero){ $conNumero = " and Numero like '%$Numero' ";}
	$cons = "Select Fecha,Numero,Estado,Masivo
	from Infraestructura.Bajas where Bajas.Compania='$Compania[0]'
	and Fecha>='$AnioI-$MesI-01' and Fecha<='$AnioI-$MesI-$UltDia'
	$conFecha $conNumero Order By Numero,Fecha";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$Bajas[$fila[1]] = array($fila[0],$fila[1],$fila[2],$fila[3]);
	}	
	?>
<body background="/Imgs/Fondo.jpg">
	<table style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" width="50%" align="center">
    <tr align="center" valign="middle" bgcolor="<? echo $Estilo[1]?>" style="color:#FFFFFF; font-weight:bold">
    	<td>Fecha</td><td>Acta</td><td width="5%" colspan="3">Buscar</td>
    </tr>
    <tr align="center" valign="middle">
    	<td width="8%" align="center"><? echo "$AnioI-$MesI-"?><input type="text" name="DiaI" value="<? echo $DiaI?>" maxlength="2" style="width:20px"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
        <td width="8%"><? echo $AnioI?><input type="text" name="Numero" value="<? echo $Numero?>" maxlength="6" style="width:50px"
         onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
         
         <td colspan="3">
         <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
         <input type="hidden" name="Tipo" value="<? echo $Tipo?>">
         <input type="hidden" name="AnioI" value="<? echo $AnioI?>">
         <input type="hidden" name="MesI" value="<? echo $MesI?>">
         <input type="hidden" name="Clase" value="<? echo $Clase;?>" >
         <button type="submit" name="Buscar"><img src="/Imgs/b_search.png" title="Buscar Registro"></button>
		 <input type="checkbox" name="Recursivo" title="Busqueda Recursiva" value="1">
         </td>
    </tr>
    <?
	if(count($Bajas) > 0)
	{
		foreach($Bajas as $B)
		{
			$StyleANULADO = "";
			$StyleMASIVO = "";
			if($B[3]==1){$StyleMASIVO="title='Baja Masiva' style='font-weight:bold; color:#00F'";}
			if($B[2]=="ANULADO"){ $StyleANULADO = "style='text-decoration:underline; color:#F00'";}
			?><tr <? echo $StyleMASIVO;?><? echo $StyleANULADO;?> ><td align="center"><? echo $B[0];?></td><td align="center"><? echo $B[1];?></td>
			<td align="center"><img src="/Imgs/b_print.png" title="Ver Acta" style="cursor:hand;"
            onClick="open('/Informes/Infraestructura/Formatos/Bajas.php?Anio=<?echo $AnioI?>&DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $B[1]?>','','width=800,height=600,scrollbars=yes');"></td>
			<? if($B[2]=="Solicitado" && $B[3] != 1)
			{
			?>
			<td align="center"><a target="_parent" 
			href="NewBajas.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Anio=<? echo substr($B[0],0,4)?>&Mes=<? echo  substr($B[0],5,2)?>&Dia=<? echo  substr($B[0],8,2)?>&Numero=<? echo $B[1] ?>&Tercero=<? echo $B[2]?>&Clase=<? echo $Clase?>&Tipo=<? echo $Tipo?>">
			<img src="/Imgs/b_edit.png" border="0" /></a></td>
			<td align="center"><img src="/Imgs/b_drop.png" style="cursor:hand;" 
			onclick="if(confirm('Desea Eliminar El registro?')){location.href='ListaBajas.php?DatNameSID=<? echo $DatNameSID?>&Elim=1&Clase=<? echo $Clase;?>&Tipo=<? echo $Tipo?>&Numero=<? echo $B[1]?>&AnioI=<? echo substr($B[0],0,4)?>&MesI=<? echo substr($B[0],5,2)?>';}" /></td>
			<?	
			}?>
            
            <?
		}	
	}
    ?></table><? 
?>
</body>