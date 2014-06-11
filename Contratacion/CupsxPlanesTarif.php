<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Retirar)
	{
		$cons = "Delete from ContratacionSalud.CUPSXPlanes where AutoId = '$Plan' and Compania = '$Compania[0]'";
		$res = ExQuery($cons);
		//echo $cons;
		$cons = "Delete from ContratacionSalud.PlanesTarifas where AutoID = '$Plan' and Compania = '$Compania[0]'";
		$res = ExQuery($cons);			
		$Plan = "";  ?>
		<script language="javascript">
			parent.location.href="PlanesTarifarios.php?DatNameSID=<? echo $DatNameSID?>";
		</script><?
		$Retirar='';
	}
	if($Eliminar)
	{
		if($Plan!=""){
			$cons = "Delete from ContratacionSalud.CUPSXPlanes where CUP = '$CUP' and AutoId = '$Plan' and Compania = '$Compania[0]'";
			$res = ExQuery($cons);
			//echo $cons;
		}
		$Eliminar='';
	}
	
?>	
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function AbrirCUPS()
	{
		frames.FrameOpener.location.href='Cups.php?DatNameSID=<? echo $DatNameSID?>&Elemento=AutoId&VrElemento=<? echo $Plan?>&Texto=Valor';
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='10px';
		document.getElementById('FrameOpener').style.left='10px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='800';
		document.getElementById('FrameOpener').style.height='600';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"><?
if($Plan)
{
	?>
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>"
align="center">
    <tr align="center">
    	<td colspan="8">
    	<input type='button' name='Agregar' value='Agregar CUP' onclick='AbrirCUPS()' />
	    <input type='button' name='RetirarPlan' value='Retirar Plan' 
    	onClick="if(confirm('Desea Retirar El Plan Tarifario?')){document.FORMA.Retirar.value=1;document.FORMA.submit();}"/>
	    <input type="hidden" name="Eliminar" value="<? echo $Eliminar?>">
    	<input type="hidden" name="Retirar" value=""/>
	</tr>        
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
        <td>CUP</td><td>Nombre</td><td>Grupo</td><td>Tipo</td><td>Cod. SOAT</td><td>Det. SOAT</td>
    </tr>
    <tr>
    	<td>
        	<input type="text" name="CodCup" value="<? echo $CodCup?>" style="width:80" onFocus="xLetra(this)" onKeyPress="xLetra(this)"
            onkeyup="xLetra(this);frames.CupsxPlanesTarifDet.location.href='CupsxPlanesTarifDet.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>&CodCup='+FORMA.CodCup.value+'&NomCup='+FORMA.NomCup.value+'&GrupCup='+FORMA.GrupCup.value+'&TipCup='+FORMA.TipCup.value+'&SoatCup='+FORMA.SoatCup.value+'&DetSoatCup='+FORMA.DetSoatCup.value"/>
        </td>
        <td>
        	<input type="text" name="NomCup" value="<? echo $NomCup?>" style="width:250" onFocus="xLetra(this)" onKeyPress="xLetra(this)"
            onkeyup="xLetra(this);frames.CupsxPlanesTarifDet.location.href='CupsxPlanesTarifDet.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>&CodCup='+FORMA.CodCup.value+'&NomCup='+FORMA.NomCup.value+'&GrupCup='+FORMA.GrupCup.value+'&TipCup='+FORMA.TipCup.value+'&SoatCup='+FORMA.SoatCup.value+'&DetSoatCup='+FORMA.DetSoatCup.value"/>
        </td>
        <td>
        <?	$cons="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
			$res=ExQuery($cons);?>
            <select name="GrupCup" onChange="FORMA.submit()" style="width:200">
            	<option></option>
          	<?	while($fila=ExFetch($res))
				{
					if($GrupCup==$fila[0]){echo "<option value='$fila[0]' selected>$fila[1]</option>";}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}
				}?>
            </select>
        </td>
        <td>
        <?	$cons="select codigo,tipo from contratacionsalud.tiposservicio where compania='$Compania[0]'";
			$res=ExQuery($cons);?>
            <select name="TipCup" onChange="FORMA.submit()">
            <option></option>
          	<?	while($fila=ExFetch($res))
				{
					if($TipCup==$fila[0]){echo "<option value='$fila[0]' selected>$fila[1]</option>";}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}
				}?>
            </select>
        </td>
        <td>
        	<input type="text" name="SoatCup" value="<? echo $SoatCup?>" style="width:80" onFocus="xLetra(this)" onKeyPress="xLetra(this)"
            onkeyup="xLetra(this);frames.CupsxPlanesTarifDet.location.href='CupsxPlanesTarifDet.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>&CodCup='+FORMA.CodCup.value+'&NomCup='+FORMA.NomCup.value+'&GrupCup='+FORMA.GrupCup.value+'&TipCup='+FORMA.TipCup.value+'&SoatCup='+FORMA.SoatCup.value+'&DetSoatCup='+FORMA.DetSoatCup.value"/>
        </td>
        <td>
        	<input type="text" name="DetSoatCup" value="<? echo $DetSoatCup?>" style="width:250" onFocus="xLetra(this)" onKeyPress="xLetra(this)"
            onkeyup="xLetra(this);frames.CupsxPlanesTarifDet.location.href='CupsxPlanesTarifDet.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>&CodCup='+FORMA.CodCup.value+'&NomCup='+FORMA.NomCup.value+'&GrupCup='+FORMA.GrupCup.value+'&TipCup='+FORMA.TipCup.value+'&SoatCup='+FORMA.SoatCup.value+'&DetSoatCup='+FORMA.DetSoatCup.value"/>
        </td>
    </tr>
</table>	<?
}?>
</div>
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
<iframe frameborder="0" id="CupsxPlanesTarifDet" src="CupsxPlanesTarifDet.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>" width="100%" height="85%"></iframe>
<?
	if($Plan)
	{echo "entra";
		?><script language="javascript">
        	frames.CupsxPlanesTarifDet.location.href="CupsxPlanesTarifDet.php?DatNameSID=<? echo $DatNameSID?>&Plan=<? echo $Plan?>&CodCup=<? echo $CodCup?>&NomCup=<? echo $NomCup?>&GrupCup=<? echo $GrupCup?>&TipCup=<? echo $TipCup?>&SoatCup=<? echo $SoatCup?>&DetSoatCup=<? echo $DetSoatCup?>";
        </script><?
	}
?>
</body>