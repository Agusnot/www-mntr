<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	
	if($Guardar){
		if($ReqVoBo=='on'){$ReqVoBo="1";}else{$ReqVoBo="0";}
		if($Facturable=='on'){$Facturable="1";}else{$Facturable="0";}
		if(!$Minimos){$Minimos="0";}
		if(!$Maximos){$Maximos="0";}
		if($Clase=='CUPS')
		{
			$cons="update ContratacionSalud.cupsxplanservic set reqvobo=$ReqVoBo,facturable=$Facturable,minimos=$Minimos,maximos=$Maximos,clase='CUPS'
			where compania='$Compania[0]' and AutoId=$Plan and CUP='$Codigo'";			
		}
		else{
			$cons="update ContratacionSalud.medsxplanservic set reqvobo=$ReqVoBo,facturable=$Facturable,minimos=$Minimos,maximos=$Maximos
			where compania='$Compania[0]' and AutoId=$Plan and codigo='$Codigo' and  almacenppal='$Almacen'";			
		}
		//echo $cons;
		$res=ExQuery($cons);
		?>
        <script language="javascript">
			parent.document.FORMA.submit();
		</script>
        <?
	}
	if($Clase=='CUPS')
	{
		$cons="select nombre,reqvobo,facturable,minimos,maximos from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
		where cupsxplanservic.compania='$Compania[0]' and cups.compania='$Compania[0]' and cup=codigo and autoid=$Plan and cup='$Codigo'";		
	}
	else
	{
		$cons="select (nombreprod1||' '||unidadmedida||' '||presentacion) as nombre,reqvobo,facturable,minimos,maximos,medsxplanservic.almacenppal 
		from contratacionsalud.medsxplanservic,consumo.codproductos where medsxplanservic.compania='$Compania[0]' and medsxplanservic.autoid=$Plan 
		and medsxplanservic.codigo='$Codigo' and codigo=codproductos.codigo1 and codproductos.compania = '$Compania[0]' and  codproductos.almacenppal=medsxplanservic.almacenppal 
		and medsxplanservic.almacenppal='$Almacen' and anio='$ND[year]'";
	}
	//echo $cons;
	$res=ExQuery($cons);
	$fila=ExFetch($res);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
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
	function Validar()
	{
		
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td colspan="10" align="right"><button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button></td>
    </tr>
	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    	<td>Codigo</td><td>Nombre</td><td>Req VoBo</td><td>Facturable</td><td>Minimos</td><td>Maximos</td>
   	<?	if($Clase!='CUPS'){echo "<td>Alamecn</td>";}?>
	</tr>
	<tr>
    	<td><? echo $Codigo?></td><td><? echo $fila[0]?></td>
        <td align="center"><input type="checkbox" name="ReqVoBo" <? if($fila[1]==1){?> checked="checked"<? }?>/></td>
        <td align="center"><input type="checkbox" name="Facturable" <? if($fila[2]==1){?> checked="checked"<? }?>/></td>
        <td align="center"><input type="text" name="Minimos" value="<? echo $fila[3]?>" onkeydown="xNumero(this)" onkeypress="xNumero(this)" onkeyup="xNumero(this)" style="width:40"/></td>
        <td align="center"><input type="text" name="Maximos" value="<? echo $fila[4]?>" onkeydown="xNumero(this)" onkeypress="xNumero(this)" onkeyup="xNumero(this)" style="width:40"/></td>
		<? if($Clase!='CUPS'){echo "<td align='center'>$fila[5]</td>";}?>
	</tr>
    <tr align="center">
    	<td colspan="10"><input type="submit" value="Guardar" name="Guardar"/>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Codigo" value="<? echo $Codigo?>" />
<input type="hidden" name="Plan" value="<? echo $Plan?>" />
<input type="hidden" name="Almacen" value="<? echo $Almacen?>" />
</form>            
</body>
</html>
