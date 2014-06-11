		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
		?>	
		
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				<style>
					a{color:blue;text-decoration:none;}
					a:hover{text-decoration:underline;}
				</style>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
			
					<form name="FORMA" method="post" >

					<?
					if($Ver&&$Especialidad&&$Formato)
					{	
						$Remplazo=NULL;
						$FechaFin=str_replace("\\",$Remplazo,$FechaFin);
						$cons="select tblformat from historiaclinica.formatos where formato='$Formato' and tipoformato='$Especialidad' and compania='$Compania[0]'";
						$res=ExQuery($cons);
						$fila=ExFetch($res); $Tbl=$fila[0];
						$cons="select cedula,primape,segape,primnom,segnom,fecnac from histoclinicafrms.$Tbl,central.terceros where $Tbl.compania='$Compania[0]' and
						identificacion=cedula and $Tbl.fecha>='$FechaIni' and $Tbl.fecha<='$FechaFin
						group by cedula,primape,segape,primnom,segnom,fecnac order by primape,segape,primnom,segnom";
						$res=ExQuery($cons);
						//echo $cons;
						$cont=1;
						while($fila=ExFetch($res))
						{
							$Edad=ObtenEdad($fila[5]);
							$Registros[$cont]=array($fila[0],"$fila[1] $fila[2] $fila[3] $fila[4]",$Edad);
							$cont++;
						}?>
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >	       
							<tr>
								<td class="encabezado2Horizontal">Usuarios</td>
							</tr>
						<?	if($NoRegistros>0)
							{
								for($i=1;$i<=$NoRegistros;$i++)
								{
									$Num=rand(1,$cont);
									if($Registros[$Num][0]){?>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td><font size="3"><a href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Registros[$Num][0]?>&Buscar=1'>
											<? echo $Registros[$Num][1]?></a></font>
											<br><? echo $Registros[$Num][0]?><br><strong>Edad: </strong> <? echo $Registros[$Num][2]?> Años</td>
										</tr>
							<?		}
									else{
										$i--;
									}
								}
							}
							else
							{
								foreach($Registros as $Reg)
								{?>
									<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
										<td><font size="3"><a href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Reg[0]?>&Buscar=1'>
										<? echo $Reg[1]?></font></a><br><? echo $Reg[0]?></td>
									</tr>
							<?	}
							}
						?>
						</table><?
					}?>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
			</body>
		</html>