<?
	session_start();
	include("Funciones.php");
	if(!$BaseDatos){$BaseDatos="Contabilidad";}
	
	if($Mod)
	{
		$Criterio=str_replace("|","'",$Criterio);
		$cons="Select * from $BaseDatos.$Tabla where $Criterio";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		for($i=0;$i<=ExNumFields($res)-1;$i++)
		{
			$VrCampo[$i]=$fila[$i];
		}
		$Elim=1;
	}
	if($Elim)
	{
		$Criterio=str_replace("|","'",$Criterio);
		$cons="Delete from $BaseDatos.$Tabla where $Criterio";
		$cons = $cons . " Limit 1";
		$res=ExQuery($cons);
	}
$Criterio="";
	if($Guarda)
	{
			$cons2="Select * from $BaseDatos.$Tabla";
			$res2=ExQuery($cons2);
			for($i=0;$i<=ExNumFields($res2)-1;$i++)
			{
				$Nom=$Nom . "," . mysql_field_name($res2,$i) . ",";
			}
			$cons="Insert into $BaseDatos.$Tabla (";
			
			foreach($_GET as $nombre_campo => $valor)
			{	
				if(ereg(",$nombre_campo,",$Nom))
				{
					$cons= $cons ."$nombre_campo,";
				}
			}
			$cons=substr($cons,0,strlen($cons)-1);
			$cons= $cons .") values (";
			foreach($_GET as $nombre_campo => $valor)
			{
				if($nombre_campo=="Compania"){$valor="$Compania[0]";}

				if(ereg(",$nombre_campo,",$Nom))
				{
					$cons= $cons ."'$valor',";
				}
			}
			$cons=substr($cons,0,strlen($cons)-1);
			$cons=$cons . ")";
			$res=ExQuery($cons);
	}
?>
<style type="text/css">
.style3 {font-family: Tahoma}
</style>
<body background="/Imgs/Fondo.jpg">
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<?
if(!$Tabla){?>
<table border="0" cellpadding="3" style="font-family:<?echo $Estilo[8]?>;font-size:13px;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5"><td><strong>Configuraciones Generales</td></tr>
	<tr><td><a href="Configuracion.php?Tabla=ConvDirecciones">Convenciones Direcci&oacute;n</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=Departamentos">Departamentos</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=Municipios">Municipios</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=Estilos">Estilos</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=FirmasInformes">Firmas Informes</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=FormasPago">Formas de Pago</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=NaturalezaCuentas">Naturaleza de Cuentas</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=RegimenTercero">Regimen de Terceros</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=TiposComprobante">Tipos de Comprobante</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=TiposCuenta">Tipos de Cuenta</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=TiposTercero">Tipos de Tercero</a></div>

<tr bgcolor="#e5e5e5"><td><strong>Configuraciones x Compa&ntilde;ia</td></tr>
	<tr><td><a href="Configuracion.php?Tabla=BasesRetencion">Bases de Retencion</a></div></td></tr>
	<tr><td><a href="Configuracion.php?Tabla=ConceptosAfectacion">Conceptos Afectacion</a></div></td>
	<tr><td><a href="Configuracion.php?Tabla=CentrosCosto">Centros de Costo</a></div>
	<tr><td><a href="Configuracion.php?Tabla=Comprobantes">Comprobantes</a></div>
	<tr><td><a href="Configuracion.php?Tabla=CruzarComprobantes">Cruce de Comprobantes</a></div>
	<tr><td><a href="Configuracion.php?Tabla=PermisosxComprobantes">Permisos x Comprobantes</a></div>

	<tr><td><a href="Configuracion.php?Tabla=EstructuraPuc">Estructura PUC</a></div>

	<tr><td><a href="Configuracion.php?Tabla=ConceptosPago">Conceptos de Pago</a></div>

	</table>
<? }?>
</body>
<table border="0" cellpadding="3" style="font-family:<?echo $Estilo[8]?>;font-size:13px;font-style:<?echo $Estilo[10]?>">
<?
	if($Tabla)
	{

		$cons="Select * from $BaseDatos.$Tabla";
		$res=ExQuery($cons);echo ExError($res);
		for($i=0;$i<=ExNumFields($res)-1;$i++)
		{
			if(mysql_field_name($res,$i)=="Compania"){$cond1=" where Compania='$Compania[0]'";}
		}
		$cons="Select * from $BaseDatos.$Tabla $cond1";
		$res=ExQuery($cons);echo ExError($res);

		echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'>";
		for($i=0;$i<=ExNumFields($res)-1;$i++)
		{
			echo "<td>". mysql_field_name($res,$i) ."</td>";
		}
		echo "</tr>";
		echo "<form name='FORMA'>";
		echo "<tr>";
		for($i=0;$i<=ExNumFields($res)-1;$i++)
		{
			if(mysql_field_name($res,$i)=="Compania"){$VrCampo[$i]=$Compania[0];}

			if(mysql_field_name($res,$i)=="Cuenta" || mysql_field_name($res,$i)=="CuentaBase" || mysql_field_name($res,$i)=="CuentaCruzar" || mysql_field_name($res,$i)=="CuentaDebe" || mysql_field_name($res,$i)=="CuentaHaber"){
			?><td><input readonly="yes" style="width:90px;" type='Text' value="<?echo $VrCampo[$i]?>" name="<? echo mysql_field_name($res,$i)?>">
			<input type='Button' value='...' onClick="open('/Contabilidad/BusquedaxOtros.php?Tipo=Cuentas&Campo=<?echo mysql_field_name($res,$i)?>','','width=600,height=400')">
			</td>
			<?}

			elseif(mysql_field_name($res,$i)=="Comprobante" || mysql_field_name($res,$i)=="CruzarCon"){
			?><td><input readonly="yes" style="width:90px;" type='Text' value="<?echo $VrCampo[$i]?>" name="<? echo mysql_field_name($res,$i)?>">
			<input type='Button' value='...' onClick="open('/Contabilidad/BusquedaxOtros.php?Tipo=Comprobante&Campo=<?echo mysql_field_name($res,$i)?>','','width=600,height=400')">
			</td>
			<?}
			
			elseif(mysql_field_name($res,$i)=="Exogena"){
			?><td><input readonly="yes" style="width:90px;" type='Text' value="<?echo $VrCampo[$i]?>" name="<? echo mysql_field_name($res,$i)?>">
			<input type='Button' value='...' onClick="open('/Contabilidad/BusquedaxOtros.php?Tipo=CodigoExogena&Campo=<?echo mysql_field_name($res,$i)?>','','width=600,height=400')">
			</td>
			<?}
			
			
			elseif(mysql_field_name($res,$i)=="Movimiento"){
			?><td>
			<select name="<? echo mysql_field_name($res,$i)?>">
			<option value="Debe">Debe</option>
			<option value="Haber">Haber</option>
			</select>
			</td>
			<?}
			elseif(mysql_field_name($res,$i)=="TipoComprobant"){
			?><td>
			<select name="<? echo mysql_field_name($res,$i)?>">
			<?
				$cons99="Select Tipo from $BaseDatos.TiposComprobante";
				$res99=ExQuery($cons99);echo ExError($res99);
				while($fila99=ExFetch($res99))
				{
					if($fila99[0]==mysql_field_name($res,$i)){echo "<option selected value='$fila99[0]'>$fila[990]</option>";}
					else{echo "<option value='$fila99[0]'>$fila99[0]</option>";}
				}
			?>
			</select>
			</td>
			<?}

			else{
			?><td><input type='Text' style="width:100px;" value="<?echo $VrCampo[$i]?>" name="<? echo mysql_field_name($res,$i)?>"></td><?}?>
	 <?	}?>
		<input type="Hidden" name="Guarda" value="1">
		<td><input type="Submit" name="Guardar" value="G"></td>
		<input type="Hidden" name="Tabla" value="<? echo $Tabla?>">
		</form>
<?
		echo "</tr>";

		while($fila=ExFetch($res))
		{
			if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}
			echo "<tr bgcolor='$BG'>";
			$n++;
			for($i=0;$i<=ExNumFields($res)-1;$i++)
			{
				if($fila[$i]!='+'){
				$Criterio=$Criterio . mysql_field_name($res,$i) . "=|$fila[$i]| and ";}
				echo "<td>$fila[$i]</td>";
			}
			$Criterio=substr($Criterio,0,strlen($Criterio)-4);?>
			<td><a href="Configuracion.php?Tabla=<?echo $Tabla?>&Mod=1&Criterio=<?echo $Criterio?>"><img border=0 src="/Imgs/b_edit.png"></a></td>
			<td><a href="Configuracion.php?Tabla=<?echo $Tabla?>&Elim=1&Criterio=<?echo $Criterio?>"><img border=0 src="/Imgs/b_drop.png"></a></td>
<?			$Criterio="";
			echo "</tr>";
		}
	}
?>
</table>
<?