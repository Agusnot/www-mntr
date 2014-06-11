<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Guardar){		
		if($VariacionMin){
			$PMin=$PorctMin/100;
			$cons="select minimos,cup from contratacionsalud.cupsxplanservic where compania='$Compania[0]' and autoid=$Autoid and clase='$Clase'";
			//echo $cons;
			$res=ExQuery($cons);
			while($fila=ExFetch($res)){				
				$PctjMin=$PMin*$fila[0];				
				if($VariacionMin=="Incremento"){
					$NMin=$fila[0]+$PctjMin;
				}
				else{
					$NMin=$fila[0]-$PctjMin;
				}				
				$cons2="update contratacionsalud.cupsxplanservic set minimos=$NMin where compania='$Compania[0]' and autoid=$Autoid and clase='$Clase' and cup='$fila[1]'";
				//echo "<br>$cons2";
				$res2=ExQuery($cons2);
			}
			
		}
		if($VariacionMax){
			$PMax=$PorctMax/100;
			$cons="select maximos,cup from contratacionsalud.cupsxplanservic where compania='$Compania[0]' and autoid=$Autoid and clase='$Clase'";
			//echo $cons;
			$res=ExQuery($cons);
			while($fila=ExFetch($res)){				
				$PctjMax=$PMax*$fila[0];				
				if($VariacionMax=="Incremento"){
					$NMax=$fila[0]+$PctjMax;
				}
				else{
					$NMax=$fila[0]-$PctjMax;
				}				
				$cons2="update contratacionsalud.cupsxplanservic set maximos=$NMax where compania='$Compania[0]' and autoid=$Autoid and clase='$Clase' and cup='$fila[1]'";
				//echo "<br>$cons2";
				$res2=ExQuery($cons2);
			}
			
		}
		?><script language="javascript">parent.parent.location.href="PlanesServicio.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Autoid=<? echo $Autoid?>";</script><?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5" cellpadding="2" align="center">  	
	<tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" >Minimo</td>
        <td>
        	<select name="VariacionMin" onChange="document.FORMA.submit()"><option></option>
      	<?	if($VariacionMin=="Incremento"){
            	echo "<option value='Incremento' selected>Incremento</option>";
			}
			else{
				echo "<option value='Incremento'>Incremento</option>";
			}
			if($VariacionMin=="Decremento"){
                echo "<option value='Decremento' selected>Decremento</option>";
			}
			else{
				echo "<option value='Decremento'>Decremento</option>";
			}?>
            </select>
        </td>
        <td>
        	<select name="PorctMin">
 	<?	if($VariacionMin){
			for($i=1;$i<101;$i++){
				if($i==$PorctMin){
					echo "<option value'$i' selected>$i</option>";
				}
				else{
					echo "<option value'$i'>$i</option>";
				}
			}	
		}?>
            </select><strong>%</strong>
        </td>
    </tr>
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" >Maximo</td>
        <td>
        	<select name="VariacionMax" onChange="document.FORMA.submit()"><option></option>           
       	<?	if($VariacionMax=="Incremento"){
            	echo "<option value='Incremento' selected>Incremento</option>";
			}
			else{
				echo "<option value='Incremento'>Incremento</option>";
			}
			if($VariacionMax=="Decremento"){
                echo "<option value='Decremento' selected>Decremento</option>";
			}
			else{
				echo "<option value='Decremento'>Decremento</option>";
			}?>
            </select>
        </td>
        <td>
        	<select name="PorctMax">
 	<?	if($VariacionMax){
			for($i=1;$i<101;$i++){
				if($i==$PorctMax){
					echo "<option value'$i' selected>$i</option>";
				}
				else{
					echo "<option value'$i'>$i</option>";
				}
			}	
		}?>
            </select><strong>%</strong>
        </td>
    </tr>
    <tr align="center">
    	<td colspan="3"><input type="submit" name="Guardar" value="Guardar"> </td>
    </tr>
</table> 
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Clase" value="<? echo $Clase?>">
<input type="hidden" name="Autoid" value="<? echo $Autoid?>">
</form>
</body>
</html>
