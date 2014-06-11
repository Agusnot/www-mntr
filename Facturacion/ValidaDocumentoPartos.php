<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$cons="select cedula,valor from salud.multas where compania='$Compania[0]' and estado='AC'";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Multas[$fila[0]]=$fila[1];
	}
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		//parent.document.FORMA.submit();
	}

</script>
<?
	if($CedDef)
	{?>
        <script language="javascript">
			//alert('<? echo $NomCampo?>');
			//parent.document.FORMA.<? echo $NomCampo?>.value="<? echo $CedDef?>";			
			parent.document.getElementById('<? echo $NomCampo?>').value="<? echo $CedDef?>";
			//parent.document.FORMA.submit();
			CerrarThis();
		</script>
<?	}
?>
<head>
<style>
	a{color:blue; text-decoration:none;}
	a:hover{color:red; text-decoration:underline;}
</style>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
<?
if($Cedula){
	$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Compania='$Compania[0]' and Identificacion ilike '$Cedula%'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		?>
		<tr title="seleccionar" onMouseOver="this.bgColor='#AAD4FF'" style="cursor:hand" onMouseOut="this.bgColor=''"
        <?	if($Multas[$fila[0]]){?>
        		onClick="if(confirm('El usuario tiene multas pendientes, desea contiuanr con la asignacion de la cita?')){
                			location.href='ValidaDocumentoAgendaInforme.php?DatNameSID=<? echo $DatNameSID?>&Multa=<? echo $Multa?>&CedDef=<? echo $fila[0]?>&Cedula=<? echo $Cedula?>'}"
                       		
		<?	}
			else{?>
        		onClick="location.href='ValidaDocumentoPartos.php?DatNameSID=<? echo $DatNameSID?>&Multa=<? echo $Multa?>&CedDef=<? echo $fila[0]?>&Cedula=<? echo $Cedula?>&NomCampo=<? echo $NomCampo?>'"
       	<?	}?>
        >
        	<td><? echo $fila[0]?></td>
        	<td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></td></tr>
<?	}
}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Multa" value="<? echo $Multa?>">
</table>
</form>
</body>
</html>
