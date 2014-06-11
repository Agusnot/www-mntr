<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>

<form name="FORMA">
<input type="hidden" name="CodZonaA" value="<? echo $CodZonaA ?>"/>

<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="height:10px;" class="Tit1"><td><center>Asistente de Búsqueda</td></tr>
<tr><td>
<?
	if($Tipo=="Generico1")
	{
		?>
			<script language="javascript">
            	function PonerValor(Valor)
				{
					parent.frames.document.FORMA1.<? echo $Objeto?>.value = Valor;//ver si se utiliza en otras busquedas la FORMA
					parent.frames.document.FORMA1.<? echo $SigObjeto?>.focus();
				}
            </script>
		<?
		echo "Busqueda por $TipoG";
		echo "<br>Criterio <b>$Valor</b><br>";
		$cons = "Select ".$Valor1.",".$Valor2." from ".$Tabla." where Compania='$Compania[0]' and ".$Valor1." like '".$Valor."%'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			?><a href="#" onclick="PonerValor('<? echo $fila[0]?>')"><?
			echo "$fila[0] - $fila[1]<br></a>";
		}
	}
	//--
	if($Tipo=="Generico2")
	{
		?>
			<script language="javascript">
            	function PonerValor(Valor,Valor1)
				{
					parent.frames.document.FORMA.<? echo $Objeto?>.value = Valor;
					parent.frames.document.FORMA.<? echo $Objeto1?>.value = Valor1;
					parent.frames.document.FORMA.<? echo $SigObjeto?>.focus();
				}
            </script>
		<?
		echo "Busqueda por $TipoG";
		echo "<br>Criterio <b>$Valor</b><br>";
		$cons = "Select ".$Valor1.",".$Valor2." from ".$Tabla." where Compania='$Compania[0]' and ".$Valor1." like '".$Valor."%'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			?> <a href="#" onclick="PonerValor('<? echo $fila[0]?>','<? echo $fila[1]?>')"> <?
			echo "$fila[0] - $fila[1]<br></a>";
		}
	}
	//echo "$Tipo -- $TipoG -- $Valor -- $Valor1 -- $Valor2 -- $Tabla -- $ObjetoA -- $ObjetoN -- $NomObjeto -- $SigObjeto";
	if($Tipo=="Generico3")
	{ 
		
		?>
			<script language="javascript">
            	function PonerValor(Valor,Valor1,Valor2)
				{
					parent.frames.document.FORMA.<? echo $ObjetoA?>.value = Valor;
					parent.frames.document.FORMA.<? echo $ObjetoN?>.value = Valor1;
					parent.frames.document.FORMA.<? echo $NomObjeto?>.value = Valor2;
					parent.frames.Ocultar();					
					parent.frames.document.FORMA.<? echo $SigObjeto?>.focus();
				}
            </script>
		<?
		echo "Busqueda Por $TipoG";
		echo "<br>Criterio <b>$Valor</b><br>";
		$cons = "Select ".$Valor1.",".$Valor2." from ".$Tabla." where Compania='$Compania[0]' and ".$Valor2." ilike '".$Valor."%' order by ".$Valor1;
		$res = ExQuery($cons);
		//echo $cons;
		while($fila = ExFetch($res))
		{
			?><a href="#" onclick="PonerValor('<? echo $CodA ?>','<? echo $fila[0] ?>','<? echo $fila[1] ?>')"><?
			echo "$fila[0] - $fila[1]<br></a>";
		}
	}
	if($Tipo=="PlanCuentasDetalle")
	{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,NomCuenta)
		{
			parent.frames.document.getElementById('<? echo $Objeto?>').value= CuentaConta;	
			parent.frames.document.getElementById('<? echo $Objeto2?>').value= CuentaConta;	
			parent.frames.document.getElementById('<? echo $Objeto1?>').value= NomCuenta;	
//			parent.document.getElementById(Objeto).value=CuentaConta;
			/*parent.frames.document.FORMA.<? echo $Objeto?>.value = CuentaConta;	
			parent.frames.document.FORMA.<? echo $Objeto2?>.value = CuentaConta;	
			parent.frames.document.FORMA.<? echo $Objeto1?>.value = NomCuenta;	*/
			
			//parent.document.getElementById(Objeto).focus();			
			parent.frames.Ocultar();	
			//parent.frames.document.FORMA.<? echo $SigObjeto?>.focus();		
		}
		</script>
<?
			echo "B&uacute;squeda por Plan de Cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if($Cuenta){
				$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[2]!="Detalle")
					{
						 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
					}
					else
					{
						?><a style='font-size:10px;' href='#' onclick="PonerCuenta('<? echo $fila[0]?>','<? echo $fila[1]?>')"><? echo "$fila[0] - $fila[1]";?></a><br><?
					}
				}
			}
		}
	if($Tipo=="Comprobantes")
	{ ?>
		<script language="JavaScript">
		function PonerComprobante(Comprobante)
		{
			//parent.document.getElementById(Objeto).value=Comprobante;
			parent.frames.document.FORMA.<? echo $Objeto?>.value = Comprobante;	
			parent.frames.document.FORMA.<? echo $Objeto1?>.value = Comprobante;			
			//parent.document.getElementById(Objeto).focus();			
			parent.frames.Ocultar();	
			parent.frames.document.FORMA.<? echo $SigObjeto?>.focus();		
		}
		</script>
<?
			echo "B&uacute;squeda por $TipoG<br>";
			echo "Criterio <strong>$Comprobante</strong><br>";
			if($Comprobante){
				$cons="Select Comprobante,TipoComprobant,CompPresupuesto from Contabilidad.Comprobantes where Comprobante ilike '$Comprobante%' 
				and Compania='$Compania[0]' Order By Comprobante";
								
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($Objeto=="CompCausacion")
					{
						if($fila[1]!="Facturas"||$fila[2]=="")
						{
							 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
						}
						else
						{
							?><a style='font-size:10px;' href='#' onclick="PonerComprobante('<? echo $fila[0]?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?
						}
					}
					else
					{
						if($fila[1]!="Ingreso"||$fila[2]=="")
						{
							 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
						}
						else
						{
							?><a style='font-size:10px;' href='#' onclick="PonerComprobante('<? echo $fila[0]?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?
						}
					}
				}
			}
		}
	if($Tipo=="CuentaPresupuestal")
	{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaPresu,NomCuenta)
		{
			parent.frames.document.FORMA.<? echo $Objeto?>.value = CuentaPresu;	
			parent.frames.document.FORMA.<? echo $Objeto2?>.value = CuentaPresu;	
			parent.frames.document.FORMA.<? echo $Objeto1?>.value = NomCuenta;			
			//parent.document.getElementById(Objeto).focus();			
			parent.frames.Ocultar();	
			parent.frames.document.FORMA.<? echo $SigObjeto?>.focus();		
		}
		</script>
<?
			echo "B&uacute;squeda por Cuenta Presupuestal<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if($Cuenta){
				$cons="Select Cuenta,Nombre,Tipo,Vigencia,ClaseVigencia from Presupuesto.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' 
				and Anio=$Anio Order By Cuenta";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[3]!="Actual"||$fila[4]!="")
					{
						 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
					}
					else
					{
						?><a style='font-size:10px;' href='#' onclick="PonerCuenta('<? echo $fila[0]?>','<? echo $fila[1]?>')"><? echo "$fila[0] - $fila[1]";?></a><br><?
					}
				}
			}
		}
	
	//----
	if($Tipo=="CodCatastro")
	{
		?>
			<script language="javascript">
            	function PonerCodCatastro(CodCatastro)
				{
					parent.frames.document.FORMA1.CodCatastro.value = CodCatastro;
					parent.frames.document.FORMA1.Propietario.value = "";
					parent.frames.document.FORMA1.Cedula.value = "";
					parent.frames.Ocultar();
					parent.frames.document.FORMA1.submit();
					//parent.frames.document.FORMA.CodCatastro.focus();
				}
            </script>
		<?
		echo "Busqueda Por Codigo de Catastro";
		echo "<br>Criterio <b>$CodCatastro</b><br>";
		$cons = "Select CodCatastro,Direccion from Predial.Predios where Compania='$Compania[0]' and Zona='$Zona' and Sector='$Sector' and Vereda='$Vereda' and 
		CodCatastro like '$CodCatastro%'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			?> <a href="#" onclick="PonerCodCatastro('<? echo $fila[0]?>')"> <?
			echo "$fila[0] - $fila[1]<br></a>";
		}
	}	
	if($Tipo=="Propietario")
	{
		echo "Busqueda Por Propietario";
		echo "<br>Criterio <b>$Nombre $Cedula</b><br>";
		if($Nombre||$Cedula)
		{
			$cons = "Select Nombre,NoDocumento from Predial.Propietarios where Compania='$Compania[0]' and Nombre ilike '%$Nombre%' and NoDocumento like '%$Cedula%' 
			group by Nombre,NoDocumento order by Nombre";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?> <a href="#" onclick="parent.frames.AbrirPropietarios('<? echo $fila[1] ?>','<? echo $fila[0]?>');parent.frames.Ocultar()" > <?
				echo "$fila[0] ".round($fila[1],0)."<br></a>";
			}
		}
	}
	
	if($Tipo=="ZonaNu")
	{		
		?>
			<script language="javascript">
            	function PonerZona(CodZonaN,Zona,CodZonaA)
				{
					parent.frames.document.FORMA.CodZonaN.value = CodZonaN;
					parent.frames.document.FORMA.NZona.value = Zona;
					parent.frames.document.FORMA.CodZonaA.value = CodZonaA;
					parent.frames.Ocultar();
					//parent.frames.document.FORMA.submit();
					parent.frames.document.FORMA.NIncremento.focus();
				}
            </script>
		<?
		echo "Busqueda Por Zona";
		echo "<br>Criterio <b>$Zona</b><br>";
		$cons = "Select CodZona,Zona from Predial.Zonas where Compania='$Compania[0]' and Zona ilike '$Zona%' order by CodZona";
		$res = ExQuery($cons);
		//echo $cons;
		while($fila = ExFetch($res))
		{
			?> <a href="#" onclick="PonerZona('<? echo $fila[0] ?>','<? echo $fila[1] ?>','<? echo $CodZonaA ?> ')"><?
			echo "$fila[0] -- $fila[1]<br></a>";
		}
	}
	if($Tipo=="CCG")
	{
?>
		<script language="JavaScript">
		function PonerCentro(Codigo)
		{ 
		<? if($Frame)$F="$Frame.";?>
			parent.frames.<? echo $F?>document.FORMA.CC.value=Codigo;
			//parent.frames.<? echo $F?>document.FORMA.CC.focus();
			parent.frames.Ocultar();
		<?	if($Objeto2){?>
				parent.frames.<? echo $F?>document.FORMA.<? echo $Objeto2?>.value=Codigo;
		<?	}	?>
			//parent.frames.<? echo $F?>document.<? echo $Siguiente?>.focus();
			parent.frames.document.FORMA.<? echo $SigObjeto?>.focus();
			
			//parent.frames.document.body.scrollTop = '0';
			
			
		}
		</script>
<?
		echo "B&uacute;squeda por Centros de Costo<br>";
		echo "Criterio <strong>$Centro</strong><br>";
		$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto WHERE Compania='$Compania[0]' and Anio=$Anio and Codigo ilike '$Centro%' Order By Codigo";
		//echo $cons."<br>";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[2]=="Detalle"){?><a style='font-size:11px;' href='#' onclick="PonerCentro('<? echo $fila[0]?>')"><? }
			echo ("$fila[0] - $fila[1]")?></a><br>
			<? 
		}
	}
	
?>
</td></tr>
</table>
</form>