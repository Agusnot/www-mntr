		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if(!$Cuenta && $Cuenta!='0'){exit;}
		?>
	
	<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				
			</head>
			
			<body>	
				<div align="center">
					<table class="tabla1" width="90%"   <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
						<tr>
							<td class='encabezado1Horizontal'>MES</td>
							<td class='encabezado2Horizontal'>D&Eacute;BITOS</td>
							<td class='encabezado2Horizontal'>CR&Eacute;DITOS</td>
						</tr>
					<?
						for($i=1;$i<=12;$i++)
						{
							if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
							else{$BG="white";$Fondo=1;}

							$consMes="Select Mes from Central.Meses where Numero=$i";
							$resMes=ExQuery($consMes,$conex);echo ExError($resMes);
							$filaMes=ExFetch($resMes);
							echo "<tr bgcolor='$BG'><td>".strtoupper($filaMes[0])."</td>";

							$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where CC = '$Cuenta' and date_part('month',Fecha)=$i and date_part('year',Fecha)=$Anio and Compania='$Compania[0]' and Estado='AC'";
							$res=ExQuery($cons);
							$fila=ExFetch($res);echo ExError($res);
							$Debitos=$fila[0];$Creditos=$fila[1];
							if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
							echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";
						}
					?>
					</table>
				</div>	
				
			</body>
	</html>		
