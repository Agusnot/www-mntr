<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Guardar){
		$cons="update salud.multas set estado='AN',usulev='$usuario[1]',fechalevanta='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',levanta='$Cancela',
		origenlev='$Origen',motivolev='$Motivo'
		where compania='$Compania[0]' and cedula='$Cedula' and fechacrea='$Fecha' and valor=$Valor and entidad='$EPS'";
		$res=ExQuery($cons);
		?>
        <script language="javascript">
			parent.document.FORMA.submit();
		</script>
        <?
	}
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
		parent.document.FORMA.submit();
	}
	function Validar()
	{
		if(document.FORMA.Origen.value==""){alert("Debe seleccionar el origen del levantamiento de la multa!!!");return false;}
		if(document.FORMA.Motivo.value==""){alert("Debe seleccionar el motivo del levantamiento de la multa!!!");return false;}
		if(document.FORMA.Cancela.value==""){alert("Debe digitar el nombre de quien levanta la multa!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg" onload='document.FORMA.Origen.focus()'>
<form name="FORMA" method="post" onSubmit="return Validar()">
<?

?>
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td colspan="2" align="right"><button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button></td>
    </tr>
	<tr>
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Origen Levantamiento</td>
  	<?	$cons="select origen from salud.origenlevantamientomulta where compania='$Compania[0]'";
		$res=ExQuery($cons);?>
        <td>
        	<select name="Origen" onChange="document.FORMA.submit();"><option></option>
           	<?	while($fila=ExFetch($res))
				{
					if($Origen==$fila[0])
					{echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else
					{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>	
   	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Motivo Levantamiento</td>
  	<?	$cons="select motivo from salud.motivolevantamientomulta where compania='$Compania[0]' and origen='$Origen'";
		$res=ExQuery($cons);?>
        <td>
        	<select name="Motivo">
           	<?	while($fila=ExFetch($res))
				{
					if($Motivo==$fila[0])
					{echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else
					{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>	
   	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Nombre Quien Cancela</td>
        <td>
        	<input type="text" name="Cancela" onKeyDown="xLetra(This)" onKeyPress="xLetra(This)" onKeyUp="xLetra(This)" value="<? echo $Cancela?>" style="width:200">
        </td>
  	</tr>
    <tr align="center">
    	<td colspan="2"><input type="submit" value="Guardar" name="Guardar"></td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Cedula" value="<? echo $Cedula?>">
<input type="hidden" name="Fecha" value="<? echo $Fecha?>">
<input type="hidden" name="Valor" value="<? echo $Valor?>">
<input type="hidden" name="EPS" value="<? echo $EPS?>">
</form>    
</body>
</html>
