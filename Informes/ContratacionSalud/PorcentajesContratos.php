<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../../Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" action="PorcentajesContratos.php">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="4" style='font : normal normal small-caps 12px Tahoma;'>	
    <tr>
    	<td><strong>% de ejecuci&oacute;n:</strong></td>
        <td>
        	<select name="OpcVer">
            	<option value="">Seleccionar</option>
				<option value="1" <?if($OpcVer==1)echo "selected='selected'";?>>50%</option>
                <option value="2" <?if($OpcVer==2)echo "selected='selected'";?>>70%</option>
                <option value="3" <?if($OpcVer==3)echo "selected='selected'";?>>90%</option>
                <option value="4" <?if($OpcVer==4)echo "selected='selected'";?>>100%</option>
            </select>
        </td><td><input type="submit" value="Generar"></td>       
    </tr>    
</table>
</form>
</body>
</html>

    <?
	switch($OpcVer){
	case '':
	$Por="porcentajeejecutado between '50' and '50.9' or porcentajeejecutado between '70' and '70.9' OR porcentajeejecutado between '90' and '90.9' OR porcentajeejecutado between '99.9' and '100' and estado='AC'";
	break;
	case 1:
	$Por="porcentajeejecutado between '50' and '50.9' and estado='AC'";
	break;
	case 2:
	$Por="porcentajeejecutado between '70' and '70.9' and estado='AC'";
	break;
	case 3:
	$Por="porcentajeejecutado between '90' and '90.9' and estado='AC'";
	break;
	case 4:
	$Por="porcentajeejecutado between '99.9' and '100' and estado='AC'";
	break;
	}
	$cons ="select entidad, nomresppago, contrato, numero, tipoasegurador, fechaini, fechafin, monto, mttoejecutado, porcentajeejecutado, estado  from contratacionsalud.contratos 
	inner join central.terceros on contratacionsalud.contratos.entidad=central.terceros.identificacion where $Por";
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
			<td>Estado</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?><td><?echo $fila[1]?></td><td><?echo $fila[2]?></td><td><?echo $fila[3]?></td>
                    <td><?echo $fila[4]?></td><td><?echo $fila[5]?></td><td><?echo $fila[6]?></td><td><?echo number_format($fila[7],2)?></td>
                    <td><?echo number_format($fila[8],2)?></td><td><?echo $fila[9]?></td></td><td><?echo $fila[10]?></td>
                </tr><?
    $count++;}
    ?>
</table>