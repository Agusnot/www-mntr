<?
	function Quitar_TransaccionTmp($Tabla)
	{
		global $Compania; global $Paciente;	
		$cons = "Update $Tabla set TransaccionTmp=NULL, Eliminar=NULL Where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
		ExQuery($cons);	
	}	
	function  Borrar_Temporales($Tabla,$TMPCOD,$TablaQT)
	{
		global $Compania; global $Paciente;
		Quitar_TransaccionTmp($TablaQT);
		$cons = "Delete from $Tabla Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Identificacion='$Paciente[1]'";
		ExQuery($cons);	
	}
	
	function Clear_Table($TableName,$TMPCOD,$CamposNull,$Editar)
	{
		global $Compania;
		if(!$Editar)
		{
			$Campos = "and ".str_replace(","," is NULL and ",$CamposNull)." is NULL";
			$cons = "Delete from $TableName Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' $Campos";
			ExQuery($cons);			
		}
	}
	
	function Modify_Table($TableName,$TMPCOD,$Campos,$CamposMod,$Valores,$Editar)
	{
		global $Compania;
		//Los Campos cambios deben tener el mismo tamaño de los valores cambio
		if(!$Editar)
		{
			$CamposUpt=explode(",",$CamposMod);
			$ValoresUpt=explode(",",$Valores);
			for($i=0;$i<count($CamposUpt);$i++)
			{
				if($ValoresUpt[$i]!="NULL"){$ValoresUpt[$i]="'$ValoresUpt[$i]'";}
				$Upt = $Upt." ".$CamposUpt[$i]." =".$ValoresUpt[$i].",";	
			}
			$Upt = substr($Upt,0,strlen($Upt)-1);
			$CamposCond = "and ".str_replace(","," is NULL and ",$Campos)." is NULL";
			$cons = "Update $TableName set $Upt Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' $CamposCond";
			$res = ExQuery($cons);
		}
	}
	
	function Delete_Item($TableName,$Identificadores,$ValoresIdentificadores)
	{
		global $Compania;
		$CamposDelete=explode("|",$Identificadores);
		$ValoresDelete=explode("|",$ValoresIdentificadores);
		$cons = "Delete from $TableName Where ";
		for($i=0;$i<count($CamposDelete);$i++)
		{
			if($ValoresDelete[$i]!="NULL"){$ValoresDelete[$i]="'$ValoresDelete[$i]'";}
			$cons = $cons.$CamposDelete[$i]." = ".$ValoresDelete[$i]." and ";
		}
		$cons = $cons." Compania = '$Compania[0]'";
		$res = ExQuery($cons);
	}
	
	function Update_Item($TableName,$IdentificadoresUPT,$ValoresUPT,$IdentificadoresCodicion,$ValoresCondicion)
	{
		global $Compania;
		$CamposUpt = explode("|",$IdentificadoresUPT);
		$NewValoresUpt = explode("|",$ValoresUPT);
		$CamposCond = explode("|",$IdentificadoresCodicion);
		$Condiciones = explode("|",$ValoresCondicion);
		$cons = "Update $TableName set ";
		for($i=0;$i<count($CamposUpt);$i++)
		{
			if($NewValoresUpt[$i]!="NULL"){$NewValoresUpt[$i]="$NewValoresUpt[$i]";}
			$cons = $cons.$CamposUpt[$i]." = ".$NewValoresUpt[$i];
			if($i != count($CamposUpt)-1){$cons = $cons.",";}	
		}
		$cons = $cons." Where ";
		for($i=0;$i<count($CamposCond);$i++)
		{
			if($Condiciones[$i]=="NULL" || $Condiciones[$i]=="not NULL")
			{
				$cons = $cons.$CamposCond[$i]." is ".$Condiciones[$i]." and ";	
			}
			else
			{
				$cons = $cons.$CamposCond[$i]." = '".$Condiciones[$i]."' and ";	
			}	
		}
		$cons = $cons." Compania='$Compania[0]'";
		$res = ExQuery($cons);
	}
?>
