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
		//parent.document.FORMA.submit();
	}
</script>
<?
if($Guardar)
{
	if($Pagadores)
	{
		$ban=0;
		while( list($cad,$val) = each($Pagadores))
		{
			
			if($ban==0)
			{
				$Pags=$cad;
				$ban=1;
			}
			else
			{
				$Pags=$Pags.";".$cad;
			}
		}
	}
	if($Pags){
		$Incluye=$Pags;
		session_register("Incluye");
	}
	?>
   	<script language="javascript">		
		//alert('<? echo $Pags?>');
		parent.document.FORMA.AuxIncluir.value="<? echo $Pags?>";
		CerrarThis();
	</script><?
}
if($Regimenes)
{
	$Regimenes="'".$Regimenes;
	$Regimenes=str_replace(";","','",$Regimenes);
	$Regimenes=$Regimenes."'";
	$Reg=" and tipoasegurador in (".$Regimenes.") ";
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom, identificacion from central.terceros where compania='$Compania[0]' and tipo='Asegurador' $Reg";

	$res=ExQuery($cons);
	$ban=0;
	$AsegContra=" and entidad in (";
	while($fila=ExFetch($res))
	{
		$Aseguradoras[$fila[1]]=$fila[0];
		
		if($ban==0)
		{
			$ban=1;
			$AsegContra=$AsegContra."'".$fila[1]."'";
		}
		else{
			$AsegContra=$AsegContra.",'".$fila[1]."'";
		}	
	}
	$AsegContra=$AsegContra.")";
	//if($AsegContra==" and entidad in ()"){$AsegContra="";}
}
if($Incluye)
{ 
	$AuxIncluye=explode(";",$Incluye);
	foreach($AuxIncluye as $AI)
	{				
		$Inclu[$AI]=$AI;
		//echo $Inclu[$AI]."<br>";
	}
		
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function ChequearTodos(chkbox) 
	{ 
		for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
		{ 
			var elemento = document.forms[0].elements[i]; 
			if (elemento.type == "checkbox") 
			{ 
				elemento.checked = chkbox.checked 
			} 
		} 
	}	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="4" style="font : normal normal small-caps 12px Tahoma">	
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
    	<td colspan="4">Pagadores A seleccionar</td>
 	</tr>
    <tr><td colspan="3" align="center"><input type="submit" value="Guardar" name="Guardar"/></td>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Entidad</td><td>Contrato</td><td>No. Contrato</td>
        <td><input type="checkbox" name="Todos" title="Seleccionar Todos" onClick="ChequearTodos(this);"/></td>
   	</tr>
<?	
	if($Regimenes){
		$cons="select entidad,contrato,numero,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom from contratacionsalud.contratos,central.terceros 
		where terceros.compania='$Compania[0]' and contratos.compania='$Compania[0]' and identificacion=entidad and estado='AC' $AsegContra order by nom,contrato,numero";
		//echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        		<td><? echo strtoupper($Aseguradoras[$fila[0]])?></td><td><? echo strtoupper($fila[1])?></td><td><? echo strtoupper($fila[2])?></td>
                <td><input type="checkbox" name="Pagadores[<? echo "$fila[0]*$fila[1]*$fila[2]"?>]" <? if($Inclu["$fila[0]*$fila[1]*$fila[2]"]){?> checked="checked"<? }?>/></td>
	        </tr>	
<?		}?>		
		<tr><td colspan="3" align="center"><input type="submit" value="Guardar" name="Guardar"/></td>
<?	}
	else{?>    
    	<tr><td colspan="4">No existen registros coincidentes</td></tr>
<?	}?>
</table>
</form>    
</body>
</html>
