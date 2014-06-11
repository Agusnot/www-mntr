<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$PerIni){$PerIni="$ND[year]-$ND[mon]-01";}
	if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
	$cons="select codigo,diagnostico from salud.cie";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{//if($fila[0]=="F069"){echo $fila[0]." ".$fila[1];}
		$CIE[$fila[0]]=$fila[1];	
	}
?>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.TipoFormato.value==""){alert("Debe seleccionar el Tipo de Formato!!!");return false;}
		if(document.FORMA.Formato.value==""){alert("Debe seleccionar el Formato!!!");return false;}
		if(document.FORMA.PerIni.value==""){alert("Debe digitar la fecha inicial!!!");return false;}
		if(document.FORMA.PerFin.value==""){alert("Debe digitar la fecha final!!");return false;}
		if(document.FORMA.PerFin.value<document.FORMA.PerIni.value){alert("La fecha final debe ser mayor o igual a la fecha inicial!!!");return false;}
		if(document.FORMA.EdadIni.value!=""){
			if(document.FORMA.EdadFin.value==""){alert("Debe digitar la edad final!!!");return false;}
			if(document.FORMA.EdadFin.value<document.FORMA.EdadIni.value){alert("La edad final debe ser mayor o igual a la edad inicial!!!");return false;}
		}
	}
</script>
<form name="FORMA" method="post" onsubmit="return Validar()">
<table border="1" cellspacing=0 style='font : normal normal small-caps 11px Tahoma;' bordercolor="white">
<tr align="center"><td  bgcolor="#e5e5e5" style="font-weight:bold">Tipo de Formato</td><td  bgcolor="#e5e5e5" style="font-weight:bold">Formato</td>
<td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Periodo</td><td bgcolor="#e5e5e5" style="font-weight:bold">Sexo</td>
<td rowspan="4"><input type="submit" name="Ver" value="Ver"></td></tr>
<tr align="center">
<td>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<select name="TipoFormato" onChange="FORMA.submit();">
<option></option>
<?

	$cons="Select Especialidad from Salud.Especialidades where Compania='$Compania[0]' order by especialidad";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$TipoFormato){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		
	}
?>
</select>
</td>

<td>
<select name="Formato" onChange="FORMA.submit();">
<option></option>
<?
	if($Formato){$Formato=explode("|",$Formato);}
	$cons="Select Formato,tblformat from HistoriaClinica.Formatos where TipoFormato='$TipoFormato' and Compania='$Compania[0]' and Estado='AC'
	order by formato";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$Formato[0]){echo "<option selected value='$fila[0]|$fila[1]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]|$fila[1]'>$fila[0]</option>";}
		
	}
?>
</select>
</td>
<td>
<input type="text" name="PerIni" value="<? echo $PerIni?>" style="width:80px;"><input type="text" name="PerFin" value="<? echo $PerFin?>" style="width:80px;">
</td>
<td align="center">
	<select name="Sexo" onchange="document.FORMA.submit();">
    	<option></option>
        <option value="F" <? if($Sexo=="F"){?> selected="selected"<? }?>>Femenino</option>
        <option value="M" <? if($Sexo=="M"){?> selected="selected"<? }?>>Masculino</option>
    </select>
</td>
</tr>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Grupo Etareo</td><td>Ambito</td><td>Entidad</td><td>Contrato</td>
<tr align="center">
<td><strong>Desde:</strong>
	<input type="text" name="EdadIni" onkeypress="xNumero(this)" onkeyup="xNumero(this)" value="<? echo $EdadIni?>" style="width:20px"/>
    <strong>Hasta:</strong>
	<input type="text" name="EdadFin" onkeypress="xNumero(this)" onkeyup="xNumero(this)" value="<? echo $EdadFin?>" style="width:20px"/>
</td>
<td>
<?	
	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
	$res=ExQuery($cons);?>
    <select name="Ambito" onchange="document.FORMA.submit()">    	
    	<option></option>
 	<? 	while($fila=ExFetch($res))
		{
			if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
	?>
    </select>
</td>
<td>
<? 	$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and tipo='Asegurador' 
	order by primape,segape,primnom,segnom";
	$res=ExQuery($cons);?>
     <select name="Entidad" onchange="document.FORMA.submit()">
    	<option></option>
 	<? 	while($fila=ExFetch($res))
		{
			if($fila[0]==$Entidad){echo "<option value='$fila[0]' selected>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}
			else{echo "<option value='$fila[0]'>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}
		}
	?>
    </select>
</td>
<td>
<?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$Entidad' group by contrato order by contrato";
	$res=ExQuery($cons);?>
    <select name="Contrato" onchange="document.FORMA.submit()">
    	<option></option>
 	<? 	while($fila=ExFetch($res))
		{
			if($fila[0]==$Contrato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
	?>
    </select>
</td>

</tr>
</table>
</form>

<?
	if($TipoFormato && $Formato[0])
	{
		$Campos=NULL;
		echo "<table bordercolor='#e5e5e5' border=1 style='font : normal normal small-caps 11px Tahoma;'>";
		$cons="Select Item,id_item from HistoriaClinica.ItemsxFormatos where TipoFormato='$TipoFormato' and Formato='$Formato[0]' and Compania='$Compania[0]' and Estado='AC'
		and TipoControl!=''";
		$res=ExQuery($cons);
		echo "<tr bgcolor='#e5e5e5'><td>Fecha</td><td>Cedula</td><td>Paciente</td></td><td>Unidad</td>";
		$ContTit=4;
		while($fila=ExFetch($res))
		{
			$Campos=$Campos."cmp".substr("00000",0,5-strlen($fila[1])).$fila[1].",";
			echo "<td>$fila[0]</td>";
			$Titulos[$ContTit]=$fila[0];			
			$ContTit++;			
		}		
		$cons="Select Item,id_item from HistoriaClinica.ItemsxFormatos where TipoFormato='$TipoFormato' and Formato='$Formato[0]' and Compania='$Compania[0]' 
		and Estado='AC'	and item='Diagnostico'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); if($fila[0]){$BanDx=1;}
				
		if($BanDx){
			$cons="select id,detalle,tipo,iditem from historiaclinica.dxformatos where compania='$Compania[0]' and formato='$Formato[0]' 
			and tipoformato='$TipoFormato' order by tipo";
			$res=ExQuery($cons);
			$Cont=1;
			while($fila=ExFetch($res))
			{
				$Campos=$Campos."dx$Cont,";
				$Cont++;				
				echo "<td>$fila[1]</td>";
				//echo "<td>$fila[0]</td>";
				$Titulos[$ContTit]=$fila[1];			
				$ContTit++;	
			}
		}
				
		$Campos=substr($Campos,0,strlen($Campos)-1);
		if($Sexo){$Genero=" and sexo='$Sexo'";}else{$Genero="";}
		if($EdadFin){$EIni=$ND[year]-$EdadIni; $EFin=$ND[year]-$EdadFin; $Edad="and fecnac<='$EIni-$ND[mon]-$ND[mday]' and fecnac>='$EFin-$ND[mon]-$ND[mday]'";}
		if($Ambito){$Amb="and ambito='$Ambito'";}else{$Amb="";}
		if($Contrato){$Contr="and contrato='$Contrato'";}else{$Contr="";}
		if($Entidad){
			$cons="select numservicio,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom),entidad,contrato 
			from salud.pagadorxservicios,central.terceros where pagadorxservicios.compania='$Compania[0]' and terceros.compania='$Compania[0]'
			and numservicio in (Select numservicio 
								from histoclinicafrms.$Formato[1],Central.Terceros
								where Terceros.Identificacion=$Formato[1].Cedula and $Formato[1].Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' 
								and TipoFormato='$TipoFormato' and Formato='$Formato[0]' and Fecha>='$PerIni' and Fecha<='$PerFin' $Genero $Edad $Amb)
			and entidad='$Entidad' and entidad=identificacion $Contr";
			$res=ExQuery($cons);
			//echo $cons;
			$banPag=0;
			while($fila=ExFetch($res))
			{
				$Pagadores[$fila[0]]=array($fila[1],$fila[2],$fila[3]);	
				if($banpag==0){$Pags="'$fila[0]'"; $banpag=1;}else{$Pags=$Pags.",'$fila[0]'";}
			}
			if($Pags){
				$PagsIn="and numservicio in ($Pags)";
			}
			else{$PagsIn="and numservicio in ('-1','-2')";}
		}
		else{$PagsIn="";}
		
		$consPac="Select Fecha,Cedula,PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom,UnidadHosp,$Campos,numservicio 
		from histoclinicafrms.$Formato[1],Central.Terceros
		where Terceros.Identificacion=$Formato[1].Cedula and $Formato[1].Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and
		TipoFormato='$TipoFormato' and Formato='$Formato[0]' and Fecha>='$PerIni' and Fecha<='$PerFin'
		$Genero $Edad $Amb $PagsIn
		Order By Fecha desc";
		$resPac=ExQuery($consPac);
		//echo $consPac;
		while($filaPac=ExFetch($resPac))
		{
			echo "<tr>";
			for($i=0;$i<ExNumFields($resPac)-1;$i++)
			{	
				//echo $Titulos[$i]."<br>";
				if($CIE[$filaPac[$i]]){
					echo "<td title='".$Titulos[$i]."'>$filaPac[$i] - ".$CIE[$filaPac[$i]]."&nbsp;</td>";	
				}
				else{
					echo "<td title='".$Titulos[$i]."'>$filaPac[$i]&nbsp;</td>";	
				}
			}
			echo "</tr>";
			$TotPac++;
		}
		echo "</tr></table>";
		echo "<hr>Total Registros: $TotPac";
	}
?>