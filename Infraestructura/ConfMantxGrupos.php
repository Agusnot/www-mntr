<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Guardar)
	{
            while (list ($cad,$val)=each($Frecuencia))
            {
                if($Frecuencia)
                {
                    if($Frecuencia[$cad]=="anio" || $Frecuencia[$cad]=="mes" || $Frecuencia[$cad]=="dia")
                    {
                        $cons = "Update Infraestructura.GruposdeElementos set FecMantenimiento='".$Frecuencia[$cad]."',
                        ValorFecMantenimiento=".$ValorFrecuencia[$cad].",LimiteProrroga='".$Prorroga[$cad]."',
                        ValorLimiteProrroga=".$ValorProrroga[$cad]." Where Compania='$Compania[0]' and Anio=$ND[year] and Grupo='$cad'";
                        $res = ExQuery($cons);
                    }
                }
            }
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
    function CambiarValidacion(Objeto,Grupo,valor)
    {
        if(valor=="anio"){document.getElementById(Objeto+"["+Grupo+"]").value='5'};
        if(valor=="mes"){document.getElementById(Objeto+"["+Grupo+"]").value='11'};
        if(valor=="dia"){document.getElementById(Objeto+"["+Grupo+"]").value='30'};
    }
    function ValidarCampo(Campo,Objeto,Grupo)
    {
        if(parseInt(Campo.value)>parseInt(document.getElementById(Objeto+"["+Grupo+"]").value))
        {
            alert("Valor Invalido");
            Campo.value='';Campo.focus();
        }
    }
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="submit" name="Guardar" value="Guardar" />
<table border="1" width="30%" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
    <tr bgcolor="#e5e5e5" style="font-weight: bold">
        <td rowspan="2">Grupo</td><td align="center" colspan="2">Frecuencia</td><td align="center" colspan="2">Prorroga</td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight: bold">
        <td>En</td><td>Valor</td><td>En</td><td>Valor</td>
    </tr>
    <?
    $cons = "Select Grupo,FecMantenimiento,ValorFecMantenimiento,LimiteProrroga,ValorLimiteProrroga from Infraestructura.GruposdeElementos
    Where Compania='$Compania[0]' and Clase='Devolutivos' and Anio=$ND[year] order by Grupo";
    $res = ExQuery($cons);
    while($fila=ExFetch($res))
    {
        ?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
            <td><? echo $fila[0]?></td>
            <td>
                <select name="Frecuencia[<? echo $fila[0]?>]"
                        onchange="CambiarValidacion('ValorValida1','<? echo $fila[0]?>',this.value);">
                    <option></option>
                    <option value="anio" <? if($fila[1]=="anio"){ echo " selected";}?>>A&ntilde;os</option>
                    <option value="mes" <? if($fila[1]=="mes"){ echo " selected";}?>>Meses</option>
                    <option value="dia" <? if($fila[1]=="dia"){ echo " selected";}?>>Dias</option>
                </select>
                <input type="hidden" id="ValorValida1[<? echo $fila[0]?>]"
                       value="<?
                       if($fila[1]=="anio"){echo "5";}
                       if($fila[1]=="mes"){echo "11";}
                       if($fila[1]=="dia"){echo "30";}
                       ?>"/>
            </td>
            <td>
                <input type="text" name="ValorFrecuencia[<? echo $fila[0]?>]" value="<? echo $fila[2]?>" size="3"
                       onkeyup="xNumero(this)" onkeyDown="xNumero(this)"
                       onBlur="campoNumero(this);ValidarCampo(this,'ValorValida1','<? echo $fila[0]?>');">
            </td>
            <td>
                <select name="Prorroga[<? echo $fila[0]?>]"
                        onchange="CambiarValidacion('ValorValida2','<? echo $fila[0]?>',this.value);">
                    <option></option>
                    <option value="anio" <? if($fila[3]=="anio"){ echo " selected";}?>>A&ntilde;os</option>
                    <option value="mes" <? if($fila[3]=="mes"){ echo " selected";}?>>Meses</option>
                    <option value="dia" <? if($fila[3]=="dia"){ echo " selected";}?>>Dias</option>
                </select>
                <input type="hidden" id="ValorValida2[<? echo $fila[0]?>]" />
            </td>
            <td>
                <input type="text" name="ValorProrroga[<? echo $fila[0]?>]" value="<? echo $fila[4]?>" size="3"
                       onkeyup="xNumero(this)" onkeyDown="xNumero(this)"
                       onBlur="campoNumero(this);ValidarCampo(this,'ValorValida2','<? echo $fila[0]?>');"
                        value="<?
                       if($fila[3]=="anio"){echo "5";}
                       if($fila[3]=="mes"){echo "11";}
                       if($fila[3]=="dia"){echo "30";}
                       ?>"/>
            </td>
        </tr><?
    }
    ?>
</table>
</form>
</body>