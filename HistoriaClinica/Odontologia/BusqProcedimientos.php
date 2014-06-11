<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
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
	function Insertar(Cod,Nom)
	{
		parent.document.getElementById('Cup').value=Cod;
		parent.document.getElementById('NomCup').value=Nom;		
		CerrarThis();
	}
</script>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">   	
<?
if($Codigo!=''||$Nombre!=''){?>
	<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
<?		
	if($Codigo==''){
		 $cons="select codigo,nombre,quirurgico from contratacionsalud.cups where compania='$Compania[0]' and Nombre ilike '%$Nombre%'";
	}
	else{
		if($Nombre==''){
			 $cons="select codigo,nombre,quirurgico from contratacionsalud.cups where compania='$Compania[0]' and codigo ilike '$Codigo%'";
		}
		else{
			 $cons="select codigo,nombre,quirurgico from contratacionsalud.cups where compania='$Compania[0]' and codigo ilike '$Codigo%' 
			 and Nombre ilike '%$Nombre%'";
		}		
	}

	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="parent.CerrarThis();
							parent.parent.document.FORMA.NomCup.value='<? echo $fila[1]?>';
							parent.parent.document.FORMA.Cup.value='<? echo $fila[0]?>'; 
                            parent.parent.document.FORMA.QoNQ.value='<? echo $fila[2]?>'; 
                            parent.parent.document.FORMA.submit();">
			<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td></tr>	
<?	}?>
	</table><?        
}
?>
</body>
</html>