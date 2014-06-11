<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Elim)
	{
		$cons = "Update InfraEstructura.Traslados set Estado='ANULADO' Where Compania='$Compania[0]' and Numero='$Numero'";
		$res = ExQuery($cons);
		unset($Numero,$Eliminar);
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
	if($DiaI){ $conFecha = " and FechaSolicita='$AnioI-$MesI-$DiaI' ";}
	if($Numero){ $conNumero = " and Numero like '%$Numero' ";}
	$cons = "Select Usuario,Cedula,PrimApe,SegApe,PrimNom,SegNom From Infraestructura.Administrador, Central.Terceros
	Where Administrador.Cedula = Terceros.Identificacion and Administrador.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]' and Usuario = '$usuario[0]'";
	//echo $cons;
	$res = ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		$cons1 = "Select Cedula,PrimApe,SegApe,PrimNom,SegNom From Central.Usuarios, Central.Terceros Where
		Usuarios.Cedula = Terceros.Identificacion and Terceros.Compania='$Compania[0]'
		and Nombre = '$usuario[0]'";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		$Identificacion = $fila1[0];
	}
	if($Identificacion){ $conIdentificacion= " and Cedula like '$Identificacion%' ";}
	if($CC){$conCC = " and CCDestino like '$CC%'";}
	$cons = "Select FechaSolicita,Numero,PrimApe,SegApe,PrimNom,SegNom,Traslados.Cedula,Estado,CCDestino,CentrosCosto.CentroCostos,Masivo
	from Infraestructura.Traslados,Central.Terceros,Central.CentrosCosto where Traslados.Cedula=Terceros.Identificacion and Traslados.Compania='$Compania[0]'
	and Terceros.Compania='$Compania[0]' and FechaSolicita>='$AnioI-$MesI-01' and FechaSolicita<='$AnioI-$MesI-$UltDia'
	and CentrosCosto.Anio = $AnioI and CentrosCosto.Codigo = Traslados.CCDestino 
	$conFecha $conNumero $conIdentificacion $conCC Order By Numero,FechaSolicita";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$Traslado[$fila[1]] = array($fila[0],$fila[1],"$fila[2] $fila[3] $fila[4] $fila[5]",$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]); 	
	}	
	?>
<script language="javascript">
	function Abrir(Numero)
	{
		open("/Informes/Infraestructura/Formatos/Traslados.php?DatNameSID=<? echo $DatNameSID?>&Numero="+Numero,'','width=800,height=600,scrollbars=yes');
	}
</script>   

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
	<table style="font : normal normal small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" width="100%">
    <tr align="center" valign="middle" bgcolor="<? echo $Estilo[1]?>" style="color:#FFFFFF; font-weight:bold">
    	<td>Fecha</td><td>Numero</td><td>Tercero</td><td>CC Destino</td><td width="5%" colspan="3">Buscar</td>
    </tr>
    <tr align="center" valign="middle">
    	<td width="8%" align="left"><? echo "$AnioI-$MesI-"?><input type="text" name="DiaI" value="<? echo $DiaI?>" maxlength="2" style="width:20px"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
        
        <td width="8%"><? echo $AnioI?><input type="text" name="Numero" value="<? echo $Numero?>" maxlength="6" style="width:50px"
         onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
         
         <td width="30%"><input type="text" name="Identificacion" value="<? echo $Identificacion?>" style="text-align:center" 
         onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" maxlength="20"></td>
         
         <td width="30%"><input type="text" name="CC" value="<? echo $CC;?>" style="text-align:center" 
         onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" maxlength="20"></td>
         
         <td colspan="3">
         <input type="hidden" name="Tipo" value="<? echo $Tipo?>">
         <input type="hidden" name="AnioI" value="<? echo $AnioI?>">
         <input type="hidden" name="MesI" value="<? echo $MesI?>">
         <input type="hidden" name="Clase" value="<? echo $Clase;?>" >
        <button name="Buscar" type="submit"><img src="/Imgs/b_search.png" title="Buscar Registro"></button>
		<input type="checkbox" name="Recursivo" title="Busqueda Recursiva" value="1">
         </td>
    </tr>
    <?
	if(count($Traslado) > 0)
	{
		foreach($Traslado as $Tras)
		{
			$An = "";
			$Masivo="";
			if($Tras[7]){$Masivo=" style='color:#00F' title='Traslado Masivo'";}
			else{$Masivo=" title='$Tras[4]'";}
			if ($Tras[4]=="ANULADO"){ if($Masivo){unset($Masivo);}$An=" style='text-decoration:underline; color:#F00' ";}
			?><tr onMouseOver="this.bgColor='#AAD4FF'" <? echo $Masivo?><? echo $An;?> 
				onmouseout="this.bgColor='#FFFFFF'"><td align="left"><? echo $Tras[0];?></td><td align="right"><? echo $Tras[1];?></td>
			<td align="center"><? echo "$Tras[2] - $Tras[3]";?></td><td align="center"><? echo "$Tras[5] - $Tras[6]";?></td>
			<td><img onClick="Abrir('<? echo $Tras[1];?>')" src="/Imgs/b_print.png" title="Ver Acta" style="cursor:hand" /></td>
            <? if($Tras[4]=="Solicitado")
			{
			?>
			<td><a target="_parent" 
			href="Traslados.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Anio=<? echo substr($Tras[0],0,4)?>&Mes=<? echo  substr($Tras[0],5,2)?>&Dia=<? echo  substr($Tras[0],8,2)?>&Numero=<? echo $Tras[1] ?>&Tercero=<? echo $Tras[2]?>&Identificacion=<? echo $Tras[3]?>&CCDestino=<? echo "$Tras[5] - $Tras[6]";?>&CCD=<? echo $Tras[5]?>&Clase=<? echo $Clase?>&Tipo=<? echo $Tipo?>">
			<img src="/Imgs/b_edit.png" border="0" /></a></td>
			<td><img src="/Imgs/b_drop.png" style="cursor:hand;" 
			onclick="if(confirm('Desea Eliminar El registro?')){location.href='ListaTraslados.php?DatNameSID=<? echo $DatNameSID?>&Elim=1&Clase=<? echo $Clase;?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Tras[1]?>&AnioI=<? echo substr($Tras[0],0,4)?>&MesI=<? echo substr($Tras[0],5,2)?>';}" /></td>
			<?	
			}
		}	
	}
    ?></table><? 
?>
</form>
</body>