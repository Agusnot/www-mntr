<?
	//$SubUb;
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	$Clase = "Devolutivos";
	if($Tipo=="Bajas"){$Tabla="Bajas";}
	if($Tipo=="Traslados"){$Tabla="Traslados";}
	if($Registrar)
	{
		if($Tipo=="Bajas")
		{
			if($CC){$notaBajaCC=" and CentroCostos='$CC' ";}
			if($IDRA){$notaBajaIDRA = " and Responsable='$IDRA' ";}
			$cons = "Select CodElementos.AutoId,Codigo,Responsable,CentroCostos from Infraestructura.CodElementos,Infraestructura.Ubicaciones
			Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CodElementos.AutoId = Ubicaciones.AutoId
			and FechaFin is NULL and NotaBaja is not NULL $notaBajaCC $notaBajaIDRA";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$cons1 = "Insert into Infraestructura.Bajas(Compania,AutoId,Codigo,TMPCOD,Responsable,CCResponsable,Masivo,sububicacionresp)
				values('$Compania[0]',$fila[0],'$fila[1]','$TMPCOD','$fila[2]','$fila[3]',1,'$SubUb')";
				$res1 = ExQuery($cons1);	
			}	
		}
		if($Elemento)
		{
			if($Tipo == "Traslados"){$Tabla = "Infraestructura.Traslados";}
			if($Tipo == "Bajas")
			{
				$Tabla = "Infraestructura.Bajas";
			}
			while(list($cad,$val) = each($Elemento))
			{
				//echo "$cad<br>";
				//$Vals[0]:AutoId $Vals[1]:Codigo $Vals[2]:Responsable $Vals[3]:CentroCostos
				$Vals = explode("|",$cad);
				$cons = "Insert into $Tabla (Compania,AutoId,Codigo,TMPCOD,Responsable,CCResponsable,Masivo)
				values('$Compania[0]',$Vals[0],'$Vals[1]','$TMPCOD','$Vals[2]','$Vals[3]',1)";
				$res = ExQuery($cons);
			}	
		}
		?>
		<script language="javascript">
        	location.href="ListaAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Numero=<? echo $Numero?>&Tipo=<? echo $Tipo?>";
        </script>
		<?
	}
	if($Clase){ $PClase = " and CodElementos.Clase='$Clase' ";}
	if($Estado){ $PEstado = " and Estado = '$Estado' ";}
	if($Impacto){ $PImpacto = " and Impacto = '$Impacto' ";}
	if($Grupo){ $PGrupo = " and Grupo = '$Grupo' ";}
	if($Relacion)
	{
		if($Relacion=="Encontrados")
		{
			$ConsUVE = " and UVE is not NULL ";	
		}
		else
		{
			$ConsUVE = " and UVE is NULL ";
		}
	}
	if($IDRA || $CC)
	{
		$CampoSelect=",Responsable,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,PrimApe,SegApe,PrimNom,SegNom";
		$TablasFrom=",InfraEstructura.Ubicaciones,Central.Terceros, Central.CentrosCosto";
		if($IDRA){$CamposWhereID=" and Responsable='$IDRA' ";}
		if($CC){$CamposWhereCC=" and Ubicaciones.CentroCostos = '$CC' ";}
		if($SubUb){$CamposWhereSub = " and Ubicaciones.SubUbicacion = '$SubUb'";};
		$CamposWhere=" and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]'
		and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = Terceros.Identificacion
		and FechaIni<='$ND[year]-$ND[mon]-$ND[mday]' and (FechaFin>='$ND[year]-$ND[mon]-$ND[mday]' or FechaFin Is NULL) 
		$CamposWhereID $CamposWhereCC $CamposWhereSub";
	}
	if($Tipo=="Traslados"){$adNotIn = "and TMPCOD='$TMPCOD' and Estado is not NULL";}
	if($Tipo=="Bajas"){$notaBaja="and CodElementos.Autoid not in (Select AutoId from Infraestructura.CodElementos Where Compania='$Compania[0]' and NotaBaja is not NULL)";}
	$cons = "Select distinct CodElementos.Codigo,Nombre,Modelo,Serie,Marca,Grupo,
	FechaAdquisicion,Estado,Impacto,Caracteristicas,CodElementos.AutoId,CodElementos.Tipo,UVE,UUVE$CampoSelect
	From Infraestructura.CodElementos$TablasFrom  
	Where CodElementos.Compania = '$Compania[0]' 
	and CodElementos.Tipo != 'Orden Compra' and (Eliminado != 1 or Eliminado is NULL) and (EstadoCompras != 'ANULADO' or EstadoCompras is NULL) $CamposWhere $PClase 
	$PEstado$PImpacto$PGrupo$ConsUVE 
	and CodElementos.Autoid not in (Select AutoId from Infraestructura.$Tabla Where Compania='$Compania[0]' $adNotIn)
	$notaBaja
	Order by Grupo,CodElementos.Codigo";
	$res = ExQuery($cons);
?>
<script language="javascript">
	function Des_MarcarTodo(objeto)
	{
		if(objeto.checked==true)
		{	for (i=0;i<document.FORMA.elements.length;i++){if(document.FORMA.elements[i].type == "checkbox"){document.FORMA.elements[i].checked=1;}}
			//alert("Se ha marcado todos los elementos.."); 
    	}
		else
		{	for (i=0;i<document.FORMA.elements.length;i++){if(document.FORMA.elements[i].type == "checkbox"){document.FORMA.elements[i].checked=0;}}
			//alert("Se ha desmarcado todos los elementos..");
    	}	
	}
	
	function Cambiar(Objeto,Grupo,Registros)
	{
		if(Objeto.checked == true)
		{for(i=0;i<Registros;i++){document.getElementById(Grupo+i).checked=true;}}
		else
		{for(i=0;i<Registros;i++){document.getElementById(Grupo+i).checked=false;}}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>"  />
<input type="hidden" name="CC" value="<? echo $CC?>" />
<input type="hidden" name="IDRA" value="<? echo $IDRA?>" />
<input type="hidden" name="SubUb" value="<? echo $SubUb?>" />
<table style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellspacing="0" cellpadding="0">
<tr bgcolor='<? echo $Estilo[1]?>'  style='color:white;font-weight:bold;'>
        <td colspan="15" title="Marcar/Desmarcar Todo"><input type="checkbox" checked name="QuitarTodo" onClick="Des_MarcarTodo(this)" /></td></tr>
<?
while($fila = ExFetch($res))
{
	$cons1 = "Select distinct Ubicaciones.CentroCostos, PrimNom, SegNom, PrimApe, SegApe, FechaIni, FechaFin, CentrosCosto.CentroCostos,Responsable 
	From Central.Terceros,Infraestructura.Ubicaciones,Central.CentrosCosto
	Where Ubicaciones.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AutoId=$fila[10] and Terceros.Identificacion = Ubicaciones.Responsable
	and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year]";
	$res1 = ExQuery($cons1);
	$fila1 = ExFetch($res1);
	
	if($fila[5] != $GrupAnt)
	{
		?>
        <tr bgcolor='<? echo $Estilo[1]?>'  style='color:white;font-weight:bold;'><td colspan='15' align='center'>
        <input type="checkbox" name="<? echo $fila[5]?>" checked 
        onclick="Cambiar(this,'<? echo str_replace(" ","_",$fila[5])?>',registros<? echo str_replace(" ","_",$fila[5])?>.value);" /><? echo $fila[5] ?></td></tr>
		<input type="hidden" name="registros<? echo str_replace(" ","_",$GrupAnt)?>" value="<? echo $NumRec?>" /><?
		$NumRec = 0;
	}
	?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
	<?
	?><td><input type="checkbox" name="Elemento[<? echo "$fila[10]|$fila[0]|$fila1[8]|$fila1[0]";?>]" id="<? echo str_replace(" ","_",$fila[5]).$NumRec;?>" checked /></td><?
	echo "<td>$fila[0]</td><td>$fila[1] $fila[9] $fila[4] $fila[2] $fila[3]</td>
	<td>$fila1[3] $fila1[4] $fila1[1] $fila1[2]</td>
	<td>$fila1[0] - $fila1[7]</td>";
	echo "</tr>";
	$GrupAnt = $fila[5];
	$NumRec++;
}
?>
<input type="hidden" name="registros<? echo str_replace(" ","_",$GrupAnt)?>" value="<? echo $NumRec?>" />
</table>
<input type="submit" name="Registrar" value="Registrar" onClick="parent.document.FORMA.Guardar.disabled = false;"/>
<input type="button" name="Volver" value="Volver" onClick="location.href='DetAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Tipo=<? echo $Tipo?>'" />
</form>
</body>