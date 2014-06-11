<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{			
		while( list($cad,$val) = each($IncluyeCup))
		{
			if($cad && $val)
			{				
				if(!$Timeconsulsuge[$cad]){
					$Ban=1;	
				} 	
				else{
					$cons = "Insert into Contratacionsalud.Cupsxconsulextern (Codigo,Timeconsulsuge,Compania,Cargo) values ('$cad',$Timeconsulsuge[$cad],'$Compania[0]','$Cargo')";				
					$res = ExQuery($cons);				
					echo ExError($res);						
				}
			}
		}
		if($Ban==1){
			?><script language="javascript">
			alert("Los CUPS con Tiempos por Consulta en blanco no se incluiran!!!");
			</script><?
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
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
		alert("Debe seleccionar almenos un Procedimiento");return false;
	}	document.FORMA.submit();	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
	
	if($Codigo || $Nombre)
	{
		$cons = "Select Codigo,Nombre from Contratacionsalud.Cups where Compania = '$Compania[0]' and
		Codigo like '$Codigo%' and Nombre ilike '$Nombre%' and Codigo not in(Select codigo from  contratacionsalud.cupsxconsulextern where Cargo = '$Cargo' and Compania='$Compania[0]') order by Nombre";					
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
?>		<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
			<tr>
            <td></td>
            <td></td>
            <td align="center"><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
            <td align="right">
			<button name="Guardar" onClick="Validar()"><img src="/Imgs/b_save.png" title="Guardar"></button>
			</td></tr>            
		    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    		<td>Codigo</td><td>Nombre</td><td>Incluir?</td><td>Tiempo por Consulta Sugerido</td>
    	</tr>
        <?  while($fila = ExFetch($res))
			{
    		?>	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> <?
				echo "<td>$fila[0]</td><td>$fila[1]</td>";							

				?> 
				<td align="center"><input type="checkbox" name="IncluyeCup[<? echo $fila[0]?>]" ></td>              
				<td align="center"><select name="Timeconsulsuge[<? echo $fila[0]?>]"><option></option>
             <? for($i=10;$i<70;$i=$i+10){?>
					<option value="<? echo $i?>"><? echo $i?></option>			
			<?	}?>
                </select></td><? 
				echo "</tr>";
			}
		?> </table>	<? 	
		} 
	} ?>
    <input type="hidden" name="Clase" value="<? echo $Cargo?>">
    <input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
