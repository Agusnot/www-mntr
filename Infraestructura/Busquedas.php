<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
?><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>


<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>

<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="height:10px;" class="Tit1"><td><center>Asistente de B&uacute;squeda</td></tr>
<tr><td>
<?
	if(!$Tipo)
	{
		echo "<center><em>Haga clic sobre un par&aacute;metro de b&uacute;squeda</em>";
	}
	else
	{
		if($Tipo == "SubUbicacionxCC")//Creado el 21-09-2010
		{
			if($Frame){$f="frames.$Frame.";}
			echo "B&uacute;squeda por Sub Ubicacion<br>";
			echo "Criterio <strong>$SubUbicacion</strong><br>";
			$cons = "Select SubUbicacion From Infraestructura.SubUbicaciones 
			Where Compania='$Compania[0]' and CC='$CC' and SubUbicacion != '-' and SubUbicacion ilike '%$SubUbicacion%' order by SubUbicacion";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><a href="#" onclick="parent.<? echo $f?>document.FORMA.<? echo $ObjUbicacion?>.value='<? echo $fila[0]?>'"><? echo $fila[0];?></a><br /><?
			}
			
		}
		if($Tipo == "RespAct")//Creado el 04-08-2010
		{
			echo "B&uacute;squeda por C&oacute;digo de Elemento<br>";
			echo "Criterio <strong>$Codigo</strong><br>";
			$cons = "Select distinct(PrimNom || ' ' || SegNom || ' ' || PrimApe || ' ' || SegApe) 
			From InfraEstructura.CodElementos,InfraEstructura.Ubicaciones,Central.CentrosCosto,Central.Terceros
			Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
			and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and 
			Ubicaciones.Responsable = Terceros.Identificacion and (CodElementos.Tipo='Levantamiento Inicial' or (CodElementos.Tipo='Compras' and EstadoCompras='Ingresado'))
			and (PrimNom || ' ' || SegNom || ' ' || PrimApe || ' ' || SegApe) ilike '%$Responsable%'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><a href="#" onclick="parent.AbrirElemento('Tercero','<? echo $Frame;?>','<? echo $fila[0];?>');parent.Ocultar()"><?
				echo "$fila[0]";
				?></a><br /><?	
			}	
		}
		if($Tipo=="SerInfraest")//Creado el 04-08-2010
		{
			if($CC){$ConsCC = " Ubicaciones.CentroCostos = '$CC' and ";}
			if(!$Identificacion){echo "<em>Digite el Responsable para buscar por este item</em><br>";exit;}
			if($SubUb){$ConsSubUb = " (SubUbicacion='$SubUb' or SubUbicacion is NULL) and";}
			echo "B&uacute;squeda por Serie de Elementos<br>";
			echo "Criterio <strong>$Serie</strong><br>";
			$cons = "Select distinct(Serie)
			From InfraEstructura.CodElementos,InfraEstructura.Ubicaciones,Central.CentrosCosto,Central.Terceros
			Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
			and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = '$Identificacion' and $ConsCC $ConsSubUb
			Ubicaciones.Responsable = Terceros.Identificacion and (CodElementos.Tipo='Levantamiento Inicial' or (CodElementos.Tipo='Compras' and EstadoCompras='Ingresado'))
			and Serie ilike '$Serie%'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><a href="#" onclick="parent.AbrirElemento('Serie','<? echo $Frame;?>','<? echo $fila[0];?>','<? echo $Identificacion;?>');parent.Ocultar()"><?
				echo "$fila[0]";
				?></a><br /><?	
			}
		}
		if($Tipo=="ModInfraest")//Creado el 04-08-2010
		{
			if($CC){$ConsCC = " Ubicaciones.CentroCostos = '$CC' and ";}
			if($SubUb){$ConsSubUb = " (SubUbicacion='$SubUb' or SubUbicacion is NULL) and";}
			if(!$Identificacion){echo "<em>Digite el Responsable para buscar por este item</em><br>";exit;}
			echo "B&uacute;squeda por Modelo de Elementos<br>";
			echo "Criterio <strong>$Modelo</strong><br>";
			$cons = "Select distinct(Modelo)
			From InfraEstructura.CodElementos,InfraEstructura.Ubicaciones,Central.CentrosCosto,Central.Terceros
			Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
			and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = '$Identificacion' and $ConsCC $ConsSubUb
			Ubicaciones.Responsable = Terceros.Identificacion and (CodElementos.Tipo='Levantamiento Inicial' or (CodElementos.Tipo='Compras' and EstadoCompras='Ingresado'))
			and Modelo ilike '$Modelo%'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><a href="#" onclick="parent.AbrirElemento('Modelo','<? echo $Frame;?>','<? echo $fila[0];?>','<? echo $Identificacion;?>','<? echo $CC?>');parent.Ocultar()"><?
				echo "$fila[0]";
				?></a><br /><?	
			}			
		}
		if($Tipo == "CCxTercero")//Creado el 27-07-2010
		{
			if($Frame){$f="frames.$Frame.";}
			echo "B&uacute;squeda por Centro de Costos<br>";
			echo "Criterio <strong>$CC</strong><br>";
			$cons = "Select CC,CentrosCosto.CentroCostos From Central.CentrosCosto, Central.Terceros, Infraestructura.TercerosxCC
			Where CentrosCosto.Compania='$Compania[0]' and TercerosxCC.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
			and CentrosCosto.Anio=$Anio and TercerosxCC.Anio=$Anio and CentrosCosto.Codigo = TercerosxCC.CC
			and CentrosCosto.Anio = TercerosxCC.Anio and TercerosxCC.Tercero = Terceros.Identificacion
			and Terceros.Identificacion = '$Cedula' and CC like '$CC%'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><a href="#"
                onclick="parent.<? echo $f?>document.FORMA.<? echo $ObjetoCC?>.value='<? echo $fila[0];?>';
                <? if($ObjetoCCVer){?>parent.<? echo $f?>document.FORMA.<? echo $ObjetoCCVer?>.value='<? echo "$fila[0] - $fila[1]"?>';parent.Ocultar();<? }?>"><br />
				<? echo "$fila[0] - $fila[1]"; ?></a><?
			}	
		}
		if($Tipo == "NomInfraest")//Creado el 26-07-2010
		{
			if($CC){$ConsCC = " Ubicaciones.CentroCostos = '$CC' and ";}
			if($SubUb){$ConsSubUb = " (SubUbicacion='$SubUb' or SubUbicacion is NULL) and";}
			if(!$Identificacion){echo "<em>Digite el Responsable para buscar por este item</em><br>";exit;}
			echo "B&uacute;squeda por Nombre de Elemento<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			$cons = "Select distinct(CodElementos.AutoId),CodElementos.Codigo,Nombre,Caracteristicas, Modelo, Serie
			From InfraEstructura.CodElementos,InfraEstructura.Ubicaciones,Central.CentrosCosto,Central.Terceros
			Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
			and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = '$Identificacion' and $ConsCC $ConsSubUb
			Ubicaciones.Responsable = Terceros.Identificacion and (CodElementos.Tipo='Levantamiento Inicial' or (CodElementos.Tipo='Compras' and EstadoCompras='Ingresado'))
			and (Nombre || ' ' || Caracteristicas) ilike '%$Nombre%'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><li><a href="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC=<? echo $CC;?>&Identificacion=<? echo $Identificacion;?>&Frame=<? echo $Frame?>&Tipo=CodInfraest&Anio=<? echo $Anio?>&Codigo=<? echo $fila[1]?>" target="_self" >  
				<? echo "$fila[1] - $fila[2] $fila[3] $fila[4] $fila[5]"; ?></a></li><?
			}
		}
		if($Tipo == "CodInfraest")//Creado el 26-07-2010
		{
			//echo $CC;
			if($CC){$ConsCC = " Ubicaciones.CentroCostos = '$CC' and ";}
			if($SubUb){$ConsSubUb = " (SubUbicacion='$SubUb' or SubUbicacion is NULL) and";}
			if(!$Identificacion){echo "<em>Digite el Responsable para buscar por este item</em><br>";exit;}
			if(!$Frame){$Frame="no";}else{$Framef = $Frame;$Frame=".frames.$Frame";}
			?>
			<script language="javascript">
            	function PonerInfraestructura(Frame,AutoId,CodElemento,NomElemento,Caract,Modelo,Serie,IDResp,NomResp,CodCC,NomCC)
				{
					parent<? echo $Frame?>.document.FORMA.AutoId.value = AutoId;
					parent<? echo $Frame?>.document.FORMA.Codigo.value = CodElemento;
					parent<? echo $Frame?>.document.FORMA.Nombre.value = NomElemento;
					parent<? echo $Frame?>.document.FORMA.Caracteristicas.value = Caract;
					parent<? echo $Frame?>.document.FORMA.Modelo.value = Modelo;
					parent<? echo $Frame?>.document.FORMA.Serie.value = Serie;
					//parent<? echo $Frame?>.document.FORMA.IDRA.value = IDResp;
					//parent<? echo $Frame?>.document.FORMA.Responsable.value = NomResp;
					//parent<? echo $Frame?>.document.FORMA.CodCCAct.value = CodCC;
					//parent<? echo $Frame?>.document.FORMA.CC.value = CodCC + " - " +NomCC;
					parent.Ocultar();
				}
            </script>	
			<?
			echo "B&uacute;squeda por C&oacute;digo de Elemento<br>";
			echo "Criterio <strong>$Codigo</strong><br>";
			$cons = "Select distinct(CodElementos.AutoId),CodElementos.Codigo,Nombre,Caracteristicas,Modelo,Serie,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,
			Responsable,PrimNom,SegNom,PrimApe,SegApe 
			From InfraEstructura.CodElementos,InfraEstructura.Ubicaciones,Central.CentrosCosto,Central.Terceros
			Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
			and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = '$Identificacion' and $ConsCC $ConsSubUb
			Ubicaciones.Responsable = Terceros.Identificacion and (CodElementos.Tipo='Levantamiento Inicial' or (CodElementos.Tipo='Compras' and EstadoCompras='Ingresado'))
			and CodElementos.Codigo ilike '$Codigo%'";
			
			//echo $cons;
			$res = ExQuery($cons);
			if(!$Evento || $Evento != "keyup")
			{
				if(ExNumRows($res)==1)
				{
					$fila = ExFetch($res);
					?><script language="javascript">
					PonerInfraestructura('<? echo $Framef;?>','<? echo $fila[0];?>','<? echo $fila[1];?>','<? echo $fila[2];?>','<? echo $fila[3];?>','<? echo $fila[4];?>','<? echo $fila[5];?>','<? echo $fila[8];?>','<? echo "$fila[9] $fila[10] $fila[11] $fila[12]"; ?>','<? echo $fila[6];?>','<? echo $fila[7];?>');</script><?	
				}	
			}
			
			while($fila=ExFetch($res))
			{
				?><li><a href="#" 
                onclick="PonerInfraestructura('<? echo $Framef;?>','<? echo $fila[0];?>','<? echo $fila[1];?>','<? echo $fila[2];?>','<? echo $fila[3];?>','<? echo $fila[4];?>','<? echo $fila[5];?>','<? echo $fila[8];?>','<? echo "$fila[9] $fila[10] $fila[11] $fila[12]"; ?>','<? echo $fila[6];?>','<? echo $fila[7];?>');"> 
				<? echo "$fila[1] - $fila[2] $fila[3] $fila[4] $fila[5]"; ?> </a></li> <?	
			}
		}
		if($Tipo == "TerceroxCC")//Creado el 12-07-2010
		{
			//Recibe el Criterio $Tercero y $CC
			$cons = "Select distinct Identificacion,PrimApe,SegApe,PrimNom,SegNom from Infraestructura.TercerosxCC,Central.Terceros
			where (PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' ||SegNom) ilike '%$Tercero%' 
			and Terceros.Identificacion = TercerosxCC.Tercero and TercerosxCC.CC = '$CC'
			and TercerosxCC.Anio = $ND[year] and TercerosxCC.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' order by
			PrimApe,SegApe,PrimNom,SegNom";
			$res = ExQuery($cons);
			echo "B&uacute;squeda por tercero<br>";
			echo "Criterio <strong>$Tercero</strong><br>";
			while($fila=ExFetch($res))
			{
				?><a href="#" <?
                if($Frame){$F = "frames.$Frame.";}
                ?>onclick="parent.<? echo $F?>document.FORMA.<? echo $ObjTercero;?>.value = '<? echo strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]");?>';
                parent.<? echo $F?>document.FORMA.<? echo $ObjId;?>.value = '<? echo $fila[0];?>';"><?
				echo strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]</a><br>");
			}
		}
		if($Tipo=="Retenciones")
		{?>
		
			<script language="javascript">
				function CalculaRetencion(Concepto,Porc,Base)
				{
					if(parent.frames.NuevoMovimiento.FORMA.TotCosto != undefined)
					{
						if(parent.frames.NuevoMovimiento.FORMA.TotCosto.value=="")
						{
							alert("Seleccione primero el producto y la cantidad a ingresar!");
						}	
					}
					parent.frames.NuevoMovimiento.FORMA.VrReteFte.value=((parent.frames.NuevoMovimiento.FORMA.CostoIni.value*Base/100)*Porc)/100;
					parent.frames.NuevoMovimiento.FORMA.PorcReteFte.value=Porc;
					//parent.frames.NuevoMovimiento.FORMA.ConceptoReteFte.value=Concepto;
				}
			</script>	
<?			echo "<strong>Aplicar Retenciones</strong><br><br>";?>

			<a href="#" onclick="CalculaRetencion('','0','')"><li><? echo "Sin Retencion" ?></li></a><br>
<?			$cons="Select Concepto,Porcentaje,Base from Contabilidad.BasesRetencion where Compania='$Compania[0]' and Anio=$Anio and Consumo=1";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{?>
				<a href="#" onclick="CalculaRetencion('<? echo $fila[0]?>','<? echo $fila[1]?>','<? echo $fila[2]?>')"><li><? echo "$fila[0] ($fila[1])" ?></li></a><br>
<?			}
		}
		if($Tipo=="CambiaTerceroComp")
		{	
			$cons="Update Contabilidad.TmpMovimiento set Identificacion='$Cedula' where NUMREG='$NUMREG'";
			$res=ExQuery($cons);echo ExError();
			?>
            <script language="javascript">
				parent.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<? echo $Comprobante?>&NUMREG=<? echo $NUMREG?>';
			</script>
            <?
		}
		if($Tipo=="Identificacion"){
		if($Frame){$f="frames.$Frame.";}
		if($ObjetoNombre){$o="$ObjetoNombre";}else{$o="Tercero";}
		if($ObjId){$oi="$ObjId";}else{$oi="Identificacion";}
		$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros where Identificacion='$Identificacion' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
		echo "Criterio <strong>$Identificacion</strong><br>";
		echo "Registros Encontrados (" . ExNumRows($res) . ")";
		$fila=ExFetch($res);
		echo "<li>".strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")."</li>";
			echo "<br>";?>
		<a onclick="open('NuevoTercero.php?Cerrar=1','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
		<script language="JavaScript">
		parent.document.<? echo $f?>FORMA.<? echo $o?>.value="<? echo strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")?>";
		parent.document.<? echo $f?>FORMA.<? echo $oi?>.value="<? echo $fila[4]?>";
		parent.document.FORMA.Detalle.focus();
		</script>
<?
		}
		
		if($Tipo=="Nombre")
		{
                    if($TerceroAdm)
                    {
                        $TerceroAdm="'".str_replace(",","','",$TerceroAdm)."'";
                        $andIdentificacion = " and Identificacion in($TerceroAdm)";
                    }
                    if($Frame){$f="Frame=$Frame"."&";}
                    if($ObjetoNombre){$o="ObjetoNombre=$ObjetoNombre"."&";}
                    if($ObjId){$oi="ObjId=$ObjId"."&";}
                    $cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros 
                    where (PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' ||SegNom) ilike '%$Nombre%' and Terceros.Compania='$Compania[0]' $andIdentificacion
                    Order By PrimApe,SegApe,PrimNom,SegNom";
                    $res=ExQuery($cons);echo ExError($res);
                    echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
                    echo "Criterio <strong>$Nombre</strong><br>";
                    echo "Registros Encontrados (" . ExNumRows($res) . ")";
                    while($fila=ExFetch($res))
                    {
                            if(ExNumRows($res)==1){?><script language="javascript">location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&<? echo $oi.$o.$f?>Tipo=Identificacion&Identificacion=<? echo $fila[0]?>'</script><? }
                            echo "<li><a href='Busquedas.php?DatNameSID=$DatNameSID&".$oi.$o.$f."Tipo=Identificacion&Identificacion=$fila[0]'>".strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]")."</a></li>";
                    }
                    echo "<br>";?>
                    <a onclick="open('NuevoTercero.php?Cerrar=1','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
<?		}
		
		if($Tipo=="PlanCuentas")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,CC,Descripcion,Tipo)
		{
			parent.frames.NuevoMovimiento.document.FORMA.Cuenta.value=CuentaConta;
			parent.frames.NuevoMovimiento.document.FORMA.Cuenta.focus();

			if(!CC){CC="0";}
			parent.frames.NuevoMovimiento.document.FORMA.Movimiento.value=Tipo;
			parent.frames.NuevoMovimiento.document.FORMA.ValeCC.value=CC;
			if(CC=="on"){parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=false;}
			else{parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=1;}
			parent.frames.TotMovimientos.document.FORMA.Descripcion.value=Descripcion;
			if(Naturaleza=="Debito"){parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="";}
			if(Naturaleza=="Credito"){parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="";}

		}
		function PonerMovimiento(Tipo,Naturaleza,CC,Descripcion)
		{
			if(!CC){CC="0";}
			parent.frames.NuevoMovimiento.document.FORMA.Movimiento.value=Tipo;
			parent.frames.NuevoMovimiento.document.FORMA.ValeCC.value=CC;
			if(CC=="on"){parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=false;}
			else{parent.frames.NuevoMovimiento.document.FORMA.CC.disabled=1;}
			parent.frames.TotMovimientos.document.FORMA.Descripcion.value=Descripcion;
			ValDiferencia=parent.frames.TotMovimientos.document.FORMA.Diferencia.value;
			ValorDif="";
			for(x=0;x<=ValDiferencia.length;x=x+1)
			{
				if(ValDiferencia.substr(x,1)!=",")
				{

					ValorDif=ValorDif + ValDiferencia.substr(x,1);
				}
			}
			if(!ValorDif){$ValorDif=0;}
			if(Naturaleza=="Debito"){if(parent.frames.NuevoMovimiento.document.FORMA.EstadoMod.value==0 && ValorDif<0){parent.frames.NuevoMovimiento.document.FORMA.Haber.value=0;parent.frames.NuevoMovimiento.document.FORMA.Debe.value=Math.abs(ValorDif);}parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="";}
			if(Naturaleza=="Credito"){if(parent.frames.NuevoMovimiento.document.FORMA.EstadoMod.value==0 && ValorDif>0){parent.frames.NuevoMovimiento.document.FORMA.Debe.value=0;parent.frames.NuevoMovimiento.document.FORMA.Haber.value=Math.abs(ValorDif);}parent.frames.NuevoMovimiento.document.FORMA.Debe.style.backgroundColor="#E6E4F1";parent.frames.NuevoMovimiento.document.FORMA.Haber.style.backgroundColor="";}
			
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if(!$Cuenta){exit;}
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza,CentroCostos from Contabilidad.PlanCuentas 
			where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[2]=="Titulo")
				{
					echo "<a style='font-size:10px;color:black'>$fila[0] - $fila[1]</a><br>";
				}
				else
				{
					?><a style='font-size:10px;' href='#' 
                	<? if($NoMovimiento)
					{
						if(!$ID)
						{
						if($Frame){$F="frames.$Frame.";}
						?>onclick="parent.<? echo $F;?>document.FORMA.<? echo $ObjCuenta;?>.value='<? echo $fila[0];?>';<?
							if($ValidaCuenta){?>parent.document.FORMA.<? echo $ObjetoValida;?>.value='1'<? }?>;"<? }
						else
						{?>onclick="parent.document.getElementById('<? echo $ObjCuenta;?>' + '[' + '<? echo $ID;?>' + ']').value='<? echo $fila[0];?>'"<? }
						
					}
					else
					{
						?>onclick="PonerCuenta('<? echo $fila[0]?>','<? echo $fila[3]?>','<? echo $fila[4]?>','<? echo $fila[1]?>','<? echo $fila[2]?>')"<?
					}
					?>
                    >
					<? echo "$fila[0] - $fila[1]"?></a><br><? ;
				}
			}
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza,CentroCostos from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$Anio";
			$res=ExQuery($cons);
			$fila=ExFetch($res);$Movimiento=$fila[2];
			if(ExNumRows($res)==1){
			?>
			<a href='#' onclick="open('SaldosxCuenta.php?Cuenta=<?echo $Cuenta?>','','width=400,height=300,scrollbars=yes')">Ver Saldos</a><br>
			<a href='#' onclick="open('MovimientoxCuenta.php?Cuenta=<?echo $Cuenta?>','','width=800,height=300,scrollbars=yes')">Ver Movimientos</a><br><?
			if(!$NoMovimiento)
			{
				?><script language="JavaScript">PonerMovimiento('<?echo $Movimiento?>','<?echo $fila[3]?>','<?echo $fila[4]?>','<? echo $fila[1]?>')</script><?
			}
			if($fila[2]=="Titulo")
			{?>
				<a href='#' onclick="open('DetalleCuenta.php?Cuenta=<?echo $Cuenta?>&Nuevo=Nuevo&Cerrar=1','','width=400,height=300,scrollbars=yes')">Nueva Cuenta</a><br>
<?			}}
			else{?><script language="JavaScript">PonerMovimiento('0')</script><? }
			
		}

		if($Tipo=="CCG")
		{
?>
                    <script language="JavaScript">
                    function PonerCentro(Codigo)
                    {
                            <? if($Frame)$F="$Frame.";?>
                            parent.frames.<? echo $F?>document.FORMA.CC.value=Codigo;
                            parent.frames.<? echo $F?>document.FORMA.CC.focus();
                            //parent.frames.document.body.scrollTop = '0';

                    }
                    </script>
<?                  if($CCAdm)
                    {
                        $CCAdm = "'".str_replace(",","','",$CCAdm)."'";
                        $AndCC = " and Codigo in($CCAdm)";
                    }
                    echo "B&uacute;squeda por Centros de Costo<br>";
                    echo "Criterio <strong>$Centro</strong><br>";
                    $cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto 
                    WHERE Compania='$Compania[0]' and Anio=$Anio and Codigo like '$Centro%' $AndCC
                    Order By Codigo";
                    $res=ExQuery($cons);
                    while($fila=ExFetch($res))
                    {
                            if($fila[2]=="Detalle"){?><a style='font-size:11px;' href='#' onclick="PonerCentro('<? echo $fila[0]?>')"><? }
                            echo ("$fila[0] - $fila[1]")?></a><br>
                            <?
                    }
		}
		if($Tipo=="CC")
		{
?>
		<script language="JavaScript">
		function PonerCentro(Codigo)
		{
			parent.frames.NuevoMovimiento.document.FORMA.CC.value=Codigo;
			parent.frames.NuevoMovimiento.document.FORMA.CC.focus();
			
		}
		</script>
<?
			echo "B&uacute;squeda por Centros de Costo<br>";
			echo "Criterio <strong>$Centro</strong><br>";
			$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto WHERE Compania='$Compania[0]' and Anio=$Anio Order By Codigo";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[2]=="Detalle"){?><a style='font-size:11px;' href='#' onclick="PonerCentro('<? echo $fila[0]?>')"><? }
				echo ("$fila[0] - $fila[1]")?></a><br>
				<? 
			}
		}
		if($Tipo=="Departamentos")
		{
			$cons="Select Departamento from Central.Departamentos where Departamento ilike '$Departamento%'";

			$res=ExQuery($cons);echo ExError($res);
			echo "B&uacute;squeda por Departamentos<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Departamento.value='<?echo $fila[0]?>';parent.document.FORMA.ValDepto.value=1;parent.document.FORMA.Municipio.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Municipio")
		{

			$cons="Select Codigo from Central.Departamentos where Departamento='$Departamento'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$CodDepto=$fila[0];

			$cons="Select Municipio from Central.Municipios where Departamento='$CodDepto' and Municipio ilike '$Municipio%'";


			$res=ExQuery($cons);echo ExError($res);
			echo "B&uacute;squeda por Municipio<br>";
			echo "Criterio <strong>$Nombre</strong><br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Municipio.value='<?echo $fila[0]?>';parent.document.FORMA.ValMpo.value=1;parent.document.FORMA.TipoTercero.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Direccion")
		{
			$cons="Select Convencion,Detalle from Contabilidad.ConvDirecciones";

			$res=ExQuery($cons);echo ExError($res);
			echo "Convencion de Direcciones<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Direccion.value=parent.document.FORMA.Direccion.value + '<?echo "$fila[0] "?>';parent.document.FORMA.Direccion.focus();"><?echo strtoupper($fila[0])."  (".$fila[1].")"?></a></li>
<?			}
		}
		if($Tipo=="TercerosxTodos")
		{
			$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros 
			where PrimApe ilike '%$PrimApe%' and  SegApe ilike '%$SegApe%' and PrimNom ilike '%$PrimNom%' and SegNom ilike '%$SegNom%' and Terceros.Compania='$Compania[0]' Order By PrimApe,SegApe,PrimNom,SegNom";
			$res=ExQuery($cons);echo ExError($res);
			echo "Terceros existentes<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				echo "<li style='font-size:9px;'>$fila[0] $fila[1] $fila[2] $fila[3] - $fila[4]</li>";
			}
		}
		if($Tipo=="Bancos")
		{
			$cons="Select Nombre from Contabilidad.PlanCuentas where Banco=1 and Nombre ilike '$Banco%' and Compania='$Compania[0]' and Anio=$Anio";

			$res=ExQuery($cons);echo ExError($res);
			echo "Bancos<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.Banco.value='<?echo "$fila[0]"?>';parent.document.FORMA.Banco.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Bancos1")
		{
			$cons="Select Nombre from Contabilidad.PlanCuentas where Banco=1 and Nombre ilike '$Banco%' and Compania='$Compania[0]' and Anio=$Anio";

			$res=ExQuery($cons);echo ExError($res);
			echo "Bancos<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{?>
				<li><a href="#" onclick="parent.document.FORMA.BancoRecRec.value='<?echo "$fila[0]"?>';parent.document.FORMA.BancoRecRec.focus();"><?echo strtoupper($fila[0])?></a></li>
<?			}
		}
		if($Tipo=="Cheque")
		{
			$cons="Select Cuenta from Contabilidad.PlanCuentas where Nombre='$Banco' and Compania='$Compania[0]' and Anio=$Anio";
			$res=ExQuery($cons);echo ExError($res);
			$fila=ExFetch($res);
			$CuentaBanco=$fila[0];
			
			$cons="Select NoCheque from Contabilidad.Movimiento where Cuenta='$CuentaBanco' AND NoCheque!=0 and Compania='$Compania[0]' Order By Numero Desc;";
			$res=ExQuery($cons);echo ExError($res);
			echo "<strong>Cheque n&uacute;mero</strong><br>";
			if(ExNumRows($res)>0)
			{
				$fila=ExFetch($res);
				$NoCheque=$fila[0]+1;
				echo $NoCheque;
			?>
				<script language="JavaScript">
					parent.document.FORMA.NumCheque.value=<?echo $NoCheque?>
				</script>
			<? }
			else{echo "<em>Banco aun no ha generado cheques</em>";}
		}

		if($Tipo=="PlanCuentasGr")
		{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza)
		{
			parent.document.FORMA.Cuenta.value=CuentaConta;
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<?echo $fila[0]?>,'<?echo $fila[3]?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}

		if($Tipo=="PlanCuentasDetalle")
		{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,Objeto)
		{
			parent.document.getElementById(Objeto).value=CuentaConta;
			parent.document.getElementById("Val"+Objeto).value=1;
			parent.document.getElementById(Objeto).focus();
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if($Cuenta){
				$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[2]=="Titulo")
					{
						 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
					}
					else
					{
						?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<? echo $fila[0]?>,'<? echo $fila[3]?>','<? echo $Objeto?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?
					}
				}
			}
		}

		if($Tipo=="CodigoExogena")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(CuentaE)
		{
			parent.document.FORMA.Codigo.value=CuentaE;
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre from Contabilidad.CodigosExogena where Cuenta ilike '$Codigo%' Order By Cuenta";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				?><a style='font-size:10px;' href='#' onclick="PonerCuenta('<?echo $fila[0]?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}

		if($Tipo=="CuentasCierre")
		{
?>
		<script language="JavaScript">
		function PonerCuenta(Objeto,CuentaConta,Tipo)
		{
			if(Objeto=="Ingresos"){parent.document.FORMA.Ingresos.value=CuentaConta;parent.document.FORMA.TipoIngresos.value=Tipo;}
			if(Objeto=="Gastos"){parent.document.FORMA.Gastos.value=CuentaConta;parent.document.FORMA.TipoGastos.value=Tipo;}
			if(Objeto=="Utilidad"){parent.document.FORMA.Utilidad.value=CuentaConta;parent.document.FORMA.TipoUtilidad.value=Tipo;}
			if(Objeto=="Perdida"){parent.document.FORMA.Perdida.value=CuentaConta;parent.document.FORMA.TipoPerdida.value=Tipo;}
			if(Objeto=="Costos"){parent.document.FORMA.Costos.value=CuentaConta;parent.document.FORMA.TipoCostos.value=Tipo;}
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
			$res=ExQuery($cons);

			while($fila=ExFetch($res))
			{
				?><a style='font-size:10px;' href='#' onclick="PonerCuenta('<?echo $Objeto?>',<?echo $fila[0]?>,'<?echo $fila[2]?>')"><?echo "$fila[0] - $fila[1]"?></a><br><?
			}
		}

		if($Tipo=="Comprobante")
		{
			$cons="Select Comprobante from Contabilidad.Comprobantes where Comprobante ilike '$Comprobante%' and Compania='$Compania[0]'";

			$res=ExQuery($cons);echo ExError($res);
			echo "Comprobantes<br>";
			echo "Registros Encontrados (" . ExNumRows($res) . ")";
			while($fila=ExFetch($res))
			{
				if(!$ObjComprobante)
                {
                	?><li><a href="#" onclick="parent.document.FORMA.Comprobante.value='<? echo "$fila[0]"?>';parent.document.FORMA.Banco.focus();">
                	<? echo strtoupper($fila[0])?></a></li><?
                }
				else
				{
					?><li><a href="#" onclick="parent.document.FORMA.<? echo $ObjComprobante?>.value='<? echo "$fila[0]"?>';">
                	<? echo strtoupper($fila[0])?></a></li><?
				}
			}
		}

		if($Tipo=="HabilitarComprobantes")
		{
			$cons="SELECT Nuevo FROM Contabilidad.PermisosxComprobantes WHERE Nuevo =1	AND Perfil = '$Perfil' and Comprobante='$Comprobante'";
			$res=ExQuery($cons);echo ExError($res);
			if(ExNumRows($res)==0)
			{?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=true;
				</script>
				
			<?}
			else
			{?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=false;
				</script>
<?			}

		}
		
		if($Tipo=="RevisaCierre")
		{
			$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{?>
				<script language="JavaScript">
					parent.document.FORMA.MesInvalido.value=1;
				</script>
			<?}
			else
			{?>
				<script language="JavaScript">
					parent.document.FORMA.MesInvalido.value=0;
				</script>
			<? }
			
		}
		
		if($Tipo=="HabilitarComprobantes")
		{
			$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio";
			echo $cons;
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=true;
				</script>
				
			<?}
			else
			{?>
				<script language="JavaScript">
					parent.document.FORMA.Nuevo.disabled=false;
				</script>
<?			}
		}
	}
?>
</td></tr>
</table>