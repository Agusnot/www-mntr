<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select usuario,cargo,especialidad from salud.medicos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Meds[$fila[0]]=array($fila[1],$fila[2]);		
	}
	$cons="select nombre,usuario from central.usuarios order by nombre";
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
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

	function ChequearTodos(chkbox) { 
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

<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.NomUsu.focus();">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="1"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td colspan="10" align="right"><button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button></td>
    </tr>
    <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Nombre</td><td>Cargo</td><td>Especialidad</td>
    </tr>
    <tr align="center">
    	<td>
    		<input type="text" name="NomUsu" value="<? echo $NomUsu?>" style="width:250" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.ResultDestinatarios.location.href='ResultDestinatarios.php?DatNameSID=<? echo $DatNameSID?>&Destinatarios=<? echo $Destinatarios?>&Nombre='+this.value+'&Cargo='+Cargo.value+'&Espec='+Especialidad.value;">
      	</td>
        <td>
        <?	$cons="select cargos from salud.cargos where compania='$Compania[0]' order by cargos";
			$res=ExQuery($cons);?>
        	<select name="Cargo" onChange="frames.ResultDestinatarios.location.href='ResultDestinatarios.php?DatNameSID=<? echo $DatNameSID?>&Destinatarios=<? echo $Destinatarios?>&Nombre='+NomUsu.value+'&Cargo='+this.value+'&Espec='+Especialidad.value;">
            	<option></option>
            <?	while($fila=ExFetch($res))
				{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}  ?>
            </select>
        </td>
         <td>
        <?	$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
			$res=ExQuery($cons);?>
        	<select name="Especialidad" onChange="frames.ResultDestinatarios.location.href='ResultDestinatarios.php?DatNameSID=<? echo $DatNameSID?>&Destinatarios=<? echo $Destinatarios?>&Nombre='+NomUsu.value+'&Cargo='+Cargo.value+'&Espec='+Especialidad.value;">
            	<option></option>
            <?	while($fila=ExFetch($res))
				{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}  ?>
            </select>
        </td>
   	</tr>
    
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
<iframe frameborder="0" id="ResultDestinatarios" src="ResultDestinatarios.php?DatNameSID=<? echo $DatNameSID?>&Destinatarios=<? echo $Destinatarios?>" width="100%" height="85%"></iframe>
</body>
</html>