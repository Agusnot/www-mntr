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
		if(document.FORMA.Opcion.value=="1"){			
			open('ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&IdHistoria=<? echo $Id_Historia?>&Titulo='+document.FORMA.Titulo.value+'&Opc=1','','');
		}
		if(document.FORMA.Opcion.value=="2")
		{
			if(document.FORMA.FechaIni.value=="")
			{
				alert("Debe seleccionar la fecha Inicial!!!");
			}
			else
			{
				if(document.FORMA.FechaFin.value=="")
				{
					alert("Debe seleccionar la fecha Final!!!");
				}
				else{
					if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value)
					{
						alert("La fehca inicial debe ser menor o igual a la fecha final!!!");
					}
					else{
						open('ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&Titulo='+document.FORMA.Titulo.value+'&TipoFormato=<? echo $TipoFormato?>&Opc=2&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value,'','');
					}
				}
			}
		}
		if(document.FORMA.Opcion.value=="3"){
			open('ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&Titulo='+document.FORMA.Titulo.value+'&TipoFormato=<? echo $TipoFormato?>&Opc=3','','');
		}
		CerrarThis();
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
	<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
		<tr>
        	<td><input type="radio" name="ImpHC" value="1" onClick="DesHabilitaPerido();document.FORMA.Opcion.value=1;" /> Esta Nota</td>    	            	
	  	</tr> 
        <tr>
        	<td><input type="radio" name="ImpHC" value="2" onClick="HabilitaPeriodo();document.FORMA.Opcion.value=2;"/> Peridodo</td>
        </tr>
        <tr>
        	<td>Desde: <input type="text" name="FechaIni" readonly style=" width:80" disabled="disabled" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"/> 
            	Hasta: <input type="text" name="FechaFin" readonly style="width:80" disabled="disabled" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"/></td>
        </tr>
        <tr>
        	<td><input type="radio" name="ImpHC" value="3" onClick="DesHabilitaPerido();document.FORMA.Opcion.value=3;"/> Todo el Formato</td>
        </tr>
        <tr>
        <?	$cons="select titulo from historiaclinica.titulosxformato where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFormato'";
			$res=ExQuery($cons);			?>
        	<td>Titulo: 
            <select name="Titulo"><option></option>
     	<?	while($fila=ExFetch($res))
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}?>
            </select>
            </td>
        </tr> 
        <input type="hidden" name="Opcion"/>
        <tr align="center"> 
        	<td><input type="button" value="Imprimir" onClick="Validar()"/></td>
        </tr>
   	</table>
</form>    
</body>
</html>
