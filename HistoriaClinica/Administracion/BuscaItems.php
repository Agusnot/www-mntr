<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	if($NewFormato){
	$cons="Select Parametro from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and TipoControl='Lista Opciones' and Item='$Item' 	";

	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$ValFila=$fila[0];
	$ValFila=explode(";",$ValFila);
	$c=count($ValFila);		
	$j=0;
	for($i=0;$i<=(count($ValFila)-1);$i++){	
		$cons="select vritem from historiaclinica.cupsxformatos where compania='$Compania[0]' and vritem='$ValFila[$i]' and  formato='$NewFormato' and tipoformato='$TF'";
		$res=ExQuery($cons);
		if(ExNumRows($res)<=0){
			$Aux[$j]=$ValFila[$i];
			$j++;
		}
	}
?>
<script language='JavaScript'>
	parent.document.form1.VrItem.length=<? echo count($Aux);?>;
	<? 	for($i=0;$i<=(count($Aux)-1);$i++){		
			?>
			parent.document.form1.VrItem.options[<?echo $i?>].value="<?echo $Aux[$i]?>";
			parent.document.form1.VrItem.options[<?echo $i?>].text="<?echo $Aux[$i]?>";
	<?	}	?>
	
</script>
<?}?>