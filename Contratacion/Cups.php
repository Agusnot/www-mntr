<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Nuevo)
	{
		?><script language="javascript">
        	location.href = "NuevoCUP.php?DatNameSID=<? echo $DatNameSID?>";
		</script><?
	}
	if($VrElemento){
		$cons="select nombreplan from contratacionsalud.planestarifas where compania='$Compania[0]' and autoid=$VrElemento";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$NomPlan=$fila[0];
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
		parent.document.FORMA.submit();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
    <? if($Elemento){?><tr><td colspan="10" align="right"><button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button></td></tr><? }?>
     <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="2"><? echo $NomPlan?></td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td width="10%">Codigo</td><td width="35%">Nombre</td>
		<? if(!$Elemento)
		{
			?><td width="24%">Grupo</td><td width="18%">Tipo</td><td width="8%">Clasificacion</td>
	        <td rowspan="2" bgcolor="#ffffff" align="center" valign="center" width="5%"><input type="submit" name="Nuevo" value="Nuevo" /></td><?
		}?>
        
    </tr>
    <tr>
    	<td><input type="text" name="Codigo" style="width:100%" value="<? echo $Codigo?>"
        onkeyup="xLetra(this);frames.Busquedascups.location.href='BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&Nombre='+Nombre.value+'&Clasificacion='+Clasificacion.value
        <? if(!$Elemento){ echo "+'&Grupo='+Grupo.value+'&Tipo='+Tipo.value;";}
		else{ echo "+'&Elemento=$Elemento&VrElemento=$VrElemento&Texto=$Texto';";}?>"
        onKeyDown="xLetra(this)" /></td>
        <td><input type="text" name="Nombre" style="width:100%" value="<? echo $Nombre?>"
        onkeyup="xLetra(this);frames.Busquedascups.location.href='BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+Codigo.value+'&Nombre='+this.value+'&Clasificacion='+Clasificacion.value
        <? if(!$Elemento) { echo "+'&Grupo='+Grupo.value+'&Tipo='+Tipo.value;";}
		else{ echo "+'&Elemento=$Elemento&VrElemento=$VrElemento&Texto=$Texto';";}?>"
        onKeyDown="xLetra(this)" /></td>
        <? if(!$Elemento)
		{ ?><td><select name="Grupo" style="width:100%"
        	onchange="frames.Busquedascups.location.href='BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+Codigo.value+'&Nombre='+Nombre.value+'&Grupo='+this.value+'&Tipo='+Tipo.value+'&Clasificacion='+Clasificacion.value">
        	<option value=""></option>
            <?
            	$cons = "Select Codigo,Grupo from ContratacionSalud.GruposServicio where Compania = '$Compania[0]'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Grupo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}
				}
			?>
        </select></td>
        <td><select name="Tipo" style="width:100%"
        onchange="frames.Busquedascups.location.href='BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+Codigo.value+'&Nombre='+Nombre.value+'&Grupo='+Grupo.value+'&Tipo='+this.value+'&Clasificacion='+Clasificacion.value">
        	<option value=""></option>
            <?
            	$cons = "Select Codigo,Tipo from ContratacionSalud.TiposServicio where Compania = '$Compania[0]'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Grupo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}
				}
			?>
        </select></td>
        <td>
        	<select name="Clasificacion" onChange="frames.Busquedascups.location.href='BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+Codigo.value+'&Nombre='+Nombre.value+'&Grupo='+Grupo.value+'&Tipo='+Tipo.value+'&Clasificacion='+Clasificacion.value"><option></option>
            	<option value="2" <? if($Clasificaicon==2){?> selected<? }?>>POS</option>
                <option value="1" <? if($Clasificaicon==1){?> selected<? }?>>No POS</option>
            </select>
        </td>
		<?
		}
		else{?>
        	<input type="hidden" name="Tipo">
            <input type="hidden" name="Clasificacion">
	<?	}?>
    </tr>
    </table>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="Busquedascups" src="BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
<?
	if($Codigo || $Nombre)
	{
		?><script language="javascript">
        	frames.Busquedascups.location.href="BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>";
        </script><?
	}
?>
</body>