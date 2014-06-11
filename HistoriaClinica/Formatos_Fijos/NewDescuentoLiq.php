<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
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
<?
	if($Guardar){
		if($Edit==1){
			$cons="update facturacion.descuentosliq set usuario='$usuario[1]',fechacrea='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',motivo='$Motivo'
			where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion is null";						
		}
		else{
			$cons="insert into facturacion.descuentosliq (compania,usuario,fechacrea,motivo,cedula) 
			values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Motivo','$Paciente[1]')";
		}
		//echo $cons;
		$res=ExQuery($cons);
		?><script language="javascript">parent.document.FORMA.submit();</script><?
	}
	if($Edit==1){
		$cons="select motivo from facturacion.descuentosliq where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion is null";
		//echo $cons;
		$res=ExQuery($cons); $fila=ExFetch($res); $Motivo=$fila[0];
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Motivo.value==""){
			alert("Debe digitar el motivo!!!"); return false;
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Motivo</td>
    </tr>
    <tr>
    	<td>
	    	<textarea name="Motivo" cols="30" rows="8" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"><? echo $Motivo?></textarea>
      	</td>
    </tr>
    <tr>
    	<td align="center">
        	<input type="Submit" value="Guardar" name="Guardar">
        </td>
    </tr>
</table> 
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
