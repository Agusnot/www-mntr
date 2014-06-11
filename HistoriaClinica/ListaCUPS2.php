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
		parent.parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.parent.document.getElementById('FrameOpener').style.top='1px';
		parent.parent.document.getElementById('FrameOpener').style.left='1px';
		parent.parent.document.getElementById('FrameOpener').style.width='1';
		parent.parent.document.getElementById('FrameOpener').style.height='1';
		parent.parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Asignar(Cod,Nom)
	{
		parent.parent.document.FORMA.CUP.value=Cod;
		parent.parent.document.FORMA.CodCUP.value=Cod+' - '+Nom;
		CerrarThis();
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
	if($Codigo || $Nombre)
	{ 
		if($Codigo){$COD="and codigo ilike '$Codigo%'";}
		if($Nombre){$NOM="and nombre ilike '%$Nombre%'";}
		
		$cons="Select nombre,codigo from contratacionsalud.CUPS
		where  Compania='$Compania[0]' $COD $NOM";
		$res=ExQuery($cons);

		if(ExNumRows($res)>0){?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
   				<td>Codigo</td><td>Medicamento</td></tr>
    		</tr>
	<?	}
		//echo $cons;
		while($fila=ExFetch($res))
		{
			?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
            onClick="Asignar('<? echo $fila[1]?>','<? echo $fila[0]?>')">
				<td><? echo $fila[1];?></td><td><? echo $fila[0];?></td>
			</tr>
	<?	}
	}
?>
</table>
</body>
</html>
