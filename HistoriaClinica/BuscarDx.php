<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	function Edad($edad){
		list($anio,$mes,$dia) = explode("-",$edad);
		$anio_dif = date("Y") - $anio;
		$mes_dif = date("m") - $mes;
		$dia_dif = date("d") - $dia;
		if($mes_dif<0){
			$anio_dif--;
			//echo "fechanac=$edad $anio_dif $mes_dif $dia_dif<br>";
		}
		elseif($mes_dif==0){
			if ($dia_dif < 0){
				$anio_dif;
				//echo "fechanac=$edad $anio_dif $mes_dif $dia_dif<br>";
			}
		}		
		return $anio_dif;
	}
	$Edad=Edad($Paciente[23]);
	$cons="Select Diagnostico,Codigo from Salud.CIE where favorito='1' limit 1";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){$Fav=1;$Colsp=2;}
	
	
		// Define el focus de acuerdo al elemento desde el cual se realiza la solicitud
		if(isset($_GET["focus"])){		
			if (strtoupper($_GET["focus"]) == "DX") {
				$eventoOnload = 'onLoad="'."javascript: document.getElementById('Codigo').focus()".'";';
			} else {
				$eventoOnload = 'onLoad="'."javascript: document.getElementById('Diagnostico').focus()".'";';
			}
		} else {
			$eventoOnload = 'onLoad="'."javascript: document.getElementById('Diagnostico').focus()".'";';
		}
	
	
?>
		<title>Buscar Diagnóstico</title>
		<body background="/Imgs/Fondo.jpg" <?php echo $eventoOnload ; ?>>

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
			function AbrirPyP(){
				st = document.body.scrollTop;
				//parent.document.getElementById('FrameFondo').style.position='absolute';
				//parent.document.getElementById('FrameFondo').style.top='1px';
				//parent.document.getElementById('FrameFondo').style.left='1px';
				//parent.document.getElementById('FrameFondo').style.display='';
				//parent.document.getElementById('FrameFondo').style.width='100%';
				//parent.document.getElementById('FrameFondo').style.height='100%';
			
				//frames.FrameOpener.location.href="/HistoriaClinica/Formatos_Fijos/VerificaPyP.php?HistoC=1&DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Paciente[26]?>&Edad=<? echo $Edad?>&Sexo=<? echo $Paciente[24]?>&Dx=1&CodDx="+parent.document.FORMA.<? echo $ControlOrigen?>.value;
				//document.getElementById('FrameOpener').style.position='absolute';
				//document.getElementById('FrameOpener').style.top=st;
				//document.getElementById('FrameOpener').style.left='1';
				//document.getElementById('FrameOpener').style.display='';
				//document.getElementById('FrameOpener').style.width='100%';
				//document.getElementById('FrameOpener').style.height='100%';		
				
				document.FORMA.submit();
			}
			
			
		</script>
		<form name="FORMA" >
		<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
		<button name="Cerrar" onClick="CerrarThis()" style="position:absolute; top:1; right:2;"><b>X</b></button>
		<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
		<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo</td><td>Diagnostico</td>
		<?
		if($Fav)
		{
			$PC="+'&Clasificacion='+Clasificacion.value";
		?>
			<script language="javascript">
				
				parent.document.getElementById('FrameOpener').style.width='700';
			</script>
		<td>Clase</td>
		<?
		}?>
		</tr>
		<tr>
		<td><input type="Text" name="Codigo" id = "Codigo" style="width:50px;" onKeyUp="frames.ListaCIE.location.href='ListaCIE.php?ControlOrigen=<? echo $ControlOrigen;?>&DetalleOrigen=<? echo $DetalleObj;?>&Codigo='+this.value+'&Diagnostico='+Diagnostico.value<? echo $PC?>"></td>
		<td><input type="Text" name="Diagnostico" id="Diagnostico" style="width:500px;" onKeyUp="frames.ListaCIE.location.href='ListaCIE.php?ControlOrigen=<? echo $ControlOrigen;?>&DetalleOrigen=<? echo $DetalleObj;?>&Codigo='+Codigo.value+'&Diagnostico='+this.value<? echo $PC?>"></td>
		<?
		if($Fav)
		{
			$Colsp=3;
			if(!$Clasificacion){$Clasificacion="Favoritos";}
		?>
		<td>
		<select name="Clasificacion" onChange="frames.ListaCIE.location.href='ListaCIE.php?Codigo='+Codigo.value+'&Diagnostico='+Diagnostico.value+'&Clasificacion='+Clasificacion.value">
			<option value="">Todos</option>
			<option value="Favoritos" <? if($Clasificacion=="Favoritos"){?> selected<? }?>>Favoritos</option>
			<option value="No Favoritos" <? if($Clasificacion=="No Favoritos"){?> selected<? }?>>No Favoritos</option>
		</select>
		</td>
		<?
		}
		else
		{$Colsp=2;}
		?>
		</tr>
		<tr><td colspan="<? echo $Colsp?>">
		<iframe name="ListaCIE" id="ListaCIE" src="ListaCIE.php?DatNameSID=<? echo $DatNameSID?>" style="height:240px;width:100%;" frameborder="0"></iframe>
		</td></tr>

		</table>
		<center><br>

		<?
			if($Fav)
			{?>
			<script language="javascript">
			frames.ListaCIE.location.href="ListaCIE.php?Codigo=<? echo $Codigo;?>&Diagnostico=<? echo $Diagnostico;?>&Clasificacion=<? echo $Clasificacion;?>";
			</script>
			<?
			}?>
		</form>

		<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
		<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1"> 
		</body>
		</html>
	
	
		
		
		