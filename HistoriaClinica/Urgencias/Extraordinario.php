<?php
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();		
	include("Funciones.php");
	$ND=getdate();
	$extra=$_GET['extra'];
	$cons0="select autoid from salud.extraordinario order by autoid desc limit 1";
	$res0=ExQuery($cons0);
	$fila0=ExFetch($res0);
	if($fila0[0]==0){
		$fila0[0]=1;
	}
	else{
		$fila0[0]++;
	}
	if($extra != null){
		$cons1="insert into salud.extraordinario values($fila0[0],$extra,'$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
		$res1=ExQuery($cons1);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Apertura Historias</title>
<style type="text/css"> 
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
body {
	margin-left: 0px;
	margin-right: 0px;
	background-image: url(../Imgs/Fondo.jpg);
	margin-top: 0px;
	margin-bottom: 0px;
} 
-->
</style>

</head>
<body background="/Imgs/Fondo.jpg">
<div align="center">
</div>
<br></br>
<blockquote>
	<?php
		$cons2="select extra from salud.extraordinario order by fecha desc limit 1";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		if($fila2[0]==0){
			?>
			<a href="?DatNameSID=<?php echo $DatNameSID ?>&extra=1">ACTIVAR SITUACI&Oacute;N EXTRAORDINARIA</a>
			<?php
		}
	if($fila2[0]==1){
			?>
			<a href="?DatNameSID=<?php echo $DatNameSID ?>&&extra=0">DESACTIVAR SITUACI&Oacute;N EXTRAORDINARIA</a>
			<?php
		}
		?>
</blockquote>
</body>
</html>