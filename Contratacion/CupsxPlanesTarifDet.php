<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Eliminar)
	{
		if($Plan!=""){
			$cons = "Delete from ContratacionSalud.CUPSXPlanes where CUP = '$CUP' and AutoId = '$Plan' and Compania = '$Compania[0]'";
			$res = ExQuery($cons);
			echo $cons;
		}
		$Eliminar='';
	}
	
?>	
<script language="javascript">
	function AbrirCUPS()
	{
		frames.FrameOpener.location.href='Cups.php?DatNameSID=<? echo $DatNameSID?>&Elemento=AutoId&VrElemento=<? echo $Plan?>&Texto=Valor';
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='10px';
		document.getElementById('FrameOpener').style.left='10px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='800';
		document.getElementById('FrameOpener').style.height='600';
	}
	function EditarCup(e,Codigo,Nombre,Precio)
	{	
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href='EditCupPlanTarf.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+Codigo+'&Nombre='+Nombre+'&Plan=<? echo $Plan?>&Precio='+Precio;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y-70+st;
		document.getElementById('FrameOpener').style.left='20px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='850';
		document.getElementById('FrameOpener').style.height='170';
		
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?

if($Plan)
{
	$cons="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GrupoCup[$fila[0]]=$fila[1];	
	}
	$cons="select codigo,tipo from contratacionsalud.tiposservicio where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$TipoCup[$fila[0]]=$fila[1];	
	}
	  
	if($CodCup){$CP=" and cup ilike '$CodCup%'";}
	if($NomCup){$NC=" and nombre ilike '%$NomCup%'";}
	if($GrupCup){$GC=" and grupo ='$GrupCup'";}
	if($TipCup){$TC=" and tipo='$TipCup'";}
	if($SoatCup){$SC=" and soat ilike '$SoatCup%'";}
	if($DetSoatCup){$DSC=" and detallesoat ilike '%$DetSoatCup%'";}
	$cons = "Select CUP,Nombre,Valor,grupo,tipo,soat,detallesoat from ContratacionSalud.CupsXPlanes,ContratacionSalud.CUPS 
	where CUPS.Codigo = CUPSXPlanes.CUP and	AutoId = '$Plan' and CupsXPlanes.Compania='$Compania[0]' and CUPS.compania='$Compania[0]' $CP $NC $GC $TC $SC $DSC
	order by cup";
	$res = ExQuery($cons);
	//echo $cons;
	if(ExNumRows($res)>0)
	{?>
	<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
    	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
        	<td>CUP</td><td>Nombre</td><td>Grupo</td><td>Tipo</td><td>Cod. SOAT</td><td>Det. SOAT</td><td>Valor</td>
        </tr>
        <? while($fila = ExFetch($res))
		{
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><?
			echo "<td>$fila[0]</td><td>$fila[1]</td><td>&nbsp;".$GrupoCup[$fila[3]]."</td><td>&nbsp;".$TipoCup[$fila[4]]."</td><td>&nbsp;$fila[5]</td>
			<td>&nbsp;$fila[6]</td><td align='right'>".number_format($fila[2],2)."</td>";
			?>
            <td width="16px">
           		<img title="Editar" style="cursor:hand" src="/Imgs/b_edit.png" 
                onClick="EditarCup(event,'<? echo $fila[0]?>','<? echo $fila[1]?>','<? echo $fila[2]?>')">
            </td>
			<td width="16px"><a href="#" style="cursor:hand"
               	onclick="if(confirm('Desea eliminar el registro?'))
               	{parent.location.href='CupsxPlanesTarif.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&CUP=<? echo $fila[0]?>&Plan=<? echo $Plan?>';}">
			<img title="Eliminar" border="0" src="/Imgs/b_drop.png"/></a>
			</td>            
            </tr>
			<?
		}
    ?> </table>
<?	}
}
	?>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none;border:#e5e5e5 ridge" frameborder="0" height="1"></iframe>
</body>