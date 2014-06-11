<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	$ND=getdate();
        if($Guardar)
	{
		if($CtaContable==""){$CtaContable = " NULL ";}	else{$CtaContable = " '$CtaContable' ";}
		if($VrReteFte==""){$VrReteFte = " NULL ";}		else{$VrReteFte = " '$VrReteFte' ";}
		if($CtaReteFteE==""){$CtaReteFteE = " NULL ";}	else{$CtaeReteFteE = " '$CtaReteFteE' ";}
		if($CtaReteFteS==""){$CtaReteFteS=" NULL ";}	else{$CtaReteFteS=" '$CtaReteFteS' ";}
		if($VrReteICA==""){$VrReteICA=" NULL ";}		else{$VrReteICA=" '$VrReteICA' ";}
		if($CtaReteICAE==""){$CtaReteICAE=" NULL ";}	else{$CtaReteICAE=" '$CtaReteICAE' ";}
		if($CtaReteICAS==""){$CtaReteICAS=" NULL ";}	else{$CtaReteICAS=" '$CtaReteICAS' ";}
		if($CtaProveedor==""){$CtaProveedor=" NULL ";}	else{$CtaProveedor=" '$CtaProveedor' ";}
		if($CtaGasto==""){$CtaGasto=" NULL ";}			else{$CtaGasto=" '$CtaGasto' ";}
		if($CtaIVAE==""){$CtaIVAE=" NULL ";}			else{$CtaIVAE=" '$CtaIVAE' ";}
		if($CtaIVAS==""){$CtaIVAS=" NULL ";}			else{$CtaIVAS=" '$CtaIVAS' ";}
		if(!$Editar)
		{
                    $cons = "select * from Consumo.grupos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio and Grupo='$Grupo'";
                    $res = ExQuery($cons);
                    if(ExNumRows($res)>0){$MensajeInserta="El grupo $Grupo ingresado tiene nombre repetido para el mismo Anio";}
                    $cons = "Insert into Consumo.Grupos
                    (Grupo,AlmacenPpal,Compania,CtaContable,ReteFte,CtaReteFteE,CtaReteFteS,ReteICA,CtaReteICAE,CtaReteICAS,CtaProveedor,CtaGasto,CtaIVAE,CtaIVAS,Anio,grupofact)
                    values
                    ('$Grupo','$AlmacenPpal','$Compania[0]',$CtaContable,$VrReteFte,$CtaReteFteE,$CtaReteFteS,$VrReteICA,
                    $CtaReteICAE,$CtaReteICAS,$CtaProveedor,$CtaGasto,$CtaIVAE,$CtaIVAS,'$Anio','$AuxGrupFact')";
		}
		else
		{
                    $cons = "Update Consumo.Grupos set
                    Grupo='$Grupo',CtaContable=$CtaContable,ReteFte=$VrReteFte,CtaReteFteE=$CtaReteFteE,CtaReteFteS=$CtaReteFteS,
                    ReteICA=$VrReteICA,CtaReteICAE=$CtaReteICAE,CtaReteICAS=$CtaReteICAS,CtaProveedor=$CtaProveedor,CtaGasto=$CtaGasto,
                    CtaIVAE=$CtaIVAE,CtaIVAS=$CtaIVAS,grupofact='$AuxGrupFact'
                    where Grupo='$Grupox' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and Anio = '$Anio'";
		}
		if(!$MensajeInserta)
                {$res=ExQuery($cons);$Editar = 1;}
        }
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
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
	function Validar()
	{
		var b=0;
		if(document.FORMA.Grupo.value==""){alert("Debe llenar el campo Nombre Grupo");b=1;}
		else{if(document.FORMA.CtaContable.value==""){alert("Debe llenar el Campo Cuenta Grupo");b=1;}
			else{if(document.FORMA.CtaProveedor.value==""){alert("Debe llenar el Campo Cuenta Proveedor");b=1;}}}
		if(b==1) return false;
	}
	Ocultar();
</script>
<body background="/Imgs/Fondo.jpg">
<?
	if($Editar)
	{
		$cons1="Select  Grupo,CtaContable,ReteFte,CtaReteFteE,CtaReteFteS,ReteICA,CtaReteICAE,CtaReteICAS,CtaProveedor,CtaIVAE,CtaIVAS,CtaGasto,grupofact
		from Consumo.Grupos where Grupo='$Grupo' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and Anio='$Anio'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$Grupo=$fila1[0];
		$CtaContable=$fila1[1];
		$VrReteFte = $fila1[2];
		if($fila1[2]>0){$CheckedR=" checked ";}
		$CtaReteFteE = $fila1[3];
		$CtaReteFteS = $fila1[4];
		$VrReteICA=$fila1[5];
		if($fila1[5]>0){$CheckedI=" checked ";}
		$CtaReteICAE = $fila1[6];
		$CtaReteICAS = $fila1[7];
		$CtaProveedor=$fila1[8];
		$CtaIVAE=$fila1[9];
		$CtaIVAS=$fila1[10];
		$CtaGasto=$fila1[11];
		$AuxGrupFact=$fila1[12];
		$cons2="select grupo from contratacionsalud.gruposservicio where compania='$Compania[0]' and codigo='$fila1[12]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$GrupoFact=$fila2[0];
	}
?>
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal; ?>"  />
<input type="Hidden" name="Grupox" value="<? echo $Grupo; ?>"  />
<input type="Hidden" name="Editar" value="<? echo $Editar; ?>"  />
<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="6" align="center"><? echo $AlmacenPpal;?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">NOMBRE GRUPO:</td>
    	<td><input type="text" onFocus="Ocultar()" name="Grupo" value="<? echo $Grupo;?>" maxlength="30" size="30" 
        	onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
   	  <td bgcolor="#e5e5e5" align="right">Cuenta Grupo:</td>
        <td><input type="text" name="CtaContable" value="<? echo $CtaContable;?>" style="width:100%;" 
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaContable';" 
		onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaContable';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
    </tr>
    <tr>
    	<td align="right" bgcolor="#e5e5e5" colspan="3">Cuenta Proveedor:</td>
        <td><input type="text" name="CtaProveedor" value="<? echo $CtaProveedor; ?>" style="width:100%;"  
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaProveedor'" 
        onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaProveedor';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
    </tr>
    <tr>
    	<td align="right" bgcolor="#e5e5e5" colspan="3">Cuenta Gasto:</td>
        <td><input type="text" name="CtaGasto" value="<? echo $CtaGasto; ?>" style="width:100%;"  
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaGasto'" 
        onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaGasto';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
    </tr>
    <tr>
    	<td align="right" bgcolor="#e5e5e5" colspan="3">Grupo Facturacion:</td>
        <td>
        	<input type="text" name="GrupoFact" readonly style="width:100%" 
            onClick="Mostrar();frames.Busquedas.location.href='BusqGrupoFac.php?DatNameSID=<? echo $DatNameSID?>'" value="<? echo $GrupoFact?>">
            <input type="hidden" name="AuxGrupFact" value="<? echo $AuxGrupFact?>">
        </td>
    </tr>
    <tr bgcolor="#e5e5e5">
    	<td colspan="2">&nbsp;</td>
        <td colspan="2" align="center">CUENTA</td>
    </tr>
    <tr bgcolor="#e5e5e5">
    	<td colspan="2">&nbsp;</td>
        <td align="center">Entrada</td><td align="center">Salida</td>
    </tr>
    <tr>
    	<td align="right" bgcolor="#e5e5e5">ReteFuente<input type="checkbox" onFocus="Ocultar()" name="ReteFte"  <? echo $CheckedR; ?> /></td>
        <td><input type="text" onFocus="Ocultar()" name="VrReteFte" value="<? echo $VrReteFte; ?>"  size="6" maxlength="6" 
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td><input type="text" name="CtaReteFteE" value="<? echo $CtaReteFteE; ?>" size="20" maxlength="20" 
		onFocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteFteE';" 
        onkeyup="xNumero(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteFteE';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td><input type="text" name="CtaReteFteS" value="<? echo $CtaReteFteS; ?>" size="20" maxlength="20"
        onFocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteFteS';" 
        onkeyup="xNumero(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteFteS';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
    </tr>
    <tr>
    	<td align="right" bgcolor="#e5e5e5">ICA<input type="checkbox" onFocus="Ocultar()" name="ReteICA"  <? echo $CheckedI; ?> /></td>
        <td><input type="text" onFocus="Ocultar()" name="VrReteICA" value="<? echo $VrReteICA; ?>" size="6" maxlength="6"  
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td><input type="text" name="CtaReteICAE" value="<? echo $CtaReteICAE; ?>" size="20" maxlength="20" 
        onFocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteICAE';" 
		onkeyup="xNumero(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteICAE';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td><input type="text" name="CtaReteICAS" value="<? echo $CtaReteICAS; ?>" size="20" maxlength="20" 
        onFocus="Mostrar();
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteICAS'" 
		onkeyup="xNumero(this);
        frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaReteICAS';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
    </tr>
    <tr>
    	<td colspan="2" bgcolor="#e5e5e5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IVA</td>
        <td><input type="text"  name="CtaIVAE" value="<? echo $CtaIVAE; ?>" size="20" maxlength="20"
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaIVAE'"
		onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaIVAE';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td><input type="text" name="CtaIVAS" value="<? echo $CtaIVAS; ?>" size="20" maxlength="20" 
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaIVAS'" 
		onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=CtaIVAS';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
    </tr>
    <? if($Editar){?>
			<tr><td colspan="4"><iframe src="ItemsxGrupo.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal?>&Grupo=<? echo $Grupo?>" 
            frameborder="0" style="width:100%"></iframe></td></tr>
		<? } ?>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cerrar" onClick="location.href='ConfigGrupos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal?>';Ocultar();" />
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<?
    if($MensajeInserta)
    {
        ?><script language="javascript">
            alert("<? echo $MensajeInserta?>");
            location.href="ConfigGrupos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal?>";
        </script><?
    }
?>
</body>
