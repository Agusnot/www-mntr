<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($TMPCOD==''){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if($Guardar==1){
		while( list($cad,$val) = each($Incluir))
		{
			if($cad && $val)
			{				
				$cons="insert into salud.tempdispomedsxgrup (usuario,compania,tmpcod) values ('$cad','$Compania[0]','$TMPCOD') ";			
				$res = ExQuery($cons);				
				echo ExError($res);			
				?>
			<script language="javascript">
				location.href='NewDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Primero=1&Grupal=1&Especialidad=<? echo $Especialidad?>&TMPCOD=<? echo $TMPCOD?>';
			</script> <?							
			}
		}
		
	}
	else{
		$cons="Delete from salud.tempdispomedsxgrup where compania='$Compania[0]' and tmpcod='$TMPCOD'";
		$res = ExQuery($cons);echo ExError();	
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function ChequearTodos(chkbox) { 
	for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
	{ 
		var elemento = document.forms[0].elements[i]; 
		if (elemento.type == "checkbox") 
		{ 
			elemento.checked = chkbox.checked 
		} 
	} 
}
function Validar(){
	var ban=0;
	for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
	{ 
		var elemento = document.forms[0].elements[i]; 
		if (elemento.type == "checkbox") 
		{ 
			if(elemento.checked&&elemento.name!='Todos'){
				ban=1
			}
		} 	
	} 
	if(ban==0){
		alert("Debe seleccionar almenos un medico!!!");return false;
	}
	else{
		document.FORMA.Guardar.value=1;
		document.FORMA.submit();		
	}
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Disponibilidad por Bloques</td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2"><? echo $Especialidad?></td>	
    </tr>
    <tr>
    	<td></td><td align="center"><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Medico</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Incluir</td>
    </tr>
	
	<?
	$consult="Select nombre,Medicos.usuario as usu ,asistencial from Salud.Medicos,central.usuarios,salud.cargos 
	where Medicos.Especialidad='$Especialidad' and Medicos.usuario=usuarios.usuario and Medicos.Compania='$Compania[0]' and cargos.compania='$Compania[0]' and medicos.cargo=cargos.cargos";
	//echo $consult;
    $result=ExQuery($consult);
	while($row=ExFetchArray($result)){
		if($row['asistencial']==1){?>
	    	<tr align="center"><td><? echo $row[0]?></td><td><input type="checkbox" name="Incluir[<? echo $row[1]?>]" ></tr>
<?		}
	}?>
    <tr>
    	<td colspan="2"><input type="button" value="Abrir Disponibilidad" onClick="Validar()"><input type="button" value="Cancelar" onClick="location.href='ConfMedicos.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
<input type="hidden" name="Guardar" value="">
<input type="hidden" name="Especialidad" value="<? echo $Especialidad?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="TMPCOD" 
</form>
</body>
</html>
