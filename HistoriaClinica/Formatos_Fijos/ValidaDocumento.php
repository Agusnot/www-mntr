<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($CedDef)
	{
		$cons="Update Central.Terceros set Tipo='Paciente' where Identificacion='$CedDef' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		echo ExError();

		$cons="Select * from Central.Terceros where Identificacion='$CedDef' and Compania='$Compania[0]' and Tipo='Paciente'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Paciente[1]=$fila[0];
		$n=1;
		for($i=1;$i<=ExNumFields($res);$i++)
		{
			$n++;
			$Paciente[$n]=$fila[$i];
		}
		
		if(!$Paciente[21]){
			$cons="Update Central.Terceros set NumHa='$CedDef' where Identificacion='$CedDef' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
		}
		
		?>
        <script language="javascript">
		parent.parent.location.href='/HistoriaClinica/HistoriaClinica.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
        <?
	}
?>
<head>
<style>
	a{color:blue; text-decoration:none;}
	a:hover{color:red; text-decoration:underline;}
</style>

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

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
<?	if($Codigo!=''||$Nombre!=''||$Cedula!=''){
		$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Compania='$Compania[0]' and Identificacion ilike '$Cedula%'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{?>
			<tr><td><? echo $fila[0]?></td><td><a href="ValidaDocumento.php?DatNameSID=<? echo $DatNameSID?>&CedDef=<? echo $fila[0]?>&Cedula=<? echo $Cedula?>"><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></a></td></tr>
<?		}
	}
?>
</table>
</body>
</html>
