<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
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
	}
    </script>
    <body background="/Imgs/Fondo.jpg">
    <table border="0" width="100%">
    	<tr><td align="right"><button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar" /></button></td></tr>
    </table>
	<table width="100%" border="1" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="5"><em>Elementos pertenecientes al <? echo "$Tipo $ElementoTipo";?></em></td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Codigo</td><td>Nombre</td><td>Caracteristicas</td><td>Modelo</td><td>Serie</td>
    </tr>
	<?
		if($CC){ $ConsCC = " Ubicaciones.CentroCostos = '$CC' and ";}
		if($Tipo=="Tercero"){$Tipo="(PrimNom || ' ' || SegNom || ' ' || PrimApe || ' ' || SegApe)";}
		$cons = "Select distinct(CodElementos.AutoId),CodElementos.Codigo,Nombre,Caracteristicas,Modelo,Serie
		From InfraEstructura.CodElementos,InfraEstructura.Ubicaciones,Central.CentrosCosto,Central.Terceros
		Where CodElementos.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
		and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable='$Identificacion' and $ConsCC 
		Ubicaciones.Responsable = Terceros.Identificacion and (CodElementos.Tipo='Levantamiento Inicial' or (CodElementos.Tipo='Compras' and EstadoCompras='Ingresado'))
		and $Tipo = '$ElementoTipo' order by Nombre";
		$res = ExQuery($cons);
		if(ExNumRows($res)==1)
		{
			$fila = ExFetch($res);
			?>
			<script language="javascript">
            	parent.frames.Busquedas.location.href="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC=<? echo $CC?>&Identificacion=<? echo $Identificacion?>&Frame=<? echo $Frame?>&Tipo=CodInfraest&Codigo=<? echo $fila[1]?>";
				CerrarThis();
            </script>
			<?		
		}
		while($fila = ExFetch($res))
		{
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'" style="cursor:hand" title="Agregar este elemento"
            onclick="parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&CC=<? echo $CC?>&Identificacion=<? echo $Identificacion?>&Frame=<? echo $Frame?>&Tipo=CodInfraest&Codigo=<? echo $fila[1]?>';
            CerrarThis();"><?
			echo "<td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td></tr>";	
		}		
	
	
?>
	</table>
	</body>