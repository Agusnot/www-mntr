<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND = getdate();
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}
	$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";	
?>
<body background="/Imgs/Fondo.jpg">
<? 
if($Nombre!=''||$Codigo!=''){?>
<table align="center" bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
<?	
	if($Nombre==''){		
		$cons3="select cup,nombre from salud.plantillaprocedimientos,contratacionsalud.cups
		where cup like '$Codigo%' and plantillaprocedimientos.compania='$Compania[0]' and laboratorio!='' and cups.compania='$Compania[0]' and cups.codigo=plantillaprocedimientos.cup
		group by cup,nombre order by cup";
	}
	else{
		if($Codigo==''){
			$cons3="select cup,nombre from salud.plantillaprocedimientos,contratacionsalud.cups
		where nombre ilike '%$Nombre%' and plantillaprocedimientos.compania='$Compania[0]' and laboratorio!='' and cups.compania='$Compania[0]' and cups.codigo=plantillaprocedimientos.cup
		group by cup,nombre order by cup";
		}
		else{
			$cons3="select cup,nombre from salud.plantillaprocedimientos,contratacionsalud.cups
			where nombre ilike '%$Nombre%' and cup like '$Codigo%' and plantillaprocedimientos.compania='$Compania[0]' and laboratorio!='' and cups.compania='$Compania[0]' 
			and cups.codigo=plantillaprocedimientos.cup group by cup,nombre order by cup";
		}
	}	
	//echo $cons3;
	$res3=ExQuery($cons3);echo ExError();
	
	if(ExNumRows($res3)>0/*1!=0*/){?>
		<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td></tr>
<?		while($fila3=ExFetch($res3)){?>			
            <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="parent.CerrarThis();    	                        
                            parent.parent.document.FORMA.CodCUP.value='<? echo $fila3[0]?>';
                            parent.parent.document.FORMA.NomCUP.value='<? echo $fila3[1]?>';
                            parent.parent.document.FORMA.submit();"
            >      
				<td><? echo $fila3[0]?></td><td><? echo $fila3[1]?></td>
        	<tr>
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
