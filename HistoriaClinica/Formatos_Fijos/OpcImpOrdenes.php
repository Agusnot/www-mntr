<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
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
		//parent.document.FORMA.submit();
	}
	function HabilitaPeriodo()
	{
		document.FORMA.FechaIni.disabled=false;
		document.FORMA.FechaFin.disabled=false;
	}
	function DesHabilitaPerido()
	{
		document.FORMA.FechaIni.disabled=true;
		document.FORMA.FechaFin.disabled=true;
	}
	function Validar()
	{
		if(document.FORMA.Opcion.value=="Actual"){			
			open('ImprimeOrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&Radios=<? echo $Radios?>&IdEsc=<? echo $IdEsc?>&Opcion='+document.FORMA.Opcion.value,'','');
		}
		if(document.FORMA.Opcion.value=="Periodo")
		{
			if(document.FORMA.FechaIni.value=="")
			{
				alert("Debe seleccionar la fecha Inicial!!!");return false;
			}
			else
			{
				if(document.FORMA.FechaFin.value=="")
				{
					alert("Debe seleccionar la fecha Final!!!");return false;
				}
				else{
					if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value)
					{
						alert("La fecha inicial debe ser menor o igual a la fecha final!!!");return false;
					}
					else{
						open('ImprimeOrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&Radios=<? echo $Radios?>&IdEsc=<? echo $IdEsc?>&Opcion='+document.FORMA.Opcion.value+'&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value,'','');
					}
				}
			}
		}
		if(document.FORMA.Opcion.value=="Todas"){
			open('ImprimeOrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&Radios=<? echo $Radios?>&IdEsc=<? echo $IdEsc?>&Opcion='+document.FORMA.Opcion.value,'','');
		}
		CerrarThis();
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Opcion" >
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
	<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
		<tr>
        	<td colspan="3"><input type="radio" name="ImpOrden" value="Actual" onClick="DesHabilitaPerido();document.FORMA.Opcion.value='Actual';" /> Esta Orden</td>    	            	
	  	</tr> 
        <tr>
        	<td style="width:75" rowspan="2"><input type="radio" name="ImpOrden" value="Periodo" onClick="HabilitaPeriodo();document.FORMA.Opcion.value='Periodo';"/> Peridodo</td>
            <td style="width:71">Desde:</td>
            <td>Hasta</td>
        </tr>
        <tr>
        <td> <input type="text" name="FechaIni" readonly style=" width:70" disabled="disabled" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"/></td> 
        <td><input type="text" name="FechaFin" readonly style="width:70" disabled="disabled" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"/></td>
        </tr>
        <tr>
        	<td colspan="3"><input type="radio" name="ImpOrden" value="Todas" onClick="DesHabilitaPerido();document.FORMA.Opcion.value='Todas';"/> Todas las Ordenes</td>
        </tr>       
   	</table>
    <center><input type="button" value="Imprimir" onClick="Validar()"/></center>
</form>    
</body>
</html>
