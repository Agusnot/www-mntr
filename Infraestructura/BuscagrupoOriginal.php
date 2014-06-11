<?
	session_start();
	include("Funciones.php");
	//echo $Codigo;
	$ND = getdate();
	$cons = "Select ModoDeprecia,ValorDeprecia,CodGrupo,NumInicial from Infraestructura.GruposdeElementos Where Compania='$Compania[0]' and Grupo ilike '$Grupo'";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$DepEn = $fila[0];
	$DepDur = $fila[1];
	$cons = "Select Codigo From Infraestructura.CodElementos Where Compania='$Compania[0]' and Grupo ilike '$Grupo' and Codigo IS NOT NULL Order by Codigo desc";
	$res = ExQuery($cons);
	if($Tipo != "Orden Compra")
	{
		if(!$Codigo)
		{
			if(ExNumRows($res)>0)
			{
				$fila = ExFetch($res);
				while($fila[0]*1==0)
				{
					$fila = ExFetch($res);
				}
				$Codigo = $fila[0] + 1;
				$CodGrupo = substr($Codigo,0,-6);
			}
			else
			{
				$cons1 = "Select CodGrupo,NumInicial from Infraestructura.GruposdeElementos Where Compania='$Compania[0]' and Grupo ilike '$Grupo' and Anio=$ND[year]";
				$res1 = ExQuery($cons1);
				$fila1 = ExFetch($res1);
				$Codigo = $fila1[0].$fila1[1];
				$CodGrupo = $fila1[0];	
			}	
		}
		else
		{
			$CodGrupo = substr($Codigo,0,-6);	
		}
?>
<script language="javascript">
	parent.document.FORMA.DepEn.value = "<? echo $DepEn?>";
	parent.document.FORMA.DepDur.value = "<? echo $DepDur?>";
	parent.document.FORMA.Codigo.value="<? echo $Codigo?>";
	parent.document.FORMA.Codigo.style.fontSize="<? echo 11-((strlen($CodGrupo)-4)/2);?>px";
</script>
<?	
	}
	
