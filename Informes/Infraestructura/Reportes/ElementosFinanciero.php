<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	require('LibPDF/fpdf.php');
	$ND=getdate();
	$cons = "Select AutoId,Sum(VrDepreciacion) from Infraestructura.Depreciaciones Where Compania='$Compania[0]' Group by AutoId";
	$res = ExQuery($cons);
	while($fila = ExFetch($res)){$Dep[$fila[0]] = $fila[1];}
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
	$cons = "Select distinct CodElementos.AutoId,CodElementos.Codigo,Nombre,Caracteristicas,Estado,CostoInicial,DepAcumulada,Grupo,AxICostoIni, AxIDepAcumulada
	$CampoSelect
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
		Where Ubicaciones.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AutoId=$fila[0] and Terceros.Identificacion = Ubicaciones.Responsable
		and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year] order by FechaFin desc";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		if(!$fila1[9]){$fila1[9]="N/A";}
		$DepAcumulada = $fila[6] + $Dep[$fila[0]];
		$Saldo = $fila[5] - $DepAcumulada;
		$Datos["$fila1[3] $fila1[4] $fila1[1] $fila1[2] - $fila1[8]"]["$fila1[0] - $fila1[7]"]["$fila1[9]"][$fila[0]]= 
                array($fila[1],"$fila[2] $fila[3]",$fila[4],$fila[5],$fila[8],$DepAcumulada,$fila[9],$Saldo);
		$NumRec++;
	}
class PDF extends FPDF
{
	function BasicTable($data)
	{
		global $Relacion;global $NumRec;
		if(!$Relacion){$Relacion = "Todos";}
		$Anchos=array(25,85,16,25,27,25,27,25);
		$fill=false;$this->SetFillColor(248,248,248);
		if(!$data){echo "no data";exit;}
		while(list($cad,$val) = each($data))
		{
			$this->SetFillColor(228,228,228);
			$this->SetFont('Arial','B',8);
			$TotTCostosIni = 0;
			$TotTDepAcumul = 0;
			$TotTSaldos = 0;
			$this->Cell(255,5,"RESPONSABLE: ".utf8_decode(strtoupper($cad)),'1',0,'L',1);$this->Ln();
			$TotTCostosIni = 0;
                        $TotTDepAcumul = 0;
			$TotTSaldos = 0;
                        $TotAxICI = 0;
                        $TotAxIDA = 0;
                        while(list($cad1,$val1) = each($val))
			{
				$this->SetFont('Arial','B',8);
				$TotCCCostosIni = 0;
				$TotCCDepAcumul = 0;
				$TotCCSaldos = 0;
                                $TotCCAxICI = 0;
                                $TotCCAxIDA = 0;
				$this->Cell(255,5,"CENTRO DE COSTOS: ".utf8_decode(strtoupper($cad1)),"TRL",0,'L');$this->Ln();
				while(list($cad2,$val2) = each($val1))
				{
					if($cad2)
					{
						$this->SetFont('Arial','B',8);
						$this->Cell(255,5,"SUBUBICACION: ".utf8_decode(strtoupper($cad2)),"RLB",0,'L');$this->Ln();
					}
					else
					{
						$this->Cell(255,0,"","T",0,'C');
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
							if($x==1){$col = substr($col,0,47);}
							if($x==3){$TotCCCostosIni = $TotCCCostosIni + $col;}
							if($x==4){$TotCCAxICI = $TotCCAxICI + $col;}
                                                        if($x==5){$TotCCDepAcumul = $TotCCDepAcumul + $col;}
							if($x==6){$TotCCAxIDA = $TotCCAxIDA + $col;}
                                                        if($x==7){$TotCCSaldos = $TotCCSaldos + $col;}
                                                        if($x>=3){$Alinea='R';$col=number_format($col,2);}
							else{$Alinea='L';}
							$Lines="LR";
							if($POSY>=190 && $POSY<195){$Lines="LRB";}
							$this->Cell($Anchos[$x],5,utf8_decode(strtoupper($col)),$Lines,0,$Alinea,$fill);
							$x++;
						}
						$this->Ln();
						$cont++;
					}
					$this->Cell(255,0,"","T",0,'C');
					$this->Ln();	
				}
				$this->SetFont('Arial','B',8);
				$this->Cell(126,5,"TOTALIZADO CENTRO DE COSTOS","TRL",0,'R');
				$this->Cell(25,5,number_format($TotCCCostosIni,2),"TRL",0,'R');
                                $this->Cell(27,5,number_format($TotCCAxICI,2),"TRL",0,'R');
				$this->Cell(25,5,number_format($TotCCDepAcumul,2),"TRL",0,'R');
				$this->Cell(27,5,number_format($TotCCAxIDA,2),"TRL",0,'R');
                                $this->Cell(25,5,number_format($TotCCSaldos,2),"TRL",0,'R');
                                $this->Ln();
				$TotTCostosIni = $TotTCostosIni + $TotCCCostosIni;
				$TotAxICI = $TotAxICI + $TotCCAxICI;
                                $TotTDepAcumul = $TotTDepAcumul + $TotCCDepAcumul;
				$TotAxIDA = $TotAxIDA + $TotCCAxIDA;
                                $TotTSaldos = $TotTSaldos + $TotCCSaldos;
                                
			}
			$this->SetFillColor(228,228,228);
			$this->Cell(126,5,"TOTALIZADO TERCERO: ",'1',0,'R',1);
			$this->Cell(25,5,number_format($TotTCostosIni,2),"TRL",0,'R');
                        $this->Cell(27,5,number_format($TotAxICI,2),"TRL",0,'R');
			$this->Cell(25,5,number_format($TotTDepAcumul,2),"TRL",0,'R');
			$this->Cell(27,5,number_format($TotAxIDA,2),"TRL",0,'R');
                        $this->Cell(25,5,number_format($TotTSaldos,2),"TRL",0,'R');
                        $this->Ln();
			$this->Cell(255,0,"","T",0,'C');
			$this->Ln();
			$this->Ln(5);
			$this->Cell(125,8,"____________________________________",0,0,'C');
			$this->Cell(125,8,"____________________________________",0,0,'C');
			$this->Ln(5);
			$this->Cell(125,8,"RESPONSABLE SUMINISTROS",0,0,'C');
			$this->Cell(125,8,"RECIBE A CONFORMIDAD",0,0,'C');
			$this->Ln(4);
			$Tercero = explode("-",$cad);
			$this->Cell(125,8,"",0,0,'C');
			$this->Cell(125,8,utf8_decode(strtoupper($Tercero[0])),0,0,'C');
			$this->Ln(4);
			$this->Cell(125,8,"",0,0,'C');
			if($Tercero[2]){$Tercero[1]="$Tercero[1]-$Tercero[2]";}
			$this->Cell(125,8,utf8_decode(strtoupper($Tercero[1])),0,0,'C');
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
    $this->Cell(25,5,"Codigo",1,0,'C',1);
    $this->Cell(85,5,"Nombre",1,0,'C',1);
    $this->Cell(16,5,"Estado",1,0,'C',1);
    $this->Cell(25,5,"Costo Inicial",1,0,'C',1);
    $this->Cell(27,5,"Ajustes x Inf",1,0,'C',1);
    $this->Cell(25,5,"Dep. Acumulada",1,0,'C',1);
    $this->Cell(27,5,"Ajustes x Inf",1,0,'C',1);
    $this->Cell(25,5,"Saldo",1,0,'C',1);
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

$pdf=new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$pdf->BasicTable($Datos);
$pdf->Output();
?>
