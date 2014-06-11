<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	session_register("NombreMes");
	session_register("NombreMesC");
	
	$ND=getdate();
	
	$fecha = "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
	$dia = $ND[mday];	$mes = $ND[mon];	$anio = $ND[year];
	$cons="select codigo,desde,hasta from salud.grupoetareo where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GrupEt[$fila[0]]=array($fila[0],$fila[1],$fila[2]);
	}
	if($ND[hours]>=18){ 
		$cons="select dia from salud.censogeneral where compania='$Compania[0]' and dia='$ND[year]-$ND[mon]-$ND[mday]'";
		$res=ExQuery($cons);
		//echo $cons;
		$fila=ExFetch($res);
		if(!$fila[0]){ //echo "entra";
			$cons="select ambito from salud.ambitos where compania='$Compania[0]' and hospitalizacion=1 and ambito!='Sin Ambito' and hospitaldia=0";
			$res=ExQuery($cons);
			$ContPacientes=0;
			$ContCamas=0;
			while($fila=ExFetch($res)){				
				$cons2="select pabellon,nocamas from salud.pabellones where ambito='$fila[0]'";
				$res2=ExQuery($cons2);
				while($fila2=ExFetch($res2))
				{						
					if($fila2[0]){
						$cons4="select count(cedula) from salud.pacientesxpabellones where pabellon='$fila2[0]'
						and fechai<='$ND[year]-$ND[mon]-$ND[mday]' and (fechae>='$ND[year]-$ND[mon]-$ND[mday]' or fechae is null)";	
						$res4=ExQuery($cons4);
						$fila4=ExFetch($res4);
						$CamasD=$fila2[1]-$fila4[0];
						$cons5="insert into salud.censogeneral (compania,ambito,unidad,dia,numpacientes,numcamas,numcamasdispo) values
						('$Compania[0]','$fila[0]','$fila2[0]','$ND[year]-$ND[mon]-$ND[mday]',$fila4[0],$fila2[1],$CamasD)";
						//echo $cons5."<br>";
						$res5=ExQuery($cons5);
						if($GrupoEt)
						{
							foreach($GrupEt as $GE){				
								$sumar = $FrecAgendaInt; # cantidad de dias a sumar
								$fecD = date("d/m/Y", mktime(0,0,0,$mes,$dia,$anio-$GE[1])); 
								$fecH = date("d/m/Y", mktime(0,0,0,$mes,$dia,$anio-$GE[2])); 				
								$fecDesde=$fecD; $Desde=str_replace("/","-",$fecDesde);  $Desde=explode("-",$Desde); 
								$fecHasta=$fecH; $Hasta=str_replace("/","-",$fecHasta);  $Hasta=explode("-",$Hasta);
								$cons4="select count(cedula) from salud.pacientesxpabellones,central.terceros where pabellon='$fila2[0]'
								and fechai<='$ND[year]-$ND[mon]-$ND[mday]' and (fechae>='$ND[year]-$ND[mon]-$ND[mday]' or fechae is null)and terceros.compania='$Compania[0]'
								and identificacion=cedula and fecnac>='$Hasta[2]-$Hasta[1]-$Hasta[0]' and fecnac<='$Desde[2]-$Desde[1]-$Desde[0]'";		
								$res4=ExQuery($cons4);
								$fila4=ExFetch($res4);
								$CamasD=$fila2[1]-$fila4[0];
								$cons5="insert into salud.censoxedades (compania,ambito,unidad,dia,pacientes,grupoetareo) values
								('$Compania[0]','$fila[0]','$fila2[0]','$ND[year]-$ND[mon]-$ND[mday]',$fila4[0],$GE[0])";
								//echo $cons5."<br>";
								$res5=ExQuery($cons5);
							}
						}
					}
				}
			}
		}
	}
	$cons="Select Mes from Central.Meses Order By Numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$i++;
		$NombreMes[$i]=$fila[0];
		$NombreMesC[$i]=substr($fila[0],0,3);
	}

	include("ValidarArchivos.php");

	if($CrearVarios)
	{
		$cons="Insert into Central.Terceros (PrimApe,Identificacion,Compania) values ('VARIOS','99999999999-0','$Compania[0]')";
		$res=ExQuery($cons);
		echo ExError($res);
	}
?>
<html>
<head>
<meta http-equiv="refresh" content="60">
<script language="javascript" src="/Funciones.js"></script>
</head>
<body  style="text-align: center;">
<?
	if($Sistema[$NoSistema]!="Mentor Software")
	{

	$query = "SELECT cedula FROM salud.servicios WHERE tiposervicio = 'Hospitalizacion' AND estado = 'AC' ";
	$res = ExQuery($query);
	while($filas = ExFetch($res)){
		$query0 = "SELECT detalle, idescritura, numorden, primnom, segnom, primape, segape, fecha  
					FROM salud.ordenesmedicas, central.terceros 
					WHERE tipoorden='Orden Egreso' AND cedula='$filas[0]' AND estado='AN' AND identificacion = '$filas[0]' LIMIT 1";
					
		$res0 = ExQuery($query0);
		if(ExNumRows($res0)>0){
		    
			$fila0 = ExFetch($res0);
			$Nom = "".$fila0[3]." ".$fila0[4]." ".$fila0[5]." ".$fila0[6];
			$ced = $filas[0];
		
			$query1 = "SELECT detalle, fecha FROM salud.ordenesmedicas 
						WHERE tipoorden='Suspension' AND cedula='$filas[0]'  
						AND detalle = 'Suspensión Orden $fila0[0]' 
						AND idescritura >= $fila0[1] AND estado = 'AC' ";
			$res1 = ExQuery($query1);

			if(ExNumRows($res1)>0){
		//$fila1 = ExFetch($res1);
			while($fila1 = ExFetch($res1)){
				//echo $bb = $fila1[1].' <br>';
		$aaaa3=substr("$fila1[1]", -19,4);
		$mm3=substr("$fila1[1]", -14,2);
		if($mm3<10){$mm3=str_replace("0","","$mm3");}
		$dd3=substr("$fila1[1]", -11,2);
		if($dd3<10){$dd3=str_replace("0","","$dd3");}
		
		$hh3 = substr("$fila1[1]", -8,2);
		$min3 = substr("$fila1[1]", -5,2);
		$seg3 = substr("$fila1[1]", -2,2);
		
		
		$ano1 = $aaaa3; 
		$mes1 = $mm3; 
		$dia1 = $dd3; 
		$hor1 = $hh3;
		$min1 = $min3;
		$seg1 = $seg3;
		
		$ano2 = "$ND[year]"; 
		$mes2 = "$ND[mon]"; 
		$dia2 = "$ND[mday]"; 
		$hor2 = "$ND[hours]";
		$min2 = "$ND[minutes]";
		$seg2 = "$ND[seconds]";
		
		$timestamp1 = mktime($hor1,$min1,$seg1,$mes1,$dia1,$ano1); //----------> BD
		$timestamp2 = mktime($hor2,$min2,$seg2,$mes2,$dia2,$ano2); //----------> Sistema
		$nuevafecha = mktime($hor1,$min1+30,$seg1,$mes1,$dia1,$ano1);
	
	//echo ('Nom --> '.$Nom.'Ced --> '.$ced.' BD --> '.$timestamp1.' Sumada-> '.$nuevafecha.' sistema--> '.$timestamp2.'<br>');
		 
			   
			   if($timestamp2 < $nuevafecha ){
				 
				?>
					<script language="javascript">
					var nom ='<?php echo $Nom;?>'
					var ced ='<?php echo $ced;?>'
				    alert('Se ha cancelado la Orden de Egreso del paciente '+nom+' con identificación '+ced+' ')
										
					</script>
				<?php
				}
				
		}
			}
	
		}
	}
	?>



<div style="margin:0 auto; width: 363px; height: 220px; text-align: center;"><img src="/Imgs/Mentor.jpg"></div>
<br>
<table style="font-family: Tahoma, Geneva, sans-serif; font-size:12px;" align="center">
<?	
	$cons = "Select InstruccionSQL,MsjAlerta,Estado,Archivo,Id from Alertas.AlertasProgramadas where Compania='$Compania[0]' and estado='Activo' order by MsjAlerta";
	//echo $cons;
	$res = ExQuery($cons);
	while ($fila=ExFetch($res))
	{
		$cons3="select usuario from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$fila[4]";
		$res3=ExQuery($cons3);
		if(ExNumRows($res3)>0){
			$BanUsus=1;
			$cons4="select usuario from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$fila[4] and usuario='$usuario[1]'";
			$res4=ExQuery($cons4);
			if(ExNumRows($res4)>0){$BanUsuSi=1;}
		}
		$cons2="SELECT Id from Alertas.AlertasxModulos,Central.UsuariosxModulos where AlertasxModulos.Modulo=UsuariosxModulos.Modulo and AlertasxModulos.Id=$fila[4] 
		and UsuariosxModulos.Usuario='$usuario[1]' and Alertas.AlertasxModulos.Compania='$Compania[0]'";
		//echo $cons2;
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{	
		
		//Parametros adicionales para alertas 
		    $param = '';
			if($fila[4] == 19){
			 $param = '&y=1';
			}
			if($fila[4] == 22){
			 $param = '&y=3';
			}
			$cons1=str_replace("|","'",$fila[0]);			
			$cons1=str_replace("[COMPANIA]","$Compania[0]",$cons1);
			$cons1=str_replace("[FEC_ACTUAL]","$ND[year]-$ND[mon]-$ND[mday]",$cons1);
			$cons1=str_replace("[USU]","$usuario[1]",$cons1); //echo $cons1."<br>";						
			$cons1=str_replace("+","||",$cons1);

			$res1=ExQuery($cons1);
			
            //Para poder enviar variables GET desde las alertas
            $abuscar   = '?';
            $pos = strpos($fila[3], $abuscar);
            if ($pos !== false){
                $parametros = "&DatNameSID=$DatNameSID";
            }
            else{
                $parametros = "?DatNameSID=$DatNameSID";
            }
                        
			if(!$fila[3]){
                $fila[3]="ModOpciones.php";
            }
			else{
                $fila[3]=$fila[3]."".$parametros;
            }
			if(ExNumRows($res1)>0){
				if($BanUsus==1){
					if($BanUsuSi==1){$Msj=$Msj." <tr><td><a href='$fila[3]$param' target='Derecha' style='text-decoration: none'>&nbsp;$fila[1]</a></td></tr> ";}
				}
				else{
					$Msj=$Msj." <tr><td><a href='$fila[3]$param' target='Derecha' style='text-decoration: none'>&nbsp;$fila[1]</a></td></tr> ";
				}
			}			
		/*	if($fila[4]==">"){if(ExNumRows($res1)>$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>* $fila[1] *</a>";}}
			if($fila[4]=="<"){if(ExNumRows($res1)<$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]=="=="){if(ExNumRows($res1)==$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]=="<>"){if(ExNumRows($res1)!=$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]==">="){if(ExNumRows($res1)>=$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]=="<="){if(ExNumRows($res1)<=$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}*/
		}
		$BanUsuSi="";
	}
	if($Msj){ echo $Msj?>    	
			<!--<marquee SCROLLDELAY="155"><? //echo $Msj?></marquee>-->        
<? }
?>
</tr></table>
<?
	}
else
{?>
<img src="/Imgs/Principal.jpg">
<? }
	if(!$Validar){exit;}

	$cons98="Select * from central.revesquemas where tipo=1";
	$res98=ExQuery($cons98);
	while($fila98=ExFetch($res98))
	{
		$DBEs[$fila98[1]]=$fila98[1];
	}
	

	$dbname[1] ="Presupuesto";
	$dbname[2] ="Contabilidad";
	$dbname[3] ="Central";
	$dbname[4] ="Consumo";
	$dbname[5] ="ContratacionSalud";
	$dbname[6] ="Salud";
	$dbname[7] ="Alertas";
	$dbname[8] ="Predial";



	foreach($DBEs as $Ndb)
	{

		$cons31="Select NomTabla from Central.ValidaBD where BD=$Ndb Group By NomTabla Order By NomTabla";
		$res31=ExQuery($cons31);
		while($fila31=ExFetch($res31))
		{
			$NomTabla=$fila31[0];
			$NumTablas[$Ndb]=$NumTablas[$Ndb]+1;

			$cons="SELECT column_name,column_default,data_type,character_maximum_length,data_type FROM information_schema.columns WHERE table_name ='".strtolower($NomTabla)."' 
			and table_schema='".strtolower($dbname[$Ndb])."' Order By column_name";

			$res=ExQuery($cons);
			
			$consPk="Select constraint_name,column_name from information_schema.constraint_column_usage where table_schema='".strtolower($dbname[$Ndb])."'
			and table_name='".strtolower($NomTabla)."'";

			$resPk=ExQuery($consPk);

			while($filasPk=ExFetch($resPk))
			{
				$ListaPk[$dbname[$Ndb]][$NomTabla][$filasPk[1]][$filasPk[0]]=$filasPk[0];
//				echo "---> ". $dbname[$Ndb] ." ".$NomTabla . " " . $filasPk[1].  $ListaPk[$dbname[$Ndb]][$NomTabla][$filasPk[1]][$filasPk[0]]."<br>";
			}
//echo "<font color='red'>".$ListaPk['Presupuesto']['ClasesCuenta']['cuenta']."</font>";;
			if(ExNumRows($res)==0)
			{
				$ij++;
				$TablaInexistente[$ij]="$dbname[$Ndb].$NomTabla";
			}
			else
			{
				$re=0;
				$cons32="Select NomCampo,Tipo,Longitud,Llaves,VrDef from Central.ValidaBD where BD=$Ndb and NomTabla='$NomTabla' Order By NomCampo";
				$res32=ExQuery($cons32);
				while($fila32=ExFetch($res32))
				{
					$Campo=$fila32[0];$DatCampo[0]=$fila32[0];$DatCampo[1]=$fila32[1];$DatCampo[2]=$fila32[2];$DatCampo[3]=$fila32[3];
					$DatCampo[4]=$fila32[4];

					$NumCampos[$dbname[$Ndb]][$NomTabla]=ExNumRows($res);
					$filCampos=ExFetch($res);

					$NumCamposMatriz[$dbname[$Ndb]][$NomTabla]++;

					if($filCampos[0]!=strtolower($DatCampo[0]))
					{
						$m++;
						$CamposInexistentes[$m]="$dbname[$Ndb].$NomTabla.$DatCampo[0]";
					}
					else
					{
					
						$filCampos[1]=explode(":",$filCampos[1]);
						$filCampos[1]=str_replace("'","",$filCampos[1]);
						if($filCampos[1][0]!=$DatCampo[4])
						{
							$we++;
							$CamposSinDefault[$we]=$dbname[$Ndb].".".$NomTabla.".".$DatCampo[0]."-". $DatCampo[4]." (".$filCampos[1][0].")";
						}

						$DatCampo[3]=explode(",",$DatCampo[3]);
//echo $dbname[$Ndb].".".$NomTabla.".".$DatCampo[0]. count($DatCampo[3]) ." ---> ". count($ListaPk[$dbname[$Ndb]][$NomTabla][strtolower($DatCampo[0])])."<br>";
						if((count($DatCampo[3])!=count($ListaPk[$dbname[$Ndb]][$NomTabla][strtolower($DatCampo[0])])) && $DatCampo[3][0]!='')
						{
//							ECHO "<hr>Here!";
							$uy++;
							$DifNoIndex[$uy]=$dbname[$Ndb].".".$NomTabla.".".$DatCampo[0];
						}

						for($sd=0;$sd<=count($DatCampo[3])-1;$sd++)
						{
//							echo "<font color='red'>".$dbname[$Ndb].$NomTabla.$DatCampo[0].$DatCampo[3][$sd]."</font>".$DatCampo[3][$sd]."<br>";
							if(trim($ListaPk[$dbname[$Ndb]][$NomTabla][strtolower($DatCampo[0])][$DatCampo[3][$sd]])!=trim($DatCampo[3][$sd]))
							{
								$g++;
								$PkFalta=$DatCampo[0].".".$ListaPk[$dbname[$Ndb]][$NomTabla][strtolower($DatCampo[0])][$DatCampo[3][$sd]]."->".$DatCampo[3][$sd];
								$CamposSinIndexPK[$g]=$dbname[$Ndb].".".$NomTabla.".".$PkFalta." (P.K.)";
							}
						}


						$DatCampo[1]=strtolower($DatCampo[1]);
						if($DatCampo[1]=="char"){$DatCampo[1]="string";}

						if($DatCampo[1]=="double"){$DatCampo[1]="double precision";$DatCampo[2]="";}
						if($DatCampo[1]=="int"){$DatCampo[1]="integer";$DatCampo[2]="";}
						if($DatCampo[1]=="date"){$DatCampo[2]="";}
						if($DatCampo[1]=="longtext"){$DatCampo[1]="text";$DatCampo[2]="";}
						if($DatCampo[1]=="datetime"){$DatCampo[1]="timestamp without time zone";$DatCampo[2]="";}
						
						
						if($filCampos[4]!=$DatCampo[1])
						{
							$s++;
							$CamposIncompatibles[$s]=$dbname[$Ndb].".".$NomTabla.".".$DatCampo[0].".".$filCampos[4]."(Tipo err&oacute;neo)";
						}
						if($filCampos[3]!=$DatCampo[2])
						{
							$s++;
							$CamposIncompatibles[$s]=$dbname[$Ndb].".".$NomTabla.".".$DatCampo[0].".".$filCampos[3]." (longitud err&oacute;nea)";
						}
					}
					$re++;
				}
				if($NumCampos[$dbname[$Ndb]][$NomTabla]!=$NumCamposMatriz[$dbname[$Ndb]][$NomTabla])
				{
					$zz++;
					$NoCamposIncorrectos[$zz]=$dbname[$Ndb].".".$NomTabla." (".$NumCampos[$dbname[$Ndb]][$NomTabla] ." a " .$NumCamposMatriz[$dbname[$Ndb]][$NomTabla]." )";
				}
			}
		}
	}
echo "</center>";

echo "<br><br><br>";
	if($TablaInexistente || $CamposInexistentes || $CamposIncompatibles || $NoCamposIncorrectos || $CamposSinIndexPK || $CamposSinDefault || $DifNoIndex)
	{
		echo "<font size='2' color='blue'><em>El sistema ha detectado una inconsistencia en la base de datos instalada, se recomienda informar de este cambio al administrador del software para evitar inestabilidad en el funcionamiento de la aplicacion:";
	}
	if($TablaInexistente){echo "<li><strong>Tablas no existentes:</strong> ";}
	for($x=1;$x<=count($TablaInexistente);$x++)
	{
		echo "$TablaInexistente[$x]- ";
	}

	if($CamposInexistentes){echo "<li><strong>Campos no existentes</strong>: ";}
	for($x=1;$x<=count($CamposInexistentes);$x++)
	{
		echo "$CamposInexistentes[$x]- ";
	}

	if($CamposIncompatibles){echo "<li><strong>Campos estructuralmente incompatibles:</strong>";}
	for($x=1;$x<=count($CamposIncompatibles);$x++)
	{
		echo "$CamposIncompatibles[$x]- ";
	}


	if($CamposSinIndexPK){echo "<li><strong>Indices principales no creados:</strong>";}
	for($x=1;$x<=count($CamposSinIndexPK);$x++)
	{
		echo "$CamposSinIndexPK[$x]- ";
	}

	if($DifNoIndex){echo "<li><strong>Indices no coinciden:</strong>";}
	for($x=1;$x<=count($DifNoIndex);$x++)
	{
		echo "$DifNoIndex[$x]- ";
	}

	if($CamposSinDefault){echo "<li><strong>Valores x Defecto no establecidos:</strong>";}
	for($x=1;$x<=count($CamposSinDefault);$x++)
	{
		echo "$CamposSinDefault[$x]- ";
	}

	foreach($DBEs as $Ndb)
	{

		$result = ExQuery ("SELECT table_name FROM information_schema.columns WHERE table_schema='".strtolower($dbname[$Ndb])."' Group By table_name;");
		if(ExNumRows($result)!=$NumTablas[$Ndb]){echo "<font size='2' color='blue'><em><li>El numero de tablas existentes en la base de datos <strong>$dbname[$Ndb] (".ExNumRows($result).")</strong> no corresponde al estandar establecido por el sofware ($NumTablas[$Ndb]). Informe al administrador del sistema sobre esta novedad</li>";}
	}
	if($NoCamposIncorrectos){echo "<strong><li>No de Campos incorrectos: </strong>";}
	for($x=1;$x<=count($NoCamposIncorrectos);$x++)
	{
		echo "$NoCamposIncorrectos[$x]- ";
	}

	$TotRegAcceso=229;
	$cons="Select * from Central.AccesoxModulos";
	$res=ExQuery($cons);
	if(ExNumRows($res)!=$TotRegAcceso){echo "<font size='2' color='blue'><em><strong><br><li>El n&uacute;mero de opciones de acceso Instaladas (".ExNumRows($res).") no corresponde con el estandar del Software($TotRegAcceso)</font></a><br></strong>";}

	$cons="Select * from Central.Terceros where Identificacion = '99999999999-0' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		echo "<font size='2' color='blue'><em><strong><br><li>El tercero varios no existe para la compa&ntilde;ia <a href='ModOpciones.php?CrearVarios=1'><font color='#ff0000'>Crear</font></a><br></strong>";
	}


		ValidarArchivos();

?>
</body>
<html>