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
		parent.document.FORMA.submit();
	}
function seleccionarAnio(elemento) {	
  	if(elemento){
	  var combo = parent.document.forms["FORMA"].AnioNac;
	  var cantidad = combo.length;
	  for (i = 0; i < cantidad; i++) {
		 if (combo[i].value == elemento) {
			combo[i].selected = true;
		 }   
	  }
	}
}
function seleccionarMes(elemento) {	
	if(elemento){
	  var combo = parent.document.forms["FORMA"].MesNac;
	  var cantidad = combo.length;
	  for (i = 0; i < cantidad; i++) {
		 if (combo[i].value == elemento) {
			combo[i].selected = true;
		 }   
	  }
	}
}
function seleccionarDia(elemento) {	
	if(elemento){
	  var combo = parent.document.forms["FORMA"].DiaNac;
	  var cantidad = combo.length;
	  for (i = 0; i < cantidad; i++) {
		 if (combo[i].value == elemento) {
			combo[i].selected = true;
		 }   
	  }
	}
}
</script>
<?
	if($CedDef)
	{
		$cons="Update Central.Terceros set Tipo='Paciente' where Identificacion='$CedDef' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		echo ExError();

		$cons="Select * from Central.Terceros where Identificacion='$CedDef' and Compania='$Compania[0]' and Tipo='Paciente'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		
		?>
        <script language="javascript">
			parent.document.FORMA.Cedula.value="<? echo $fila[0]?>";						
		<?	if($Multa!=1){	
				$anio=substr($fila[22],0,4);?>
				seleccionarAnio(<? echo $anio?>);
			<?	$mes=substr($fila[22],5,2);?>			
				seleccionarMes(<? echo $mes?>);
			<?	$dia=substr($fila[22],8,2);?>
				seleccionarDia(<? echo $dia?>);											
				parent.document.FORMA.PrimApe.value="<? echo $fila[1]?>";
				parent.document.FORMA.SegApe.value="<? echo $fila[2]?>";
				parent.document.FORMA.PrimNom.value="<? echo $fila[3]?>";
				parent.document.FORMA.SegNom.value="<? echo $fila[4]?>";
				parent.document.FORMA.Telefono.value="<? echo $fila[7]?>";
				if(parent.document.FORMA.Asegurador.value==""){parent.document.FORMA.Asegurador.value="<? echo $fila[25]?>";}
		<?	}?>
			parent.document.FORMA.submit();
			CerrarThis();
		</script>
        <?
	}
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
								location.href='ValidaDocumentoAgenda.php?DatNameSID=<? echo $DatNameSID?>&Multa=<? echo $Multa?>&CedDef=<? echo $fila[0]?>&Cedula=<? echo $Cedula?>'}"
								
			<?	}
				else{?>
					onClick="location.href='ValidaDocumentoAgenda.php?DatNameSID=<? echo $DatNameSID?>&Multa=<? echo $Multa?>&CedDef=<? echo $fila[0]?>&Cedula=<? echo $Cedula?>'"
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
