<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	ini_set("memory_limit","512M");	
	include("Informes.php");	
	require('LibPDF/fpdf.php');	
	$ND=getdate();

class PDF extends FPDF
{//192,168.1.110
	function BasicTable($data)
	{
		$Anchos=array(25,70,5,23,70,30,15,24);    
		$fill=false;$this->SetFillColor(248,248,248);
		if(!$data){exit;}
		foreach($data as $row)
		{
			$x=0;
			foreach($row as $col)
			{
				$POSY=$this->GetY();
				if($x==1){$col=substr($col,0,52);}
				if($x>6){$Alinea='R';$col=number_format($col,2);}else{$Alinea="L";}
				if($col=="TOTALES"){$Final=1;$Alinea="R";}
				if($Final)
				{
					$fill=1;$this->SetFillColor(218,218,218);$this->SetFont('Arial','B',7);$Lines="LRBT";
				}
				else
				{
				if($POSY>=187 && $POSY<192){$Lines="LRB";}
				else{$Lines="LR";}
				}
				$this->Cell($Anchos[$x],5,strtoupper($col),$Lines,0,$Alinea,$fill);
				$x++;
			}
			$this->Ln();
			$fill=!$fill;
			
		}
	}
	
	//Cabecera de página
	function Header()
	{
		global $Compania; global $ND;
		//Logo
	//    $this->Image('/Imgs/Logo.jpg',10,8,33);
		//Arial bold 15
		$this->SetFont('Arial','B',12);
		//Movernos a la derecha
	
		//Título
		$this->Ln(4);
		$this->Ln(4);
		$this->Cell(0,8,"ANEXO TECNICO No.1",0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"INFORME DE POSIBLES INCONSISTENCIAS EN LAS BASES DE DATOS DE",0,0,'C');
		$this->Ln(4);
		$this->Cell(0,8,"LA ENTIDAD RESPONSABLE DEL PAGO",0,0,'C');
		$this->Ln(4);		
		
	}
	
	//Pie de página
	function Footer()
	{
		global $ND;
		//Posición: a 1,5 cm del final
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Ln(3);
		$this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
	}
}

$cons="select departamento from central.departamentos where codigo='$Compania[18]'";
$res=ExQuery($cons);
$fila=ExFetchArray($res);$Depart=substr($fila[0],0,1);$Depart2=strtolower( substr($fila[0],1,strlen($fila[0])));$Dpto=$Depart.$Depart2;
$cons="select municipio from central.municipios where codmpo like '%$Compania[19]%' and departamento='$Compania[18]'";
$res=ExQuery($cons);
$fila=ExFetchArray($res);$Municipio=substr($fila[0],0,1);$Municipio2=strtolower( substr($fila[0],1,strlen($fila[0])));$Muni=$Municipio.$Municipio2;

$cons="select * from reportes3047.inc_bd where compania='$Compania[0]' and numinforme=$NumInf";
$res=ExQuery($cons);
if(ExNumRows($res)>0){
	$fila=ExFetchArray($res);
	$Fecha=explode(" ",$fila['fecha']);
	
	$cons2="select departamento from central.departamentos where codigo='".$fila['departamento']."'";
	$res2=ExQuery($cons2);
	$fila2=ExFetchArray($res2);$DepartUsu=substr($fila2[0],0,1);$DepartUsu2=strtolower( substr($fila2[0],1,strlen($fila2[0])));$DptoUsu=$DepartUsu.$DepartUsu2;
	$cons2="select municipio from central.municipios where codmpo like '%".$fila['municipio']."%' and departamento='".$fila['departamento']."'";
	$res2=ExQuery($cons2);
	$fila2=ExFetchArray($res2);$MunicipioUsu=substr($fila2[0],0,1);$MunicipioUsu2=strtolower( substr($fila2[0],1,strlen($fila2[0])));$MuniUsu=$MunicipioUsu.$MunicipioUsu2;
	$cons2="select cobertura from reportes3047.coberturasalud where codigo='".$fila['coberturasalud']."'";
	$res2=ExQuery($cons2);
	$fila2=ExFetchArray($res2);$Cobertura=$fila2[0];
	$cons2="select tipodoc from central.tiposdocumentos where codigo='".$fila['tipodocfisico']."'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2); $TipoDocUsu=$fila2[0];
	
	
	$cons2="select tipodoc from central.tiposdocumentos where codigo='".$fila['tipodocumento']."'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2); $TipoDoc=$fila2[0];
	
	$CodEnt=$fila['entidad'];
	$cons2="select primape,segape,primnom,segnom from central.terceros where identificacion='$CodEnt' and compania='$Compania[0]'";
	$res2=ExQuery($cons2);
	$fila2=ExFetchArray($res2); $Entd=$fila2[0]." ".$fila2[1]." ".$fila2[2]." ".$fila2[3];
}
$pdf=new PDF('P','mm','Letter'); //L horizontal P vertical
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$ruta=$_SERVER['DOCUMENT_ROOT'];
$pdf->Ln(6);
$pdf->Cell(0,8,"MINISTERIO DE LA PROTECCION SOCIAL",0,0,'C');
$pdf->Image($ruta."/Imgs/Escudo_Colombia.jpg",15,30,15,15,"JPG",'');
$pdf->Ln(4);
$pdf->Cell(0,8,"INFORME DE POSIBLES INCONSISTENCIAS EN LA BASE DE DATOS DE LA ENTIDAD RESPONSALBE",0,0,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Ln(4);
$pdf->Ln(5);

$pdf->Cell(119,8,"INFORME No: ",0,0,'R'); $pdf->SetFont('Arial','',8);$pdf->Cell(3,8,"$NumInf",0,0,'C'); $pdf->SetFont('Arial','B',9);
$pdf->Cell(11,8,"FECHA:",0,0,'L'); $pdf->SetFont('Arial','',8);$pdf->Cell(19,8,"$Fecha[0]",0,0,'C'); $pdf->SetFont('Arial','B',9);
$pdf->Cell(11,8,"HORA:",0,0,'L'); $pdf->SetFont('Arial','',8);$pdf->Cell(12,8,"$Fecha[1]",0,0,'C');
$pdf->Ln(4);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,8,"INFORMACION DEL PRESTADOR",0,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(7);
$pdf->Cell(15,8,"NOMBRE:",0,0,'l'); $pdf->SetFont('Arial','',8); $pdf->Cell(110,8,strtoupper($Compania[0]),0,0,'l'); $pdf->SetFont('Arial','B',8); 
$pdf->Cell(5,8,"NIT:",0,0,'l'); $pdf->SetFont('Arial','',8); $pdf->Cell(20,8,str_replace("NIT","",strtoupper($Compania[1])),0,0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8); 
$pdf->Cell(17,8,"CODIGO:",0,0,'L'); $pdf->SetFont('Arial','',8); $pdf->Cell(30,8,strtoupper($Compania[17]),0,0,'L');
$pdf->SetFont('Arial','B',8); 
$pdf->Cell(36,8,"DIRECCION PRESTADOR:",0,0,'L'); $pdf->SetFont('Arial','',8); $pdf->Cell(100,8,strtoupper($Compania[2]),0,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(17,8,"TELEFONO:",0,0,'L'); $pdf->SetFont('Arial','',8); $pdf->Cell(30,8,strtoupper($Compania[3]),0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,8,"DEPARTAMENTO:",0,0,'L');$pdf->SetFont('Arial','',8); $pdf->Cell(53,8,utf8_decode($Dpto),0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(17,8,"MUNICIPIO:",0,0,'L');$pdf->SetFont('Arial','',8); $pdf->Cell(30,8,utf8_decode($Muni),0,0,'L');
$pdf->Ln(4);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(68,8,"ENTIDAD A LA QUE SE LE INFORMA (PAGADOR):",0,0,'L'); $pdf->SetFont('Arial','',8); $pdf->Cell(130,8,strtoupper(utf8_decode($Entd)),0,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(13,8,"CODIGO:",0,0,'L'); $pdf->SetFont('Arial','',8); $pdf->Cell(17,8,strtoupper(utf8_decode($CodEnt)),0,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);$pdf->Cell(33,8,"TIPO INCONSISTENCIA:",0,0,'L'); $pdf->SetFont('Arial','',8); $pdf->Cell(30,8,strtoupper($fila[tipoinconsistencia]),0,0,'L');
$pdf->Ln(4);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,8,"DATOS DEL USUARIO (como aparece en la base de datos)",0,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(7);
$pdf->Cell(20,8,"1er APELLIDO:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(106,8,strtoupper(utf8_decode($fila['primape'])),0,0,'L');$pdf->SetFont('Arial','B',8);
$pdf->Cell(21,8,"2do APELLIDO:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(28,8,strtoupper(utf8_decode($fila['segape'])),0,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20,8,"1er NOMBRE:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(106,8,strtoupper(utf8_decode($fila['primnom'])),0,0,'L');$pdf->SetFont('Arial','B',8);
$pdf->Cell(20,8,"2do NOMBRE:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(28,8,strtoupper(utf8_decode($fila['segnom'])),0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(6);
$pdf->Cell(56,8,"TIPO DOCUMENTO DE IDENTIFICACION:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(70,8,strtoupper($TipoDoc),0,0,'l');
$pdf->SetFont('Arial','B',8);$pdf->Cell(37,8,"NUMERO IDENTIFICACION:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(22,8,$fila['identificacion'],0,0,'l');
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(34,8,"FECHA DE NACIMIENTO:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(92,8,$fila['fecnac'],0,0,'l');
$pdf->SetFont('Arial','B',8);$pdf->Cell(17,8,"TELEFONO:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(15,8,$fila['telefono'],0,0,'l');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);$pdf->Cell(34,8,"COBERTURA EN SALUD:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper($Cobertura),0,0,'l');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(6);
$pdf->Cell(55,8,"DIRECCION DE RESIDENCIA HABITUAL:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(88,8,strtoupper($fila['direccion']),0,0,'l');
$pdf->Ln(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(25,8,"DEPARTAMENTO:",0,0,'l');$pdf->SetFont('Arial','',8);$pdf->Cell(101,8,utf8_decode($DptoUsu),0,0,'l');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(16,8,"MUNICIPIO:",0,0,'L');$pdf->SetFont('Arial','',8); $pdf->Cell(35,8,utf8_decode($MuniUsu),0,0,'L');
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,8,"INFORMACION DE LA POSIBLE INCONSISTENCIA",0,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(6);
$pdf->Cell(80,8,"VARIABLE PRESUNTAMENTE INCORRECTA",0,0,'L'); $pdf->Cell(80,8,"VDATOS SEGUN DOCUMENTOS DE IDENTIFICACION (fisico)",0,0,'L');
$pdf->Ln(7);
if($fila['malprimape']==1){$MalPripA="X";}else{$MalPripA=" ";}
$pdf->Cell(3,3,$MalPripA,1,0,'C');$pdf->SetFont('Arial','',8);$pdf->Ln(-2.5);$pdf->SetX(3+10);$pdf->Cell(87,8,"PRIMER APELLIDO",0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(31,8,"PRIMER APELLIDO:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($fila['primapefisico'])),0,0,'l');
$pdf->Ln(7);
$pdf->SetFont('Arial','B',8);
if($fila['malsegape']==1){$MalSegA="X";}else{$MalSegA=" ";}
$pdf->Cell(3,3,$MalSegA,1,0,'C');$pdf->Ln(-2.5);$pdf->SetX(3+10);$pdf->SetFont('Arial','',8);$pdf->Cell(87,8,"SEGUNDO APELLIDO",0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(31,8,"SEGUNDO APELLIDO:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($fila['segapefisico'])),0,0,'l');
$pdf->Ln(7);
$pdf->SetFont('Arial','B',8);
if($fila['malprimnom']==1){$MalPrimN="X";}else{$MalPrimN=" ";}
$pdf->Cell(3,3,$MalPrimN,1,0,'C');$pdf->Ln(-2.5);$pdf->SetX(3+10);$pdf->SetFont('Arial','',8);$pdf->Cell(87,8,"PRIMER NOMBRE",0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(31,8,"PRIMER NOMBRE:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($fila['primnomfisico'])),0,0,'l');
$pdf->Ln(7);
$pdf->SetFont('Arial','B',8);
if($fila['malsegnom']==1){$MalSegN="X";}else{$MalSegN=" ";}
$pdf->Cell(3,3,$MalSegN,1,0,'C');$pdf->Ln(-2.5);$pdf->SetX(3+10);$pdf->SetFont('Arial','',8);$pdf->Cell(87,8,"SEGUNDO NOMBRE",0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(31,8,"SEGUNDO NOMBRE:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($fila['segnomfisico'])),0,0,'l');
$pdf->Ln(7);
$pdf->SetFont('Arial','B',8);
if($fila['maltipodoc']==1){$MalTipoD="X";}else{$MalTipoD=" ";}
$pdf->Cell(3,3,$MalTipoD,1,0,'C');$pdf->Ln(-2.5);$pdf->SetX(3+10);$pdf->SetFont('Arial','',8);$pdf->Cell(87,8,"TIPO DOCUMENTO DE IDENTIFICACION",0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(56,8,"TIPO DOCUMENTO DE IDENTIFICACION:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($TipoDocUsu)),0,0,'l');
$pdf->Ln(7);
$pdf->SetFont('Arial','B',8);
if($fila['malidentificacion']==1){$MalId="X";}else{$MalId=" ";}
$pdf->Cell(3,3,$MalId,1,0,'C');$pdf->Ln(-2.5);$pdf->SetX(3+10);$pdf->SetFont('Arial','',8);$pdf->Cell(87,8,"NUMERO DOCUMENTO DE IDENTIFICACION",0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(62,8,"NUMERO DOCUMENTO DE IDENTIFICACION:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($fila['identificacionfisico'])),0,0,'l');
$pdf->Ln(7);
$pdf->SetFont('Arial','B',8);
if($fila['malidentificacion']==1){$MalId="X";}else{$MalId=" ";}
$pdf->Cell(3,3,$MalId,1,0,'C');$pdf->Ln(-2.5);$pdf->SetX(3+10);$pdf->SetFont('Arial','',8);$pdf->Cell(87,8,"FECHA DE NACIMIENTO",0,0,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(34,8,"FECHA DE NACIMIENTO:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($fila['fecnacfisico'])),0,0,'l');

$pdf->Ln(8);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,8,"OBSERVACIONES",0,0,'L');
$pdf->Ln(4);
$pdf->SetFont('Arial','',8);
$pdf->Multicell(0,8,$fila['observaciones'],0,'J',0);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,8,"INFORMACION DE LA PERSONA QUE REPORTA",0,0,'C');
$cons="select nombre,cedula,usuario from central.usuarios where usuario='".$fila['usuario']."'";
$res=ExQuery($cons);
$fila=ExFetch($res);
$cons2="select cargo,telefono from salud.medicos where compania='$Compania[0]' and usuario='$fila[2]'";
$res2=ExQuery($cons2);
$fila2=ExFetch($res2);
$pdf->SetFont('Arial','B',8);
$pdf->Ln(6);
$pdf->Cell(43,8,"NOMBRE DE QUIEN INFORMA:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(85,8,strtoupper(utf8_decode($fila[0])),0,0,'l');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(16,8,"TELEFONO:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(30,8,strtoupper(utf8_decode($fila2[1])),0,0,'l');
$pdf->SetFont('Arial','B',8);
$pdf->Ln(4);
$pdf->Cell(31,8,"CARGO O ACTIVIDAD:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode($fila2[0])),0,0,'l');
/*$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,8,"TELEFONO CELULAR:",0,0,'L');$pdf->SetFont('Arial','',8);$pdf->Cell(56,8,strtoupper(utf8_decode("EN Q BD??")),0,0,'l');*/


//$pdf->BasicTable($Datos);

//$pdf->Cell(262,0,"","B",0,'C');

/*$pdf->Ln(20);
$pdf->Cell(0,8,"____________________________________",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(0,8,"RESPONSABLE SUMINISTROS",0,0,'C');
$pdf->Ln(5);*/

$pdf->Output();
?>	