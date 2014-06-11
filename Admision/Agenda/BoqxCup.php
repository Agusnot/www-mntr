<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Cups[$fila[0]]=$fila[1];	
	}
	if($CodigoElim)
	{
		$cons="update salud.dispoconsexterna set cuppermitido=NULL where compania='$Compania[0]' and horaini='$HoraIni' and minsinicio='$MinIni'
		and usuario='$Profecional' and fecha='$Fecha' and  cuppermitido='$CodigoElim' ";
		//echo $cons;
		$res=ExQuery($cons);
		
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Especialidad.value==""){alert("Debe seleccionar una especialidad!!!");return false;}
		if(document.FORMA.Profecional.value==""){alert("Debe seleccionar un profecional!!!");return false;}
	}
	function raton(e,HoraIni,MinIni,Especialidad,Profecional,Fecha) { 
		x = e.clientX; 
		y = e.clientY; 	
		st = document.body.scrollTop;
		frames.FrameOpener2.location.href="CupBloqueo.php?DatNameSID=<? echo $DatNameSID?>&HoraIni="+HoraIni+"&MinIni="+MinIni+"&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>";
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top=y+st-25;
		document.getElementById('FrameOpener2').style.left=10;
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='860px';
		document.getElementById('FrameOpener2').style.height='300px';
	} 
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table align="center">
<tr><td>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Especialidad</td>
        <td><? $cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
			$res=ExQuery($cons);?>		
        <select name="Especialidad" onChange="document.FORMA.submit()">
        <option></option>
        <? while($fila=ExFetch($res))
			{
				if($fila[0]==$Especialidad){?>
					<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
			<?	}
				else{?>
					<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
			<?	}				
			}
		?>
        </select></td>
    </tr>
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Profesional</td>
    	<td>
	<? $res=ExQuery("Select nombre,Medicos.usuario as usu from Salud.Medicos,central.usuarios 
					where Especialidad='$Especialidad' and Medicos.usuario=usuarios.usuario and Compania='$Compania[0]' and estadomed='Activo'
					order by usu");?>
    	<select name="Profecional" onChange="document.FORMA.submit()"><option></option>
   <?	if($Especialidad!=''){
   			while($fila=ExFetch($res)){
            	if($fila[1]==$Profecional){?>
	   				<option value="<? echo $fila[1]?>" selected><? echo $fila[0]?></option>
           <? 	}else{?>
           			<option value="<? echo $fila[1]?>"><? echo $fila[0]?></option>
   	<?			}
			}
		}?>
        </select>
       </td>
    </tr>
    <tr>
    <?	if(!$Fecha){
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			$Fecha="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";}?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha</td>
        <td><input type="text" name="Fecha" value="<? echo $Fecha?>" readonly onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')"></td>
  	</tr>
    <tr align="center">
    	<td colspan="2"><input type="submit" value="Ver" name="Ver" /></td>
    </tr>
</table>
<br />
<?
if($Ver)
{?>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<?	$cons="select horaini,minsinicio,horasfin,minsfin,idhorario,cuppermitido from salud.dispoconsexterna 
	where compania='$Compania[0]' and usuario='$Profecional' and fecha='$Fecha' order by horaini,minsinicio";
	$res=ExQuery($cons);?>
    <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Hora Incial</td><td>Hora final</td><td>CUP Permitido</td><td></td>
    </tr>
<?	while($fila=ExFetch($res))
	{
		if($fila[1]<10){$C3="0";}else{$C3="";}
		if($fila[3]<10){$C4="0";}else{$C4="";}?>
		<tr align="center">
        	<td><? echo "$fila[0]:$C3$fila[1]";?></td><td><? echo "$fila[2]:$C4$fila[3]";?></td><td><? echo "$fila[5] - ".$Cups[$fila[5]];?>&nbsp;</td>
            <td>
         <?	if(!$fila[5]){?>
            	<img src="/Imgs/s_process.png" title="Restringir" style="cursor:hand"
            	onClick="raton(event,'<? echo $fila[0]?>','<? echo $fila[1]?>','<? echo $Especialista?>','<? echo $Profecional?>','<? echo $Fecha?>')"/>
      	<?	}
			else{?>
				<img src="/Imgs/b_drop.png" title="Eliminar Restriccion" style="cursor:hand"
                onClick="if(confirm('¿Esta seguro de eliminar esta restriccion?')){location.href='BoqxCup.php?DatNameSID=<? echo $DatNameSID?>&CodigoElim=<? echo $fila[5]?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HoraIni=<? echo $fila[0]?>&MinIni=<? echo $fila[1]?>&Ver=1';}">
		<?	}?></td>
        </tr>	
<?	}?>
    </table><?
}?>
<iframe scrolling="no" id="FrameOpener2" name="FrameOpener2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>    
<input type="hidden" name="CodigoElim" value="">
</form>  
</body>
</html>