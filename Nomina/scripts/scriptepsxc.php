<?
//	if($DatNameSID){session_name("$DatNameSID");}
//	session_start();
	include("Funciones.php");
/*	$cons="
--ALTER TABLE nomina.arpxc DROP CONSTRAINT \"Pk_Nomina_Arpxc\";
ALTER TABLE nomina.centrocostos DROP CONSTRAINT \"Pk_Nomina_Centrocostos\";
ALTER TABLE nomina.cesantiasxc DROP CONSTRAINT \"Pk_Nomina_Cesantiasxc\";
ALTER TABLE nomina.contratos DROP CONSTRAINT \"Pk_Nomina_Contrato\";
ALTER TABLE nomina.epsxc DROP CONSTRAINT \"Pk_Nomina_Epsxc\";
ALTER TABLE nomina.pensionesxc DROP CONSTRAINT \"Pk_Nomina_Pensionesxc\";
ALTER TABLE nomina.salarios DROP CONSTRAINT \"Pk_Nomina_Salarios\";
-----Crea la tabla ARPXC
CREATE TABLE nomina.arpxc1
(
  compania character varying(200) NOT NULL,
  identificacion character varying(100) NOT NULL,
  arp double precision NOT NULL,
  fecinicio date NOT NULL,
  fecfin date,
  numcontrato numeric NOT NULL,
  CONSTRAINT \"PK_Nomina_Arpxc\" PRIMARY KEY (compania, identificacion, arp, fecinicio, numcontrato)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nomina.arpxc1 OWNER TO postgres;
-----Crea la tabla CENTROCOSTOS
CREATE TABLE nomina.centrocostos1
(
  compania character varying(200) NOT NULL,
  identificacion character varying(100) NOT NULL,
  cc character varying(200) NOT NULL,
  fecinicio date NOT NULL,
  fecfin date,
  porcentaje integer NOT NULL,
  numcontrato numeric NOT NULL,
  CONSTRAINT \"Pk_Nomina_Centrocostos\" PRIMARY KEY (compania, identificacion, cc, fecinicio, numcontrato, porcentaje)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nomina.centrocostos1 OWNER TO postgres;
-----Crea la Tabla CESANTIASXC
CREATE TABLE nomina.cesantiasxc1
(
  compania character varying(200) NOT NULL,
  identificacion character varying(80) NOT NULL,
  cesantias character varying(200) NOT NULL,
  fecinicio date NOT NULL,
  fecfin date,
  novgral character varying(5),
  numcontrato numeric NOT NULL,
  CONSTRAINT \"Pk_Nomina_Cesantiasxc\" PRIMARY KEY (compania, identificacion, cesantias, fecinicio, numcontrato)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nomina.cesantiasxc1 OWNER TO postgres;
-----Crea la tabla CONTRATOS
CREATE TABLE nomina.contratos1
(
  compania character varying(200) NOT NULL,
  identificacion character varying(100) NOT NULL,
  tipovinculacion character varying(100) NOT NULL,
  tipocontrato character varying(100) NOT NULL,
  cargo character varying(100),
  seccion character varying(100),
  grupo character varying(100),
  estado character varying(100),
  hrslab integer,
  jornflexible character(2),
  pactocolectivo character(2),
  alimentos character(2),
  cuenta character varying(100) NOT NULL,
  banco character varying(100),
  numero numeric NOT NULL,
  fecinicio date NOT NULL,
  fecfin date,
  usuario character varying(100) NOT NULL,
  CONSTRAINT \"Pk_Nomina_Contratos\" PRIMARY KEY (compania, identificacion, tipovinculacion, tipocontrato, fecinicio, usuario, cuenta)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nomina.contratos1 OWNER TO postgres;
-----Crea la tabla EPSXC
CREATE TABLE nomina.epsxc1
(
  compania character varying(200) NOT NULL,
  identificacion character varying(80) NOT NULL,
  eps character varying(200) NOT NULL,
  fecinicio date NOT NULL,
  fecfin date,
  novgral character varying(5),
  numcontrato numeric NOT NULL,
  CONSTRAINT \"Pk_Nomina_Epsxc\" PRIMARY KEY (compania, identificacion, eps, fecinicio, numcontrato)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nomina.epsxc1 OWNER TO postgres;
-----Crea la tabla PENSIONESXC
CREATE TABLE nomina.pensionesxc1
(
  compania character varying(200) NOT NULL,
  identificacion character varying(80) NOT NULL,
  pensiones character varying(200) NOT NULL,
  fecinicio date NOT NULL,
  fecfin date,
  novgral character varying(5),
  numcontrato numeric NOT NULL,
  CONSTRAINT \"Pk_Nomina_Pensionesxc\" PRIMARY KEY (compania, identificacion, pensiones, fecinicio, numcontrato)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nomina.pensionesxc1 OWNER TO postgres;
-----Crea la Tabla SALARIOS
CREATE TABLE nomina.salarios1
(
  compania character varying(200) NOT NULL,
  identificacion character varying(100) NOT NULL,
  fecinicio date NOT NULL,
  fecfin date,
  salario integer NOT NULL,
  numcontrato numeric NOT NULL,
  CONSTRAINT \"Pk_Nomina_Salarios\" PRIMARY KEY (compania, identificacion, fecinicio, salario, numcontrato)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE nomina.salarios1 OWNER TO postgres;
";
//	echo $cons;
	$Res=pg_query($cons);*/
//----------ingresa datos a la tabla ARPXC	
	$cons="select * from nomina.arpxc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Arpxc[$fila[1]][$fila[6]][$fila[7]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7]);
	}
	if($Arpxc)
	{
		foreach($Arpxc as $Iden)
		{
			foreach($Iden as $NumC)
			{
				foreach($NumC as $Arp)
				{
//					echo $Arp[0]." - ".$Arp[1]." - ".$Arp[2]." - ".$Arp[3]." - ".$Arp[4]." - ".$Arp[5]." - ".$Arp[6]." - ".$Arp[7]."<br>";
					if($$Arp[4]==0&&$$Arp[5]==0)
					{
						$cont=strlen($Arp[3]);
						if($cont==1){$Arp[3]="0$Arp[3]";}
						$cons1="insert into nomina.arpxc1(compania,identificacion,fecinicio,fecfin,numcontrato,arp) values ('$Arp[0]','$Arp[1]','$Arp[2]-$Arp[3]-01',NULL,'$Arp[6]','$Arp[7]');";
						$res=ExQuery($cons1);
						echo $cons1."<br>";
					}
					else
					{
						$cont=strlen($Arp[5]);
						if($cont==1){$Arp[5]="0$Arp[5]";}
						$cont1=strlen($Arp[3]);
						if($cont1==1){$Arp[3]="0$Arp[3]";}
						$cons1="insert into nomina.arpxc1(compania.identificacion,fecinicio,fecfin,numcontrato,arp) values ('$Arp[0]','$Arp[1]','$Arp[2]-$Arp[3]-01','$Arp[4]-$Arp[5]-30','$Arp[6]','$Arp[7]');";
						$res=ExQuery($cons);
						echo $cons1."<br>";
					}
				}
			}
		}
	}
//---------INGRESA DATOS A LA TABLA CENTROCOSTOS	
	$cons="select * from nomina.centrocostos";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CenCos[$fila[1]][$fila[2]][$fila[7]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);
	}
	if($CenCos)
	{
		foreach($CenCos as $Iden)
		{
			foreach($Iden as $CC)
			{
				foreach($CC as $NumC)
				{
			//		echo $NumC[0]." - ".$NumC[1]." - ".$NumC[2]." - ".$NumC[3]." - ".$NumC[4]." - ".$NumC[5]." - ".$NumC[6]." - ".$NumC[7]." - ".$NumC[8]."<br>";
					if($NumC[6]==0&&$NumC[8]==0)
					{
						$cont=strlen($NumC[5]);
						if($cont==1){$NumC[5]="0$NumC[5]";}
						$cons="insert into nomina.centrocostos1(compania,identificacion,cc,fecinicio,fecfin,porcentaje,numcontrato) values ('$NumC[0]','$NumC[1]','$NumC[2]','$NumC[4]-$NumC[5]-01',NULL,'$NumC[3]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
					else
					{
						$cont=strlen($NumC[5]);
						if($cont==1){$NumC[5]="0$NumC[5]";}
						$cont=strlen($NumC[6]);
						if($cont==1){$NumC[6]="0$NumC[6]";}
						$cons="insert into nomina.centrocostos1(compania,identificacion,cc,fecinicio,fecfin,porcentaje,numcontrato) values ('$NumC[0]','$NumC[1]','$NumC[2]','$NumC[4]-$NumC[5]-01','$NumC[8]-$NumC[6]-30','$NumC[3]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
				}
			}
		}
	}
//-----INGRESA DATOS A LA TABLA CESANTIASXC
	$cons="select * from nomina.cesantiasxc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Censa[$fila[1]][$fila[6]][$fila[7]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);
	}
	if($Censa)
	{
		foreach($Censa as $Iden)
		{
			foreach($Iden as $Cesan)
			{
				foreach($Cesan as $NumC)
				{
			//		echo $NumC[0]." - ".$NumC[1]." - ".$NumC[2]." - ".$NumC[3]." - ".$NumC[4]." - ".$NumC[5]." - ".$NumC[6]." - ".$NumC[7]." - ".$NumC[8]."<br>";
					if($NumC[4]==0&&$NumC[5]==0)
					{
						$cont=strlen($NumC[3]);
						if($cont==1){$NumC[3]="0$NumC[3]";}
						$cons="insert into nomina.cesantiasxc1(compania,identificacion,cesantias,fecinicio,fecfin,novgral,numcontrato) values ('$NumC[0]','$NumC[1]','$NumC[6]','$NumC[2]-$NumC[3]-01',NULL,'$NumC[8]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
					else
					{
						$cont=count($NumC[3]);
						if($cont==1){$NumC[3]="0$NumC[3]";}
						$cont1=count($NumC[5]);
						if($cont1==1){$NumC[5]="0$NumC[5]";}
						$cons="insert into nomina.cesantiasxc1(compania,identificacion,cesantias,fecinicio,fecfin,novgral,numcontrato) values ('$NumC[0]','$NumC[1]','$NumC[6]','$NumC[2]-$NumC[3]-01','$NumC[4]-$NumC[5]-30','$NumC[8]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
				}
			}
		}
	}
//----- INGRESA LOS DATOS PARA LA TABLA CONTRATOS
	$cons="select * from nomina.contratos";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Contra[$fila[1]][$fila[12]][$fila[14]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16]);
	}
	if($Contra)
	{
		foreach($Contra as $Iden)
		{
			foreach($Iden as $Cuen)
			{
				foreach($Cuen as $NumC)
				{
			//		echo $NumC[0]." - ".$NumC[1]." - ".$NumC[2]." - ".$NumC[3]." - ".$NumC[4]." - ".$NumC[5]." - ".$NumC[6]." - ".$NumC[7]." - ".$NumC[8]." - ".$NumC[9]." - ".$NumC[10]." - ".$NumC[11]." - ".$NumC[12]." - ".$NumC[13]." - ".$NumC[14]." - ".$NumC[15]." - ".$NumC[16]."<br>";
					if($NumC[16]=='')
					{
			//			$cont=strlen($NumC[3]);
			//			if($cont==1){$NumC[3]="0$NumC[3]";}
						$cons="insert into nomina.contratos1(compania,identificacion,tipovinculacion,tipocontrato,cargo,seccion,grupo,estado,hrslab,
						jornflexible,pactocolectivo,alimentos,cuenta,banco,numero,fecinicio,fecfin,usuario) values
						('$NumC[0]','$NumC[1]','$NumC[2]','$NumC[3]','$NumC[4]','$NumC[5]','$NumC[6]','$NumC[7]','$NumC[8]','$NumC[9]','$NumC[10]','$NumC[11]','$NumC[12]','$NumC[13]',
						'$NumC[14]','$NumC[15]',NULL,'$usuario[1]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
					else
					{
			//			$cont=count($NumC[3]);
			//			if($cont==1){$NumC[3]="0$NumC[3]";}
			//			$cont1=count($NumC[5]);
			//			if($cont1==1){$NumC[5]="0$NumC[5]";}
						$cons="insert into nomina.contratos1(compania,identificacion,tipovinculacion,tipocontrato,cargo,seccion,grupo,estado,hrslab,
						jornflexible,pactocolectivo,alimentos,cuenta,banco,numero,fecinicio,fecfin,usuario) values
						('$NumC[0]','$NumC[1]','$NumC[2]','$NumC[3]','$NumC[4]','$NumC[5]','$NumC[6]','$NumC[7]','$NumC[8]','$NumC[9]','$NumC[10]','$NumC[11]','$NumC[12]','$NumC[13]',
						'$NumC[14]','$NumC[15]','$NumC[16]','$usuario[1]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
				}
			}
		}
	}
//--------INGRESA DATOS TABLA EPSXC
	$cons="select * from nomina.epsxc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Eps[$fila[6]][$fila[4]][$fila[7]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);
	}
	if($Eps)
	{
		foreach($Eps as $Iden)
		{
			foreach($Iden as $Cuen)
			{
				foreach($Cuen as $NumC)
				{
			//		echo $NumC[0]." - ".$NumC[1]." - ".$NumC[2]." - ".$NumC[3]." - ".$NumC[4]." - ".$NumC[5]." - ".$NumC[6]." - ".$NumC[7]." - ".$NumC[8]."<br>";
					if($NumC[2]==0&&$NumC[3]==0)
					{
						$cont=strlen($NumC[1]);
						if($cont==1){$NumC[1]="0$NumC[1]";}
						$cons="insert into nomina.epsxc1(compania,identificacion,eps,fecinicio,fecfin,novgral,numcontrato) values ('$NumC[5]','$NumC[6]','$NumC[4]','$NumC[0]-$NumC[1]-01',NULL,'$NumC[8]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
					else
					{
						$cont=count($NumC[1]);
						if($cont==1){$NumC[1]="0$NumC[1]";}
						$cont1=count($NumC[3]);
						if($cont1==1){$NumC[3]="0$NumC[3]";}
						$cons="insert into nomina.epsxc1(compania,identificacion,eps,fecinicio,fecfin,novgral,numcontrato) values ('$NumC[5]','$NumC[6]','$NumC[4]','$NumC[0]-$NumC[1]-01','$NumC[2]-$NumC[3]-30','$NumC[8]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
				}
			}
		}
	}
//-------INGRESA DATOS TABLA PENSIONESXC
	$cons="select * from nomina.pensionesxc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Pens[$fila[6]][$fila[4]][$fila[7]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);
	}
	if($Pens)
	{
		foreach($Pens as $Iden)
		{
			foreach($Iden as $Cuen)
			{
				foreach($Cuen as $NumC)
				{
			//		echo $NumC[0]." - ".$NumC[1]." - ".$NumC[2]." - ".$NumC[3]." - ".$NumC[4]." - ".$NumC[5]." - ".$NumC[6]." - ".$NumC[7]." - ".$NumC[8]."<br>";
					if($NumC[4]==0&&$NumC[5]==0)
					{
						$cont=strlen($NumC[5]);
						if($cont==1){$NumC[5]="0$NumC[5]";}
						$cons="insert into nomina.pensionesxc1(compania,identificacion,pensiones,fecinicio,fecfin,novgral,numcontrato) values ('$NumC[0]','$NumC[1]','$NumC[6]','$NumC[2]-$NumC[3]-01',NULL,'$NumC[8]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
					else
					{
						$cont=count($NumC[3]);
						if($cont==1){$NumC[3]="0$NumC[3]";}
						$cont1=count($NumC[5]);
						if($cont1==1){$NumC[5]="0$NumC[5]";}
						$cons="insert into nomina.pensionesxc1(compania,identificacion,pensiones,fecinicio,fecfin,novgral,numcontrato) values ('$NumC[0]','$NumC[1]','$NumC[6]','$NumC[2]-$NumC[3]-01','$NumC[4]-$NumC[5]-30','$NumC[8]','$NumC[7]')";
						$res=ExQuery($cons);
						echo $cons."<br>";
					}
				}
			}
		}
	}
//----------INGRESA DATOS A TABLA SALARIOS
	$cons="select * from nomina.salarios";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Salario[$fila[1]][$fila[5]][$fila[7]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);
	}
	
	if($Salario)
	{
		foreach($Salario as $Iden)
		{
			foreach($Iden as $Sal)
			{
				foreach($Sal as $NumC)
				{
			//		echo $NumC[0]." - ".$NumC[1]." - ".$NumC[2]." - ".$NumC[3]." - ".$NumC[4]." - ".$NumC[5]." - ".$NumC[6]." - ".$NumC[7]." - ".$NumC[8]."<br>";
					$cont=strlen($NumC[3]);
					if($cont==1){$NumC[3]="0$NumC[3]";}
					$cont=strlen($NumC[4]);
					if($cont==1){$NumC[4]="0$NumC[4]";}
					$cons="insert into nomina.salarios1(compania,identificacion,fecinicio,fecfin,salario,numcontrato) values ('$NumC[0]','$NumC[1]','$NumC[2]-$NumC[3]-01','$NumC[6]-$NumC[4]-30','$NumC[5]','$NumC[7]')";
					$res=ExQuery($cons);
					echo $cons."<br>";
				}
			}
		}
	}
	
//--------ELIMINAR 	TABLAS VIEJAS(OPCIONAL)
//--------CAMBIAR NOMBRE DE LAS TABLAS TODAS
$ren="ALTER TABLE nomina.arpxc RENAME TO arpxc0";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.arpxc1 RENAME TO arpxc";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.centrocostos RENAME TO centrocostos0";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.centrocostos1 RENAME TO centrocostos";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.cesantiasxc RENAME TO cesantiasxc0";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.cesantiasxc1 RENAME TO cesantiasxc";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.contratos RENAME TO contratos0";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.contratos1 RENAME TO contratos";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.epsxc RENAME TO epsxc0";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.epsxc1 RENAME TO epsxc";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.pensionesxc RENAME TO pensionesxc0";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.pensionesxc1 RENAME TO pensionesxc";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.salarios RENAME TO salarios0";
$res=pg_query($ren);
$ren="ALTER TABLE nomina.salarios1 RENAME TO salarios";
$res=pg_query($ren);
?>