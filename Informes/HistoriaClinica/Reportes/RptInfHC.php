<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$PerIni){$PerIni="$ND[year]-$ND[mon]-01";}
	if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
	
	echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;'>";
	echo "<tr bgcolor='#e5e5e5'><td>Id</td><td>Fecha</td><td>Cedula</td><td>Nombre</td><td>Sexo</td><td>Edad</td><td>CUP</td><td>Cod</td><td>Dx 1</td><td>Dx 2</td><td>Dx 3</td><td>Cargo</td><td>Medico</td><td>Entidad</td>";
	
	if($Cargo){$condAdc1=" and Medicos.Cargo='$Cargo'";}
	if($Entidad){$condAdc2=" and Entidad='$Entidad'";}
	if($Sexo){$condAdc3=" and Sexo='$Sexo'";}
	if($CUPSel){$condAdc4=" and CUP='$CUPSel'";}

	$cons4="Select table_name FROM information_schema.columns
	where table_schema='histoclinicafrms'
	Group By table_name Order By table_name";
	$res4=pg_query($cons4);
	while($fila4=ExFetch($res4))
	{
		$ArrayTables[$fila4[0]]=$fila4[0];
	}


	$cons40="SELECT column_name,table_name
	FROM information_schema.columns 
	where table_schema='histoclinicafrms'
	and (column_name='dx1')
	Order By table_name";

	$res40=pg_query($cons40);
	while($fila40=ExFetch($res40))
	{
		$MatCampos[$fila40[1]]=$fila40[0];
	}


	$cons="Select Fecha,Cedula,PrimApe,SegApe,PrimNom,SegNom,Sexo,FecNac,Entidad,Medicos.Cargo,0,Medico,CUP
	from salud.Agenda,Central.Terceros,salud.Medicos
	where Agenda.Cedula=Terceros.Identificacion
	and Medicos.Usuario=Agenda.Medico
	and Medicos.Compania=Agenda.Compania
	and Terceros.Compania=Medicos.Compania and Terceros.Compania='$Compania[0]'
	and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='Atendida'
	$condAdc1
	$condAdc2
	$condAdc3
	$condAdc4
	Order By Fecha";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Edad="";
		$Edad=ObtenEdad($fila[7]);

		$cons99=NULL;

		foreach($ArrayTables as $TablaFrms)
		{
			$Dato=$MatCampos[$TablaFrms];
			if($Dato)
			{
				if($DX){$condDx=" and Dx1='$DX'";}
				$cons99=$cons99."Select Dx1,Dx2,Dx3,Dx4,Dx5 from histoclinicafrms.$TablaFrms where Compania='$Compania[0]' and Fecha='$fila[0]' and Cedula='$fila[1]' and Cargo='$fila[9]' $condDx Union ";
			}
		}
		$cons44="Select Nombre from Central.Usuarios where Usuario='$fila[11]'";
		$res44=ExQuery($cons44);
		$fila44=ExFetch($res44);
		$UsuarioEscr=$fila44[0];
		
		$cons88="Select Nombre from ContratacionSalud.Cups where Codigo='$fila[12]' and Compania='$Compania[0]'";
		$res88=ExQuery($cons88);
		$fila88=ExFetch($res88);
		$DetCUP=$fila88[0];
		
		$cons89="Select PrimApe from Central.Terceros where Identificacion='$fila[8]' and Compania='$Compania[0]'";
		$res89=ExQuery($cons89);
		$fila89=ExFetch($res89);
		$Entidad=$fila89[0];
		$cons99=substr($cons99,0,strlen($cons99)-6);
		$res99=ExQuery($cons99);
		while($fila99=ExFetch($res99))
		{
			$xi++;
			$cons98="Select Diagnostico from salud.cie where Codigo='$fila99[0]'";
			$res98=ExQuery($cons98);
			$fila98=ExFetch($res98);
			
			$cons98="Select Diagnostico from salud.cie where Codigo='$fila99[1]'";
			$res98=ExQuery($cons98);
			$fila97=ExFetch($res98);

			$cons98="Select Diagnostico from salud.cie where Codigo='$fila99[2]'";
			$res98=ExQuery($cons98);
			$fila96=ExFetch($res98);

			echo "<tr><td>$xi</td><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4] $fila[5]</td><td>$fila[6]</td><td>$Edad</td><td>$DetCUP</td><td>$fila[12]</td><td>$fila99[0] $fila98[0]</td><td>$fila99[1] $fila97[0]</td><td>$fila99[2] $fila96[0]</td><td>$fila[9]</td><td>$UsuarioEscr</td><td>$Entidad</td></tr>";
		}
	}

?>