<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
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
</head>
<body background="/Imgs/Fondo.jpg">
<style>
a{color:black;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;' align="center" width="100%">
<?
	if($Codigo || $Medicamento)
	{ 
		if($Codigo){$COD="and AutoId ilike '$Codigo%' ";}
		if($Medicamento){$MED="and (NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) ilike '%$Medicamento%'";}
		
		$cons="Select (NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida),AutoId,POS from Consumo.CodProductos 
		where  Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' $COD $MED";
		$res=ExQuery($cons);
		//echo $cons;
		if(ExNumRows($res)>0){?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
   				<td>Codigo</td><td>Medicamento</td><td>Pos</td></tr>
    		</tr>
	<?	}
		//echo $cons;
		while($fila=ExFetch($res))
		{
			if($fila[2]=="1"){$P="POS";}else{$P="NO POS";}?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
            onClick="parent.document.FORMA.CodSeleccionado.value='<? echo $fila[1]?>';parent.document.FORMA.MedSeleccionado.value='<? echo $fila[0]?>'">
				<td><? echo $fila[1];?></td><td><? echo $fila[0];?></td><td><? echo $P;?></td>
			</tr>
	<?	}
	}
?>
</table>
</body>
</html>
