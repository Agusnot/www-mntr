<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons = "Insert into Consumo.TarifariosVenta
			(Compania,AlmacenPpal,Tarifario,UsuarioCre,FechaCre,Estado) values
			('$Compania[0]','$AlmacenPpal','$Tarifario','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$Estado')";		
		}
		else
		{
			$cons = "Update Consumo.TarifariosVenta set Tarifario='$Tarifario', Estado='$Estado'
			where Tarifario='$Tarifariox' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";	
		}
		//echo $cons;exit;
		$res=ExQuery($cons);
		//echo ExError();exit;
		?>
		<script language="javascript">location.href="ConfTarifariosVenta.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal;?>";</script>
		<?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Tarifario.value==""){alert("Debe llenar el campo Nombre Tarifario");return false;}
	}
</script>
<?
	$cons = "select estado from consumo.tarifariosventa where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tarifario = '$Tarifario'";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$Estado = $fila[0];
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="Tabla" value="<? echo $Tabla; ?>"  />
<input type="Hidden" name="Campo" value="<? echo $Campo; ?>"  />
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal; ?>"  />
<input type="Hidden" name="Tarifariox" value="<? echo $Tarifario; ?>"  />
<input type="Hidden" name="Editar" value="<? echo $Editar; ?>"  />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="2" align="center"><? echo $AlmacenPpal;?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Nombre Tarifario:</td>
        <td><input type="text" name="Tarifario" value="<? echo $Tarifario;?>" maxlength="30" size="30"  
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
    </tr>
    <tr>
       		<td bgcolor="#e5e5e5">Estado:</td>
            <td><select name="Estado" style="width:100%" >
            <?
            	//echo $Estado."=-=-=-";
				if($Editar)
				{
					if($Estado=="AC"){echo "<option selected value='AC'>Activo</option><option value='IN'>Inactivo</option>";}
					else{echo "<option value='IN'>Inactivo</option><option value='AC'>Activo</option>";}
				}
				else{
			?>
            		<option value="AC">Activo</option>
                    <option value="IN">Inactivo</option> <? } ?>
            </select>
	</tr>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfTarifariosVenta.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>'" />
</form>
</body>