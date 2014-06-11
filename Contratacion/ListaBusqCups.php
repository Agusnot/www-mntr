<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND = getdate();
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}
	$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";	
?>

<head>
	<meta charset="UTF-8">
</head>
<body background="/Imgs/Fondo.jpg">
<? 
if($Nombre!=''||$Codigo!=''){?>
<table align="center" bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
<?
//-----------------------------------Encontrar la entidad,contrato y No Contrato de la tabla Servicios-----------------------------------------------------------------------------------

	//echo $cons1;
//--------------------------------------------------Encontrar el plan de servicio--------------------------------------------------------------------------------------------------------
	$cons2="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$Numero' and compania='$Compania[0]'";	
	$res2=ExQuery($cons2);echo ExError();	
	//echo $cons2;
	$fila2=ExFetch($res2);
	if($fila2[0]==''){$fila2[0]='-2';}
//-------------------------------------------Encontrar los cups para el plan de servicios------------------------------------------------------------------------------------------------
		
	if($Nombre==''){
		$cons3="select codigo,nombre,cups.grupo,cups.tipo from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes where codigo=cupsxplanservic.cup 
		and cupsxplanservic.cup=cupsxplanes.cup and cupsxplanes.compania='$Compania[0]' and codigo ilike '$Codigo%' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] 
		and cupsxplanservic.clase='CUPS' and cupsxplanservic.compania='$Compania[0]' and cups.compania='$Compania[0]'";
	}
	else{
		if($Codigo==''){
			$cons3="select codigo,nombre,cups.grupo,cups.tipo from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes where codigo=cupsxplanservic.cup 
			and cupsxplanservic.cup=cupsxplanes.cup and cupsxplanes.compania='$Compania[0]' and nombre ilike '%$Nombre%' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] 
			and cupsxplanservic.clase='CUPS' and cupsxplanservic.compania='$Compania[0]' and cups.compania='$Compania[0]'";		
		}
		else{
			$cons3="select codigo,nombre,cups.grupo,cups.tipo from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes where codigo=cupsxplanservic.cup 
			and cupsxplanservic.cup=cupsxplanes.cup and cupsxplanes.compania='$Compania[0]' and nombre ilike '$Nombre%' and codigo ilike '%$Codigo%' and cupsxplanservic.autoid=$fila2[0] 
			and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS' and cupsxplanservic.compania='$Compania[0]' and cups.compania='$Compania[0]'";	
		}
	}	
	$res3=ExQuery($cons3);echo ExError();
	//echo $cons3;
	if(ExNumRows($res3)>0/*1!=0*/){?>
		<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td></tr>
<?		while($fila3=ExFetch($res3)){
			if($fila3[2]!=''){?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="parent.CerrarThis();
    	                        parent.parent.document.FORMA.Nombre.value='<? echo $fila3[1]?>';
        	                    parent.parent.document.FORMA.Codigo.value='<? echo $fila3[0]?>';                                
            	                parent.CerrarThis();"
	            >
       	<?	}
			else{?>
            	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="alert('Este Cup no tiene un grupo definido')">
<?			}?>
			<td><? echo $fila3[0]?></td><td><? echo $fila3[1]?></td><tr>
<?		}
	}
	else{
		if($fila2[0]=='-2'){?>
			<tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">El pacienete no tiene una EPS relacionada o activa</td></tr>	
	<?	}
		else{?>
			<tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">No hay registros coincidentes</td></tr>	
<?		}
	}?>
</table>
<?
}
?>
</body>    
