<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	function VerDatosFormato(e,Num){	
		y = e.clientY; 
		x = e.clientX; 
		st = document.body.scrollTop;	
		parent.frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerDatosFormato.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top=y-20+st;
		parent.document.getElementById('FrameOpener').style.left='10%';
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='800';
		parent.document.getElementById('FrameOpener').style.height='550';
	}
	function VerReporte(e,Num){			
		y = e.clientY; 
		x = e.clientX; 
		st = document.body.scrollTop;
		parent.frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerDatosHallazgos.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top=y-40+st;
		parent.document.getElementById('FrameOpener').style.left='10%';
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='800';
		parent.document.getElementById('FrameOpener').style.height='400';
	}
	function VerAcciones(e,Num){			
		y = e.clientY; 
		x = e.clientX; 
		st = document.body.scrollTop;
		parent.frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerAcciones.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top=y-40+st;
		parent.document.getElementById('FrameOpener').style.left='10%';
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='850';
		parent.document.getElementById('FrameOpener').style.height='360';
	}
	function VerSeguimietno(e,Num){			
		y = e.clientY; 
		x = e.clientX; 
		st = document.body.scrollTop;
		parent.frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerSeguimiento.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top=y-60+st;
		parent.document.getElementById('FrameOpener').style.left='10%';
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='850';
		parent.document.getElementById('FrameOpener').style.height='400';
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td><input type="radio" name="Informe" onClick="VerDatosFormato(event,'<? echo NumRep?>')"> Formato Paciente Seguro</td>
  	</tr>
    <tr>
    	<td><input type="radio" name="Informe" onClick="VerReporte(event,'<? echo NumRep?>')"> Reporte de Hallazgos</td>
    </tr>
<?	if($Caso==2){?>    
		<tr>
        	<td><input type="radio" name="Informe" onClick="VerAcciones(event,'<? echo NumRep?>')"> Acciones Propuestas</td>           
        </tr>
<?	}
	if($Caso==3){?>    
    	<tr>
        	<td><input type="radio" name="Informe" onClick="VerAcciones(event,'<? echo NumRep?>')"> Acciones Propuestas</td>           
        </tr>
		<tr>
        	<td><input type="radio" name="Informe" onClick="VerSeguimietno(event,'<? echo NumRep?>')"> Seguimiento Caso</td>           
        </tr>
<?	}?>
</table>
</form>    
</body>
</html>
