<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	if($BuscaFormatos){
	$cons="Select Formato from HistoriaClinica.Formatos where TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
	echo $cons;
	$res=ExQuery($cons);
?>
<script language='JavaScript'>
		parent.document.FORMA.Traerde.length=<?echo ExNumRows($res);?>+1;
		<?while($fila=ExFetch($res)){$i++;?>;
		parent.document.FORMA.Traerde.options[<?echo $i?>].value="<? echo $fila[0]?>";
		parent.document.FORMA.Traerde.options[<?echo $i?>].text="<? echo $fila[0]?>";
		<?}?>
</script>
<? }
	if($BuscaItems){
		//$cons="Select Item from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'	Order By Pantalla,Id_Item";
		$cons="Select Item from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' AND (titulo <> 1 OR titulo IS NULL)	Order By Pantalla,Id_Item";
		$res=ExQuery($cons);
		?>
		<script language='JavaScript'>
				parent.document.FORMA.CampoTraerDe.length=<?echo ExNumRows($res);?>+1;
				<?while($fila=ExFetch($res)){$i++;?>;
				parent.document.FORMA.CampoTraerDe.options[<?echo $i?>].value="<? echo $fila[0]?>";
				parent.document.FORMA.CampoTraerDe.options[<?echo $i?>].text="<? echo $fila[0]?>";
				<?}?>
		</script>
		<? 
	}
	if($BuscaTagsXML){
		$consXML="select tag from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$FormatoXML order by orden";		
		$resXML=ExQuery($consXML);		?>		
        <script language="javascript">
			parent.document.FORMA.TagXML.length=<?echo ExNumRows($resXML);?>+1
		<?	while($filaXML=ExFetch($resXML))
			{
				$i++;?>
				parent.document.FORMA.TagXML.options[<?echo $i?>].value="<? echo $filaXML[0]?>";
				parent.document.FORMA.TagXML.options[<?echo $i?>].text="<? echo $filaXML[0]?>";
		<?	}?>
		</script>
<?	}	
	if($BuscaEtiqXML){
		$consXML="select etiqxml from  historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFomarto' and 
		etiqxml!='$EtiqXML' and tagxml='$TagXML'";
		$resXML=ExQuery($consXML);	
		$consXML2="select etiquetaxml from  historiaclinica.dxformatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFomarto' and 
		etiquetaxml!='$EtiqXML' and tagxml='$TagXML'";		
		$resXML2=ExQuery($consXML2);	
    	$consXML="select etiqueta from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$FormatoXML and tag='$TagXML' 
		and etiqueta not in ('0'";
		while($filaNoxml=ExFetch($resXML)){$consXML=$consXML.",'$filaNoxml[0]'";}
		while($filaNoxml2=ExFetch($resXML2)){$consXML=$consXML.",'$filaNoxml2[0]'";}
		$consXML=$consXML.") order by orden";		
		$resXML=ExQuery($consXML);		?>		
        <script language="javascript">
			parent.document.FORMA.EtiqXML.length=<?echo ExNumRows($resXML);?>+1
		<?	while($filaXML=ExFetch($resXML))
			{
				$i++;?>
				parent.document.FORMA.EtiqXML.options[<?echo $i?>].value="<? echo $filaXML[0]?>";
				parent.document.FORMA.EtiqXML.options[<?echo $i?>].text="<? echo $filaXML[0]?>";
		<?	}?>
		</script>
<?	}
	if($BuscaEtiqXML2){
		$consXML="select etiquetaxml from  historiaclinica.dxformatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFomarto' and 
		etiquetaxml!='$EtiqXML' and tagxml='$TagXML'";		
		$resXML=ExQuery($consXML);		
		$consXML2="select etiqxml from  historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFomarto' and 
		etiqxml!='$EtiqXML' and tagxml='$TagXML'";
		$resXML2=ExQuery($consXML2);
    	$consXML="select etiqueta from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$FormatoXML and tag='$TagXML' 
		and etiqueta not in ('0'";
		while($filaNoxml=ExFetch($resXML)){$consXML=$consXML.",'$filaNoxml[0]'";}
		while($filaNoxml2=ExFetch($resXML2)){$consXML=$consXML.",'$filaNoxml2[0]'";}
		$consXML=$consXML.") order by orden";		
		//echo $consXML;
		$resXML=ExQuery($consXML);		?>		
        <script language="javascript">
			parent.document.getElementById('<? echo $NomSelecEtiqXML?>').length=<? echo ExNumRows($resXML);?>+1			
		<?	while($filaXML=ExFetch($resXML))
			{ 
				$i++;?>
				parent.document.FORMA.<? echo "$NomSelecEtiqXML"?>.options[<?echo $i?>].value="<? echo $filaXML[0]?>";
				parent.document.FORMA.<? echo "$NomSelecEtiqXML"?>.options[<?echo $i?>].text="<? echo $filaXML[0]?>";
				//parent.document.getElementById('<? echo $NomSelecEtiqXML?>').value="59";
				//parent.document.getElementById('<? echo $NomSelecEtiqXML?>').text="50";				
		<?	}?>
		</script>
<?	}
	if($CambiarValXML&&$EtiqXML){
		
		$cons="select longitud,obliga,tipodato,descripcion from  historiaclinica.etiquetasxformatoxml 
		where compania='$Compania[0]' and formato=$FormatoXML and tag='$TagXML' and etiqueta='$EtiqXML' order by orden";		
		$res=ExQuery($cons);
		$fila=ExFetch($res);?>
        <script language="javascript">
		<?	if($fila[2]=="Cadena"){?>
				parent.document.FORMA.TipoDato.value="C";	
				parent.document.FORMA.Longitud.value="<? echo $fila[0]?>";
		<?	}
			if($fila[1]=="Si"){?>
				parent.document.FORMA.Obligatorio.checked=true;
		<?	}
			else{?>
				parent.document.FORMA.Obligatorio.checked=false;
		<?	}?>
			if(parent.document.FORMA.TIP.value==""){
				parent.document.FORMA.TIP.value="<? echo $fila[3]?>";
			}
		</script>
<?	}?>	