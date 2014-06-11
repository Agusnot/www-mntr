<?
	if($DatNameSID){session_name("$DatNameSID");
	session_start();
	include("Funciones.php");
	$ND = getdate();
    
	$AnioAc=$ND[year];

	if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
	$AnioInc=$AnioAc-10;
	$AnioAf=$AnioAc+10;
	
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	
	if($Clasificacion) $Cla="and consumo.codproductos.clasificacion='$Clasificacion'";
	?><form name='FORMA' action='SalidasXCP.php' target='Abajo'>
            <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold"><td><center>Periodo Inicial</td><td>Periodo Final</td><td>Almacen Ppal</td><td>Clasificacion</td>
            </tr>
		<tr>
                <td>
                    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
                    <select name="Anio"><?
                        for($i=$AnioInc;$i<$AnioAf;$i++)
                        if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
                        else{echo "<option value=$i>$i</option>";}
		?>  </select>
		<select name="MesIni">
		<? for($i=1;$i<=12;$i++)
		{
			if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}
		?>
		</select>
		<input type='Text' name='DiaIni' style='width:20px;' maxlength="2" value='01'>

		</td>
		<td>
		<select name="MesFin">
		<? for($i=1;$i<=12;$i++)
		{
			if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}
		?>
		</select>
		<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" value='<? echo $ND[mday]?>'></td>
        <td>
        <select name="AlmacenPpal">
            <option></option>
<?                      $cons = "Select AlmacenesPpales.AlmacenPpal from Consumo.UsuariosxAlmacenes,Consumo.AlmacenesPpales
                        where Usuario='$usuario[0]' and AlmacenesPpales.Compania='$Compania[0]' and UsuariosxAlmacenes.Compania='$Compania[0]'
                        and UsuariosxAlmacenes.AlmacenPpal=AlmacenesPpales.AlmacenPpal $AdCons";
                        $res = ExQuery($cons);
			if($fila[0]='FARMACIA')
				echo "<option selected value='$fila[0]'>$fila[0]</option>";
?>
           </select>
        </td>
                <td><select name="Clasificacion">
            <option></option>
<?                      $cons = "Select Clasificacion from Consumo.Clasificaciones";
                        $res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
?>
           </select></td>
        </tr>
		<tr>
		<td colspan="4" align="center"><input type="Submit" name="Ver" value="Ver" onClick="Ocultar();parent.document.getElementById('Abajo').rows='83,*';"></td>
		</tr></table>
		
		<?
	if($Anio){
	$cons = "SELECT consumo.codproductos.codigo1, consumo.codproductos.nombreprod1, consumo.codproductos.unidadmedida, consumo.codproductos.presentacion,
consumo.codproductos.clasificacion, sum(consumo.movimiento.cantidad)
  FROM consumo.movimiento
  INNER JOIN consumo.codproductos ON consumo.movimiento.autoid=consumo.codproductos.autoid
  where fecha between '$FechaIni' and '$FechaFin'
  and consumo.codproductos.almacenppal='FARMACIA' $Cla
  and consumo.movimiento.almacenppal='FARMACIA'
  and tipocomprobante='Salidas'
  and consumo.movimiento.estado='AC' and consumo.codproductos.anio='$Anio'
GROUP BY consumo.codproductos.codigo1, consumo.codproductos.nombreprod1, consumo.codproductos.unidadmedida, consumo.codproductos.presentacion, 
 consumo.codproductos.clasificacion
  order by consumo.codproductos.nombreprod1";
	//echo $cons;
	$res = ExQuery($cons);
	 ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
           <td>No.</td>
			<td>C&Oacute;DIGO</td>
            <td>NOMBRE PRODUCTO</td>
			<td>UNIDAD DE MEDIDA</td>
			<td>PRESENTACI&Oacute;N</td>
            <td>CLASIFICACI&Oacute;N</td>
			<td>CANTIDAD</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?></td><td><?echo $fila[0]?></td><td><?echo $fila[1]?></td><td><?echo $fila[2]?></td>
                    <td><?echo $fila[3]?></td><td><?echo $fila[4]?></td><td><?echo $fila[5]?></td>
                    <td><?echo $fila[6]?></td>
                </tr><?
    $count++;}
    ?>
</table>
<?}?>
</form>
</body><?}?>