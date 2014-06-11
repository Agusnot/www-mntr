<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nomb, cedula,equip1,equip2,equip3,equip4,medio1,medio2,medio3,crono1,crono2,crono3,crono4
	,crono5,crono6,crono7 from historiaclinica.regpacienteseg,central.terceros where regpacienteseg.compania='$Compania[0]' and cedula=identificacion
	and numrep=$NumRep and terceros.compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
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
		parent.document.FORMA.submit();
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">FORMATO PACIENTE SEGURO</td>
  	</tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Paciente</td><td><? echo $fila[0]?></td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Identificaicon</td><td><? echo $fila[1]?></td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">Grupo de Trabajo</td>        
    </tr>
    <tr><td colspan="4"><strong>1.</strong><? echo $fila[2]?></td></tr>
    <tr><td colspan="4"><strong>2.</strong><? echo $fila[3]?></td></tr>
    <tr><td colspan="4"><strong>3.</strong><? echo $fila[4]?></td></tr>
    <tr><td colspan="4"><strong>4.</strong><? echo $fila[5]?></td></tr>
<?	if($fila[6]==1||$fila[7]==1||$fila[8]==1)
	{?>    
	   	<tr>
    		<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">Metodos Utiliazdos para Optener la Informacion</td>        
	    </tr>
<?	}
	$cont=1;
	if($fila[6]==1){
		echo "<tr><td colspan='4'>$cont Analisis de la Historia Clinica, Protocolos, Procedimientos."; $cont++;
	}
	if($fila[7]==1){
		echo "<tr><td colspan='4'>$cont Entrevista a las Personas que Interviene en el Proceso."; $cont++;
	}
	if($fila[8]==1){
		echo "<tr><td colspan='4'>$cont Otros Mecanismos: Declaraciones, Observaciones, etc."; 
	}?> 
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">Cronologia del Incidente</td>        
    </tr>         
  	<tr><td colspan="4"><strong>1.</strong><? echo $fila[9]?></td></tr>
    <tr><td colspan="4"><strong>2.</strong><? echo $fila[10]?></td></tr>
    <tr><td colspan="4"><strong>3.</strong><? echo $fila[11]?></td></tr>
    <tr><td colspan="4"><strong>4.</strong><? echo $fila[12]?></td></tr>     
    <tr><td colspan="4"><strong>5.</strong><? echo $fila[13]?></td></tr>
    <tr><td colspan="4"><strong>6.</strong><? echo $fila[14]?></td></tr>
    <tr><td colspan="4"><strong>7.</strong><? echo $fila[15]?></td></tr>
</table>	
</table>
</body>
</html>
