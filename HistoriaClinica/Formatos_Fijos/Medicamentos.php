<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania = '$Compania[0]' and SSFarmaceutico = 1";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript"> 
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Medicamento.focus()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana" />
<form name="FORMA" method="post">
<input type="hidden" name="Formulacion" value="<? echo $Formulacion?>" />
	<table border="1" bordercolor="#e5e5e5" width="80%" align="center" style='font : normal normal small-caps 13px Tahoma;'>
    	<tr>
        	<td bgcolor="#e5e5e5" width="15%" align="center" style="font-weight:bold">Almacen Principal</td>
            <td width="85%"><select name="AlmacenPpal" onChange="FORMA.Submit()" style="width:100%">
    		<?
    			$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania = '$Compania[0]' and SSFarmaceutico = 1";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if(AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>
    		</select></td>
        </tr>
        <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
        	<td>Codigo</td><td>Medicamento</td>
        </tr>
        <tr>
        	<td><input type="text" name="Codigo" style="width:100%" 
            onkeyup="xLetra(this);frames.BusqMedicamentos.location.href='BusqMedicamentos.php?DatNameSID=<? echo $DatNameSID?>&Formulacion=<? echo $Formulacion?>&AlmacenPpal='+
            FORMA.AlmacenPpal.value+
            '&Codigo='+this.value+'&Medicamento='+FORMA.Medicamento.value+'&Paquete=<?echo $Paquete?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<?echo $NoContrato?>';" onKeyDown="xLetra(this)"/></td>
            <td><input type="text" name="Medicamento" style="width:100%"
            onkeyup="xLetra(this);frames.BusqMedicamentos.location.href='BusqMedicamentos.php?DatNameSID=<? echo $DatNameSID?>&Formulacion=<? echo $Formulacion?>&AlmacenPpal='+
            FORMA.AlmacenPpal.value+
            '&Codigo='+FORMA.Codigo.value+'&Medicamento='+this.value+'&Paquete=<?echo $Paquete?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<?echo $NoContrato?>';"
            onFocus="frames.BusqMedicamentos.location.href='BusqMedicamentos.php?DatNameSID=<? echo $DatNameSID?>&Formulacion=<? echo $Formulacion?>&AlmacenPpal='+FORMA.AlmacenPpal.value+'&Codigo='+FORMA.Codigo.value+'&Medicamento='+this.value+'&Paquete=<?echo $Paquete?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<?echo $NoContrato?>;" onKeyDown="xLetra(this)" /></td>
        </tr>
    </table>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="BusqMedicamentos" name="BusqMedicamentos" frameborder="0" width="100%" height="75%" src="BusqMedicamentos.php?DatNameSID=<? echo $DatNameSID?>"></iframe>
</body>