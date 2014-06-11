<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND = getdate();$Anio=$ND[year];
    if($Tipo=="ConteoProductos")
    {
        ?>
        <script language="javascript">
            //for(i = 1; i<=1000; i++){parent.document.FORMA.Bodegas.remove(parent.document.FORMA.Bodegas.options[i-1]);}
            //for(i = 1; i<=1000; i++){parent.document.FORMA.Estantes.remove(parent.document.FORMA.Bodegas.options[i-1]);}
            op = new Option("","");
            parent.document.FORMA.Bodegas.options[0] = op;
            oop = new Option("","");
            parent.document.FORMA.Estantes.options[0] = oop;
        </script>
        <?
        $cons = "Select Bodega from Consumo.Bodegas Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' order by bodega asc";
        $res = ExQuery($cons);
        $i=1;
        while($fila = ExFetch($res))
        {
            ?>
            <script language="javascript">
                op = new Option("<? echo $fila[0]?>","<? echo $fila[0]?>");
                parent.document.FORMA.Bodegas.options[<? echo $i?>] = op;
            </script>
            <?
            $i++;
        }
        $cons = "Select Estante from Consumo.CodProductos Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio group by Estante";
        $res = ExQuery($cons);
        $j=1;
        while($fila=ExFetch($res))
        {
            ?>
            <script language="javascript">
                op = new Option("<? echo $fila[0]?>","<? echo $fila[0]?>");
                parent.document.FORMA.Estantes.options[<? echo $j?>] = op;
            </script>
            <?
            $j++;
        }
    }
    if($Tipo=="LibMedsControl")
    {
        ?>
        <script language="javascript">
            //for(i = 1; i<=1000; i++){parent.document.FORMA.Medicamento.remove(document.FORMA.Durante.options[i-1]);}
            //for(i = 1; i<=1000; i++){parent.document.FORMA.Medicamento.remove(document.FORMA.Durante.options[i-1]);}
            op = new Option("Mostrar todos","");
            parent.document.FORMA.Medicamento.options[0] = op;
        </script>
        <?
        $cons = "Select Autoid,Nombreprod1,UnidadMedida,Presentacion
        from Consumo.CodProductos Where Compania='$Compania[0]' 
        and AlmacenPpal='$AlmacenPpal' and Control='Si' and anio='$Anio'";
        $res = ExQuery($cons);
        $i=1;
        while($fila = ExFetch($res))
        {
            ?>
            <script language="javascript">
                op = new Option("<? echo/*substr($fila[1],0,10);*/"$fila[1] $fila[2] $fila[3]";?>","<? echo $fila[0]?>");
                parent.document.FORMA.Medicamento.options[<? echo $i?>] = op;
                parent.document.FORMA.Medicamento.options[<? echo $i?>].title = "<? echo "$fila[1] $fila[2] $fila[3]"?>";
            </script>
            <?
            $i++;
        }
    }
?>