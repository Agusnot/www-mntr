<?php
	$EnginneDef=2;

	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$UserPgsql="postgres";
		}
	else
	{
		$UserPgsql="apache";
	}

	if($EnginneDef==1){$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');}
	if($EnginneDef==2){//$lev=error_reporting ('Err'); 
	$conex = pg_connect("dbname=sistema user=$UserPgsql password=Server*1982") or die ('no establecida');}

 date_default_timezone_set('America/Bogota');
 define("UTF_8", 1); 
 define("ASCII", 2); 
 define("ISO_8859_1", 3); 
 function codificacion($texto) 
 { 
     $c = 0; 
     $ascii = true; 
     for ($i = 0;$i<strlen($texto);$i++) { 
         $byte = ord($texto[$i]); 
         if ($c>0) { 
             if (($byte>>6) != 0x2) { 
                 return ISO_8859_1; 
             } else { 
                 $c--; 
             } 
         } elseif ($byte&0x80) { 
             $ascii = false; 
             if (($byte>>5) == 0x6) { 
                 $c = 1; 
             } elseif (($byte>>4) == 0xE) { 
                 $c = 2; 
             } elseif (($byte>>3) == 0x14) { 
                 $c = 3; 
             } else { 
                 return ISO_8859_1; 
             } 
         } 
     } 
     return ($ascii) ? ASCII : UTF_8; 
 } 

 function utf8_decode_seguro($texto) 
 { 
     return (codificacion($texto)==ISO_8859_1) ? $texto : utf8_decode($texto); 
 } 
 
	function ExAfectedRows($ExRes)
	{
		global $EnginneDef;
		$CQuery[1]="mysql_affected_rows";
		$CQuery[2]="pg_affected_rows";
		eval("\$VarDev=$CQuery[$EnginneDef](\$ExRes);");
		return $VarDev;
	}
	function ExFetchObj($ExRes)
	{
		global $EnginneDef;
		$CQuery[1]="mysql_fetch_field";
		$CQuery[2]="pg_fetch_result";
		eval("\$VarDev=$CQuery[$EnginneDef](\"$ExCons\");");
		return $VarDev;
	}

	function ExQuery($ExCons)
	{
		global $EnginneDef;
		$CQuery[1]="mysql_query";
		$CQuery[2]="pg_query";
		@eval("\$VarDev=$CQuery[$EnginneDef](\"$ExCons\");");
		$ValError=ExError();
		if(substr($ValError,0,5)=="ERROR")
		{
			echo "<font style='font-family:$Estilo[8];font-size:11px;font-style:$Estilo[10]'>Se genero este error en: <font style='color:red'><em>$ExCons</em></font><br><font style='color:blue'>". $ValError."</font></font>";
		}
		return $VarDev;
	}
	function ExFetch($ExRes)
	{
		global $EnginneDef;
		$CFetch[1]="mysql_fetch_row";
		$CFetch[2]="pg_fetch_row";
		@eval("\$VarDev=$CFetch[$EnginneDef](\$ExRes);");
		return $VarDev;
	}
	function ExFetchArray($ExRes)
	{
		global $EnginneDef;
		$CFetch[1]="mysql_fetch_array";
		$CFetch[2]="pg_fetch_array";
		eval("\$VarDev=$CFetch[$EnginneDef](\$ExRes);");
		return $VarDev;
	}
	function ExNumRows($ExRes)
	{
		global $EnginneDef;
		$CFetch[1]="mysql_num_rows";
		$CFetch[2]="pg_num_rows";
		@eval("\$VarDev=$CFetch[$EnginneDef](\$ExRes);");
		return $VarDev;
	}
	
	function ExNumFields($ExRes)
	{
		global $EnginneDef;
		$CFetch[1]="mysql_num_fields";
		$CFetch[2]="pg_num_fields";
		eval("\$VarDev=$CFetch[$EnginneDef](\$ExRes);");
		return $VarDev;
	}

	function ExError()
	{
		global $EnginneDef;
		$CFetch[1]="mysql_error()";
		$CFetch[2]="pg_last_error()";
		eval("\$VarDev=$CFetch[$EnginneDef];");
		return $VarDev;
	}

	function ExErrorNo()
	{
		global $EnginneDef;
		$CFetch[1]="ExErrorno()";
		eval("\$VarDev=$CFetch[$EnginneDef];");
		return $VarDev;
	}
	
	function ExFieldLen($ExRes,$NoCmp)
	{
		global $EnginneDef;
		$CFetch[1]="mysql_field_len";
		$CFetch[2]="pg_field_size";
		eval("\$VarDev=$CFetch[$EnginneDef](\$ExRes,\$NoCmp);");
		return $VarDev;
	}

	function ExListTables($BaseDatos)
	{
		global $EnginneDef;
		$CFetch[1]="mysql_listtables";
		eval("\$VarDev=$CFetch[$EnginneDef](\$BaseDatos);");
		return $VarDev;
	}


	function ExFieldName($res,$i)
	{
		global $EnginneDef;
		$CFetch[1]="mysql_field_name";
		$CFetch[2]="pg_field_name";
		eval("\$VarDev=$CFetch[$EnginneDef](\$res,\$i);");
		return $VarDev;
	}

	function LibErrores($NumError)
	{
		if($NumError==1451)
		{
			$Mensaje="El registro que pretende editar esta siendo usado en otra configuracion o hace parte del movimiento de una tabla, NO es posible cambiar";		
		}				
		return $Mensaje;
	}
	function Firmas($Fecha,$Compania)
	{
		$cons="Select Nombre,Cargo,Categoria from Central.CargosxCompania where Compania='$Compania[0]' and FechaIni<='$Fecha' and FechaFin>='$Fecha'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$ListaCargos[$fila[2]]=array($fila[0],$fila[1]);
		}
		return $ListaCargos;
	}
	
	function ConsecutivoComp($NomComprobante,$Anio,$BaseDatos)
	{
		global $Compania;
		$BaseDatos=explode("|",$BaseDatos);
		$ClaseVigencia=$BaseDatos[1];
		if(!$ClaseVigencia){$Vigencia="Actual";}
		else{$Vigencia="Anteriores";}
		$Tabla="Movimiento";
		if($BaseDatos[0]=="Presupuesto"){$condAdc=" and Vigencia='$Vigencia'";}elseif($BaseDatos[0]=="ContratacionAdmin"){$Tabla="Contratos";}
		
		if($NomComprobante=='Amortizaciones'||$NomComprobante=="Movimientos bancarios pagos de clientes")
		   $cons="Select Numero from $BaseDatos[0].$Tabla where Comprobante='$NomComprobante' and date_part('year',Fecha)=$Anio and Compania='$Compania[0]' 		$condAdc  
			      Order By Numero::integer Desc limit 1";
		   else 
		       $cons="Select Numero from $BaseDatos[0].$Tabla where Comprobante='$NomComprobante' and date_part('year',Fecha)=$Anio and Compania='$Compania[0]' 		$condAdc  
			          Order By Numero Desc limit 1";
		
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			$cons2="Select NumeroInicial from $BaseDatos[0].Comprobantes where Comprobante='$NomComprobante' and Compania='$Compania[0]'";
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);
			$Consec=$Anio.$fila2[0];
		}
		else
		{
			$fila=ExFetch($res);
			$Consec=$fila[0]+1;
		}
		return $Consec;
	}
	function ObtenEdad($FechaNac)
	{
		$AnioNac=substr($FechaNac,0,4);
		$MesNac=substr($FechaNac,5,2);
		$DiaNac=substr($FechaNac,8,2);
		$FecAct=getdate();
		$AnioAct=$FecAct[year];
		$MesAct=$FecAct[mon];
		$DiaAct=$FecAct[mday];
		$Edad=$AnioAct-$AnioNac;
		if($MesAct==$MesNac)
		{
			if($DiaAct<$DiaNac)
			{				
				$Edad=$Edad-1;				
			}
		}
		elseif($MesAct<$MesNac)
		{
			$Edad=$Edad-1;
		}
		if($Edad>100){$Edad="";}
		if($Edad==-1){$Edad="0";}
		return $Edad;
	}
	function ObtenMesesEnEdad($FechaNac){
		$FecNac=explode("-",$FechaNac);
		$FecAct=getdate();
				
		$MR=$FecAct[mon]-$FecNac[1];
		
		if($FecAct[mon]>$FecNac[1]){
			$MR=$FecAct[mon]-$FecNac[1];
		}			
		else{
			if($FecAct[mon]==$FecNac[1]){
				if($FecAct[mday]<$FecNac[2])
				{
					$MR=11;
				}																		
			}
			else{
				$MR=12-($FecNac[1]-$FecAct[mon]);
				if($MR<0){$MR=$MR*-1;}
			}
		}
		
		return $MR;
	}	
	function ObtenDiasEnEdad($FechaNac)
	{
		$FecNac=explode("-",$FechaNac);
		$FecAct=getdate();
		$DR=$FecAct[mday]-$FecNac[2];
		if($FecNac[2]>$FecAct[mday]){			
			if($FecNac[1]=='01'||$FecNac[1]=='03'||$FecNac[1]=='05'||$FecNac[1]=='07'||$FecNac[1]=='08'||$FecNac[1]=='10'||$FecNac[1]=='12'){
				$UDM=31;
			}
			else{
				if($FecNac[1]=='02'){
					if((($FecNac[0]%4==0) && ($FecNac[0]%100!=0)) || $FecNac[0]%400==0){
						$UDM=29;
					}
					else{
						$UDM=28;
					}
				}
				else{
					$UDM=30;
				}
			}
			//alert(UDM);					
			$DR=$UDM-($FecNac[2]-$FecAct[mday]);
		}
		return $DR;
	}
	Function NumerosxLet($Num)
	{
		$PartesNum=explode(".",$Num);
		if($PartesNum[1]<10){$PartesNum[1]=$PartesNum[1]."0";}
		if($PartesNum[1]=="00"){$PartesNum[1]="";}
		$Num=$PartesNum[0];
		global $Un,$De, $Ce, $Mi,$Letras;
	    $Un = "";
	    $De = "";
    	$Ce = "";
	    $Mi = "";
	    $Adc = "";
	    switch (strlen($Num))
		{
		    Case 1:	$Vr = $Num;
    	    		Unidades($Vr);
					break;
		    Case 2:	$Vr = substr($Num, 0, 1);
			        Decimas ($Vr);
			        $Vr = substr($Num, 1, 1);
			        Unidades($Vr);break;
			Case 3:	$Vr = substr($Num, 0, 1);
			        Centecimas($Vr);
			        $Vr = substr($Num, 1, 1);
			        Decimas($Vr);
        			$Vr = substr($Num, 2, 1);
	 		       	Unidades($Vr);break;
		    Case 4:	$Vr = substr($Num, 0, 1);
					Unidades($Vr);
			        if($Un== "Un"){$Un="";}
			        $Mi = $Un . " Mil ";
			        $Vr = substr($Num, 1, 1);
			        Centecimas($Vr);
					$Vr = substr($Num, 2, 1);
        			Decimas($Vr);
			        $Vr = substr($Num, 3, 1);
        			Unidades($Vr);break;
			Case 5:	$Vr = substr($Num, 0, 1);
					Decimas($Vr);
					$Vr = substr($Num, 1, 1);
					Unidades($Vr);
					$Mi = $De . $Un . " mil ";
			        $Vr = substr($Num, 2, 1);
			        Centecimas($Vr);
        			$Vr = substr($Num, 3, 1);
			        Decimas($Vr);
        			$Vr = substr($Num, 4, 1);
			        Unidades($Vr);break;
		    Case 6:	$Vr = substr($Num, 0, 1);
        			Centecimas($Vr);
        			$Vr = substr($Num, 1, 1);
			        Decimas($Vr);
        			$Vr = substr($Num, 2, 1);
			        Unidades($Vr);
			        $Mi = $Ce . $De . $Un . " mil ";
        			$Vr = substr($Num, 3, 1);
        			Centecimas($Vr);
        			$Vr = substr($Num, 4, 1);
        			Decimas($Vr);
        			$Vr = substr($Num, 5, 1);
        			Unidades($Vr);break;
		    Case 7: $Vr = substr($Num, 0, 1);
			        Unidades($Vr);
        			if($Un=="Un"){$Mill = "Un Millon ";}
			        else{$Mill = $Un . " Millones ";}
					$Vr = substr($Num, 1, 1);
			        Centecimas($Vr);
        			$Vr=substr($Num, 2, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 3, 1);
			        Unidades($Vr);
        			$Mi = $Ce . $De . $Un . " mil ";
			        if($Mi==" mil "){$Mi = "";}
			        $Vr = substr($Num, 4, 1);
			        Centecimas($Vr);
			        $Vr = substr($Num, 5, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 6, 1);
			        Unidades($Vr);break;
		    Case 8:	$Vr = substr($Num, 0, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 1, 1);
			        Unidades($Vr);
			        $Mill = $De . $Un . " Millones ";
			        $Vr = substr($Num, 2, 1);
			        Centecimas($Vr);
			        $Vr = substr($Num, 3, 1);
		        	Decimas($Vr);
			        $Vr = substr($Num, 4, 1);
			        Unidades($Vr);
			        $Mi = $Ce . $De . $Un . " mil ";
			        if($Mi==" mil "){$Mi="";}
					$Vr = substr($Num, 5, 1);
        			Centecimas($Vr);
			        $Vr = substr($Num, 6, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 7, 1);
			        Unidades($Vr);break;

		    Case 9:	
					$Vr = substr($Num, 0, 1);
					Centecimas($Vr);
					$Vr = substr($Num, 1, 1);
					Decimas($Vr);
					$Vr = substr($Num, 2, 1);
					Unidades($Vr);
					$Mill = $Ce . $De . $Un . " Millones ";
			        $Vr = substr($Num, 3, 1);
			        Centecimas($Vr);
			        $Vr = substr($Num, 4, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 5, 1);
			        Unidades($Vr);
					$Mi = $Ce . $De . $Un . " mil ";
					if($Mi==" mil "){$Mi="";}
					$Vr = substr($Num, 6, 1);
			        Centecimas($Vr);
			        $Vr = substr($Num, 7, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 8, 1);
			        Unidades($Vr);break;


		    Case 10:
			        $Vr = substr($Num, 0, 1);
			        Unidades($Vr);
					$MilMill = $Un . " mil ";
					if($MilMill=="Un mil "){$MilMill=" mil ";}
					$Vr = substr($Num, 1, 1);
					Centecimas($Vr);
					$Vr = substr($Num, 2, 1);
					Decimas($Vr);
					$Vr = substr($Num, 3, 1);
					Unidades($Vr);
					$Mill = $Ce . $De . $Un . " Millones ";
			        $Vr = substr($Num, 4, 1);
			        Centecimas($Vr);
			        $Vr = substr($Num, 5, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 6, 1);
			        Unidades($Vr);
					$Mi = $Ce . $De . $Un . " mil ";
					if($Mi==" mil "){$Mi="";}
					$Vr = substr($Num, 7, 1);
			        Centecimas($Vr);
			        $Vr = substr($Num, 8, 1);
			        Decimas($Vr);
			        $Vr = substr($Num, 9, 1);
			        Unidades($Vr);

		}
		if($Mill && !$Mi && !$Ce && !$De && !$Un && !$Adc){$Letras = $MilMill . $Mill . $Mi . $Ce . $De . $Un . $Adc . " de pesos ";}
		else{ $Letras = $MilMill . $Mill . $Mi . $Ce . $De . $Un . $Adc . " pesos ";}
	   $ParteEntera=$Letras;
		if($PartesNum[1])
		{
			$PedDec=NumerosxLet($PartesNum[1]);
			$ParteDec=substr($PedDec,0,strlen($PedDec)-15);
			$Letras= $ParteEntera . " con $ParteDec centavos ";
			
		}
		$Letras=$Letras." m/cte. ";
	   return $Letras;
	}  //TERMINA LA FUNCION
	
	function Unidades($Valor)
	{
		global $Un,$De, $Ce, $Mi,$Letras;
		$Un="";
	    switch($Valor)
		{
	    Case 0:	if($De=="Dieci"){$De="diez ";$Un = "";}
        		elseif($De=="Veinti"){$De = "veinte ";$Un = "";}
				if($Ce=="Ciento " && $De== ""){$Ce = "Cien";$Un = "";}
		        else{$Un = "";}
				break;
	    Case 1:	if($De=="Dieci"){$De="Once";$Un=="";}
				else
				{
		            if($De!="" && $De != "Veinti"){$Un = " Y un";}
		            else{$Un = "Un";}
				}
				break;
		Case 2:	if($De=="Dieci"){$De="Doce";$Un="";}
				else
				{
	            	if($De!="" && $De!="Veinti"){$Un=" Y dos";}
		            else{$Un = "Dos";}
				}
				break;
	    Case 3:	if($De=="Dieci"){$Un="";$De="Trece";}
		        else
				{
		            if($De!="" && $De !="Veinti"){$Un = " Y tres";}
		            else{$Un="Tres";}
				}
				break;
    	Case 4:	if($De=="Dieci"){$De = "Catorce";$Un = "";}
				else
				{
		            if($De!="" && $De!= "Veinti"){$Un = " Y Cuatro";}
		            else{$Un = "Cuatro";}
				}
				break;
	    Case 5:	if($De=="Dieci"){$De = "Quince";$Un = "";}
		        else
				{
		            if($De!="" && $De!="Veinti"){$Un = " Y cinco";}
            		else{$Un = "Cinco";}
				}
				break;
	    Case 6:	if($De!="Dieci" && $De!="" && $De!="Veinti"){$Un=" y seis";}
		        else{$Un="Seis";}
				break;
	    Case 7:	if($De!="Dieci" && $De!="" && $De!="Veinti"){$Un = " y siete";}
		        else{$Un = "Siete";}
				break;
	    Case 8:	if($De!="Dieci" && $De!="" && $De!="Veinti"){$Un = " y ocho";}
		        else{$Un = "Ocho";}
				break;
	    Case 9:	if($De!="Dieci" && $De!="" && $De!="Veinti"){$Un = " y nueve";}
		        else{$Un="Nueve";}
		}
	}//FIN DE FUNCION

	function Decimas($Valor)
	{
		global $Un,$De, $Ce, $Mi,$Letras;
	    switch($Valor)
		{
		    Case 0:$De = "";break;
		    Case 1:$De = "Dieci";break;
		    Case 2:$De = "Veinti";break;
		    Case 3:$De = "Treinta";break;
		    Case 4:$De = "Cuarenta";break;
		    Case 5:$De = "Cincuenta";break;
		    Case 6:$De = "Sesenta";break;
		    Case 7:$De = "Setenta";break;
		    Case 8:$De = "Ochenta";break;
		    Case 9:$De = "Noventa";break;
		}
	}//FIN FUNCION

	function Centecimas($Valor)
	{
		global $Un,$De, $Ce, $Mi,$Letras;
	    switch($Valor)
		{
		    Case 0:$Ce = "";break;
		    Case 1:$Ce = "Ciento ";break;
		    Case 2:$Ce = "Doscientos ";break;
		    Case 3:$Ce = "Trescientos ";break;
		    Case 4:$Ce = "Cuatrocientos ";break;
		    Case 5:$Ce = "Quinientos ";break;
		    Case 6:$Ce = "Seiscientos ";break;
		    Case 7:$Ce = "Setecientos ";break;
		    Case 8:$Ce = "Ochocientos ";break;
		    Case 9:$Ce = "Novecientos ";break;
		}
	}
	function ObtenPrecCost($CodProducto)
	{
		$cons="Select Saldo,TotCosto from CodProductos where Codigo='$CodProducto'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$SaldoIni=$fila[0];$VrSaldo=$fila[1];
		$cons="Select sum(Cantidad),sum(TotCosto) from Movimientos where (Tipo='SUM' Or Tipo='SIN') and Codigo='$CodProducto'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Entradas=$fila[0];$VrEntradas=$fila[1];
		$cons="Select sum(Cantidad),Sum(TotCosto) from Movimientos where Tipo='SAL' and Codigo='$CodProducto'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Salidas=$fila[0];$VrSalidas=$fila[1];
		
		$Existencias=$SaldoIni+$Entradas-$Salidas;
		$Saldos=$VrSaldo+$VrEntradas-$VrSalidas;
		if($Existencias>0)
		{
			$PasaSald[0]=$Saldos/$Existencias;
			$PasaSald[1]=$Existencias;
		}
		else{$PasaSald[0]=0;$PasaSald[1]=0;}
		return $PasaSald;
	}
	
	function UltimoDia($anho,$mes)
	{ 
		if(((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) 
		{ 
			$dias_febrero = 29; 
		} 
		else 
		{ 
			$dias_febrero = 28; 
		} 
		switch($mes) 
		{ 
			case 01: return 31; break; 
			case 02: return $dias_febrero; break; 
			case 03: return 31; break; 
			case 04: return 30; break; 
			case 05: return 31; break; 
			case 06: return 30; break; 
			case 07: return 31; break; 
			case 08: return 31; break; 
			case 8: return 31; break; 
			case 09: return 30; break; 
			case 9: return 30; break; 
			case 10: return 31; break; 
			case 11: return 30; break; 
			case 12: return 31; break; 
		} 
	} 
	function CNumeros($Numero,$Signo)
	{
		if (strlen($Numero)>3)
		{
			$nc=0;
			for($i=0;$i<=strlen($Numero);$i++)
			{
				$nc++;
				if($nc==4 && $i!=strlen($Numero))
				{
					$nc=1;
					$NuevoNumero= "." . substr($Numero,strlen($Numero)-$i,1) . $NuevoNumero;
				}
				else
				{
					$NuevoNumero=substr($Numero,strlen($Numero)-$i,1) . $NuevoNumero;
				}
			}
		}
		else
		{
			$NuevoNumero=$Numero;
		}
		return $Signo . $NuevoNumero;
	}
	function NumDias($FechaI,$FechaF)
	{
		$FecIng=explode(" ",$FechaI);		
		$FI=explode("-",$FecIng[0]);
		//echo "$FI[0] - ".number_format($FI[1],0)." - $FI[2] ---> ";
		$FecFin=explode(" ",$FechaF);		
		$FF=explode("-",$FecFin[0]);
		//echo "$FF[0] - ".number_format($FF[1],0)." - $FF[2] <br>";
		$timestamp1 = mktime(0,0,0,number_format($FI[1],0),number_format($FI[2],0),$FI[0]); 
		$timestamp2 = mktime(4,12,0,number_format($FF[1],0),number_format($FF[2],0),$FF[0]); 
		$segundos_diferencia = $timestamp1 - $timestamp2; 
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
		$dias_diferencia = abs($dias_diferencia); 
		$DiasEstancia=$dias_diferencia = floor($dias_diferencia);
		return ($DiasEstancia);	
	}
?>