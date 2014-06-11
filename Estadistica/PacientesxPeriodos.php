<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$C1="0";}else{$C1="";}
	if($ND[mday]<10){$C2="0";}else{$C2="";}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Regimen(e,Regimenes)
	{		
		x = e.clientX; 
		y = e.clientY;
		st = document.body.scrollTop;
		//alert(Destinatarios);
		frames.FrameOpener.location.href='RegimensPacxPer.php?DatNameSID=<? echo $DatNameSID?>&Regimenes='+Regimenes;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y+st;
		document.getElementById('FrameOpener').style.left=x;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='300';
		document.getElementById('FrameOpener').style.height='400';		
	}
	function Incluir(e,Incluye,Regimenes)
	{		
		x = e.clientX; 
		y = e.clientY;
		st = document.body.scrollTop;
		//alert(Destinatarios);
		frames.FrameOpener.location.href='IncluyePacxPer.php?DatNameSID=<? echo $DatNameSID?>&Regimenes='+Regimenes;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y+st;
		document.getElementById('FrameOpener').style.left=x-80;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='800';
		document.getElementById('FrameOpener').style.height='500';		
	}	
	function Validar()
	{
		if(document.FORMA.FechaIni.value==""){
			alert("Debe seleecionar la fecha inicial!!!");return false;
		}
		else{
			if(document.FORMA.FechaFin.value==""){
				alert("Debe selecionar la fecha final!!!");return false;
			}
			else{
				if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value)
				{
					alert("La fecha inicial debe ser menor a la fecha final!!!");return false;
				}
			}
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()" action="ResultPacientxPer.php" target="ResultPacientxPer">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="4" style='font : normal normal small-caps 12px Tahoma;'>	
	<tr>	
    	<td rowspan="2"><strong>Regimen </strong><input type="button" value="..." onClick="Regimen(event,document.FORMA.AuxRegimenes.value)"></td>
        <input type="hidden" name="AuxRegimenes" value="<? echo $AuxRegimenes?>">
        <td rowspan="2"><strong> Incluir a</strong> <input type="button" value="..." onClick="Incluir(event,document.FORMA.AuxIncluir.value,document.FORMA.AuxRegimenes.value)"></td>
        <input type="hidden" name="AuxIncluir" value="<? echo $AuxIncluir?>">      
        <td colspan="4"  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Periodo</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Ver</td>
        <td rowspan="2">Version Reducia <input type="checkbox" name="VercionRed" <? if($VercionRed){?> checked<? }?>></td>
         <td rowspan="2">Paginacion<input type="checkbox" name="Paginacion" <? if($Paginacion){?> checked<? }?>></td>
        <td rowspan="2"><input type="submit" value="Ver Lista" name="VerLista"></td>
   	</tr>
    <tr>
    	<td><strong>Desde:</strong></td>
  	<?	if(!$FechaIni){$FechaIni="$ND[year]-$C1$ND[mon]-01";}
		if(!$FechaFin){$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";}?>
        <td><input type="text" name="FechaIni" readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:80" value="<? echo $FechaIni?>"></td>
        <td><strong>Hasta:</strong></td>
        <td><input type="text" name="FechaFin" readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:80" value="<? echo $FechaFin?>"></td>
        <td><? //echo $OpcVer;?>
        	<select name="OpcVer">
            	<option value="Todos" <? if($OpcVer=="Todos"){?> selected <? }?>>Todos</option>
                <option value="Solo Ingresos" <? if($OpcVer=="Solo Ingresos"){?> selected <? }?>>Solo Ingresos</option>
                <option value="Solo Egresos" <? if($OpcVer=="Solo Egresos"){?> selected <? }?>>Solo Egresos</option>
               <!-- <option value="Ingresos Netos" <? if($OpcVer=="Ingresos Netos"){?> selected <? }?>>Ingresos Netos</option>-->
                <option value="Pacientes que vienen" <? if($OpcVer=="Pacientes que vienen"){?> selected <? }?>>Pacientes que vienen</option>
                <option value="Hospitalizados del periodo" <? if($OpcVer=="Hospitalizados del periodo"){?> selected <? }?>>Hospitalizados del periodo</option>
            </select>
        </td>       
       
    </tr>    
</table>
<input type="hidden" name="OrdenarPor">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
<iframe frameborder="0" name="ResultPacientxPer" id="ResultPacientxPer" src="ResultPacientxPer.php" width="100%" height="85%"></iframe>
</body>
</html>
