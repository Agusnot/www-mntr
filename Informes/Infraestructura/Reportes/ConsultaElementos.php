<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/fpdf.php');
	$ND=getdate();
	
	if($Clase){ $PClase = " and CodElementos.Clase='$Clase' ";}
	if($Codigo){ $PCodigo = " and CodElementos.Codigo ilike '%$Codigo' ";}
	if($Nombre){ $PNombre = " and Nombre ilike '%$Nombre%' ";}
	if($Caracteristicas){ $PCaracteristicas = " and Caracteristicas ilike '%$Caracteristicas%'";}
	if($Modelo){ $PModelo = " and Modelo ilike '%$Modelo%' ";}
	if($Serie){ $PSerie = " and Serie ilike '%$Serie%' ";}
	if($Marca){ $PMarca = " and Marca ilike '%$Marca%' ";}
	if($FechaAd){ $PFechaAd = " and FechaAdquisicion = '$FechaAd' ";}
	if($Estado){ $PEstado = " and Estado = '$Estado' ";}
	if($Impacto){ $PImpacto = " and Impacto = '$Impacto' ";}
	if($Grupo){ $PGrupo = " and Grupo = '$Grupo' ";}
	if($Incluir)
	{	if($Incluir=="Solo Activos"){$PTipo = " and CodElementos.Tipo != 'Baja'";}
		if($Incluir=="Solo Bajas"){$PTipo = " and CodElementos.Tipo = 'Baja'";}
	}
	if($Relacion)
	{
		if($Relacion=="Encontrados")
		{
			$ConsUVE = " and UVE is not NULL ";	
		}
		else
		{
			$ConsUVE = " and UVE is NULL ";
		}
	}
	if($Identificacion || $CC)
	{
		$CampoSelect=",Responsable,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,PrimApe,SegApe,PrimNom,SegNom";
		$TablasFrom=",InfraEstructura.Ubicaciones,Central.Terceros, Central.CentrosCosto";
		if($Identificacion){$CamposWhereID=" and Responsable='$Identificacion' ";}
		if($CC){$CamposWhereCC=" and Ubicaciones.CentroCostos = '$CC' ";}
		$CamposWhere=" and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]'
		and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = Terceros.Identificacion
		and FechaIni<='$ND[year]-$ND[mon]-$ND[mday]' and FechaFin Is NULL 
		$CamposWhereID $CamposWhereCC";
	}
	$cons = "Select distinct CodElementos.Codigo,Nombre,Modelo,Serie,Marca,Grupo,FechaAdquisicion,Estado,Impacto,Caracteristicas,CodElementos.AutoId,Observaciones$CampoSelect
	From Infraestructura.CodElementos$TablasFrom  
	Where CodElementos.Compania = '$Compania[0]' 
	and (Eliminado != 1 or Eliminado is NULL) and CodElementos.Tipo != 'Orden Compra' $PTipo and (EstadoCompras!='ANULADO' or EstadoCompras is NULL) $CamposWhere $PClase 
	$PCodigo$PNombre$PModelo$PSerie$PMarca$PFechaAd$PEstado$PImpacto$PCostoIni$PGrupo$PCaracteristicas$ConsUVE
	Order by Grupo,CodElementos.Codigo";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$cons1 = "Select distinct Ubicaciones.CentroCostos, PrimNom, SegNom, PrimApe, SegApe, FechaIni, FechaFin, CentrosCosto.CentroCostos, Responsable,SubUbicacion 
		From Central.Terceros,Infraestructura.Ubicaciones,Central.CentrosCosto
		Where Ubicaciones.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AutoId=$fila[10] and Terceros.Identificacion = Ubicaciones.Responsable
		and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year] order by FechaFin desc";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		if(!$fila1[9]){$fila1[9]="N/A";}
		$Datos["$fila1[3] $fila1[4] $fila1[1] $fila1[2] - $fila1[8]"]["$fila1[0] - $fila1[7]"]["$fila1[9]"][$fila[10]]= array($fila[0],"$fila[1] $fila[9]",$fila[4],$fila[2],$fila[3],$fila[7]);
		$Datos1["$fila1[0] - $fila1[7]"]["$fila1[3] $fila1[4] $fila1[1] $fila1[2] - $fila1[8]"][$fila[10]] = array($fila[0],"$fila[1] $fila[9]",$fila[4],$fila[2],$fila[3],$fila[7]);
		$NumRec++;
	}
class PDF extends FPDF
{
	function BasicTable($data)
	{
		global $Relacion;global $NumRec;
		if(!$Relacion){$Relacion = "Todos";}
		$Anchos=array(20,85,30,20,24,16);
		$fill=false;$this->SetFillColor(248,248,248);
		if(!$data){echo "no data";exit;}
		while(list($cad,$val) = each($data))
		{
			$this->SetFillColor(228,228,228);
			$this->SetFont('Arial','B',8);
			$this->Cell(195,5,"RESPONSABLE: ".utf8_decode(strtoupper($cad)),'1',0,'L',1);$this->Ln();
			while(list($cad1,$val1) = each($val))
			{
				$this->SetFont('Arial','B',8);
				$this->Cell(195,5,"CENTRO DE COSTOS: ".utf8_decode(strtoupper($cad1)),"TRL",0,'L');$this->Ln();
				while(list($cad2,$val2) = each($val1))
				{
					if($cad2)
					{
						$this->SetFont('Arial','B',8);
						$this->Cell(195,5,"SUBUBICACION: ".utf8_decode(strtoupper($cad2)),"RLB",0,'L');$this->Ln();
					}
					else
					{
						$this->Cell(195,0,"","T",0,'C');
						$this->Ln();	
					}
					foreach($val2 as $row)
					{
						$x = 0;
						foreach($row as $col)
						{
							$this->SetFont('Arial','',7);
							$POSY=$this->GetY();
							if($x==0){$col = substr($col,0,12);}
							if($x==1){$col=substr($col,0,47);}
							if($x==3){$col=substr($col,0,12);}
							if($x==4){$col=substr($col,0,12);}
							$Lines="LR";
							if($POSY>=250 && $POSY<255){$Lines="LRB";}
							$this->Cell($Anchos[$x],5,utf8_decode(strtoupper($col)),$Lines,0,$Alinea,$fill);
							$x++;
						}
						$this->Ln();
						$cont++;
					}
					$this->Cell(195,0,"","T",0,'C');
					$this->Ln();	
				}
			}
			$this->Cell(195,0,"","T",0,'C');
			$this->Ln();
			$this->Ln(5);
			$this->Cell(97,8,"____________________________________",0,0,'C');
			$this->Cell(97,8,"____________________________________",0,0,'C');
			$this->Ln(5);
			$this->Cell(97,8,"RESPONSABLE SUMINISTROS",0,0,'C');
			$this->Cell(97,8,"RECIBE A CONFORMIDAD",0,0,'C');
			$this->Ln(4);
			$Tercero = explode("-",$cad);
			$this->Cell(97,8,"",0,0,'C');
			$this->Cell(97,8,utf8_decode(strtoupper($Tercero[0])),0,0,'C');
			$this->Ln(4);
			$this->Cell(97,8,"",0,0,'C');
			if($Tercero[2]){$Tercero[1]="$Tercero[1]-$Tercero[2]";}
			$this->Cell(97,8,utf8_decode(strtoupper($Tercero[1])),0,0,'C');
			if($cont != $NumRec)
			{
				$this->AddPage();	
			}
		}
	}

function Header()
{
	global $Compania;global $Anio;global $MesIni;global $DiaIni;global $MesFin;global $DiaFin;global $ND;
    $Raiz = $_SERVER['DOCUMENT_ROOT'];
	$this->Image("$Raiz/Imgs/Logo.jpg",10,5,25,25);
    $this->SetFont('Arial','B',10);
    $this->Cell(0,5,strtoupper($Compania[0]),0,0,'C');
    $this->SetFont('Arial','B',8);
    $this->Ln(5);
    $this->Cell(0,5,strtoupper($Compania[1]),0,0,'C');
    $this->Ln(5);
    $this->Cell(0,5,"INVENTARIO",0,0,'C');
    $this->Ln(5);
    $this->Cell(0,5,"GENERADO: $ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
	$this->Ln(5);
	$this->SetFillColor(228,228,228);

	$this->Cell(20,5,"Codigo",1,0,'C',1);
	$this->Cell(85,5,"Nombre",1,0,'C',1);
	$this->Cell(30,5,"Marca",1,0,'C',1);
	$this->Cell(20,5,"Modelo",1,0,'C',1);
	$this->Cell(24,5,"Serie",1,0,'C',1);
	$this->Cell(16,5,"Estado",1,0,'C',1);
	$this->Ln(5);

}

function Footer()
{
	global $ND;
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    $this->Ln(3);
    $this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
}
}

$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();//Agrega una paguina en blanco al pdf
$pdf->SetFont('Arial','',8);//Fuente documento,negrilla,tamaño letra
$pdf->BasicTable($Datos);
$pdf->Output();
?>
