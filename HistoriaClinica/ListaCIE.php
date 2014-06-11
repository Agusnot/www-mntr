<?
	session_start();
	include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<style>
a{color:black;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>
	<script language="javascript">
	
		function asignarDiagnostico(idCodDx,codDx,idDetDx,detDx){
			
			var padre = window.parent;
			var abuelo = padre.parent;
					
			abuelo.document.getElementById(idCodDx).value = codDx ;
			abuelo.document.getElementById(idDetDx).value = detDx ;
			
			abuelo.document.getElementById('FrameOpener').style.position='absolute';
			abuelo.document.getElementById('FrameOpener').style.top='1px';
			abuelo.document.getElementById('FrameOpener').style.left='1px';
			abuelo.document.getElementById('FrameOpener').style.width='1';
			abuelo.document.getElementById('FrameOpener').style.height='1';
			abuelo.document.getElementById('FrameOpener').style.display='none';
			
			
		}
	</script>
<table border="1" id="tablaListaCIE" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
<?
	if((empty($Clasificacion)&&($Codigo || $Diagnostico))||$Clasificacion=="Favoritos"||$Codigo || $Diagnostico)
	{
		if($Codigo){$PartCons="and Codigo ilike '$Codigo%'";}
		if($Diagnostico){$PartCons=$PartCons." and Diagnostico ilike '$Diagnostico%'";}
		if($Clasificacion=="Favoritos"){$PartCons=$PartCons." and favorito = '1'";}
		elseif($Clasificacion=="No Favoritos"){$PartCons=$PartCons." and favorito is null";}
		$cons="Select Diagnostico,Codigo from Salud.CIE where 1=1 $PartCons order by codigo";
		$res=ExQuery($cons);echo ExError();
		while($fila=ExFetch($res))
		{?>
			<tr>
				<td><a href="ListaCIE.php?idCodDx=<?php echo $_GET['ControlOrigen'];?>&codDx=<?php echo $fila[1];?>&idDetDx=<?php echo $_GET['DetalleOrigen'];?>&detDx=<?php echo $fila[0];?>&asignarDiag=1" > <?php echo $fila[1]; ?></a></td>
				<td><a href="ListaCIE.php?idCodDx=<?php echo $_GET['ControlOrigen'];?>&codDx=<?php echo $fila[1];?>&idDetDx=<?php echo $_GET['DetalleOrigen'];?>&detDx=<?php echo $fila[0];?>&asignarDiag=1" > <?php echo $fila[0]; ?></a></td>
			</tr>
	<?	}
	}?>
</table>
</body>

	<?php
		// Verfica las variables GET
		if(isset($_GET["asignarDiag"])){
			
			$idCodDx=$_GET['idCodDx'];
			$codDx = $_GET['codDx'];
			$idDetDx = $_GET['idDetDx'];
			$detDx = $_GET['detDx'];
			echo "<script language='javascript'>";
			echo "asignarDiagnostico('".$idCodDx."','".$codDx."','".$idDetDx."','".$detDx."');";
			echo "</script>";
		
		}
	?>
