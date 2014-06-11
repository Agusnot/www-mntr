<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
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
	function Validar()
	{		
		if(document.FORMA.Hasta.value==""){
			alert("Debe digitar la fecha fin!!!");return false;
		}
		else{
			if(document.FORMA.Inicio.value>document.FORMA.Hasta.value){				
				alert("La fecha final debe ser mayor o igual a la fecha inicial !!!");return false;
			}
		}
	}
</script>
<?
	if($Guardar)
	{
		$cons="update salud.tmppagadorxfactura set fechafin='$Hasta' 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and entidad='$EPS' and contrato='$Contra' and nocontrato='$NoContra' 
		and fechaini='$Inicio' and tmpcod='$TMPC'";
		echo $cons."<br>";
		$res=ExQuery($cons);		
		$cons="update salud.tmppagadorxfactura set fechafin='$Hasta' 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and fechaini='$Inicio' and tmpcod='$TMPC' and tipo=2";
		$res=ExQuery($cons);		
		echo $cons."<br>";
	?>	<script language="javascript">
			if(parent.document.FORMA.NoEnvia){
				parent.document.FORMA.NoEnvia.value=1;
			}
			parent.document.FORMA.submit();	
			CerrarThis();
     	</script><?
	}
?>
</head>

<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="EPS" value="<? echo $EPS?>">
<input type="hidden" name="Contra" value="<? echo $Contra?>">
<input type="hidden" name="NoContra" value="<? echo $NoContra?>">
<input type="hidden" name="Inicio" value="<? echo $Inicio?>">
<input type="hidden" name="TMPC" value="<? echo $TMPC?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" > 
	<tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td>                
    </tr>    
    <tr>
    <?	if(!$Hasta){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$Hasta="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
    	<td><input type="text" readonly name="Hasta" onClick="popUpCalendar(this, FORMA.Hasta, 'yyyy-mm-dd')" value="<? echo $Hasta?>"></td>
    </tr>
    <tr align="center">
    	<td><input type="submit" name="Guardar" value="Guardar"></td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
