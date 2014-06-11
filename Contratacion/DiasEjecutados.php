<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../Funciones.php");
$ND=getdate();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" action="DiasEjecutados.php">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="4" style='font : normal normal small-caps 12px Tahoma;'>	
    <tr>
    	<td><strong>Estado del Contrato:</strong></td>
        <td colspan="4">
        	<select name="OpcVer" onChange="if(document.FORMA.OpcVer.value==1||document.FORMA.OpcVer.value==0){
											document.getElementById('FIIC').style.display='none';
											document.getElementById('FFIC').style.display='none';}
											else{
											document.getElementById('FIIC').style.display='inline';
											document.getElementById('FFIC').style.display='inline';
											}">
            	<option value="">Seleccionar</option>
				<option value="1" <?if($OpcVer==1)echo "selected='selected'";?>>Todos</option>
				<option value="2" <?if($OpcVer==2)echo "selected='selected'";?>>Activos</option>
                <option value="3" <?if($OpcVer==3)echo "selected='selected'";?>>Inactivos</option>
            </select>
        </td>
		</tr>
		<tr id="FIIC">
		<td><strong>Fecha Inicio Contrato:</strong></td>
		    <td><strong>Desde:</strong></td>
			<td>
                <input type="text" name="FechaIniIC" readonly="readonly" size="6"
                onclick="popUpCalendar(this, FORMA.FechaIniIC, 'yyyy-mm-dd');" value="<?echo $FechaIniIC?>"
                title="Doble click para confirmar la fecha"/>
            </td>
			<td><strong>Hasta:</strong></td>
            <td>
                <input type="text" name="FechaFinIC" readonly="readonly" size="6" 
                onclick="popUpCalendar(this, FORMA.FechaFinIC, 'yyyy-mm-dd')" value="<?echo $FechaFinIC?>"
                title="Doble click para confirmar la fecha"/>
            </td>
	    </tr>
		<tr id="FFIC">
		<td><strong>Fecha Fin del Contrato:</strong></td>
		    <td><strong>Desde:</strong></td>
			<td>
                <input type="text" name="FechaIniFC" readonly="readonly" size="6"
                onclick="popUpCalendar(this, FORMA.FechaIniFC, 'yyyy-mm-dd');" value="<?echo $FechaIniFC?>"
                title="Doble click para confirmar la fecha"/>
            </td>
			<td><strong>Hasta:</strong></td>
            <td>
                <input type="text" name="FechaFinFC" readonly="readonly" size="6" 
                onclick="popUpCalendar(this, FORMA.FechaFinFC, 'yyyy-mm-dd')" value="<?echo $FechaFinFC?>"
                title="Doble click para confirmar la fecha"/>
            </td>
		</tr>
		<tr><td colspan="5" align="center"><input type="submit" onClick="if(document.FORMA.OpcVer.value=='')alert('Selecciones una Opcion de estado')" value="Generar"></td></tr>       
    </tr>    
</table>
</form>
</body>
</html>

    <?
	if($FechaIniIC&&$FechaFinIC)$IniIC="fechaini between '$FechaIniIC' and '$FechaFinIC' and ";
	if($FechaIniIC&&!$FechaFinIC)$IniIC="fechaini between '$FechaIniIC' and '$ND[year]-$ND[mon]-$ND[mday]' and ";
	if(!$FechaIniIC&&$FechaFinIC)$IniIC="fechaini between '$ND[year]-$ND[mon]-$ND[mday]' and '$FechaFinIC' and ";
	//if($FechaFinIC)$FinIC="fechaini between '' and ''";
	if($FechaIniFC&&$FechaFinFC)$IniFC="fechafin between '$FechaIniFC' and '$FechaFinFC' and ";
	if($FechaIniFC&&!$FechaFinFC)$IniFC="fechafin between '$FechaIniFC' and '$ND[year]-$ND[mon]-$ND[mday]' and ";
	if(!$FechaIniFC&&$FechaFinFC)$IniFC="fechafin between '$ND[year]-$ND[mon]-$ND[mday]' and '$FechaFinFC' and ";
	//if($FechaFinFC)$FinFC="fechafin between '' and ''";
	switch($OpcVer){
	case "":
	case 1:
	$Por="porcentajeejecutado between '50' and '50.9' and porcentajeejecutado between '70' and '70.9' and porcentajeejecutado between '90' and '90.9' and porcentajeejecutado between '99.9' and '100' or porcentajedias between '80' and '100' or estado='AC' or estado='AN'";
	break;
	case 2:
	if(!$FechaIniIC&&!$FechaFinIC&&!$FechaIniFC&&!$FechaFinFC)
	   $Por="porcentajeejecutado between '50' and '50.9' and porcentajeejecutado between '70' and '70.9' and porcentajeejecutado between '90' and '90.9' and porcentajeejecutado between '99.9' and '100' or porcentajedias between '80' and '100' or estado='AC' or estado='AC'";
	   else $Por="$IniIC $IniFC estado='AC'";// porcentajeejecutado between '50' and '50.9' and porcentajeejecutado between '70' and '70.9' and porcentajeejecutado between '90' and '90.9' and porcentajeejecutado between '99.9' and '100' and porcentajedias between '80' and '100' or estado='AC'";
	break;
	case 3:
	if(!$FechaIniIC&&!$FechaFinIC&&!$FechaIniFC&&!$FechaFinFC)
	   $Por="porcentajeejecutado between '50' and '50.9' and porcentajeejecutado between '70' and '70.9' and porcentajeejecutado between '90' and '90.9' and porcentajeejecutado between '99.9' and '100' or porcentajedias between '80' and '100' or estado='AC' or estado='AN'";
	   else $Por="$IniIC $IniFC estado='AN'";// porcentajeejecutado between '50' and '50.9' and porcentajeejecutado between '70' and '70.9' and porcentajeejecutado between '90' and '90.9' and porcentajeejecutado between '99.9' and '100' and porcentajedias between '80' and '100' or estado='AN'";
	break;
	}
	$cons ="select entidad, nomresppago, contrato, numero, fechaini, fechafin, monto, mttoejecutado, porcentajeejecutado, porcentajedias, diastranscurridocontrato, estado,tipoasegurador  from contratacionsalud.contratos inner join central.terceros on central.terceros.identificacion=contratacionsalud.contratos.entidad where $Por order by porcentajedias desc";
    $res = ExQuery($cons);
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>No.</td>
			<td>Entidad</td>
            <td>Contrato</td>
			<td>N&uacute;mero Contrato</td>
			<td>Tipo Asegurador</td>
			<td>Fecha Inicio</td>
            <td>Fecha Final</td>
			<td>Monto</td>
			<td>Monto Ejecutado</td>
			<td>% Ejecutado</td>
			<td>% Dias Ejecutados</td>
			<td>Dias Ejecutados</td>
			<td>Estado</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?><td><?echo $fila[1]?></td><td><?echo $fila[2]?></td><td><?echo $fila[3]?></td><td><?echo $fila[12]?></td>
                    <td><?echo $fila[4]?></td><td><?echo $fila[5]?></td><td><?echo number_format($fila[6],2)?></td>
                    <td><?echo number_format($fila[7],2)?></td><td><?echo $fila[8]?></td></td><td><?echo $fila[9]?></td>
					<td><?echo $fila[10]?></td><td><?echo $fila[11]?></td>
                </tr><?
    $count++;}
    ?>
</table>
<script>if(document.FORMA.OpcVer.value==1||document.FORMA.OpcVer.value==0){
											document.getElementById('FIIC').style.display='none';
											document.getElementById('FFIC').style.display='none';
											
											document.getElementById('FIIC').style.display='none';
											document.getElementById('FFIC').style.display='none';}</script>