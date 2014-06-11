<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	$cons="select nombre,usuario from central.usuarios";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Usus[$fila[1]]=$fila[0];
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){alert("La fecha inicial debe ser menor a la fecha final!!!");return false;}
	}
	function ValidaDocumento(Objeto){
		frames.FrameOpener.location.href="/Admision/Agenda/ValidaDocumentoAgendaInforme.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='90px';
		document.getElementById('FrameOpener').style.left='325px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='250';
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">   
<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>" onFocus="Ocultar()" align="center">
	<tr align="center">
    	<td colspan="4" bgcolor="#e5e5e5" style="font-weight:bold">Periodo</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Especialidad</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Medico</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Estado</td>
        <td rowspan="8">
        	<input type="submit" value="Ver" name="Ver">
        </td>
  	</tr>
    <tr align="center">
    	<td bgcolor="#e5e5e5" align="center">Desde</td>
   	<?	if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
        <td ><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaIni?>"
        	onChange="document.FORMA.submit()">
       	</td>
        <td bgcolor="#e5e5e5" align="center">Hasta</td>
        <td><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaFin?>"
        	onChange="document.FORMA.submit()" >
      	</td> 
        <td>
        <?	$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
			$res=ExQuery($cons);?>
            <select name="Especialidad" onChange="document.FORMA.submit()">
            	<option></option>
            	<option value="Todas" <? if($Especialidad=="Todas"){?> selected<? }?>>Todas</option>
            <?	while($fila=ExFetch($res))
				{
					if($Especialidad==$fila[0]){
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
				}?>
            </select>
        </td>
        <td align="center">
        <?	if($Especialidad!=""&&$Especialidad!="Todas"){$Esp="and especialidad='$Especialidad'";}
			$cons="select usuarios.nombre,usuarios.usuario,cargo,especialidad from central.usuarios,salud.medicos 
			where compania='$Compania[0]' and usuarios.usuario=medicos.usuario $Esp order by usuarios.nombre";
			$res=ExQuery($cons);?>
            <select name="Medico" onChange="document.FORMA.submit()">
            	<option></option>
                <option value="Todos" <? if($Medico=="Todos"){?> selected<? }?>>Todos</option>
            <? 	while($fila=ExFetch($res))
               	{
                 	if($fila[1]==$Medico){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";
					} 
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					} 
                }?>
         	</select>
        </td>               
       	<td>
        	<select name="Estado" onChange="document.FORMA.submit()"><option></option>
            	<option value="Atendida" <? if($Estado=="Atendida"){?> selected<? }?>>Atendida</option>
                <option value="Activa" <? if($Estado=="Activa"){?> selected<? }?>>Activa</option>
                <option value="Cancelada" <? if($Estado=="Cancelada"){?> selected<? }?>>Cancelada</option>
                <option value="Pendiente" <? if($Estado=="Pendiente"){?> selected<? }?>>Pendiente</option>
            </select>
        </td>
    </tr> 
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="5">Entidad</td><td>Origen Cancelacion</td><td>Motivo Cancelacion</td>
   	</tr>
  	<tr>
    	<td colspan="5" align="center">
        <?	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom ) as nom,identificacion from central.terceros where compania='$Compania[0]' and tipo='Asegurador'
			order by nom";
			$res=ExQuery($cons);?>
             <select name="Entidad" onChange="document.FORMA.submit()"><option></option>
            <? 	while($fila=ExFetch($res))
               	{
                 	if($fila[1]==$Entidad){
						echo "<option value='$fila[1]' selected>$fila[0]</option>";
					} 
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					} 
                }?>
         	</select>
        </td> 
       	<td align="center">
        <?	$cons="select origencancelacion from salud.origencancelcita where compania='$Compania[0]' order by origencancelacion";
			$res=ExQuery($cons);?>
           	<select name="OrgCancelacion" onChange="document.FORMA.submit()"><option></option>
            <? 	while($fila=ExFetch($res))
               	{
                 	if($fila[0]==$OrgCancelacion){
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					} 
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					} 
                }?>
         	</select>
        </td>
        <td align="center">
        <?	if($OrgCancelacion){$OrgCan=" and origencalcel='$OrgCancelacion'";}
			$cons="select motivocancelcita from salud.motivocancelcita where compania='$Compania[0]' $OrgCan
			order by motivocancelcita";
			$res=ExQuery($cons);?>
           	<select name="MotvCancelacion" onChange="document.FORMA.submit()"><option></option>
            <? 	while($fila=ExFetch($res))
               	{
                 	if($fila[0]==$MotvCancelacion){
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					} 
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					} 
                }?>
         	</select>
        </td>
    </tr>
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="6">CUP</td><td>Cedula</td>
   	</tr>
    	<td colspan="6" align="center">     	
        <?	if($Entidad){$Ent="and entidad='$Entidad'";}
			$cons="select nombre,cup from salud.agenda,contratacionsalud.cups where cups.compania='$Compania[0]' and agenda.compania='$Compania[0]' and codigo=cup $Ent
			group by nombre,cup order by nombre";
			$res=ExQuery($cons);?>
            <select name="CUP" onChange="document.FORMA.submit()"  style=" width: 600px">
            	<option></option>
            <? 	while($fila=ExFetch($res))
               	{
                 	if($fila[1]==$CUP){
						echo "<option value='$fila[1]' selected title='$fila[0]'>$fila[0]</option>";
					} 
					else{
						echo "<option value='$fila[1]' title='$fila[0]'>$fila[0]</option>";
					} 
                }?>
         	</select>
        </td>
        <td align="center">
        	<input type="text" name="Cedula" value="<? echo $Cedula?>"  onFocus="ValidaDocumento(this)"  
        	onKeyUp="ValidaDocumento(this);xLetra(this)" onKeyDown="xLetra(this)">
        </td>    
    <tr><td colspan="6">&nbsp;</td>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Confirmacion</td>
    </tr>
    <tr align="center"><td colspan="6">&nbsp;</td>
    	<td>
        	<select name="Confirmacion" onChange="document.FORMA.submit()">
            	<option></option>
                <option value="Si" <? if($Confirmacion=="Si"){?> selected<? }?>>Si</option>
                <option value="No" <? if($Confirmacion=="No"){?> selected<? }?>>No</option>
            </select>
        </td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
<? 
if($Ver){?> 
	<iframe frameborder="0" id="Multas" src="ResultInfoAgenda.php?DatNameSID=<? echo $DatNameSID?>&Estado=<? echo $Estado?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Medico=<? echo $Medico?>&Entidad=<? echo $Entidad?>&Especialidad=<? echo $Especialidad?>&CUP=<? echo $CUP?>&OrgCancelacion=<? echo $OrgCancelacion?>&MotvCancelacion=<? echo $MotvCancelacion?>&Cedula=<? echo $Cedula?>&Confirmacion=<? echo $Confirmacion?>&Ver=<? echo $Ver?>" width="100%" height="85%"></iframe><?
}
?>    
</body>
</html>
