<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Cedula){
		$cons="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$Cedula'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		//echo $cons;
		if($fila){ 
			$Nom="$fila[0] $fila[1] $fila[2] $fila[3]";
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Cedula.value==""){
			alert("Debe digitar la cedula del paciente!!!"); return false;	
		}
		if(document.FORMA.Desde.value==""&&document.FORMA.Hasta.value!=""){
			alert("Debe seleccionar tanto la fecha inicial como la fecha final!!!"); return false;	
		}
		if(document.FORMA.Desde.value!=""&&document.FORMA.Hasta.value==""){
			alert("Debe seleccionar tanto la fecha inicial como la fecha final!!!"); return false;	
		}
		if(document.FORMA.Desde.value>document.FORMA.Hasta.value){
			alert("La fecha inicial debe ser menor o igual a la fecha final!!!"); return false;		
		}	
	}
	function ValidaDocumento(Objeto){
		frames.FrameOpener.location.href="/Admision/Agenda/ValidaDocumentoAgenda.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='90px';
		document.getElementById('FrameOpener').style.left='325px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='390';
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td>
        <td>
        	<input type="text" name="Cedula" onKeyDown="xLetra(this)" onFocus="ValidaDocumento(this)"
            onKeyPress="ValidaDocumento(this);xLetra(this);" onKeyUp="ValidaDocumento(this);xLetra(this);" value="<? echo $Cedula?>"
            style="width:90">
        </td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
        <?	if(!$Desde){
				$Desde="$ND[year]-01-01";
			}
			if(!$Hasta){
				if($ND[mon]<10){$C1="0";}else{$C1="";}
				if($ND[mday]<10){$C2="0";}else{$C2="";}
				$Hasta="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
			}?>
        <td><input type="Text" name="Desde"  readonly onClick="popUpCalendar(this, FORMA.Desde, 'yyyy-mm-dd')" value="<? echo $Desde?>" style="width:80"></td>       
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td><input type="Text" name="Hasta"  readonly onClick="popUpCalendar(this, FORMA.Hasta, 'yyyy-mm-dd')" value="<? echo $Hasta?>" style="width:80"></td>       
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Estado</td>
        <td>
        	<select name="Estado">
            	<option></option>
                 <option value="Activa" <? if($Estado=="Activa"){?> selected<? }?>>Activa</option>
                <option value="Atendida" <? if($Estado=="Atendida"){?> selected<? }?>>Atendida</option>
                <option value="Cancelada" <? if($Estado=="Cancelada"){?> selected<? }?>>Cancelada</option>
                <option value="Pendiente" <? if($Estado=="Pendiente"){?> selected<? }?>>Pendiente</option>
            </select>
        </td>
        <td>
        	<input type="submit" name="Ver" value="Ver">
        </td>
    </tr>
</table>
<br>

<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
<?
if($Cedula){
	if($Desde){$De="and fecha >='$Desde'";}
	if($Hasta){$Ha="and fecha <='$Hasta'";}
	if($Estado){$Es="and estado='$Estado'";}
	$cons="select fecha,hrsini,minsini,nombre,estado,origencancel,motivocancel from salud.agenda,central.usuarios
	where agenda.compania='$Compania[0]' and agenda.cedula='$Cedula' and usuarios.usuario=medico $De $Ha $Es
	order by fecha desc";
	//echo $cons;
	$res=ExQuery($cons);?>
    <tr gcolor="#e5e5e5" style="font-weight:bold" align="center">
        <td colspan="4"><? echo $Nom." - ".$Cedula?></td>
    </tr>
<?	if(ExNumRows($res)>0){?>
        <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
            <td>Dia</td><td>Hora</td><td>Medico</td><td>Estado</td>
        </tr><?
        while($fila=ExFetch($res))
        {
            if($fila[2]<10){$C="0";}else{$C="";}
            if($fila[4]=="Atendida"){$fila[4]="Cita Atendida";}
            if($fila[4]=="Cancelada"){$fila[4]="Cita Cancelada Por: $fila[5] - Debido a: $fila[6]";}
            echo "<tr align='center'><td>$fila[0]</td><td>$fila[1]:$C$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td></tr>";
		}
	}
	else{
		echo "<tr><td>ESTE PACIENTE NO TIENE CITAS PREVIAS</TD></TR>";	
	}
}?>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
</body>
</html>