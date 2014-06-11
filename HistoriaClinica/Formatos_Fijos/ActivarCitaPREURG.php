<?php
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();		
	include("Funciones.php");	
	$ND=getdate();
	$cons0="select autoid from salud.salasintriage order by autoid desc limit 1";
	$res0=ExQuery($cons0);
	$res0f = pg_num_rows($res0);
	if($res0f<1){
	$fila0[0]=1;
	}
	else{
	$fila0=ExFetch($res0);
	$fila0[0]=$fila0[0]+1;
	}
	$cons1="select estado from salud.salasintriage where cedula='$Paciente[1]' order by estado desc limit 1";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
	$texto="El paciente ya tiene un proceso de Urgencias Abierto :(";
	if($fila1[0]!=1){
	$cons2="insert into salud.salasintriage values('$Paciente[1]',0,0,'$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$fila0[0],1)";
	$res2=ExQuery($cons2);
	$texto="Por seguridad el paciente debe ser requisado antes de la consulta, la alerta para la requisa ha sido generada.";
	}
	?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<br>
<br>
<center><strong><?php echo"$texto"; ?></strong></center>
</form>
</body>
</html>
