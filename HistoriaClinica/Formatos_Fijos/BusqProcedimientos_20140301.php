<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND = getdate();
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}
	$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";	
	$cons="select sexo,age('$ND[year]-$ND[mon]-$ND[mday]',fecnac) from central.terceros where compania='$Compania[0]' and identificacion='$Paciente[1]'";
	$res=ExQuery($cons); $fila=ExFetch($res);
	$Sexo=$fila[0]; $ED=explode(" ",$fila[1]);
	$Edad=$ED[0];
	if($ED[1]!='year'&&$ED[1]!='years')
	{
		$Edad="0";
	}
    //-----------------------------------Encontrar la entidad,contrato y No Contrato de la tabla Servicios-----------------------------------------------------------------------------------
	$cons1="Select entidad,contrato,nocontrato from salud.pagadorxservicios,salud.servicios
	where servicios.cedula='$Paciente[1]' and pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]'and servicios.estado='AC' and 
	pagadorxservicios.numservicio=servicios.numservicio	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";
	
	$res1=ExQuery($cons1);		
	if(ExNumRows($res1)>0){
		$fila1=ExFetch($res1);		
		$Eps=$fila1[0]; $Contra=$fila1[1]; $NoContra=$fila1[2];
	}
	else{			
		$cons1="Select entidad,contrato,nocontrato,fechafin from salud.pagadorxservicios,salud.servicios
		where servicios.cedula='$Paciente[1]' and pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]'and servicios.estado='AC' and 
		pagadorxservicios.numservicio=servicios.numservicio	and '$FechaComp'>=fechaini order by fechafin desc";
		//echo $cons1;
		$res1=ExQuery($cons1);	
		if(ExNumRows($res1)>0){				
			$fila1=ExFetch($res1);
			//echo $fila1[3];
			if(!$fila1[3]){
				$Eps=$fila1[0]; $Contra=$fila1[1]; $NoContra=$fila1[2];
			}
			else{
				$Eps='-2'; $Contra='-2'; $NoContra='-2';
			}			
		}
		else{
			$Eps='-2'; $Contra='-2'; $NoContra='-2';
		}
	}	
	//echo $cons1;
	if($Guardar)
	{
		if($Cup){
            //echo count($Cup)."----------";exit;
            if(count($Cup)==1)
            {
                $Cup_aux = $Cup;
                while( list($cad,$val) = each($Cup_aux))
                {
                    $consxxx = "Select * from ContratacionSalud.ItemsxPaquete, ContratacionSalud.PaquetesxContratos
                    Where IdPaq = IdPaquete and ItemsxPaquete.Compania = '$Compania[0]' and PaquetesxContratos.Compania='$Compania[0]'
                    and Entidad = '$Eps' and Contrato='$Contra' and NoContrato = '$NoContra' and Codigo = '$cad'";
                    $resxxx = ExQuery($consxxx);
                    if(ExNumRows($resxxx)>0)
                    {
                        $Ligar_Paquete=1;
                        $Cup_Ligar = $cad;
                    }
                }
            }
			while( list($cad,$val) = each($Cup))
			{
				if($cad && $val)
				{    
					$cons="insert into salud.tmpcupsordenesmeds (compania,usuario,cedula,tmpcod,cup,finalidadcup,tipofinalidad,formaquirurgica,cantidad) values 
				('$Compania[0]','$usuario[1]','$Paciente[1]','$TMPCOD2','$cad','$FinalidadProc[$cad]','$TipFinalidad[$cad]','$FormaActQuir[$cad]',$CantCup[$cad])";
					$res=ExQuery($cons);
					//echo $cons;
				}
			}
		}?>
        <script language="javascript">
             <?
             if($Ligar_Paquete)
             {
                 ?>parent.parent.Ligar_Paquetes('<?echo $Cup_Ligar?>','<?echo $NoContra?>','<?echo $Eps?>','<?echo $Contra?>');<?
             }
             else
             {
                 ?>
                 parent.parent.document.FORMA.submit();
                 <?
             }
             ?>
        </script>
<?	}
 //echo "Edad=$Edad fila[1]=$fila[1] ED[1]=$ED[1]";
?>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<? 
if($Nombre!=''||$Codigo!=''){?>
<table align="center" bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
<?
//--------------------------------------------------Encontrar el plan de servicio-----------------------------------------------------------
	$cons2="select planbeneficios,plantarifario,ambitocontrato from contratacionsalud.contratos where entidad='$Eps' and contrato='$Contra' and numero='$NoContra' and compania='$Compania[0]'";	
	$res2=ExQuery($cons2);echo ExError();	
	$fila2=ExFetch($res2);
	if($fila2[0]==''){$fila2[0]='-2';} 
	$AmbContrato=$fila2[2];  $PlanServ=$fila[0]; $PlanTarif=$fila[0];
	//echo $cons2." ambito contrato ".$AmbContrato=$fila2[2]; 	
//----------------Encontrar los cups para el plan de servicios-------------------------------------------------------------------------------
	$cons2="select cup from contratacionsalud.cupsxplanservic where compania='$Compania[0]'";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$CupsxPlan[$fila2[0]]=$fila2[0];
	}	
//--------------Consultamos los cups----------------------------------------------?>
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td></td><td>Cantidad</td><td>Finalidad</td>
    <td>Forma Acto Quirurgico</td><td></td></tr><?
	$cons3="select cup from salud.tmpcupsordenesmeds where compania='$Compania[0]' and tmpcod='$TMPCOD2' and cedula='$Paciente[1]'";
	$res3=ExQuery($cons3);
	if(ExNumRows($res3)>0)
	{
		$RestricCods="and codigo not in (select cup from salud.tmpcupsordenesmeds where compania='$Compania[0]' and tmpcod='$TMPCOD2' and cedula='$Paciente[1]')";		
	}
	if($Codigo){$Cod="and codigo ilike '$Codigo%'";}
	if($Nombre){$Nom="and nombre ilike '%$Nombre%'";}
	$cons3="select codigo,nombre,cups.grupo,cups.tipo,nopos,notas,sexo,edadini,edadfin,ambitocup,quirurgico,finalidadcup,tipofinalidad
	from contratacionsalud.cups where compania='$Compania[0]' $Cod $Nom $RestricCods order by codigo,nombre";
	$res3=ExQuery($cons3);
	//secho $cons3;
	while($fila3=ExFetch($res3))
	{
		if($fila3[4]==1){$NP="NO POS";}else{$NP="POS";}
		if($fila3[12]){
			$TipoFinalidad=$fila3[12];
		}
		else{
			$TipoFinalidad=2;
		}		
		if(!$FinalidadProc){			
			if($fila3[11]){$BanFinalidad=1; $FinalidadProc=$fila3[11];}
		} ?>	
       	<input type="hidden" name="TipFinalidad[<? echo $fila3[0]?>]" value="<? echo $TipoFinalidad?>">
    	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" >
    		<td><? echo "$fila3[0]";?></td><td><? echo $fila3[1]?></td><td><? echo $NP?></td>
            <td>
            	<select name="CantCup[<? echo $fila3[0]?>]">
                <?	for($i=1;$i<11;$i++)
					{
						echo "<option value='$i'>$i</option>";	
					}?>
                </select>
            </td>
            <td>
		<?	$cons="select finalidad,codigo from salud.finalidadesact where tipo=$TipoFinalidad order by finalidad";	
            $res=ExQuery($cons); ?>				
            <select name="FinalidadProc[<? echo $fila3[0]?>]"><?
                while($fila=ExFetch($res)){
                    if($FinalidadProc==$fila[1]){
                        echo "<option value='$fila[1]' selected>$fila[0]</option>";
                    }
                    else{
                        if(!$BanFinalidad){
                            echo "<option value='$fila[1]'>$fila[0]</option>";
                        }
                    }			
                }
        ?>	</select>            
            </td>
            <td align="center">
       	<?	if(!$fila3[10]){
				echo "NO APLICA";?>
				<input type="hidden" name="FormaActQuir[<? echo $fila3[0]?>]" id="FormaActQuir_<? echo $fila3[0]?>" value="-1">
		<?	}
			else
			{
				$cons="select codigo,forma from salud.formarquirurgico order by forma";	
				$res=ExQuery($cons);?>
                <select name="FormaActQuir[<? echo $fila3[0]?>]" id="FormaActQuir_<? echo $fila3[0]?>">
                	<option></option>
              	<?	while($fila=ExFetch($res))
					{
						if($fila[0]==$FormaActQuir[$fila3[0]]){echo "<option value='$fila[0]' selected>$fila[1]</option>";}
						else{echo "<option value='$fila[0]'>$fila[1]</option>";}
					}?>
                </select>
		<?	}?>
            </td>
            <td>
            <input type="checkbox" name="Cup[<? echo $fila3[0]?>]" id="Cup_<? echo $fila3[0]?>"    
    <?	
				if(!$CupsxPlan[$fila3[0]]){?>
					onclick="alert('Este item no esta autorizado para el actual contrato del paciente!!!');
					if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"		
		<?		}
				elseif(!$fila3[2]||!$fila3[3])
				{?>
					onclick="alert('Este item no ha sido aun configurado adecuadamente para ser seleccionad!!!');
					if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"
			<?	}
				elseif($fila3[6]){
					if(!$Sexo){?>
                    	onclick="alert('alert('Este CUP requiere que se registre el genero del paciente!!!');
							if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"	
				<?	}
					elseif($Sexo!=$fila3[6]){
						if($Sexo=='M'){?>
							onclick="alert('Este CUP solo esta disponible para pacientes de genero femenino!!!');
							if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"	
					<?	}
						if($Sexo=='F'){?>
                        	onclick="alert('Este CUP solo esta disponible para pacientes de genero masculino!!!');
							if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"
					<?	}
					}
				}
				elseif($fila3[7]){
					if(!$Edad&&$Edad!="0"){?>
						onclick="alert('Este CUP requiere que se registre la fecha de nacimiento del paciente!!!');
						if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"
				<?	}
					elseif($Edad<$fila3[7]){?>
						onclick="alert('La edad del paciente es inferior a la permitida para poder ordenar este CUP!!!');
						if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"
				<?	}
				}
				elseif($fila3[8]){	
					if(!$Edad&&$Edad!="0"){?>
						onclick="alert('Este CUP requiere que se registre la fecha de nacimiento del paciente!!!');
						if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"
				<?	}
					elseif($Edad>$fila3[8]){?>
						onclick="alert('La edad del paciente es superior a la permitida para poder ordenar este CUP<? echo $fila3[8]?>!!!');
						if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"
				<?	}
				}
				elseif($fila3[9])
				{
					//echo $fila3[9];
					if($AmbContrato!=$fila3[9]){?>
						onclick="alert('Este CUP no esta autorizado para el proceso del contrato actual del paciente!!!');
						if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}"
				<?	}						
				}
				else{?>
				      	onclick="
                        if(getElementById('FormaActQuir_<? echo $fila3[0]?>').value==''){
                        	alert('Debe seleccionar la forma del acto quirurgico!!!');
                            if(getElementById('Cup_<? echo $fila3[0]?>').checked==true){getElementById('Cup_<? echo $fila3[0]?>').checked=false;}
                        }"		
			<?	}
	?>		></td>
    	</tr><?
	}?>
</table>
<?
}
?>
<input type="hidden" name="TMPCOD2" value="<? echo $TMPCOD2?>">
<input type="hidden" name="Guardar">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>    
