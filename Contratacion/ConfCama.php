<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<script language="javascript"> 
function CerrarThis(){
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
}
</script>
<?	if($Eliminar){
		$cons="select * from salud.pacientesxpabellones where pabellon='$UnidadMod' and estado='AC' and ambito='$Ambito' and idcama=$Id and compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError($res);
		if(ExNumRows($res)>0){?>
        	<script language="javascript">
			alert("La cama no se puede eliminar debido a que se encuentra ocupada!!!");	
			</script>
	<?	}else{
			$cons="delete from salud.camasxunidades where compania='$Compania[0]' and ambito='$Ambito' and unidad='$UnidadMod' and idcama=$Id";
			$res=ExQuery($cons);echo ExError($res);
			$cons="select nocamas from salud.pabellones where compania='$Compania[0]' and ambito='$Ambito' and pabellon='$UnidadMod'";
			$res=ExQuery($cons);echo ExError($res);
			$row=ExFetch($res);
			$camas=$row[0];
			$camas--;
			$cons="update salud.pabellones set nocamas=$camas where compania='$Compania[0]' and ambito='$Ambito' and pabellon='$UnidadMod'";
			$res=ExQuery($cons);echo ExError($res);	
			//echo $cons;
			?>	<script language="javascript">
				parent.location.href='ConfCamasxUnd.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>';
			</script>
	<?	}
	}
	if($Guardar){
		$cons="update salud.camasxunidades set estado='$Estado',nombre='$Nombre',detalle='$Detalle' where ambito='$Ambito' and compania='$Compania[0]' and unidad='$UnidadMod' and idcama=$Id";		
		$res=ExQuery($cons);echo ExError($res);
	?>	<script language="javascript">
			parent.location.href='ConfCamasxUnd.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>';
		</script>
    <?
}?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>
<body>
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr><td></td><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Id Cama:</td><td><? echo $Id?></td></tr>
	<tr>
    	<td class="3" align="right"><input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana"></td>
    </tr>
    <tr><?
	$cons="select * from salud.camasxunidades where compania='$Compania[0]' and  ambito='$Ambito' and unidad='$UnidadMod' and idcama=$Id";	
	//echo $cons;
	$res=ExQuery($cons);echo ExError($res);
	$row=ExFetch($res);
	if($row[6]=='AC'){?>
    	<td rowspan="2"><img src="/Imgs/CAMAM.png">
<?  }
	else{?>
		<td rowspan="2"><img src="/Imgs/CAMAMX.png">		
<?	}?>
	</td><td bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td><td><input type="text" name="Nombre"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $row[4]?>"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Detalle</td><td><textarea name="Detalle" cols="23" rows="6"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $row[5]?></textarea></td>
    </tr>
    <tr>
    <? 	if($row[6]=='AC'){?>
    		<td colspan="4" align="center"><input type="radio" name="Estado" checked value="AC"> Activa <input type="radio" name="Estado" value="AN">Inactiva</td>
   <?	}else{?>
   			<td colspan="4" align="center"><input type="radio" name="Estado" value="AC"> Activa <input type="radio" name="Estado" checked value="AN">Inactiva</td>
   	<?	}?>
    </tr>
    <tr>
    	<td colspan="4" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="submit" name="Eliminar" value="Eliminar"></td>
    </tr>
</table>
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="UnidadMod" value="<? echo $UnidadMod?>">
<input type="hidden" name="Id" value="<? echo $Id?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
