<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	
	$cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]'"; 
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$ConsCups[$fila[0]]=$fila[1];	
	}
	$cons="select (primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom ),
	pagadorxservicios.entidad,pagadorxservicios.contrato,pagadorxservicios.nocontrato,
	direccion,telefono,tipoasegurador,codigosgsss,planbeneficios,planservmeds
	from salud.pagadorxservicios,central.terceros,contratacionsalud.contratos
	where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and
	pagadorxservicios.entidad=identificacion and NumServicio = $Numero
	and contratos.compania='$Compania[0]' and contratos.entidad=pagadorxservicios.entidad and pagadorxservicios.contrato=contratos.contrato
	and numero=nocontrato";
	$res=ExQuery($cons);
	$Cliente=Exfetch($res);
	
	$cons="select cedula,(primape || ' ' || segape || ' ' ||  primnom || ' '  || segnom)
	,terceros.nocarnet,terceros.tipousu,terceros.nivelusu,fecnac,age('$ND[year]-$ND[mon]-$ND[mday]',fecnac),sexo,autorizac1,autorizac2,autorizac3,tiposervicio
	from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and identificacion=cedula and Identificacion='$Cedula'
	and numservicio=$Numero";
	//echo $cons;
	$res=ExQuery($cons);
	$DatPaciente=ExFetch($res);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	
</script>
</head>

<body>
<form name="FORMA" method="post">  <?
//for($i=0;$i<2;$i++){?>
<table  style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>" width="100%">  
<tr>
	<TD align="center">
    	<div>
        	<table style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>" cellpadding="2">
            <?	$Raiz=$_SERVER['DOCUMENT_ROOT'];?>
            	<tr align="center"><td align="center" rowspan="4"><img src="/Imgs/Logo.jpg" style="width:80; height:100"/>                	
                </tr>
                
            </table>
        </div>
    </TD>
    <td>
    	<div>
	        <table style="font-family:<?echo $Estilo[8]?>;font-size:15;font-style:<?echo $Estilo[10]?>" cellpadding="2">
            	<tr><td><strong><? echo strtoupper($Compania[0])?></strong></td></tr>
                <tr><td><? echo $Compania[1]?></td></tr>
                <tr><td>CODIGO SGSSS <? echo $Compania[17]?></td></tr>
                <TR><td><? echo "$Compania[2] - TELEFONOS:".strtoupper($Compania[3])?></td></TR>
          	</table>
        </div>
    </td>
	<td  align="center"><strong>ORDEN MEDICA PROCEDIMIENTOS</strong></td>
</tr>
</table>
<table style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>" cellpadding='0' bordercolor="#e5e5e5" border="0" >  
	<tr>
    	<td><strong>ASEGURADOR:</strong></td><td colspan="5"><? echo $Cliente[0]?></td>
    </tr>
    <tr>
    	<td><strong>NIT:</strong></td><td><? echo $Cliente[1]?></td><td><strong>CODIGO SGSSS:</strong></td><TD><? echo $Cliente[7]?>&nbsp;</TD>
        <td><strong>REGIMEN</strong></td><td><? echo $Cliente[6]?>&nbsp;</td>
    </tr>
    <tr>
    	<td><strong>CONTRATO:</strong></td><td><? echo $Cliente[2]?>&nbsp;</td><td><strong>No CONTRATO:</strong></td><td><? echo $Cliente[3]?>&nbsp;</td>
  	<? 	$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and autoid=$Cliente[9]";
		$res=ExQuery($cons);$fila=ExFetch($res);?>
        <td><strong>PLAN MEDS:</strong></td><td><? echo $fila[0]?>&nbsp;</td>
    </tr>
    <tr>
    	<td><strong>DIRECCION:</strong></td><td><? echo $Cliente[4]?>&nbsp;</td><td><strong>TELEFONO:</strong></td><td><? echo $Cliente[5]?>&nbsp;</td>
 	 	<? 	$cons="select nombreplan from contratacionsalud.planeservicios where compania='$Compania[0]' and autoid=$Cliente[8]";
			$res=ExQuery($cons);$fila=ExFetch($res);?>
        <td><strong>PLAN SERVS:</strong></td><td><? echo $fila[0]?>&nbsp;</td>
    </tr>
	<tr><td colspan="11">&nbsp;</td></tr>
	<tr>
    	<td><strong>PACIENTE:</strong></td><td colspan="3" width="60%"><? echo $DatPaciente[1]?>&nbsp;</td>
        <td><strong>IDENTIFICACION:</strong></td><td><? echo $DatPaciente[0]?>&nbsp;</td>
    </tr>
    	<td><strong>No CARNET:</strong></td><td><? echo $DatPaciente[2]?>&nbsp;</td>
        <td><strong>TIPO USUARIO:</strong></td><td><? echo $DatPaciente[3]?>&nbsp;</td>
        <td><strong>NIVLE USUARIO:</strong></td><td><? echo $DatPaciente[4]?>&nbsp;</td>
    </tr>
    <tr>
    	<td><strong>EDAD:</strong></td>
        <td><? $DatPaciente[6]=str_replace("years","a&ntilde;os",$DatPaciente[6]); $DatPaciente[6]=str_replace("mon","meses",$DatPaciente[6]); 
			$DatPaciente[6]=str_replace("days","dias",$DatPaciente[6]); echo $DatPaciente[6]." ($DatPaciente[5])";?>&nbsp;</td>
        <td><strong>SEXO:</strong></td><td><? if($DatPaciente[7]=="F"){echo "Femenino";}elseif($DatPaciente[7]=="M"){echo "Masculino";}?>&nbsp;</td>
        <td><strong>SERVICIO:</strong></td><td><? echo $DatPaciente[11]?></td>
    </tr>
    <!--</tr>
    	<td><strong>Autorizacion 1:</strong></td><td><? echo $DatPaciente[5]?></td><td><strong>Autorizacion 2:</strong></td><td><? echo $DatPaciente[6]?></td>
        <td><strong>Autorizacion 3:</strong></td><td><? echo $DatPaciente[7]?></td>
    </tr>-->
</table>
<br />
<table  style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>" cellpadding='0' border="1"  bordercolor="#e5e5e5" align="center">  
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td colspan="5">PROCEDIMENTOS O EXAMENES SOLICITADOS</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<TD>CODIGO</TD><td>NOMBRE</td><td>CANTIDAD</td><td>JUSTIFICACION</td><td>OBSERVACIONES</td>
</tr><?

$cons="select nombre from contratacionsalud.cups where compania='$Compania[0]' and codigo='$CodCup'";
$res=ExQuery($cons); $fila=ExFetch($res); $NomCup=$fila[0];
if($FechaIni){$FI=" and fechaini>='$FechaIni'";}
if($FechaFin){$FF=" and fechaini<='$FechaFin'";}
if($IdEsc){$IE=" and idescritura='$IdEsc'";}
if($NumOrd){$NO=" and numorden='$NumOrd '";}

 //echo $Med;
$cont=1;
//echo $cons;
//while($fila=Exfetch($res)){
$Procedimientos=explode("***",$Proceds);
foreach($Procedimientos as $CupsEnviados)
{	
	if($CupsEnviados){
		$Cups=explode(";;;",$CupsEnviados);
		$Cant="";
		$Cant=$Cups[1];
		if(!$Cant){$Cant=1;}
		$cons="select observaciones,usuario,justificacion from salud.plantillaprocedimientos 
		where plantillaprocedimientos.compania='$Compania[0]' and numservicio=$Numero 
		and cup='$Cups[0]' $FI $FF $IE $NO";
		//echo $cons."<br>";
		$res=ExQuery($cons); $fila=ExFetch($res); $Observs=$fila[0]; $Med=$fila[1];
		echo "<tr align='center'><td>$Cups[0]</td><td>".$ConsCups[$Cups[0]]."</td><td>$Cant</td><td>$fila[2]</td><td>$Observs &nbsp;</td></tr>";
	}
}
//}
?>
</table>
<?
	$cons="select nombre,cedula,rm,cargo from central.usuarios,salud.medicos where usuarios.usuario='$Med' and medicos.compania='$Compania[0]'
	and medicos.usuario=usuarios.usuario";
	$res=ExQuery($cons);
	//echo $cons;
	$fila=ExFetch($res);
?>
<br /><br />
<table style="font-family:<?echo $Estilo[8]?>;font-size:14;font-style:<?echo $Estilo[10]?>" width="100" align="center" cellspacing="1" cellpadding="1">
	<tr align="center">
    	<td><img src="/Firmas/<? echo $fila[1]?>.GIF" style="width:130; height:80"/></td>
    </tr>
    <tr align="center"><td><? echo "<strong>".strtoupper($fila[3])."</strong>: ".utf8_decode(strtoupper($fila[0]))." - <strong>RM:</strong> $fila[2]"?></td>
	<tr align="center">
    <td><hr width="380px"/></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><hr width="380px" /></td>
    </tr>
    <tr align="center">
    	<td>quien formula</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>quien recibe</td>
    </tr>
</table><?
//}?>
</form>
</body>
</html>