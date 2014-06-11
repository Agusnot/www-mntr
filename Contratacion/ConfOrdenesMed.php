<?php	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		$cons2="delete from salud.confordenesmed where compania='$Compania[0]'";			
		$res2=ExQuery($cons2);echo ExError();	
		/*$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";		
		$res=ExQuery($cons);echo ExError();
		//echo $cons;
		while($fila=ExFetch($res)){		
			$cons2="insert into salud.confordenesmed (compania,ambito,medprog,mednoprog,MedNoProg,hospitalizar,trasladound,procedimientos,interprog,dietas,notas) 
			values('$Compania[0]','$fila[0]',0,0,0,0,0,0,0,0,0)";			
			$res2=ExQuery($cons2);echo ExError();
		}*/
		if($MedProg){
			while(list($c,$v)=each($MedProg)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set medprog=1 where ambito='$c' and compania='$Compania[0]'";
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,medprog) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}
		
		if($MedNoProg){
			while(list($c,$v)=each($MedNoProg)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set MedNoProg=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,MedNoProg) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}
		if($Hospitalizar){
			while(list($c,$v)=each($Hospitalizar)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set hospitalizar=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,hospitalizar) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}
		
		
		
		

		if($Trasladound){
			while(list($c,$v)=each($Trasladound)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set trasladound=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,trasladound) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}	
		if($Procedimientos){
			while(list($c,$v)=each($Procedimientos)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set procedimientos=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,procedimientos) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}	
		if($Interprog){
			while(list($c,$v)=each($Interprog)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set interprog=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,interprog) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}	
		if($Dietas){
			while(list($c,$v)=each($Dietas)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set Dietas=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,Dietas) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}	


		if($Comedores){
			while(list($c,$v)=each($Comedores)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set Comedores=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,Comedores) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}	




		if($Notas){
			while(list($c,$v)=each($Notas)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set notas=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,notas) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}	
		if($Egreso){
			while(list($c,$v)=each($Egreso)){	
				//echo "$c $v<br>";
				$cons="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$c'";
				$res=ExQuery($cons);echo ExError();
				//echo $cons."<br>";
				if(ExNumRows($res)>0){
					$cons2="update salud.confordenesmed set egreso=1 where ambito='$c' and compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
				else{
					$cons2="insert into salud.confordenesmed (compania,ambito,egreso) values ('$Compania[0]','$c',1)";
					$res2=ExQuery($cons2);echo ExError();
					//echo "$cons2 <br>";
				}
			}
		}	
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Orden Medica</td>
<? 	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
	//echo $cons;
	$res=ExQuery($cons);echo ExError();
	if(ExNumRows($res)>0){
		while($fila=ExFetch($res))
		{?>
			<td><? echo $fila[0]?></td>
<?		}
		echo "</tr>";?>
		<tr><td bgcolor="#e5e5e5">Medicamentos Programados</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and medprog=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox"  name="MedProg[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){ echo "checked";$MP=1; }else{$MP=2;}?>></td>                
		<?	}?>        
        </tr>
       
        <tr><td bgcolor="#e5e5e5">Medicamentos No Programados</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and MedNoProg=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="MedNoProg[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>
        <tr><td bgcolor="#e5e5e5">Ingresar paciente</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and hospitalizar=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Hospitalizar[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>
		
		
		
		
		
        <tr><td bgcolor="#e5e5e5">Traslado de Unidad</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and trasladound=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Trasladound[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>
        <tr><td bgcolor="#e5e5e5">Procedimientos</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and procedimientos=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Procedimientos[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>
        <tr><td bgcolor="#e5e5e5">Interconsultas</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and interprog=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Interprog[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>
        <tr><td bgcolor="#e5e5e5">Dietas</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and dietas=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Dietas[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>
		
		


	<tr><td bgcolor="#e5e5e5">Comedores</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and Comedores=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Comedores[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>	


        <tr><td bgcolor="#e5e5e5">Notas</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and notas=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Notas[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>      
        <tr><td bgcolor="#e5e5e5">Egreso</td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
			
			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res)){
				$cons2="select ambito from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]' and Egreso=1";
				$res2=ExQuery($cons2);echo ExError();?>
				<td align="center"><input type="checkbox" name="Egreso[<? echo $fila[0]?>]" <? if(ExNumRows($res2)>0){?> checked<? }?>></td>
		<?	}?>        
        </tr>     
        <tr><td align="center" colspan="5"><input type="submit" value="Guardar" name="Guardar"></td></tr>
        	
<?	}
	else
	{?>
		</tr><tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>No se han ingresado ambitos</td></tr>
<?	}?>
	</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
