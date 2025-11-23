<?php
$sub_header = "Modificar contacto";
include_once '../resource/headerPage.php';
include_once '../resource/Database.php';

/**
 * Valida una fecha en formato YYYY-MM-DD.
 * Si la fecha es nula, vacía o no tiene el formato o valor válido, retorna null.
 *
 * @param string|null $fecha Fecha a validar.
 * @return string|null Si es válida, devuelve la misma fecha; si no, null.
 */
function validarFechaYmd(?string $fecha): ?string {
    if (empty($fecha)) {
        return null;
    }

    $fecha = trim($fecha);

    // Intentar parsear
    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    $errors = DateTime::getLastErrors();

    // Si falla o si no cumple el formato exacto → null
    if ($dt === false || !empty($errors['warning_count']) || !empty($errors['error_count'])) {
        return null;
    }

    // Verificación estricta (evita fechas overflow)
    if ($dt->format('Y-m-d') !== $fecha) {
        return null;
    }

    return $fecha;
}

/**
 * Valida un IBAN español en formato ESXXYYYYXXXXXXXXXXXXXX.
 * Devuelve el IBAN limpio en mayúsculas si es válido, o null si no lo es.
 *
 * @param string|null $iban
 * @return string|null
 */


/* Fecha de actualización automática */
$fechaactualizacionsocio = date("Y-m-d H:i:s");

/* Función de sanitización y normalización de texto */
function cleanInput(?string $value, bool $toUpper = false): ?string {
    $value = trim($value ?? '');
    return $toUpper ? strtoupper($value) : $value;
}

/* Recolección segura de datos del POST */
$idsocio          = $_POST['idsocio'] ?? null;
$idfamilar        = cleanInput($_POST['idfamilar'] ?? '');


$tiposocio        = cleanInput($_POST['tiposocio'] ?? '');
$documento        = cleanInput($_POST['documento'] ?? '');
$nombre           = cleanInput($_POST['nombre'] ?? '', true);
$apellido1        = cleanInput($_POST['apellido1'] ?? '', true);
$apellido2        = cleanInput($_POST['apellido2'] ?? '', true);
$telefono         = cleanInput($_POST['telefono'] ?? '');
$direccion        = cleanInput($_POST['direccion'] ?? '');
$cadastralNumber  = cleanInput($_POST['cadastralNumber'] ?? '');
$tsanitaria       = cleanInput($_POST['tsanitaria'] ?? '');
$email            = cleanInput($_POST['email'] ?? '');
$note             = cleanInput($_POST['note'] ?? '');
$pasaporte        = cleanInput($_POST['pasaporte'] ?? '');
$lugarnacimiento  = cleanInput($_POST['lugarnacimiento'] ?? '', true);
$nifSupport       = cleanInput($_POST['nifSupport'] ?? '');
$nseguridadsocial = cleanInput($_POST['nseguridadsocial'] ?? '');
$driveLink        = cleanInput($_POST['driveLink'] ?? '');

$iban             = cleanInput($_POST['iban'] ?? '');


$nombepadre       = cleanInput($_POST['nombepadre'] ?? '', true);
$nombremadre      = cleanInput($_POST['nombremadre'] ?? '', true);

$casadocon        = cleanInput($_POST['casadocon'] ?? '', true);
$lugarmatrimonio  = cleanInput($_POST['lugarmatrimonio'] ?? '', true);
$nacionalidad     = cleanInput($_POST['nacionalidad'] ?? '', true);
$direccionlocalidad = cleanInput($_POST['direccionlocalidad'] ?? '', true);
$cpostal          = cleanInput($_POST['cpostal'] ?? '');




$expirationDate    = validarFechaYmd($_POST['expirationDate']) ;
$fnacimiento    = validarFechaYmd($_POST['fnacimiento']) ;
$fnumerosa    = validarFechaYmd($_POST['fnumerosa']) ;
$pasaportecaducidad    = validarFechaYmd($_POST['pasaportecaducidad']) ;
$fmatrimonio    = validarFechaYmd($_POST['fmatrimonio']) ;
$fnumerosacaducidad    = validarFechaYmd($_POST['fnumerosacaducidad']) ;
$fnumerosaexpedicion    = validarFechaYmd($_POST['fnumerosaexpedicion']) ;

/* SQL Update seguro usando prepared statements */
$sqlUpdate = "UPDATE socios SET
    idfamilar = :idfamilar,
    tiposocio = :tiposocio,
    documento = :documento,
    expirationDate = :expirationDate,
    nombre = :nombre,
    apellido1 = :apellido1,
    apellido2 = :apellido2,
    telefono = :telefono,
    direccion = :direccion,
    cadastralNumber = :cadastralNumber,
    fnacimiento = :fnacimiento,
    tsanitaria = :tsanitaria,
    fnumerosa = :fnumerosa,
    email = :email,
    note = :note,
    pasaporte = :pasaporte,
    pasaportecaducidad = :pasaportecaducidad,
    lugarnacimiento = :lugarnacimiento,
    nifSupport = :nifSupport,
    nseguridadsocial = :nseguridadsocial,
    driveLink = :driveLink,
    iban = :iban,
    nombepadre = :nombepadre,
    nombremadre = :nombremadre,
    casadocon = :casadocon,
    lugarmatrimonio = :lugarmatrimonio,
    fmatrimonio = :fmatrimonio,
    nacionalidad = :nacionalidad,
    direccionlocalidad = :direccionlocalidad,
    cpostal = :cpostal,
    fnumerosacaducidad = :fnumerosacaducidad,
    fnumerosaexpedicion = :fnumerosaexpedicion
WHERE idsocio = :idsocio";

$statement = $db->prepare($sqlUpdate);
$statement->execute([
    ':idsocio' => $idsocio,
    ':idfamilar' => $idfamilar,
    ':tiposocio' => $tiposocio,
    ':documento' => $documento,
    ':expirationDate' => $expirationDate,
    ':nombre' => $nombre,
    ':apellido1' => $apellido1,
    ':apellido2' => $apellido2,
    ':telefono' => $telefono,
    ':direccion' => $direccion,
    ':cadastralNumber' => $cadastralNumber,
    ':fnacimiento' => $fnacimiento,
    ':tsanitaria' => $tsanitaria,
    ':fnumerosa' => $fnumerosa,
    ':email' => $email,
    ':note' => $note,
    ':pasaporte' => $pasaporte,
    ':pasaportecaducidad' => $pasaportecaducidad,
    ':lugarnacimiento' => $lugarnacimiento,
    ':nifSupport' => $nifSupport,
    ':nseguridadsocial' => $nseguridadsocial,
    ':driveLink' => $driveLink,
    ':iban' => $iban,
    ':nombepadre' => $nombepadre,
    ':nombremadre' => $nombremadre,
    ':casadocon' => $casadocon,
    ':lugarmatrimonio' => $lugarmatrimonio,
    ':fmatrimonio' => $fmatrimonio,
    ':nacionalidad' => $nacionalidad,
    ':direccionlocalidad' => $direccionlocalidad,
    ':cpostal' => $cpostal,
    ':fnumerosacaducidad' => $fnumerosacaducidad,
    ':fnumerosaexpedicion' => $fnumerosaexpedicion
]);
?>
<br>
<div class="container">
<div class="card shadow-sm border-0 rounded-3 overflow-hidden">
    <div class="card-header bg-light text-info">Modificación exitosa</div>
                    

</div>
</div>


<?php

include_once 'versocio.php';
?>

