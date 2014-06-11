<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
        if(!$CompaniaSel){$CompaniaSel=$Compania[0];}
	
	if($Guardar || $NuevoRol)
	{
		$cons="Delete from Central.UsuariosxModulos where Usuario='$Usuario' and Compania='$CompaniaSel'";
		$res=ExQuery($cons);
		while (list($val,$cad) = each ($Option)) 
		{
			$Cond=split("_",$val);
			$Modulo=$Cond[1];$Madre=$Cond[0];
			if(!$Modulo){$Modulo=$Madre;$Madre="";}
			$cons="Insert into Central.UsuariosxModulos(Usuario,Modulo,Madre,Compania) values ('$Usuario','$Modulo','$Madre','$CompaniaSel')";
			$res=ExQuery($cons);
			echo ExError($res);
		}
                if($Guardar){
		?>
        <script language="javascript">
			alert("El usuario debe reiniciar el sistema para que el cambio surta efecto");
			window.close();
		</script>
        <?
                }
                if($NuevoRol  && $NewRol)
                {
                  $cons87="insert into central.rolesxmodulo (rol,modulo,madre,creador)
                           select '$NewRol',modulo,madre,'usuario' from central.usuariosxmodulos where usuario='$Usuario' and Compania='$CompaniaSel'";
                  $res87=ExQuery($cons87);
                }
	}
	
?>
<script language="JavaScript">
	function Marcar()
	{
		if(document.FORMA.Marcacion.checked==1){MarcarTodo();}
		else{QuitarTodo();}
	}

	function MarcarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=1 
	}
	function QuitarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=0
	}
	function MarcarHijos(Elemento,Valor)
	{
		capas=document.getElementsByTagName('INPUT');
		for (i=0;i<capas.length;i++)
		{
			if(capas[i].id.indexOf(Elemento) != -1)
			{
				capas[i].checked=Valor;
			}
		}
	}
	
	
        function Validar(){
						
	}
</script>
<title>Compuconta Software</title>
<body background="/Imgs/Fondo.jpg">
<form method="post" name="FORMA" onSubmit="return Validar()">
	<table border="1" rules="groups" bordercolor="#ffffff" style="font-family:<? echo $Estilo[8] ?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr style="font-weight:bold"><td>Seleccione un rol:</td><td>

<? 
	$cons="select rol from central.rolesxmodulo group by rol order by rol";	

		$res=ExQuery($cons);?>		
		
        <select name="Rol" onChange="document.FORMA.submit()">
        <option></option>
        <? while($fila=ExFetch($res))
			{
				if($fila[0]==$Rol){?>
					<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
			<?	}
				else{?>
					<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
			<?	}				
			}
		?>
        </select>


        </tr>
        <tr>	
		<td style="font-weight:bold">Crear un Rol:</td>
		<td>

		<input type="text" name="NewRol" id="NewRol"maxlenght="20" style="width:80px;" onKeyUp="Validar(this.value)" />
         <button type="submit" name="NuevoRol" ><img src="/Imgs/b_usradd.png" title="Registrar roles al sistema">
		 </button>
		
		 </td>
			<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
	</td></tr>
        <tr><td  style="font-weight:bold">Compa&ntilde;ia</td>
            <td><select name="CompaniaSel" style="width:200px;" onChange="document.FORMA.submit();">
                    <?
                        $cons23="Select Nombre from central.Compania";
                        $res23=ExQuery($cons23);
                        while($fila23=ExFetch($res23))
                        {
                            if($CompaniaSel==$fila23[0]){echo "<option selected value='$fila23[0]'>$fila23[0]</option>";}
                            else{echo "<option value='$fila23[0]'>$fila23[0]</option>";}
                            
                        }
                    ?>
                    
                </select></td>
        </tr>
        
        </table>
	<table border="1" rules="groups" bordercolor="#ffffff" style="font-family:<? echo $Estilo[8] ?>;font-size:12;font-style:<? echo $Estilo[10]?>">

        <tr style="font-weight:bold"><td>Usuario: <? echo $Usuario?></td><td><input type="checkbox" name="Marcacion" onClick="Marcar()"></td></tr>
<?
	//---
	if($Rol)
	{
		$cons="Select madre,modulo from central.rolesxmodulo where Rol='$Rol'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$MatMadrexRol[$fila[0]]=$fila[0];
			$MatModuloxRol[$fila[0]][$fila[1]]=array($fila[0],$fila[1]);
		}		
	}
	/*foreach($MatModuloxRol as $mad)
	{
		foreach($mad as $mod)
		{
			echo "$mod[0] -->  $mod[1]<br>";
		}
	}*/
	
	//--
	$cons="Select Perfil from Central.AccesoxModulos 
	where Nivel=0 Order By Id";
	$res=ExQuery($cons);
        if($Rol)
        {
            $TablaBusq=" RolesxModulo";$CampoBusq=" Rol ";$VariableBusq=$Rol;
        }
        else
        {
            $TablaBusq=" UsuariosxModulos";$CampoBusq=" Usuario";$VariableBusq=$Usuario; $CondAdic1=" and Compania='$CompaniaSel'";
        }
	while($fila=ExFetch($res))
	{
                $consV1="Select * from Central.$TablaBusq where $CampoBusq='$VariableBusq' and Modulo='$fila[0]' $CondAdic1";
		$resV1=ExQuery($consV1);
		//$filaV1=$
		if(ExNumRows($resV1)==1){$Check1="checked";}else{$Check1="";}
//		if($MatMadrexRol[$fila[0]]){$Check1="checked";}else{$Check1="";}
		?>
		<tr bgcolor="#666699" style="color:white"><td><strong><? echo $fila[0] ?></td><td><input name="Option[<? echo "$Madre_$fila[0]" ?>]" <? echo $Check1 ?> type='checkbox' onClick="MarcarHijos('<? echo $fila[0]?>',this.checked)"></td></tr>
<?
		$cons1="Select Perfil,Madre from Central.AccesoxModulos
		where AccesoxModulos.Madre='$fila[0]'
		and ModuloGr='$fila[0]' Order By Id";
		$res1=ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			$consV2="Select * from Central.$TablaBusq where $CampoBusq='$VariableBusq' and Modulo='$fila1[0]' and Madre='$fila[0]' $CondAdic1";
			$resV2=ExQuery($consV2);
			if(ExNumRows($resV2)==1){$Check2="checked";}else{$Check2="";}			
?>
			<tr><td><ul><? echo $fila1[0] ?></td><td><input <? echo $Check2 ?> type="checkbox" name="Option[<? echo "$fila[0]_$fila1[0]" ?>]" id="<? echo "$fila[0]_$fila1[0]" ?> " onclick="MarcarHijos('<? echo "$fila[0]_$fila1[0]"?>',this.checked)"></td></tr>
<?
			$cons2="Select Perfil from Central.AccesoxModulos
			where AccesoxModulos.Madre='$fila1[0]' and ModuloGr='$fila[0]' Order By Id";
			$res2=ExQuery($cons2);
			while($fila2=ExFetch($res2))
			{
				$consV3="Select * from Central.$TablaBusq where $CampoBusq='$VariableBusq' and Modulo='$fila2[0]' and Madre='$fila[0]' $CondAdic1";
				$resV3=ExQuery($consV3);
				if(ExNumRows($resV3)==1){$Check3="checked";}else{$Check3="";}
?>
				<tr><td><ul><ul><? echo $fila2[0]?></td><td><input <? echo $Check3 ?> type="checkbox" id="<? echo "$fila[0]_$fila1[0]_$fila2[0]"?>" name="Option[<? echo "$fila[0]_$fila2[0]"?>]" onClick="MarcarHijos('<? echo "$fila1[0]_$fila2[0]"?>',this.checked)"></td></tr>
<?
				$cons3="Select Perfil from Central.AccesoxModulos
				where AccesoxModulos.Madre='$fila2[0]' and ModuloGr='$fila[0]' Order By Id";
				$res3=ExQuery($cons3);
				while($fila3=ExFetch($res3))
				{
					$consV4="Select * from Central.$TablaBusq where $CampoBusq='$VariableBusq' and Modulo='$fila3[0]' and Madre='$fila[0]' $CondAdic1";
					$resV4=ExQuery($consV4);
					if(ExNumRows($resV4)==1){$Check3="checked";}else{$Check3="";}?>
					<tr><td><ul><ul><ul><? echo $fila3[0] ?></td><td><input <? echo $Check3 ?> type="checkbox" id="<? echo "$fila[0]_$fila1[0]_$fila2[0]_$fila3[0]"?>" name="Option[<? echo "$fila[0]_$fila3[0]" ?>]"></td></tr>
<?				}		
			}
		}
	}
?>
</table>
<br>
<center>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Usuario" value="<? echo $Usuario?>">
<input type="submit" name="Guardar" value="Guardar">
</form>
</body>