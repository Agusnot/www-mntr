<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$DepreciarEn){ $DepreciarEn = "meses";}
	if(!$Durante){ $Durante = 1;}
	$cons="Select CuentasDepxCC.CentroCostos,CentrosCosto.CentroCostos,Cuenta from 
		Infraestructura.CuentasDepxCC,Central.CentrosCosto where 
		CuentasDepxCC.CentroCostos=CentrosCosto.Codigo and CuentasDepxCC.Compania='$Compania[0]'
		and Grupo='$Grupo' and CuentasDepxCC.Anio = $Anio";
	$res=ExQuery($cons);
	$NumFilas = ExNumRows($res);	
	if($Guardar)
	{
		if(!$Editar)
		{
			do
			{
				if($CodGrupo==$fila[0])
                                {
                                    if(!$Asignado)
                                    {
                                        $Asignado=1;
                                        $CodGrupo = $CodGrupo."01";
                                    }
                                    else
                                    {
                                        $CodGrupo ++;
                                    }
                                }
				$cons = "Select CodGrupo From InfraEstructura.GruposdeElementos Where Compania='$Compania[0]' and Anio=$Anio
				and CodGrupo=$CodGrupo";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
			}while(ExNumRows($res)>0);
			$cons = "Insert Into Infraestructura.GruposdeElementos 
			(Compania,Grupo,CtaGrupo,CtaProveedor,Anio,ModoDeprecia,ValorDeprecia,DepreciAcumulada,Clase,CodGrupo,NumInicial)
			values ('$Compania[0]','$Grupo','$CuentaGrupo','$CtaProveedor',$Anio,'$DepreciarEn',$Durante,$DepAcumulada,'$Clase',$CodGrupo,'$NumInicial')";
			$Editar = 1;
		}
		else
		{
			if($CuentaGrupo == $CtaGrupoX){$CodGrupo = $CodGrupoX;}
			do
			{
				if($CodGrupo==$fila[0])
                                {
                                    if(!$Asignado)
                                    {
                                        $Asignado=1;
                                        $CodGrupo = $CodGrupo."01";
                                    }
                                    else
                                    {
                                        $CodGrupo ++;
                                    }
                                }
				$cons = "Select CodGrupo From InfraEstructura.GruposdeElementos Where Compania='$Compania[0]' and Anio=$Anio
				and CodGrupo=$CodGrupo";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
			}while(ExNumRows($res)>0);
			$cons = "Update Infraestructura.GruposdeElementos
			set Grupo = '$Grupo', CtaGrupo = '$CuentaGrupo', CtaProveedor = '$CtaProveedor', ModoDeprecia = '$DepreciarEn',
			ValorDeprecia = $Durante, DepreciAcumulada = $DepAcumulada, Clase = '$Clase', CodGrupo = $CodGrupo, NumInicial='$NumInicial' 
			Where Anio=$Anio and Compania='$Compania[0]' and Grupo='$XGrupo'";	
		}
		$res = ExQuery($cons);
	}
	if($Editar)
	{
		$cons = "Select CtaGrupo,CtaProveedor,ModoDeprecia,ValorDeprecia,DepreciAcumulada,Clase,CodGrupo,NumInicial
		From Infraestructura.GruposDeElementos 
		Where Grupo='$Grupo' and Compania='$Compania[0]' and Anio=$Anio";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$CuentaGrupo = $fila[0]; $CtaProveedor = $fila[1]; $DepreciarEn = $fila[2]; 
		$Durante = $fila[3]; $DepAcumulada = $fila[4]; $Clase = $fila[5];
		$CodGrupo = $fila[6]; $NumInicial = $fila[7];  		
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
		if(document.FORMA.Grupo.value == ""){alert("Debe llenar el nombre del Grupo");return false;}
		if(document.FORMA.CuentaGrupo.value == ""){alert("Debe llenar la Cuenta del Grupo");return false;}
		if(document.FORMA.CtaProveedor.value == ""){alert("Debe llenar la Cuenta del Proveedor");return false;}
		if(document.FORMA.DepAcumulada.value == ""){alert("Debe llenar la Cuenta para la depreciacion acumulada"); return false;}
		if(document.FORMA.ValidaCtaGrupo.value == "0" || document.FORMA.ValidaCtaGrupo.value == "")
		{alert("Debe seleccionar una cuenta desde el asistente de busqueda para la Cuenta del Grupo"); return false;}
		if(document.FORMA.ValidaCtaProveedor.value == "0" || document.FORMA.ValidaCtaProveedor.value == "")
		{alert("Debe seleccionar una cuenta desde el asistente de busqueda para la Cuenta del Proveedor"); return false;}
		if(document.FORMA.ValidaDepAcum.value == "0" || document.FORMA.ValidaDepAcum.value == "")
		{alert("Debe seleccionar una cuenta desde el asistente de busqueda para la Depreciacion Acumulada"); return false;}
		if(document.FORMA.NumInicial.value == ""){alert("Debe llenar el Numero Inicial del Grupo");return false;}
	}
	function Cambiar(valor)
	{
		if(valor == "meses")
		{
			for(i = 1; i<=30; i++){document.FORMA.Durante.remove(document.FORMA.Durante.options[i-1]);}
			for(i = 1; i<=12; i++)
			{
				if(i== <? echo $Durante?>){op = new Option("" + i,"" + i, "defaultSelected");}
				else{op = new Option("" + i,"" + i);}
				document.FORMA.Durante.options[i-1] = op;
			}
		}
		else
		{
			for(i = 1; i<=30; i++)
			{
				if(i== <? echo $Durante?>){op = new Option("" + i,"" + i, "defaultSelected");}
				else{op = new Option("" + i,"" + i);}
				document.FORMA.Durante.options[i-1] = op;
			}	
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
	<form name="FORMA" method="post" onSubmit="return Validar()">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
    <input type="hidden" name="Anio" value="<? echo $Anio?>" />
    <input type="hidden" name="XGrupo" value="<? echo $Grupo?>" />
    <input type="hidden" name="Editar" value="<? echo $Editar?>" />
    <input type="hidden" name="ValidaCtaGrupo" <? if($Editar){ echo " value = '1'";}?> />
    <input type="hidden" name="ValidaCtaProveedor" <? if($Editar){ echo " value = '1'";}?> />
    <input type="hidden" name="ValidaDepAcum" <? if($Editar){ echo " value = '1'";}?> />
    <input type="hidden" name="Clase" value="<? echo $Clase?>" />
    <input type="hidden" name="CtaGrupo" value="<? echo $CtaGrupoX?>" />
    
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
        	<tr>
        		<td bgcolor="#e5e5e5">NOMBRE GRUPO:</td>
                <td><input type="text" name="Grupo" value="<? echo $Grupo;?>" onFocus="Ocultar()" /></td>
                <td bgcolor="#e5e5e5" align="right">Cuenta Grupo</td>
                <td><!--<input type="text" name="CtaGrupo" value="<? echo $CtaGrupo?>" style="text-align:right; width:90px" 
        			onFocus="Mostrar();
                    frames.Busquedas.location.href='Busquedas.php?NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaCtaGrupo&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CtaGrupo';"
					onkeyup="FORMA.ValidaCtaGrupo.value='0';xNumero(this);
                    frames.Busquedas.location.href='Busquedas.php?NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaCtaGrupo&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CtaGrupo';"
        			onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>-->
                    <input type="text" name="CuentaGrupo" value="<? echo $CuentaGrupo?>" style="text-align:right; width:90px"
                     onFocus="Mostrar();
                     frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaCtaGrupo&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CuentaGrupo'"
                     onKeyUp="FORMA.ValidaCtaGrupo.value='0';xNumero(this);
                     frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaCtaGrupo&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CuentaGrupo';" /></td>
        	</tr>
            <tr bgcolor="#e5e5e5" align="center">
            	<td>Codigo Grupo</td><td>Numero Inicial</td><td colspan="2">&nbsp;</td>
            </tr>
            <tr>
            	<td><input type="text" name="CodGrupo" value="<? echo $CodGrupo?>" maxlength="32" style="width:100%; text-align:right" readonly
                     onFocus="Ocultar()" /></td>
                <td><input type="text" name="NumInicial" value="<? echo $NumInicial?>" maxlength="6" style="width:100%; text-align:right"
                     onFocus="CodGrupo.value=CuentaGrupo.value.substr(0,4);Ocultar();" /></td>
            	<td align="right" bgcolor="#e5e5e5" valign="bottom">Cuenta Proveedor</td>
                <td><input type="text" name="CtaProveedor" value="<? echo $CtaProveedor?>" style="text-align:right; width:90px""   
        			onFocus="Mostrar();
                    frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaCtaProveedor&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CtaProveedor'" 
        			onkeyup="xNumero(this);FORMA.ValidaCtaProveedor.value='0';
                    frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaCtaProveedor&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CtaProveedor';"
        			onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
            </tr>
            <tr>
            	<?
                	if($Editar || $NumFilas > 0)
					{
					?><td colspan="2" align="center" bgcolor="#e5e5e5">
                    <input type="button" name="DxCC" value="Cuenta a Depreciar" title="Cuenta a Depreciar x CC"
                    onClick="location.href='CuentaDxCC.php?DatNameSID=<? echo $DatNameSID?>&Grupo=<? echo $Grupo?>&Anio=<? echo $Anio?>'"></td><?	
					}
				?>
            	<td <? if(!$Editar && $NumFilas == 0){ echo "colspan='3' ";}?> align="right" bgcolor="#e5e5e5" valign="bottom">Depreciacion Acumulada</td>
                <td><input type="text" name="DepAcumulada" value="<? echo $DepAcumulada; ?>" style="text-align:right; width:90px"" 
                onFocus="Mostrar();
                frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaDepAcum&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=DepAcumulada';" 
                onkeyup="xNumero(this);FORMA.ValidaDepAcum.value='0';
                frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ValidaCuenta=1&ObjetoValida=ValidaDepAcum&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=DepAcumulada';" 
                onKeyDown="xNumero(this)" onBlur="campoNumero(this)"  /></td>
            </tr>
            <tr>
            	<td bgcolor="#e5e5e5" align="right">Depreciar en </td>
                <td><select name="DepreciarEn" style="width:100%" onFocus="Ocultar()" onChange="Cambiar(this.value)">
                	<option <? if($DepreciarEn == "meses"){ echo " selected ";}?> value="meses">Meses</option>
                    <option <? if($DepreciarEn == "anios"){ echo " selected ";}?> value="anios">A&ntilde;os</option>
                </select></td>
                <td bgcolor="#e5e5e5" align="right">durante</td>
                <td><select name="Durante" style="width:100%" onFocus="Ocultar()">
                	<?
                    	if($DepreciarEn == "meses"){$Lim = 12;}
						else{ $Lim = 30;}
						for($i=1;$i<=$Lim;$i++)
						{
							if($Durante == $i)
							{echo "<option selected value='$i'>$i</option>";}
							else
							{echo "<option value='$i'>$i</option>";}
						}
					?>
                </select></td>
            </tr>
        </table>
    <input type="submit" name="Guardar" value="Guardar" onClick="CodGrupo.value=CuentaGrupo.value.substr(0,4)" />
    <input type="button" name="Volver" value="Volver" onClick="location.href='ConfGruposdeElementos.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Anio=<? echo $Anio?>'"  />
    </form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>