<?php
$sub_header = "Añadir contacto";
include_once '../resource/headerPage.php';
include_once '../resource/Database.php';
$fechaderegistro = date("Y-m-d H:i:s");

/**
 * Valida una fecha en formato dd-mm-aaaa y la convierte a aaaa-mm-dd.
 * Si es vacía, null o incorrecta, devuelve null.
 */
/**
 * Valida una fecha en formato aaaa-mm-dd.
 * Si está vacía, null o el formato es incorrecto, devuelve null.
 */
function validarFechaISO(?string $fecha): ?string
{
    if (empty($fecha)) {
        return null;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        return null;
    }

    $obj = DateTime::createFromFormat('Y-m-d', $fecha);

    $errores = DateTime::getLastErrors();
    if ($errores === false) {
        $errores = ['warning_count' => 0, 'error_count' => 0];
    }

    if ($obj === false || $errores['warning_count'] > 0 || $errores['error_count'] > 0) {
        return null;
    }

    return $fecha;
}



/* ----------------------------------------------------------
   Sanitización y funciones auxiliares
---------------------------------------------------------- */

function sanitizeInput(?string $v): string {
    return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8');
}



/* ----------------------------------------------------------
   Recoger datos POST (sanitizados)
---------------------------------------------------------- */

$idfamilar      = sanitizeInput($_POST['idfamilar'] ?? ''); 


$tiposocio      = sanitizeInput($_POST['tiposocio'] ?? ''); 
$documento      = sanitizeInput($_POST['documento'] ?? '');


$nombre         = sanitizeInput($_POST['nombre'] ?? '');
$apellido1      = sanitizeInput($_POST['apellido1'] ?? '');
$apellido2      = sanitizeInput($_POST['apellido2'] ?? '');
$telefono       = sanitizeInput($_POST['telefono'] ?? '');
$direccion      = sanitizeInput($_POST['direccion'] ?? '');


$tsanitaria     = sanitizeInput($_POST['tsanitaria'] ?? '');
$fnumerosa      = sanitizeInput($_POST['fnumerosa'] ?? '');
$email          = sanitizeInput($_POST['email'] ?? '');
$note           = sanitizeInput($_POST['note'] ?? '');
$pasaporte      = sanitizeInput($_POST['pasaporte'] ?? '');

$lugarnacimiento= sanitizeInput($_POST['lugarnacimiento'] ?? '');
$nifSupport     = sanitizeInput($_POST['nifSupport'] ?? '');
$nseguridadsocial = sanitizeInput($_POST['nseguridadsocial'] ?? '');
$driveLink      = sanitizeInput($_POST['driveLink'] ?? '');
$iban           = sanitizeInput($_POST['iban'] ?? '');
$nombepadre     = sanitizeInput($_POST['nombepadre'] ?? '');
$nombremadre    = sanitizeInput($_POST['nombremadre'] ?? '');
$casadocon      = sanitizeInput($_POST['casadocon'] ?? '');
$lugarmatrimonio= sanitizeInput($_POST['lugarmatrimonio'] ?? '');



$expirationDate = validarFechaISO($_POST['expirationDate'] ?? null);
$fnacimiento = validarFechaISO($_POST['fnacimiento'] ?? null);
$fnumerosacaducidad = validarFechaISO($_POST['fnumerosacaducidad'] ?? null);
$fnumerosaexpedicion = validarFechaISO($_POST['fnumerosaexpedicion'] ?? null);
$pasaportecaducidad = validarFechaISO($_POST['pasaportecaducidad'] ?? null);
$fmatrimonio = validarFechaISO($_POST['fmatrimonio'] ?? null);



// Fecha de registro
$fechaRegistro = date("Y-m-d H:i:s");

// Verificar si $idsocio está definido (parece que falta esta variable)
if (!isset($idsocio)) {
    // Si no existe, puedes generar uno o manejarlo según tu lógica
    $idsocio = null; // o generar un ID
}

/* ----------------------------------------------------------
   SQL INSERT seguro
---------------------------------------------------------- */

$sql = "INSERT INTO socios (
    idfamilar, tiposocio, documento, expirationDate, nombre, apellido1, apellido2,
    telefono, direccion,  fnacimiento, tsanitaria, fnumerosa, email,
    note, pasaporte, pasaportecaducidad, lugarnacimiento, nifSupport, nseguridadsocial,
    driveLink,  iban, nombepadre, nombremadre, casadocon,
    lugarmatrimonio, fmatrimonio, 
    fnumerosacaducidad, fnumerosaexpedicion, fechaderegistro
) VALUES (
    :idfamilar, :tiposocio, :documento, :expirationDate, :nombre, :apellido1, :apellido2,
    :telefono, :direccion, :fnacimiento, :tsanitaria, :fnumerosa, :email,
    :note, :pasaporte, :pasaportecaducidad, :lugarnacimiento, :nifSupport, :nseguridadsocial,
    :driveLink,  :iban, :nombepadre, :nombremadre, :casadocon,
    :lugarmatrimonio, :fmatrimonio, 
    :fnumerosacaducidad, :fnumerosaexpedicion, :fechaderegistro
)";

try {
    $stmt = $db->prepare($sql);
    
    $ok = $stmt->execute([
        ':idfamilar'        => $idfamilar,
        ':tiposocio'        => $tiposocio,
        ':documento'        => $documento,
        ':expirationDate'   => $expirationDate,
        ':nombre'           => $nombre,
        ':apellido1'        => $apellido1,
        ':apellido2'        => $apellido2,
        ':telefono'         => $telefono,
        ':direccion'        => $direccion,
        ':fnacimiento'      => $fnacimiento,
        ':tsanitaria'       => $tsanitaria,
        ':fnumerosa'        => $fnumerosa,
        ':email'            => $email,
        ':note'             => $note,
        ':pasaporte'        => $pasaporte,
        ':pasaportecaducidad'=> $pasaportecaducidad,
        ':lugarnacimiento'  => $lugarnacimiento,
        ':nifSupport'       => $nifSupport,
        ':nseguridadsocial' => $nseguridadsocial,
        ':driveLink'        => $driveLink,
        ':iban'             => $iban,
        ':nombepadre'       => $nombepadre,
        ':nombremadre'      => $nombremadre,
        ':casadocon'        => $casadocon,
        ':lugarmatrimonio'  => $lugarmatrimonio,
        ':fmatrimonio'      => $fmatrimonio,
  
        ':fnumerosacaducidad'=> $fnumerosacaducidad,
        ':fnumerosaexpedicion'=> $fnumerosaexpedicion,
        ':fechaderegistro'  => $fechaRegistro
    ]);

} catch (PDOException $e) {
    error_log("Error al insertar socio: " . $e->getMessage());
    $ok = false;
}


$sqlQuery =
"SELECT idsocio FROM socios 
WHERE 1
ORDER BY idsocio desc 
";
$statement = $db->prepare($sqlQuery);
$statement->execute();
$data2 = $statement->fetchAll();
$num_rows = $statement->rowCount();
$num_columns = $statement->columnCount();
// print_r($data2);
echo $data2[0]['idsocio'];

if ($tiposocio == "P") {
    



$idfamilar = $data2[0]['idsocio'];

$idsocio = $data2[0]['idsocio'];

/* SQL Update seguro usando prepared statements */
$sqlUpdate = "UPDATE socios SET
    idfamilar = :idfamilar  
WHERE idsocio = :idsocio";
$statement = $db->prepare($sqlUpdate);
$statement->execute([
    ':idsocio' => $idsocio,
    ':idfamilar' => $idfamilar
    
]);
}

?>

<div class="container ">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-12">
            <div class="card shadow-sm">

                <div class="card-body">

                    <?php if ($ok): ?>
                        <div class="alert alert-success">
                            <strong>Registro añadido correctamente.</strong>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <strong>Error al registrar el socio/hijo.</strong>
                            <?php if (isset($e)): ?>
                                <p class="mt-2 mb-0"><small>Error: <?php echo $e->getMessage(); ?></small></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'ver.php';
?>