<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Solicitar)
	{
		$cons = "Select AutoId from InfraEstructura.Bajas Where Compania='$Compania[0]' and AutoId=$AutoId and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			if(!$Editar)
			{
				$cons = "Insert Into InfraEstructura.Bajas (Compania, AutoId, Codigo, TMPCOD,SubUbicacionResp,Estado)
				values('$Compania[0]',$AutoId,'$Codigo','$TMPCOD','$SubUb','Solicitado')";	
			}
			else
			{
				$cons = "Update Infraestructura.Bajas Set AutoId=$AutoId, Codigo = '$Codigo'
				Where Compania='$Compania[0]' and TMPCOD = '$TMPCOD' and AutoId=$AutoIdX";
			}
			$res = ExQuery($cons);
			?>
			<script language="javascript">
				location.href = "DetBajas.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD;?>&Clase=<? echo $Clase;?>";	
			</script>
			<?	
		}
		else
		{
			$MostrarAlert = 1;	
		}
			
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Codigo.value==""){alert("Debe Ingresar el Codigo del Elemento");return false;}
		if(document.FORMA.Nombre.value==""){alert("Debe Ingresar el Nombre del Elemento");return false;}		
	}
	function Borrar(objeto)
	{
		if(objeto != "Codigo"){document.FORMA.Codigo.value = "";}
		if(objeto != "Nombre")document.FORMA.Nombre.value = "";
		document.FORMA.Caracteristicas.value = "";
		if(objeto != "Modelo")document.FORMA.Modelo.value = "";
		if(objeto != "Serie")document.FORMA.Serie.value = "";
		//if(objeto != "RespAct")document.FORMA.RespActual.value = "";
		//document.FORMA.CCAct.value = "";
		
	}
</script>
<?
	if($Editar)
	{
		$cons = "Select distinct(CodElementos.AutoId),CodElementos.Codigo,Nombre,Caracteristicas,Modelo,Serie,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,
		Ubicaciones.Responsable,PrimNom,SegNom,PrimApe,SegApe 
		From InfraEstructura.CodElementos,InfraEstructura.Ubicaciones,Central.CentrosCosto,Central.Terceros,InfraEstructura.Bajas
		Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
		and Bajas.Compania = '$Compania[0]' and Bajas.AutoId = CodElementos.AutoId
		and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and 
		Ubicaciones.Responsable = Terceros.Identificacion and (CodElementos.Tipo='Levantamiento Inicial' or (CodElementos.Tipo='Compras' and EstadoCompras='Ingresado'))
		and Bajas.AutoId = $AutoId and Bajas.TMPCOD='$TMPCOD'";	
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AutoId = $fila[0]; $Codigo = $fila[1]; $Nombre = $fila[2]; $Caracteristicas = $fila[3]; $Modelo = $fila[4];
		$Serie = $fila[5]; $CC = $fila[6]; $NomCCAct = $fila[7]; $IDRA = $fila[8]; $Responsable = "$fila[9] $fila[10] $fila[11] $fila[12]";
	}
?>
<form name="FORMA" method="post" onsubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>" />
<input type="hidden" name="Numero" value="<? echo $Numero;?>" />
<input type="hidden" name="Anio" value="<? echo $Anio;?>"  />
<input type="hidden" name="Tipo" value="<? echo $Tipo;?>" />
<input type="hidden" name="Clase" value="<? echo $Clase;?>" />
<input type="hidden" name="Editar" value="<? echo $Editar;?>"  />
<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr>
    	<td bgcolor="#e5e5e5" width="10%">Responsable Actual</td>
    	<td colspan="3"><input type="Text" name="Responsable" style="width:100%" value="<? echo $Responsable?>"  onFocus="parent.Mostrar();
if(CC.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=IDRA&ObjetoNombre=Responsable&Frame=Bajas&Tipo=Nombre&Nombre='+this.value;}
else{ parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&ObjId=IDRA&ObjTercero=Responsable&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}" 
onKeyUp="xLetra(this);IDRA.value='';
if(CC.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjId=IDRA&ObjetoNombre=Responsable&Frame=Bajas&Tipo=Nombre&Nombre='+this.value;}
else{ parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&ObjId=IDRA&ObjTercero=Responsable&Tercero='+this.value+'&Tipo=TerceroxCC&CC='+CC.value+'&Anio=<? echo $ND[year]?>';}"
onKeyDown="xLetra(this)"/>
<input type="hidden" name="IDRA" value="<? echo $IDRA?>" /> 

    </tr>
    <tr>
        <td bgcolor="#e5e5e5">Centro de Costos</td>
        <td> <input type="text" name="CC" style="text-align:right; width:150px" value="<? echo $CC?>" 
onFocus="parent.Mostrar();
if(IDRA.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
else{parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&ObjetoCC=CC&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+IDRA.value;};"
onkeyup="SubUb.value='';if(IDRA.value==''){parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';}
else{parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&Tipo=CCxTercero&CC='+this.value+'&Anio=<? echo $ND[year]?>&Cedula='+IDRA.value;};
xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /> </td>
		<td bgcolor="#e5e5e5" align="right">Sub Ubicaci&oacute;n</td>
        <td>
        <input type="text" name="SubUb" onKeyDown="xLetra(this)" 
        onfocus="parent.Mostrar();
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';"
        onkeyup="xLetra(this);
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=Bajas&Tipo=SubUbicacionxCC&SubUbicacion='+this.value+'&CC='+CC.value+'&ObjUbicacion=SubUb&Anio=<? echo $ND[year]?>';" />
        </td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Codigo</td>
        <td><input type="text" name="Codigo" value="<? echo $Codigo;?>" 
        onfocus="parent.Mostrar();
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=CodInfraest&Anio='+parent.document.FORMA.Anio.value+'&Codigo='+this.value;"
        onkeyup="parent.Mostrar();xLetra(this);
        Borrar('Codigo'); 
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=CodInfraest&Anio='+parent.document.FORMA.Anio.value+'&Codigo='+this.value;" onkeydown="xLetra(this)" />
        <input type="hidden" name="AutoId" value="<? echo $AutoId?>" /></td>
        <td colspan="2" bgcolor="#e5e5e5">&nbsp;</td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Nombre</td>
        <td colspan="3"><input type="text" name="Nombre" value="<? echo $Nombre?>" style="width:100%;" 
        onfocus="parent.Mostrar();
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=NomInfraest&Anio='+parent.document.FORMA.Anio.value+'&Nombre='+this.value;"
        onkeyup="xLetra(this); Borrar('Nombre');
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=NomInfraest&Anio='+parent.document.FORMA.Anio.value+'&Nombre='+this.value;" onkeydown="xLetra(this)" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Caracteristicas</td>
        <td colspan="3">
        <input type="text" name="Caracteristicas" value="<? echo $Caracteristicas?>" style="width:650px;" 
        onkeyup="xLetra(this)" onkeydown="xLetra(this)" readonly onfocus="parent.Ocultar()" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Modelo</td>
        <td><input type="text" name="Modelo" value="<? echo $Modelo?>" style="width:100%" 
        onfocus="parent.Mostrar();
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=ModInfraest&Modelo='+this.value;"
        onkeyup="Borrar('Modelo');
        xLetra(this);parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=ModInfraest&Modelo='+this.value;"
        onkeydown="xLetra(this);" /></td>
    	<td bgcolor="#e5e5e5" width="180px" align="right">Serie</td>
        <td><input type="text" name="Serie" value="<? echo $Serie?>" style="width:100%" 
        onfocus="parent.Mostrar();
        parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=SerInfraest&Serie='+this.value;"
         onkeyup="Borrar('Serie');
         parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC='+CC.value+'&SubUb='+SubUb.value+'&Identificacion='+IDRA.value+'&Frame=Bajas&Tipo=SerInfraest&Serie='+this.value;" />
         </td>
    </tr>
</table>
<input type="submit" name="Solicitar" value="Solicitar" onclick="parent.Ocultar()" />
<input type="button" name="Volver" value="Volver" 
onclick="parent.Ocultar();
location.href='DetBajas.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD;?>&Tipo=<? echo $Tipo;?>&Clase=<? echo $Clase;?>&Anio=<? echo $Anio?>';" />	
</form>
<?
	if($MostrarAlert)
	{
		?>
			<script language="javascript">alert("El elemento se encuentra procesandose");</script>
		<?	
	}
?>