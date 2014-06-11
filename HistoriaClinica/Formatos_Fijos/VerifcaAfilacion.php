<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$conex = pg_connect("dbname=sistema user=postgres password=Server*1982") or die ('no establecida');
	$cons = "SELECT Identificacion FROM Central.Terceros where Tipo='Asegurador' and compania='$Compania[0]'";
	$resultado = pg_query($conex,$cons);
	$Num=0;
	//echo $cons;
	while($fila=pg_fetch_row($resultado)){		
		$Num++;
		$EPSVar[$fila[0]]=array($Num,$fila[0]);
	}
?>
<html>
<head>
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
	function Aplicar(IdEPS)
	{
		
	<?	if($EPSVar){
			foreach($EPSVar as $E){?>
				//alert('<? //echo $E[1]?>')	;
				if(IdEPS=='<? echo $E[1]?>'){
					//alert("ese es");
					parent.document.FORMA.EPS.selectedIndex='<? echo $E[0]?>';		
				}
		<?	}
		}?>
			parent.document.getElementById('EPS').disabled=false;
		CerrarThis();
	}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<? 
$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
mysql_select_db("BDAfiliados",$conex);
$cons="select Entidad from Afiliados where Identificacion='$Identifiacion'";
$res=mysql_query($cons,$conex);  echo mysql_error($conex);
?>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
<?
	if(mysql_num_rows($res)>0){
		$fila=mysql_fetch_row($res);
		$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
		$cons2="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$fila[0]'";
		$res2=pg_query($cons2);
		$fila2=pg_fetch_row($res2);?>
		<tr style="font-weight:bold" align="center">
        	<td>AFILIACION ACTIVA</td>            
        </tr>
        <tr style="font-weight:bold" align="center" >
        	<td><? echo strtoupper(utf8_decode("$fila2[0] $fila2[1] $fila2[2] $fila2[3]"))?></td>            
        </tr>
        <tr align="center">
        	<td>
            	<input type="button" value="Asignar" onClick="Aplicar('<? echo $fila[0]?>')">
            </td>
        </tr>
<?
	}
	else
	{?>
		<tr style="font-weight:bold" align="center">
        	<td>AFILIACION ACTIVA</td>      
      	</tr>
        <tr style="font-weight:bold" align="center">
            <td>NO ENCONTRADA</td>      
        </tr>
        <script language="javascript">
			parent.document.getElementById('EPS').disabled=false;
		</script>
<?	}?>
</table>
</body>
</body>
</html>
