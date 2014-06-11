<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Tipo == "Orden Compra"){ $NumeroTipo = "NumeroOrdenCompra";}
	if($Tipo == "Compras"){ $NumeroTipo = "NumeroCompra";}
	if(!$DepEn){ $DepEn = "meses";}
	if(!$DepDur){ $DepDur = 1;}
	if(!$Grupo)
	{
		$cons = "Select Grupo from Infraestructura.GruposDeElementos 
		Where Compania = '$Compania[0]' and Anio = $ND[year] and Clase='$Clase'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Grupo = $fila[0];	
	}
	if(!$AutoId)
	{
		$cons = "Select AutoId from Infraestructura.CodElementos Where Compania='$Compania[0]' Order By AutoId Desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AutoId = $fila[0] + 1;	
	}
	if(!$Codigo){
		if(!$Consumo)
		{
			$cons = "Select CodGrupo from Infraestructura.GruposdeElementos Where Grupo = '$Grupo' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			$fila = ExFetch($res);
			$CodigoGrupo = $fila[0];
			$TamCodigo = strlen($CodigoGrupo)+1;
			$cons = "Select Codigo from Infraestructura.CodElementos 
			Where Compania = '$Compania[0]' and substr(Codigo,0,$TamCodigo)='$CodigoGrupo' and Clase = '$Clase' and Grupo='$Grupo' and Codigo Is Not NULL Order By Codigo Desc";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0)
			{
				$fila = ExFetch($res);
				$Codigo = $fila[0] + 1;
				$cons = "Select Codigo from Infraestructura.CodElementos Where Compania='$Compania[0]' and Codigo='$Codigo'";
				$res = ExQuery($cons);
				if(ExNumRows($res)>0)
				{
					$Codigo = $Codigo++;
					$CodGrupo = substr($Codigo,0,-6);
					if($CodigoGrupo != $CodGrupo){$Codigo=$CodigoGrupo."000001";};
				}
				$CodGrupo = substr($Codigo,0,-6);
			}
			else
			{
				$cons1 = "Select CodGrupo,NumInicial from Infraestructura.GruposdeElementos Where Compania='$Compania[0]' and Grupo='$Grupo' and Anio=$ND[year]";
				$res1 = ExQuery($cons1);
				$fila1 = ExFetch($res1);
				$Codigo = $fila1[0].$fila1[1];
				$CodGrupo = $fila1[0];	
			}	
		}
		
	}
	if($Guardar)
	{
		if($Depreciar == "on"){$D = 1;} else {$D = 0;}
		if($Activo == "on"){$A = 1;} else {$A = 0;}
		if(!$PorcIva){$VrIva = 0; $PorcIva = 0;}
		if(!$PorcReteFte){$VrReteFte = 0; $PorcReteFte = 0;}
		if($IVA == "on"){$IncluyeIva = 1; }	else{$IncluyeIva = 0;}
		if(!$DepDesde){$DepDesde = $FechaAd;}
                if(!$DepAcumulada){$DepAcumulada = 0;}
                if(!$AjustesInfla){$AjustesInfla = 0;}
		if(!$Editar)
		{
			if($Tipo == "Levantamiento Inicial")
			{
				$InsLevIni = " ,Codigo, FechaAdquisicion, Serie, Estado, DepAcumulada,DepDesde,UVE,UUVE,Consumo,AjustesxInflacion ";
				if($Consumo=="on"){$Codigo="";$Consumo=1;}
				else{$Consumo=0;}
				$ValsLevIni = " ,'$Codigo', '$FechaAd', '$Serie', '$Estado', $DepAcumulada, '$DepDesde',
				'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[0]',$Consumo, $AjustesInfla ";
			}
			if($Tipo == "Orden Compra")
			{
				$InsOrdenCompra = " ,PorcIva, VrIva, PorcReteFte, VrReteFte, IncluyeIva, TMPCOD, CostoOrdenCompra";
				$ValsOrdenCompra = " ,$PorcIva, $VrIva, $PorcReteFte, $VrReteFte, $IncluyeIva, '$TMPCOD', $CostoIni";
			}
			$cons = "Insert into Infraestructura.CodElementos(Compania,AutoId,Grupo,Impacto,Nombre,Caracteristicas,Modelo,Marca,CostoInicial,Depreciar,
			Activo,DepreciarEn,DepreciarDurante,Documentacion,Observaciones,Clase,UsuarioCrea,FechaCrea,Tipo $InsLevIni $InsOrdenCompra) values
			('$Compania[0]',$AutoId,'$Grupo','$Impacto','$Nombre','$Caracteristicas','$Modelo','$Marca',$CostoIni,$D,$A,'$DepEn',$DepDur,'$Documentacion',
			'$Observaciones','$Clase','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Tipo' $ValsLevIni $ValsOrdenCompra)";		
		}
		else
		{
			if(!$DepAcumulada){$DepAcumulada=0;}
                        if($Origen){$DUVE = " ,UVE = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', UUVE = '$usuario[0]' ";}
			if($Tipo == "Compras" || $Tipo == "Levantamiento Inicial")
			{
				if($Consumo=="on"){$Codigo="";$Consumo=1;}
				else{$Consumo=0;}
				$UptLevIniCompras = " ,Codigo='$Codigo',FechaAdquisicion='$FechaAd',Estado='$Estado',Serie='$Serie', DepDesde='$DepDesde' $DUVE";
				if($Tipo == "Levantamiento Inicial"){ $UptLevIniCompras = $UptLevIniCompras." ,DepAcumulada=$DepAcumulada, AjustesxInflacion=$AjustesInfla ";}
			}
			if($Tipo == "Orden Compra" || $Tipo == "Compras")
			{
				$UptOrdenCompras = " , PorcIva=$PorcIva, VrIva=$VrIva, 
				PorcReteFte=$PorcReteFte, VrReteFte=$VrReteFte, IncluyeIva=$IncluyeIva,
				Costo".str_replace(" ","",$Tipo)."='$CostoIni' ";
				$AdWhere = " and TMPCOD='$TMPCOD'";
			}
			$cons = "Update Infraestructura.CodElementos set Grupo='$Grupo', Impacto='$Impacto', Nombre='$Nombre', Caracteristicas='$Caracteristicas',
			Modelo='$Modelo', Marca='$Marca', CostoInicial=$CostoIni, Depreciar=$D, Activo=$A, DepreciarEn='$DepEn', DepreciarDurante=$DepDur,
			Documentacion='$Documentacion', Observaciones='$Observaciones', UsuarioMod='$usuario[0]', 
			FechaMod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' $UptLevIniCompras $UptOrdenCompras 
			Where Compania='$Compania[0]' and AutoId = $AutoId and Clase = '$Clase' and Tipo='$Tipo' $AdWhere";	
		}
		
		$res = ExQuery($cons);
		if($Tipo != "Levantamiento Inicial" && !$Origen)
		{
			?>
				<script language="javascript">
				location.href="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&TMPCOD=<? echo $TMPCOD;?>&Clase=<? echo $Clase;?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>";
				</script>
			<?
		}
		$Editar = 1;
	}
	
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Grupo.value==""){alert("No Existen Grupos configurados");return false;}
		if(document.FORMA.Tipo.value != "Orden Compra")
		{
			if(document.FORMA.Consumo.checked==false)
			{
				if(document.FORMA.Codigo.value==""){alert("Digite un valor para el Codigo del Elemento");return false;}		
			}
		}
		if(document.FORMA.Nombre.value==""){alert("Digite un valor para el Nombre del Elemento");return false;}
		if(document.FORMA.Modelo.value==""){alert("Digite un valor para el Modelo");return false;}
		if(document.FORMA.Marca.value==""){alert("Digite un valor para la Marca");return false;}
		if(document.FORMA.CostoIni.value==""){alert("Digite un valor para el Costo Inicial");return false;}
		if(document.FORMA.Tipo.value != "Orden Compra")
		{
			if(document.FORMA.FechaAd.value==""){alert("Digite un valor para la Fecha de Adquisicion");return false;}
			if(document.FORMA.Serie.value==""){alert("Digite un valor para la Serie");return false;}	
		}
	}
	function Cambiar(valor)
	{
		if(valor == "meses")
		{
			for(i = 1; i<=30; i++){document.FORMA.DepDur.remove(document.FORMA.DepDur.options[i-1]);}
			for(i = 1; i<=12; i++)
			{
				if(i== <? echo $DepDur?>){op = new Option("" + i,"" + i, "defaultSelected");}
				else{op = new Option("" + i,"" + i);}
				document.FORMA.DepDur.options[i-1] = op;
			}
		}
		else
		{
			for(i = 1; i<=30; i++)
			{
				if(i== <? echo $DepDur?>){op = new Option("" + i,"" + i, "defaultSelected");}
				else{op = new Option("" + i,"" + i);}
				document.FORMA.DepDur.options[i-1] = op;
			}	
		}
	}
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='50px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function AbrirAlt(e,ruta,nomid,id,w,h)
	{
		var x = e.clientX;
		var y = e.clientY;
		frames.FrameOpener.location.href=ruta+"?DatNameSID=<? echo $DatNameSID?>&"+nomid+"="+id;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y+120;
		document.getElementById('FrameOpener').style.left=x-150;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width=w;
		document.getElementById('FrameOpener').style.height=h;	
	}
	function cambios(obj)
	{
		if(obj.checked == true)
		{
			document.FORMA.Codigo.disabled = true;
			document.FORMA.Depreciar.disabled = true;
			document.FORMA.DepDur.disabled = true;
			document.FORMA.DepEn.disabled = true;
			document.FORMA.DepDesde.disabled = true;
			document.FORMA.DepAcumulada.readOnly = true;
			if(document.FORMA.CostoIni.value != "")
			{
				document.FORMA.DepAcumulada.value = document.FORMA.CostoIni.value;
			}
		}
		else
		{
			document.FORMA.Codigo.disabled = false;
			document.FORMA.Depreciar.disabled = false;
			document.FORMA.DepDur.disabled = false;
			document.FORMA.DepEn.disabled = false;
			document.FORMA.DepDesde.disabled = false;
			document.FORMA.DepAcumulada.readOnly = false;	
		}	
	}
</script>
<?
	if($Editar)
	{
		if($Tipo == "Levantamiento Inicial"){ $CT = " and Tipo = 'Levantamiento Inicial'";}
		else
		{
			$CT = " and Tipo = '$Tipo'";
			$CadAd = ",VrIva,VrReteFte,PorcIva,PorcReteFte";
		}
		$cons = "Select Codigo,FechaAdquisicion,Grupo,Impacto,Nombre,Caracteristicas,Modelo,Serie,
		Estado,Marca,CostoInicial,Depreciar,Activo,DepreciarEn,DepreciarDurante,Documentacion,Observaciones,
		IncluyeIva,DepAcumulada, DepDesde $CadAd, Consumo,AjustesxInflacion
		From Infraestructura.CodElementos Where Compania = '$Compania[0]' and AutoId = $AutoId and Clase = '$Clase' $CT";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		if($fila[0]!=""){$Codigo = $fila[0];}
		$FechaAd = $fila[1]; $Grupo = $fila[2]; $Impacto = $fila[3]; $Nombre = $fila[4];
		$Caracteristicas = $fila[5]; $Modelo = $fila[6]; $Serie = $fila[7]; $Estado = $fila[8]; $Marca = $fila[9];
		$CostoIni = $fila[10]; $D = $fila[11]; $A = $fila[12]; $DepEn = $fila[13]; $DepDur = $fila[14];
		$Documentacion = $fila[15]; $Observaciones = $fila[16];$IncluyeIva = $fila[17]; $DepAcumulada = $fila[18]; $DepDesde = $fila[19];
		$VrIva = $fila[20]; $VrReteFte = $fila[21];$PorcIva = $fila[22]; $PorcReteFte = $fila[23]; 
		if(!$FechaAd && $Tipo=="Compras"){$FechaAd="$Anio-$Mes-$Dia";}
		if($Tipo=="Levantamiento Inicial"){$ChkConsumo = $fila[20]; $AjustesInfla = $fila[21];}
		else{$ChkConsumo = $fila[24];}
		//echo $ChkConsumo;
		
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<? if($Origen){?><input type="submit" name="Guardar" value="Guardar" /><? }?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<input type="hidden" name="Clase" value="<? echo $Clase?>" />
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<input type="hidden" name="Clase" value="<? echo $Clase;?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>" />
<input type="hidden" name="CodGrupo" value="<? echo $CodGrupo?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Mes" value="<? echo $Mes?>" />
<input type="hidden" name="Dia" value="<? echo $Dia?>" />
<input type="hidden" name="Origen" value="<? echo $Origen?>" />


<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Codigo</td>
        <? if($Tipo != "Levantamiento Inicial"){ $Ocultar = " onfocus = 'parent.Ocultar();'";}?>
        <td><input type="text" name="Codigo" <? echo $Ocultar;?> size="8" value="<? if($Tipo=="Orden Compra"){ echo "";}else{echo $Codigo;}?>"
        	<? if($Tipo == "Orden Compra"){ echo " readonly ";}?> 
        	onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="font-size:<? echo 11-((strlen($CodGrupo)-4)/10)?>px; width:100%;"
            <? if($ChkConsumo){echo " disabled ";}?> /></td>
        <? if($Tipo!="Orden Compra")
		{ ?>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Fecha de<br>Adquisici&oacute;n</td>
        <td>
        <input type="text" name="FechaAd" size="8" value="<? echo $FechaAd?>" 
        <? if($Tipo=="Levantamiento Inicial"){?>onclick="popUpCalendar(this, FORMA.FechaAd, 'yyyy-mm-dd');"<? }?> readonly />
        </td>	
		<? }?>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Grupo</td>
        <td><select name="Grupo" style="width:150px" onChange="frames.Grupos.location.href='Buscagrupo.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Grupo='+this.value" <? echo $Ocultar;?>>
        <?
        	$cons = "Select Grupo,ModoDeprecia,ValorDeprecia,CodGrupo,NumInicial from Infraestructura.GruposdeElementos 
			Where Compania = '$Compania[0]' and Anio = $ND[year] and Clase='$Clase'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Grupo == "$fila[0]")
				{
					$DepEn = $fila[1]; 
					$DepDur = $fila[2];
					echo "<option selected title='$fila[0]' value='$fila[0]'>$fila[0]</option>";
				}
				else{echo "<option title='$fila[0]' value='$fila[0]'>$fila[0]</option>";}
			}
		?>
        </select></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Impacto</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold"><select name="Impacto" <? echo $Ocultar;?>>
        <?
        	$cons = "Select Nombre from Central.Impactos";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Impacto == "$fila[0]"){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
			}
		?>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td>
        <td colspan="7"><input type="text" name="Nombre" <? echo $Ocultar;?> value="<? echo $Nombre?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"
        style="width:610px" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Caracteristicas</td>
        <td colspan="7"><input type="text" name="Caracteristicas" <? echo $Ocultar;?>  value="<? echo $Caracteristicas;?>"
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="width:610px" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" <? if($Tipo == "Orden Compra"){ echo " rowspan='2' ";}?> >Modelo</td>
        <td <? if($Tipo == "Orden Compra"){ echo " rowspan='2' ";}?>><input type="text" name="Modelo" value="<? echo $Modelo;?>" size="8"
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" <? echo $Ocultar;?> /></td>
        <?  if($Tipo!="Orden Compra")
			{ ?><td bgcolor="#e5e5e5" style="font-weight:bold">Estado</td>
                <td><select name="Estado">
                <?
                    $cons = "Select Nombre from Central.Estados";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        if($Estado == "$fila[0]"){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                        else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                    }
                ?>
                </select></td>
                <td bgcolor="#e5e5e5" style="font-weight:bold">Serie</td>
                <td><input type="text" name="Serie" value="<? echo $Serie;?>"
                onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
			<? } ?>
		<td bgcolor="#e5e5e5" style="font-weight:bold" <? if($Tipo == "Orden Compra"){ echo " rowspan='2' ";}?> >Marca</td>
        <td <? if($Tipo == "Orden Compra"){ echo " rowspan='2' ";}?>><input type="text" name="Marca" value="<? echo $Marca;?>" size="8"
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" <? echo $Ocultar;?> /></td>
        <? if($Tipo != "Levantamiento Inicial")
		{	if($Tipo == "Compras"){echo "<tr>";}
			?> <td bgcolor="#e5e5e5" style="font-weight:bold;">Iva Incluido
        	<input type="checkbox" name="IVA" <? echo $Ocultar;?>
            <? if($Editar){ if($IncluyeIva){ echo " checked ";}}?>
            onClick="if(this.checked == true){VrIva.value = '0';}else{VrIva.value = (PorcIva.value*CostoIni.value/100);}" ></td>
			<td colspan="3"><input type="text" name="VrIva" size="4" readonly <? echo $Ocultar;?> value="<? echo $VrIva?>" />
            <input type="text" name="PorcIva" size="2" <? echo $Ocultar;?> value="<? echo $PorcIva;?>"
            onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this);
            if(IVA.checked == false){ VrIva.value = (this.value*CostoIni.value/100);}" />%</td>
		<? 
			if($Tipo == "Orden Compra"){echo "</tr>";}
		}?>
    <?
    if($Tipo != "Levantamiento Inicial")
	{
		if($Tipo=="Orden Compra"){echo "<tr>";}
	?>
		<td bgcolor="#e5e5e5" style="font-weight:bold">retefuente</td>
    	<td colspan="3">
        <input type="text" readonly onFocus="parent.Ocultar();" name="VrReteFte" value="<? echo $VrReteFte?>" size="4" />
		<input type="text" readonly value="<? echo $PorcReteFte?>" size="2"  
        onfocus="parent.Mostrar();parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=NuevoMovimiento&Tipo=Retenciones&Anio='+parent.document.FORMA.Anio.value;" 
        name="PorcReteFte" />%
        </td>
    <?	
		if($Tipo=="Orden Compra"){echo "</tr>";}
	}
	?>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Costo Inicial</td>
        <td><input type="text" name="CostoIni" value="<? echo $CostoIni;?>" size="8" <? echo $Ocultar;?> 
        style="text-align:right;" onKeyUp="xNumero(this);
        if(Consumo.checked==true){DepAcumulada.value = this.value;};
        <? if($Tipo != "Levantamiento Inicial")
		{?>if(IVA.checked == false){ VrIva.value = (this.value*PorcIva.value/100)};VrReteFte.value=(this.value*PorcReteFte.value/100);<? }?>" 
        onKeyDown="xNumero(this)" onBlur="campoNumero(this);
        <? if($Tipo != "Levantamiento Inicial")
		{?>if(IVA.checked == false){ VrIva.value = (this.value*PorcIva.value/100)};VrReteFte.value=(this.value*PorcReteFte.value/100);<? }?>" />
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Depreciar 
        <input type="checkbox" name="Depreciar" <? if(!$Editar){ echo " checked ";} else{ if($D){ echo " checked ";}}?> <? echo $Ocultar;?>
        onclick="if(this.checked == true){DepEn.disabled = false; DepDur.disabled = false;}else{DepEn.disabled = true; DepDur.disabled = true;}"
        <? if($ChkConsumo){echo " disabled ";}?> /></td>
        <td colspan="4" bgcolor="#e5e5e5" align="center">
        En	<select name="DepDur" <? if(!$D && $Editar){ echo " disabled ";}?> <? echo $Ocultar;?> <? if($ChkConsumo){echo " disabled ";}?> >
			<?
                if($DepEn == "meses"){$Lim = 12;}
                else{ $Lim = 30;}
                for($i=1;$i<=$Lim;$i++)
                {
                    if($DepDur == $i)
                    {echo "<option selected value='$i'>$i</option>";}
                    else
                    {echo "<option value='$i'>$i</option>";}
                }
            ?>
            </select>
        <select name="DepEn" onChange="Cambiar(this.value)" <? if(!$D && $Editar){ echo " disabled ";}?> <? echo $Ocultar;?> <? if($ChkConsumo){echo " disabled ";}?> >
            <option <? if($DepEn == "meses"){ echo " selected ";}?> value="meses">Meses</option>
            <option <? if($DepEn == "anios"){ echo " selected ";}?> value="anios">A&ntilde;os</option>
        </select>
        <?
		if($Tipo != "Orden Compra")
		{
		?>
			<!--<b>Desde</b>
             -->
		<?
		}
		if($Tipo == "Levantamiento Inicial")
		{
			?>
			<!-- <br>
			<b>Depreciacion Acumulada</b>
			 -->
			<?	
		}
		?>
        </td>
         <td bgcolor="#e5e5e5" style="font-weight:bold">
         	Activo <input type="checkbox" title="Activo/Inactivo" name="Activo" <? if(!$Editar){ echo " checked ";} else{ if($A){ echo " checked ";}}?> <? echo $Ocultar;?> />
            <!--  -->
         </td>
    </tr>
    <? if($Tipo != "Orden de Compra")
    {
        ?><tr bgcolor="#e5e5e5" style="font-weight:bold" >
        <td>Depreciar desde</td><td><input type="text" name="DepDesde" size="8" value="<? echo $DepDesde;?>"
            onclick="popUpCalendar(this, FORMA.DepDesde, 'yyyy-mm-dd')" readonly onDblClick="this.value=''" <? if($ChkConsumo){echo " disabled ";}?> /></td>
        <? if($Tipo == "Levantamiento Inicial")
        {
            ?>
            <td>Depreciaci&oacute;n acumulada</td>
            <td><input type="text" name="DepAcumulada" value="<? echo $DepAcumulada?>" onKeyUp="xNumero(this)"
                 onKeyDown="xNumero(this)" onBlur="campoNumero(this)" size="8" style="text-align:right" <? if($ChkConsumo){echo " disabled ";}?> /></td>
            <td colspan="2" align="right">Ajustes por inflacion</td>
            <td><input type="text" name="AjustesInfla" value="<? echo $AjustesInfla?>" onKeyUp="xNumero(this)"
                onKeyDown="xNumero(this)" onBlur="campoNumero(this)" size="8" style="text-align:right" <? if($ChkConsumo){echo " disabled ";}?> /></td>
            <?
        }?>
        <td>Consumo <input type="checkbox" name="Consumo" <? echo $Ocultar;?> onClick="cambios(this)"
            <? if($ChkConsumo=="1"){echo " checked ";}?> /></td>
        </tr><?
    }?>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Documentaci&oacute;n</td>
        <td colspan="7"><input type="text" name="Documentacion" style="width:610px;" value="<? echo $Documentacion;?>" <? echo $Ocultar;?>
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Observaciones</td>
        <td colspan="7"><textarea name="Observaciones" style="width:610px" <? echo $Ocultar;?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Observaciones;?></textarea></td>
    </tr>
<? if($Clase == "Devolutivos" && $Editar && ($Tipo == "Levantamiento Inicial" || ( $Tipo == "Compras" && $Origen)))
{ ?> <tr><td colspan="8">
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold; cursor:hand;">
            	<td onMouseOver="this.bgColor='#AAD4FF'" 
            	onmouseout="this.bgColor='#e5e5e5'" 
                onClick="frames.Contenido.location.href='Ubicaciones.php?DatNameSID=<? echo $DatNameSID?>&FechaIni='+FechaAd.value+'&AutoId=<? echo $AutoId?>'">Ubicaci&oacute;n</td>
                <td onMouseOver="this.bgColor='#AAD4FF'" 
            	onmouseout="this.bgColor='#e5e5e5'"
                onClick="frames.Contenido.location.href='Mantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $AutoId?>'">Mantenimiento</td>
                <td onMouseOver="this.bgColor='#AAD4FF'" 
            	onmouseout="this.bgColor='#e5e5e5'"
                onClick="frames.Contenido.location.href='Agendamiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $AutoId?>'">Agendamiento</td>
			</tr>
            <tr>
            	<td colspan="3">
                	<iframe name="Contenido" id="Contenido" frameborder="0" src="Ubicaciones.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $AutoId?>&FechaIni=<? echo $FechaAd?>"
                    height="220px" style="width:100%;">
                    </iframe>
                </td>
            </tr>	
        </table>	
</td></tr> <? } ?>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<? if(!$Origen)
{ ?>
<input type="button" name="Volver" value="Volver" <? echo $Ocultar;?> 
<? if($Tipo == "Levantamiento Inicial"){?>onClick="location.href='LevInicial.php?DatNameSID=<? echo $DatNameSID?>&Buscar=1&Clase=<? echo $Clase;?>&Identificacion='+Identificacion.value+'&CC='+CC.value"<? }
else{?> onClick="location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&TMPCOD=<? echo $TMPCOD;?>&Clase=<? echo $Clase;?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>'"<? }?> />
<? }?>

<input type="hidden" name="Identificacion" />
<input type="hidden" name="CC" />
</form>
<iframe name="Grupos" id="Grupos" height="1" src="Buscagrupo.php?DatNameSID=<? echo $DatNameSID?>&Grupo=<? echo $Grupo?>&Codigo=<? echo $Codigo?>&Tipo=<? echo $Tipo?>" style="visibility:hidden" ></iframe>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
