<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$ND[mon]="0".$ND[mon];}
	if($ND[mday]<10){$ND[mday]="0".$ND[mday];}
	$FechaHoy=$ND[year]."-".$ND[mon]."-".$ND[mday];
	if($ND[hours]<10){$ND[hours]="0".$ND[hours];}
	if($ND[minutes]<10){$ND[minutes]="0".$ND[minutes];}
	if($ND[seconds]<10){$ND[seconds]="0".$ND[seconds];}
	$HoraHoy=$ND[hours].":".$ND[minutes].":".$ND[seconds];	
	if($Guardar)
	{
		while( list($cad,$val) = each($Puntaje))
		{
			$partes=explode("_",$cad);	
			if($val==1||($partes[1]>=$idini&&$partes[1]<=$idfin))
			{
				/*echo "$cad $val<br>";				
				echo $partes[0]." ---- ".$partes[1]."<br>";*/
				$cons="INSERT INTO historiaclinica.eadevaluacion(compania, identificacion, fechacrea, fechaead, numservicio, nomarea, item, valor, usuariocrea, edadmeses)
				VALUES ('$Compania[0]', '$Paciente[1]', '$FechaHoy $HoraHoy', '$FechaHoy $HoraHoy', $NumServicio, '$partes[0]', $partes[1], $val, '$usuario[1]',
				$EdadMeses)";
				$res=ExQuery($cons);
			}
		}
		if($Observaciones)
		{
			$cons="INSERT INTO historiaclinica.eadobservaciones(compania, identificacion, fechaead, Observaciones)
			VALUES ('$Compania[0]', '$Paciente[1]', '$FechaHoy $HoraHoy', '$Observaciones')";
			$res=ExQuery($cons);
		}
		?><script language="javascript">location.href="EvaluacionEAD.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>";</script><?
	}	
	$cons="Select letraarea,area,IdItem,NombreItem,rangoedad from historiaclinica.ead1 order by letraarea,IdItem";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MItems[$fila[2]]=$fila[2];
		$MatEscala[$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);
		$MatAreas[$fila[1]]=array($fila[0],$fila[1]);
	}
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css"> body { background-color: transparent } </style>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function VerPreAntecedentes(VerIdentificacion)
{
	    document.getElementById('FrameFondo').src="Framefondo.php";				
		document.getElementById('FrameFondo').style.position='absolute';
		document.getElementById('FrameFondo').style.top='1px';
		document.getElementById('FrameFondo').style.left='1px';
		document.getElementById('FrameFondo').style.display='';
		document.getElementById('FrameFondo').style.width='100%';
		document.getElementById('FrameFondo').style.height='95%';	
		//---revizar idclap
		document.getElementById('PreAntecedentes').src='PreAntecedentes.php?DatNameSID=<? echo $DatNameSID?>&IdClap=<? echo $IdClap?>&VerIdentificacion='+VerIdentificacion;
		document.getElementById('PreAntecedentes').style.position='absolute';
		document.getElementById('PreAntecedentes').style.top='15%';
		document.getElementById('PreAntecedentes').style.left='18%';
		document.getElementById('PreAntecedentes').style.display='';
		document.getElementById('PreAntecedentes').style.width='65%';
		document.getElementById('PreAntecedentes').style.height='35%';		
}
function Validar(idini,idfin)
{
	//alert(idini+" --> "+idfin);
	var frm = document.getElementById("FORMA");
	csel=0;
	cotros=0;
	NomAnt="";
	if(idini!=""&&idfin!="")
	{
		cllenos=0;
		for (i=0;i<frm.elements.length;i++)
		{		
			pasa=false;
			if(frm.elements[i].type=="select-one"&&frm.elements[i].name!="EdadMeses")
			{
				partes=frm.elements[i].name.split("_");
				area=partes[0].split("[");
				id=partes[1].split("]");
				if((parseInt(id[0])>=idini&&parseInt(id[0])<=idfin)||(idini=="<"&&id[0]=="0"))
				{
					if(frm.elements[i].value=="1"){cllenos++;}
					//alert(id[0]);			
				}
				csel++;				
			}
			else
			{
				cotros++;	
			}							
		}
		/*if(idini=="<")
		{
			if(cllenos>1)
			{
				
			}	
		}*/
	}
	else
	{
		alert("La edad del paciente sobrepasa el permitido en por el formato!!!");return false;	
	}
	//alert(frm.elements.length+"  --  "+csel+" -- "+cotros);
	sum=parseInt(frm.elements.length)-(parseInt(csel)+parseInt(cotros));
	if(parseInt(sum)>0){return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar(document.FORMA.idini.value,document.FORMA.idfin.value);">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="idini" value="">
<input type="hidden" name="idfin" value="">
<?
echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>EVALUACIÓN ESCALA ABREVIADA DE DESARROLLO (EAD-1) </b></font></center><br>";
if($MatEscala&&$MatAreas)
{
	$FechaNacimiento=$Paciente[23];
	$EdadPacienteMeses=(ObtenEdad($FechaNacimiento)*12)+ObtenMesesEnEdad($FechaNacimiento);	
	if(!$EdadMeses){$EdadMeses=$EdadPacienteMeses;}
?>
<strong>Edad Meses para Evaluación:</strong>
<select name="EdadMeses" onChange="FORMA.submit();">
<option value="-1" <? if($EdadMeses==-1){echo "selected";}?>>< 1</option>
<?
if($EdadPacienteMeses>=1)
{
	for($i=1;$i<=$EdadPacienteMeses;$i++)
	{
		if($EdadMeses==$i)
		{
			echo "<option value='$i' selected>$i</option>";
		}
		else
		{
			echo "<option value='$i'>$i</option>";	
		}
	}
}
?>
</select>
<table  border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 10px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td>Rango<br />Edad</td>
<?
foreach($MatAreas as $Area)
{?>    
    <td>Item</td>
    <td><? echo $Area[0]."<br>".$Area[1]?></td>
    <td>Puntaje</td>
<?
}?>
</tr>
<?
$crp=0;
foreach($MItems as $IdItem)
{
	$re="";		
	if($crp>1)
	{		
		if((3%($crp-1))==0&&$crp!=2)
		{
			//echo "$IdItem $crp entra mod 0<br>";			
			$rowsp="rowspan='3'";
			$crp=2;	
		}
		else
		{			
			//echo "$IdItem $crp entra 'vacio' <br>";
			$rowsp="";	
			$crp++;			
		}
	}
	elseif($crp==1)
	{
		//echo "$IdItem $crp entra ==1<br>";
		$rowsp="rowspan='3'";	
		$crp++;		
	}
	else
	{
		//echo "$IdItem $crp entra 0<br>";
		$crp++;
		$rowsp="rowspan='1'";		
	}
	?>
    <tr>
    <?
	foreach($MatEscala as $Ar)
	{
		$Aria=$Ar[$IdItem][1];	
		if($rowsp)
		{
			//if($IdItem<10){$nc=1;$sc=4;}elseif($IdItem>=10&&$IdItem<13){$nc=2;$sc=5;}elseif($IdItem>=13){$nc=2;$sc=6;}
			if(strlen($Ar[$IdItem][4])<=5){$nc=1;$sc=4;}elseif(strlen($Ar[$IdItem][4])<=7){$nc=2;$sc=5;}elseif(strlen($Ar[$IdItem][4])==8){$nc=3;$sc=5;}elseif(strlen($Ar[$IdItem][4])>8){$nc=3;$sc=6;}
			//echo $Ar[$IdItem][4]." ".strlen($Ar[$IdItem][4])."<br>";
			//echo $EdadMeses." -- ".substr($Ar[$IdItem][4],0,$nc)." --> ".substr($Ar[$IdItem][4],$sc,$nc);
			if($EdadMeses>=trim(substr($Ar[$IdItem][4],0,$nc))&&$EdadMeses<=trim(substr($Ar[$IdItem][4],$sc,$nc))||$EdadMeses==-1&&$Ar[$IdItem][4]=="< 1")
			{
				?><script language="javascript">document.FORMA.idini.value="<? echo $IdItem;?>";
				if(document.FORMA.idini.value==0){document.FORMA.idfin.value=0;}else{document.FORMA.idfin.value=parseInt(document.FORMA.idini.value)+2;}</script><?	
			}			
		if(!$re)
		{
			$re=1;
			if($Ar[$IdItem][4]!="< 1")
			{			
				$Rango=str_replace(" ","<br><br>",$Ar[$IdItem][4]);
			}
			else
			{$Rango=$Ar[$IdItem][4];}
			?>	    
			<td align="center" <? echo $rowsp?><? if($EdadMeses>=substr($Ar[$IdItem][4],0,$nc)&&$EdadMeses<=substr($Ar[$IdItem][4],$sc,$nc)||$EdadMeses==-1&&$Ar[$IdItem][4]=="< 1"){echo "style='background-color:#D7EFFD'";}?>  ><? echo $Rango?></td>
			<?
			}
		}?>
		<td  align="right" <? if($EdadMeses>=substr($Ar[$IdItem][4],0,$nc)&&$EdadMeses<=substr($Ar[$IdItem][4],$sc,$nc)||$EdadMeses==-1&&$Ar[$IdItem][4]=="< 1"){echo "style='background-color:#D7EFFD'"; }?> ><? echo $Ar[$IdItem][2]?></td>
		<td <? if($EdadMeses>=trim(substr($Ar[$IdItem][4],0,$nc))&&$EdadMeses<=trim(substr($Ar[$IdItem][4],$sc,$nc))||$EdadMeses==-1&&$Ar[$IdItem][4]=="< 1"){echo "style='background-color:#D7EFFD'"; }?>><? echo $Ar[$IdItem][3]?></td>
		<td <? if($EdadMeses>=trim(substr($Ar[$IdItem][4],0,$nc))&&$EdadMeses<=trim(substr($Ar[$IdItem][4],$sc,$nc))||$EdadMeses==-1&&$Ar[$IdItem][4]=="< 1"){echo "style='background-color:#D7EFFD'"; }?>>
		<select id="Puntaje[<? echo $Aria."_".$IdItem?>]" name="Puntaje[<? echo $Aria."_".$IdItem?>]" <? if(empty($Ar[$IdItem][3])){echo "disabled";}?>>
		<option value="0" <? if($Puntaje[$Aria."_".$IdItem]=="0"){echo "selected";}?>>0</option>
		<option value="1" <? if($Puntaje[$Aria."_".$IdItem]=="1"){echo "selected";}?> style="background-color:#C6FFFF">1</option>
		</select> 
		</td>    	
		<?
	}
	?>
    </tr>
    <?
}
?>
</table>
<? echo "<center>";?>
Observaciones<br>
<textarea name="Observaciones" style="width:100%; height:60px"><? echo $Observaciones?></textarea><br>
<input type="submit" name="Guardar" value="Guardar" style="cursor:hand" title="Guardar Evaluación" />
<?
}
else
{	
	echo "<center>No se ha configurado las areas de evaluación de la Escala Abreviada de Desarrollo (EAD-1)!!!<br>";	
}?>
<input type="button" name="Cancelar" value="Cancelar" onClick="document.location.href='EvaluacionEAD.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>'" style="cursor:hand" title="Cancelar Registro (EAD-1)" />
<? 
echo "</center>";
?>
</form>
</body>
