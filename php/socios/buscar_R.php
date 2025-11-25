<?PHP
include_once '../resource/headerPage.php';
include_once '../resource/Database.php';


$terminobusqueda = $_POST['terminobusqueda'];
$terminobusqueda = trim($terminobusqueda);

$sqlQuery =
"SELECT * FROM socios 
WHERE documento LIKE :terminobusqueda
OR 	nombre LIKE :terminobusqueda 
OR 	apellido1 LIKE :terminobusqueda 
OR 	apellido2 LIKE :terminobusqueda 
OR 	telefono LIKE :terminobusqueda  


ORDER BY documento desc , nombre , apellido1 , apellido2
";
$statement = $db->prepare($sqlQuery);
$statement->execute(array(':terminobusqueda' => "%" . $terminobusqueda . "%"));
$data = $statement->fetchAll();
$num_rows = $statement->rowCount();
$num_columns = $statement->columnCount();
//print_r($data);

?>
<div class="container"><br>
<div class="row justify-content-center">
<div class="col-6">
<div class="card ">

<div class="card-header " style=" background-color: #295773;">
	
	<?php
	echo "<label > registros :  <span class=\"badge rounded-pill bg-primary\">$num_rows</span></label>\n";
	?>

</div>

<?php
if ($statement->rowCount() == 0) 
{

?>
	<div class="card-body ">
		<form method="post" action="07-SEARCH_socio_ALL_R.php">
			<div class="alert alert-danger"><strong>!!</strong> No hay Resultados</div>
                       <div class="mb-2">
                            <div class="input-group mb-3">
                                <span class="input-group-text text-bg-secondary col-2">tipo : </span>
                                <?php
                                    echo "<select class=\"form-control \" id=\"tipobusqueda\" name=\"tipobusqueda\">\n" ;
                                        echo  "<option value=\"documento\">Numero poliza</option>\n" ; 
                                        echo  "<option value=\"nombre\">Nombre</option>\n" ; 
                                        echo  "<option value=\"apellido1\">apellido1</option>\n" ; 
                                        echo  "<option value=\"telefono\">Tel</option>\n" ; 
                                    echo "</select>\n" ; 
                                ?>    
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text text-bg-secondary col-2">Busqueda : </span>
                            <input type="text" class="form-control" name="terminobusqueda"  placeholder="">
                        </div>
			<br>
			<button class="btn btn-primary " type="submit">BUSCAR</button>
		</form>
	</div>
<?php
} 
else 
{

?>

<div class="card-body ">
<table class="table table-striped ">
<thead>
	
	<th>Dni</th>
	<th>Nombre</th>
	
</thead>

<tbody>
<?php
for ($row = 0; $row < $num_rows; $row++) 
{

	echo "<tr>\n";

		
		$documento =  $data[$row]["documento"];
		$nombre_completo = $data[$row]["nombre"] . " " . $data[$row]["apellido1"] . " " . $data[$row]["apellido2"];
		

			echo "<td class=\"\">$documento </td>\n";
			echo "<td class=\"\">$nombre_completo</td>\n";
		
		


		echo "<td>\n";
			echo "<div class=\"btn-group btn-group-lg \">\n";
			
				echo "<form method=\"post\" action=\"../socios/ver.php\">\n";
				echo "<input type=\"hidden\"  name=\"idfamilar\" value=\"{$data[$row]['idfamilar']}\">\n";
				echo "<div ><button type=\"submit\" class=\"btn btn-secondary btn-sm \" > <i class=\"bi bi-eye\"></i> </button></div>\n";
				echo "</form>\n";
				

			echo "</div>\n";
		echo "</td>\n";

	echo "</tr>\n";
}
}

?>
</table>
</div>


