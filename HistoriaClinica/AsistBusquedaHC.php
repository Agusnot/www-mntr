<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<html>
<head>
<meta http-equiv="Content-Type"/>
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
	function Asignar(V,T,M)
	{
		switch(T)
		{		
			case 'Ambito'	:	parent.parent(0).document.FORMA.Ambito.value=V; break;
			case 'Und'		:	parent.parent(0).document.FORMA.Unidad.value=V; break;
			case 'Medicotte':	parent.parent(0).document.FORMA.Medicotte.value=V; 
								parent.parent(0).document.FORMA.Medtte.value=M; 
								break;
			case 'Enttidad':	parent.parent(0).document.FORMA.Enttidad.value=V;
								parent.parent(0).document.FORMA.EnttidadID.value=M;
								break;
		}
		CerrarThis();
	}
</script>

<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post">
<br>
<table border="0" bordercolor="#e5e5e5"  style='font : normal normal small-caps 13px Tahoma;' width="100%">
<?
if(1==1)
{
	$Valor=trim($Valor);
	switch($Tipo)
	{	
		case 'Ambito'	:	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' and ambito ilike '$Valor%'"; break;		
		case 'Und'	 	:	$cons="select pabellon from salud.pabellones where compania='$Compania[0]' and pabellon ilike '$Valor%'"; break;
		
		case 'Medicotte':	$cons="select nombre,usuarios.usuario from salud.medicos,central.usuarios,salud.cargos
								   where medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' 
								   and medicos.cargo=cargos.cargos and usuarios.usuario=medicos.usuario 
								   and cargos.tratante=1 and medicos.estadomed='Activo' and usuarios.nombre ilike '$Valor%' order by nombre"; 
								   break;
		case 'Enttidad': $cons="select primape,identificacion from central.terceros where compania='$Compania[0]' and tipo='Asegurador' and primape ilike '$Valor%' order by primape asc";
	} 
	//echo $cons;
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		while($fila=ExFetch($res)){?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="Asignar('<? echo $fila[0]?>','<? echo $Tipo?>','<? echo $fila[1]?>')">
            	<input type="hidden" name="Med" value="<? echo $fila[1]?>">
        		<td><? echo $fila[0]?></td>
	        </tr>	
<?		}
	}
	else
	{?>
		<tr><td bgcolor="#e5e5e5" align="center" style="font-weight:bold">No Hay Registros Coincidentes</td></tr>	
<?	}
}
?>
</table>    
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
