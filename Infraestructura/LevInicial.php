<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if(!$Clase){$Clase="Devolutivos";}
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.Codelementos Where Compania='$Compania[0]' and AutoId=$AutoId and Clase='$Clase'";
		$res = ExQuery($cons);		
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='110px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function AbrirDuplicar(AutoId,Clase,Codigo,e)
	{
		posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href="DuplicaProducto.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&Clase="+Clase+"&Codigo="+Codigo;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='150px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='150';
		document.getElementById('FrameOpener').style.height='150';
	}
</script>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold">Clase</td>
    <td><select name="Clase" onChange="FORMA.submit()">
            <option <? if($Clase == "Activos Fijos"){ echo "selected";}?> value="Activos Fijos">Activos Fijos</option>
            <option <? if($Clase == "Devolutivos"){ echo "selected";}?> value="Devolutivos">Devolutivos</option>
        </select></td>
    <td><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NuevoLevInicial.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase;?>&Tipo=Levantamiento Inicial'"></td>
</tr>
</table>
<?
	if($Buscar)
	{
		if($Codigo){ $PCodigo = " and CodElementos.Codigo ilike '$Codigo' ";}
		if($Nombre){ $PNombre = " and Nombre ilike '%$Nombre%' ";}
		if($Modelo){ $PModelo = " and Modelo ilike '%$Modelo%' ";}
		if($Serie){ $PSerie = " and Serie ilike '%$Serie%' ";}
		if($Marca){ $PMarca = " and Marca ilike '%$Marca%' ";}
		if($FechaAd){ $PFechaAd = " and FechaAdquisicion = '$FechaAd' ";}
		if($Estado){ $PEstado = " and Estado = '$Estado' ";}
		if($Impacto){ $PImpacto = " and Impacto = '$Impacto' ";}
		if($CostoIni){ $PCostoIni = " and CostoInicial = $CostoIni ";}
		if($Identificacion || $CC)
		{
			$CampoSelect=",Responsable,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,PrimApe,SegApe,PrimNom,SegNom";
			$TablasFrom=",InfraEstructura.Ubicaciones,Central.Terceros, Central.CentrosCosto";
			if($Identificacion){$CamposWhereID=" and Responsable='$Identificacion' ";}
			if($CC){$CamposWhereCC=" and Ubicaciones.CentroCostos = '$CC' ";}
			$CamposWhere=" and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]'
			and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = Terceros.Identificacion
			and FechaIni<'$ND[year]-$ND[mon]-$ND[mday]' and (FechaFin>'$ND[year]-$ND[mon]-$ND[mday]' or FechaFin Is NULL) 
			$CamposWhereID $CamposWhereCC";
		}
		
	$cons = "Select distinct CodElementos.Codigo,Nombre,Modelo,Serie,Marca,Grupo,FechaAdquisicion,Estado,Impacto,CostoInicial,CodElementos.AutoId$CampoSelect
	From Infraestructura.CodElementos$TablasFrom  
	Where CodElementos.Compania = '$Compania[0]' 
	and CodElementos.Clase = '$Clase' and CodElementos.Tipo = 'Levantamiento Inicial' $CamposWhere 
	$PCodigo$PNombre$PModelo$PSerie$PMarca$PFechaAd$PEstado$PImpacto$PCostoIni
	Order by Grupo,CodElementos.Codigo";
	$res = ExQuery($cons);
	}
	
	?>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="80%">
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Codigo</td><td>Nombre</td><td>Modelo</td><td>Serie</td><td>Marca</td>
        <td>Fecha Adquisicion</td><td>Estado</td><td>Impacto</td><td>Costo Inicial</td>
        <td>Responsable</td><td>CentroCostos</td>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td><input type="text" name="Codigo" style="width:100%;" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar()" value="<? echo $Codigo?>"></td>
        <td><input type="text" name="Nombre" style="width:100%;" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar()" value="<? echo $Nombre?>"></td>
        <td><input type="text" name="Modelo" style="width:100%;" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar()" value="<? echo $Modelo?>"></td>
        <td><input type="text" name="Serie" style="width:100%;" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar()" value="<? echo $Serie?>"></td>
        <td><input type="text" name="Marca" style="width:100%;" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar()" value="<? echo $Marca?>"></td>
        <td align="center"><input type="text" name="FechaAd" size="8" onFocus="Ocultar()" value="<? echo $FechaAd?>" 
        onclick="popUpCalendar(this, FORMA.FechaAd, 'yyyy-mm-dd')"  readonly /></td>
        <td><select name="Estado" style="width:100%;" onFocus="Ocultar()"><option></option>
        <?
        	$cons1 = "Select Nombre from Central.Estados";
			$res1 = ExQuery($cons1);
			while($fila1 = pg_fetch_row($res1))
			{
				if($fila1[0]==$Estado){echo "<option selected title='$fila1[0]' value='$fila1[0]'>$fila1[0]</option>";}
				else{echo "<option title='$fila1[0]' value='$fila1[0]'>$fila1[0]</option>";}
			}
		?>
        </select></td>
        <td><select name="Impacto" style="width:100%;" onFocus="Ocultar()"><option></option>
        <?
        	$cons1 = "Select Nombre from Central.Impactos";
			$res1 = ExQuery($cons1);
			while($fila1 = pg_fetch_row($res1))
			{
				if($fila1[0]==$Impacto){echo "<option selected title='$fila1[0]' value='$fila1[0]'>$fila1[0]</option>";}
				else{echo "<option title='$fila1[0]' value='$fila1[0]'>$fila1[0]</option>";}	
			}
		?>
        </select></td>
        <td><input type="text" name="CostoIni" style="width:100%;" onFocus="Ocultar()" value="<? echo $CostoIni?>" 
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
        <td>
        	<input type="Text" name="Tercero" value="<? echo $Tercero?>" onFocus="Mostrar();
            if(CC.value==''){frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;}
            else{ frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=Identificacion&ObjTercero=Tercero&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}" 
        	onKeyUp="xLetra(this);Identificacion.value='';
            if(CC.value==''){frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;}
            else{ frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=Identificacion&ObjTercero=Tercero&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}"
            onKeyDown="xLetra(this)"/>
            <input type="hidden" name="Identificacion" value="<? echo $Identificacion?>" /> 
        </td>
        <td>
        	<input type="text" name="CC" value="<? echo $CC?>" style="width:100%;text-align:right;" 
        onFocus="Mostrar();
        if(Identificacion.value==''){frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
        else{frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoCC=CC&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+Identificacion.value;};"
        onkeyup="if(Identificacion.value==''){frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
        else{frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+Identificacion.value;};
        xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /> 
        </td>
        <td><button type="submit" name="Buscar" title="Buscar" onFocus="Ocultar()"><img src="/Imgs/b_search.png" /></button></td>
    </tr>
    <?
    	if($PCodigo || $PNombre || $PModelo || $PSerie || $PMarca || $PFechaAd || $PEstado || $PImpacto || $PCostoIni || $CC || $Identificacion)
        {
          $cons = "Select distinct CodElementos.Codigo,Nombre,Modelo,Serie,Marca,Grupo,FechaAdquisicion,Estado,Impacto,CostoInicial,CodElementos.AutoId$CampoSelect
	From Infraestructura.CodElementos$TablasFrom
	Where CodElementos.Compania = '$Compania[0]'
	and CodElementos.Clase = '$Clase' and CodElementos.Tipo = 'Levantamiento Inicial' $CamposWhere
	$PCodigo$PNombre$PModelo$PSerie$PMarca$PFechaAd$PEstado$PImpacto$PCostoIni
	Order by Grupo,CodElementos.Codigo";
	$res = ExQuery($cons);
        while($fila = ExFetch($res))
		{
			$cons1 = "Select distinct Ubicaciones.CentroCostos, PrimNom, SegNom, PrimApe, SegApe, FechaIni, FechaFin, CentrosCosto.CentroCostos 
			From Central.Terceros,Infraestructura.Ubicaciones,Central.CentrosCosto
			Where Ubicaciones.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AutoId=$fila[10] and Terceros.Identificacion = Ubicaciones.Responsable
			and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year]";
			$res1 = ExQuery($cons1);
			$fila1 = ExFetch($res1);
			
			if($fila[5] != $GrupAnt)
			{
				echo "<tr bgcolor='$Estilo[1]'  style='color:white;font-weight:bold;'><td colspan='14' align='center'>$fila[5]</td></tr>";	
			}
			?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
			<?
			echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td>
			<td align='right'>$fila[6]</td><td>$fila[7]</td><td>$fila[8]</td>
			<td align='right'>".number_format($fila[9],2)."</td><td>$fila1[3] $fila1[4] $fila1[1] $fila1[2]</td>
			<td>$fila1[0] - $fila1[7]</td>";
			?>
            <td align="right">
            	<img src="/Imgs/b_import.png" style="cursor:hand;" title="duplicar"
                onClick="AbrirDuplicar('<? echo $fila[10]?>','<? echo $Clase?>','<? echo $fila[0]?>',event)">
            </td>
            <td><a href="NuevoLevInicial.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $fila[0]?>&Editar=1&AutoId=<? echo $fila[10];?>&Clase=<? echo $Clase;?>&Tipo=Levantamiento Inicial">
            	<img src="/Imgs/b_edit.png" border="0" title="Editar" />
            </a></td>
            <td><img src="/Imgs/b_drop.png" style="cursor:hand;" title="Eliminar"
            onclick="if(confirm('Desea Eliminar el registro?')){location.href='LevInicial.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Clase=<? echo $Clase?>&AutoId=<? echo $fila[10]?>'}" /></td>
            </tr>
			<?
			$GrupAnt = $fila[5];
			$TotCostoIni = $TotCostoIni + $fila[9];
		}  
        }

	?>
    <tr  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="8" align="right">TOTAL</td>
        <td align="right"><? echo number_format($TotCostoIni,2);?></td>
        <td colspan="3">&nbsp;</td>
    </tr>
    </table>
	<? 
?>
<!--<input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NuevoLevInicial.php?Clase=<? echo $Clase;?>&Tipo=Levantamiento Inicial'">-->
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>