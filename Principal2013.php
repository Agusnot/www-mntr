<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	session_register("ModSeleccionado");
	session_register("AnioAc");
	session_register("MesTrabajo");
	$AnioAc=$ND[year];
	if(!$MesTrabajo)
	{
		if($ND[mon]<10){$MesTrabajo="0".$ND[mon];}
		else{$MesTrabajo=$ND[mon];}
	}
session_register("Estilo");
$lev=error_reporting ('Err'); 
$cons="Select Nombre,BAColorFon,BAColorLet,BATipoLet,BATamLet,BAEstLet,BBColorFon,BBColorLet,BBTipoLet,BBTamLet,BBEstLet,Nit,Direccion,Telefonos,FirmaPresupuesto,NomFirmPres,Codigo,codsgsss,Departamento,Municipio,NomMunicipio
from Central.Compania,Central.Estilos where Nombre='$Compania[0]' 
and NomEstilo=Estilo";
$res=ExQuery($cons);
$MsjError=ExError($res);
if($MsjError)
{
	echo "<a href='ActualizaEnLinea.php?DatNameSID=$DatNameSID'><em><br><br><font color='blue'>Se encontró un error grave de estructura del sistema. Haga click aqui para ser remitido al modulo de actualizacion en linea y asi poder resolverlo. Es necesario que tenga activo el servicio de Internet y una cuenta
	Compuconta Software</font></em></a>";
}
$filaCompania=ExFetchArray($res);
$Compania[1]="NIT $filaCompania[11]";
$Compania[2]="$filaCompania[12]";
$Compania[3]="$filaCompania[13]";
$Compania[4]=$filaCompania[14];
$Compania[5]=$filaCompania[15];
$Compania[6]=$filaCompania[16];
$Compania[17]=$filaCompania[17];
$Compania[18]=$filaCompania[18];
$Compania[19]=$filaCompania[19];
$Compania[7]=$filaCompania[20];
$Estilo=$filaCompania;

$cons_="update consumo.movimiento set fechadespacho=fecha where fecha='$ND[year]-$ND[mon]-$ND[mday]' AND fechadespacho IS NULL";
ExQuery($cons_);
$consL="update consumo.lotes set salidas='0' where salidas is null";
ExQuery($consL);

$consC="Select consumo.movimiento.cedula,  salud.pacientesxpabellones.pabellon, salud.pabellones.centrocosto
from consumo.movimiento 
inner join salud.pacientesxpabellones on consumo.movimiento.cedula=salud.pacientesxpabellones.cedula
inner join salud.pabellones on salud.pacientesxpabellones.pabellon=salud.pabellones.pabellon
where consumo.movimiento.centrocosto='000' and consumo.movimiento.fecha >='2012-05-01' and consumo.movimiento.almacenppal='FARMACIA'
and salud.pacientesxpabellones.estado='AC'
GROUP BY consumo.movimiento.cedula,  salud.pacientesxpabellones.pabellon, salud.pabellones.centrocosto";
$resC=ExQuery($consC);
			while($filaC=ExFetch($resC)){
				  $consCA="update consumo.movimiento set centrocosto='$filaC[2]' where centrocosto='000' and cedula='$filaC[0]' and compania='$Compania[0]' and almacenppal='FARMACIA'";
				  ExQuery($consCA);
				  }
				  
$consulta="Select primape,segape,primnom,segnom,contrato,numero,fechaini,fechafin,monto,estado,entidad,consumcontra,(fechafin::Date-fechaini::Date),(current_date-fechaini::date)
  from ContratacionSalud.Contratos,central.terceros 
  where terceros.compania='$Compania[0]' and Contratos.Compania='$Compania[0]' 
  and entidad=identificacion order by primape,segape,primnom,segnom,contrato,numero";
  $result=ExQuery($consulta);
  
  while($row = ExFetchArray($result)) 
  {  
  $consFac="select sum(total),entidad,contrato,nocontrato from facturacion.facturascredito 
     where compania='$Compania[0]' and entidad='$row[10]' and contrato='$row[4]' and nocontrato='$row[5]' and estado='AC' group by entidad,contrato,nocontrato";      
  $resFac=ExQuery($consFac);
  $filaFac=ExFetch($resFac); 
  if(!$filaFac[0])$ME=0;else$ME=$filaFac[0];
  $PE=($ME*(100/$row[8]));
  $PD=((100*$row[13])/$row[12]);
  $consMP="update contratacionsalud.contratos set mttoejecutado='$ME', porcentajeejecutado='".number_format($PE,3,".",",")."',
           diascontrato='$row[12]', porcentajedias='".number_format($PD,3,".",",")."', diastranscurridocontrato='$row[13]' where 
           contrato='$row[4]' and numero='$row[5]' and fechaini='$row[6]' and fechafin='$row[7]' and estado='$row[9]' and entidad='$row[10]' and consumcontra='$row[11]'";
     ExQuery($consMP);
  }
?>
<html>
<head>
	<title><? echo $Sistema[$NoSistema]?></title>
</head>
<FRAMESET name="Principal" COLS="215,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
   <FRAME SRC="Modulos.php?DatNameSID=<? echo $DatNameSID?>" NAME="Encabezados" marginheight=8 marginwidth=8>
	<FRAMESET ROWS="95,*" FRAMEBORDER=0 FRAMESPACING=0 BORDER=0>
		<FRAME SRC="EncOpciones.php?DatNameSID=<? echo $DatNameSID?>" NAME="Encab" marginheight=8 marginwidth=8>
		<FRAME SRC="ModOpciones.php?DatNameSID=<? echo $DatNameSID?>" NAME="Derecha" marginheight=8 marginwidth=8>
	</FRAMESET>
</FRAMESET><noframes></noframes>
</HTML>
</body>
</html>
