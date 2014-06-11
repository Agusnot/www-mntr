<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");		
?>
<html>
<head>
</head>
<body background="/Imgs/Fondo.jpg">

<br />
<br />
<table align="center">

<tr><td><em>
		<input type="radio" 
        onClick="open('RptAgendaxMedico.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>&Medico=<? echo $Medico?>&Especialidad=<? echo $Especialidad?>','','width=1100,height=600')">Agenda x Medico</em>
    </td>
</tr> 
<tr><td><em><input type="radio" onClick="open('RptAgendaGeneral.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>','','width=1100,height=600')">Agenda General</em></td></tr>
<tr><td><em><input type="radio" onClick="open('RptAgendaPacienteDia.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>','','width=1100,height=600')">Pacientes agendados del dia</em></td></tr>
</table>
</body>
</head>