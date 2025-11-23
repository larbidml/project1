<?php
include_once '../resource/headerPage.php';
include_once '../resource/Database.php';

/* ============================
   FUNCIONES GENERALES
   ============================ */

/* Sanitización de salida */
function e(?string $valor): string {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

/* Formato fecha: Y-m-d => d-m-Y */
function cambiarFormatoFecha(?string $fecha): ?string {
    if (empty($fecha)) return null;

    $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
    return $fechaObj ? $fechaObj->format('d-m-Y') : null;
}

/* Calcular edad */
function calcularEdad(?string $fechaNacimiento): ?string {
    if (empty($fechaNacimiento)) return null;

    $fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
    if (!$fecha) return null;

    $edad = (new DateTime())->diff($fecha)->y;
    return $edad . " años";
}

/* Si está caducado (vacío, null o inválido → caducado) */
function estaCaducado(?string $fecha): bool {
    if (empty($fecha)) return true;

    $exp = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$exp) return true;

    return $exp < new DateTime('today');
}

/* Pintar documento + fecha de caducidad con estilo según estado */
function pintarDocumento(string $documento, ?string $fechaCaducidad): string {
    $caducado = estaCaducado($fechaCaducidad);
    $caducidadFmt = cambiarFormatoFecha($fechaCaducidad);

    $style = $caducado ? 'style="color:red; font-weight:bold;"' : '';

    $html = "<span $style>" . e($documento) . "</span>";

 
    return $html;
}

/* Banco por código */
function oficinasBancos(string $oficina): string {
    $oficinas = [
        '2080' => 'Abanca Corporación Bancaria',
        '0061' => 'Banca March',
        '0188' => 'Banco Alcalá',
        '0182' => 'BBVA',
        '0130' => 'Banco Caixa Geral',
        '0234' => 'Banco Caminos',
        '2105' => 'Banco Castilla-La Mancha',
        '0240' => 'Banco de Crédito Social Cooperativo',
        '0081' => 'Banco Sabadell',
        '0487' => 'Banco Mare Nostrum',
        '0186' => 'Banco Mediolanum',
        '0238' => 'Banco Pastor',
        '0075' => 'Banco Popular',
        '0049' => 'Banco Santander',
        '3873' => 'Banco Santander Totta',
        '2038' => 'Bankia',
        '0128' => 'Bankinter',
        '0138' => 'Bankoa',
        '0152' => 'Barclays Bank PLC',
        '3842' => 'BNP Paribas Paris',
        '3025' => 'Caixa de Credit del Enginyers',
        '2100' => 'Caixabank',
        '2045' => 'Caja de Ahorros y Monte de Piedad de Ontinyent',
        '3035' => 'Caja Laboral Popular CC',
        '3081' => 'Caja Rural Castilla-La Mancha',
        '2000' => 'Cecabank',
        '1474' => 'Citibank Europe PLC',
        '3821' => 'Commerzbank AG',
        '3877' => 'Danske Bank A/S',
        '0019' => 'Deutsche Bank SAE',
        '0239' => 'EVO Banco',
        '2085' => 'Ibercaja Banco',
        '1465' => 'ING Bank NV',
        '2095' => 'Kutxabank',
        '2048' => 'Liberbank',
        '0131' => 'Novo Banco',
        '0073' => 'Open Bank',
        '0108' => 'Société Générale',
        '2103' => 'Unicaja Banco',
    ];

    return $oficinas[$oficina] ?? '';
}

/* ============================
   PROCESAMIENTO: CONSULTA DB
   ============================ */

$idfamilar = trim($_POST['idfamilar'] ?? "");

$sql = "SELECT * FROM socios WHERE idfamilar = :idfamilar";
$stmt = $db->prepare($sql);
$stmt->execute([':idfamilar' => $idfamilar]);
$resultados = $stmt->fetchAll();

$padre = $madre = null;
$hijos = $familiares = [];

foreach ($resultados as $fila) {
    match ($fila['tiposocio']) {
        "P" => $padre = $fila,
        "C" => $madre = $fila,
        "H" => $hijos[] = $fila,
        "F" => $familiares[] = $fila,
        default => null
    };
}

if (!$padre) {
    echo "<div class='alert alert-danger'>No se encontró el socio padre.</div>";
    exit;
}

$nombre_completo_padre = $padre["nombre"] . " " . $padre["apellido1"] . " " . $padre["apellido2"];

?>

<style>
.uniform-header {
    background-color: #295773;
    font-size: 1rem;
}
</style>
<br>
<div class="container">
<div class="card shadow-sm border-0 rounded-3 overflow-hidden">

    <!-- HEADER -->
    <div class="card-header text-white py-2">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 fw-semibold"><?= e($nombre_completo_padre) ?></h5>
            </div>

            <div class="col-md-6 d-flex justify-content-end gap-2">

                <?php if (!empty($padre["driveLink"])): ?>
                    <a href="<?= e($padre["driveLink"]) ?>" target="_blank" class="btn btn-outline-light btn-sm px-3">
                        ☁️ Drive
                    </a>
                <?php endif; ?>

                <form method="post" action="02-socio_UPDATE_FORM.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= e($padre["idsocio"]) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($padre["idfamilar"]) ?>">
                    <button type="submit" class="btn btn-light btn-sm px-3">✏️ Editar</button>
                </form>

            </div>
        </div>
    </div>

    <!-- BODY -->
    <div class="card-body bg-light">

        <!-- DNI -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">DNI:</span>
                <?= pintarDocumento($padre["documento"], $padre["expirationDate"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Soporte:</span> <?= e($padre["nifSupport"]) ?>
            </div>
        </div>

        <!-- Nombre / Teléfono -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Nombre:</span>
                <?= e($nombre_completo_padre) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Tel:</span>
                <span class="<?= (strlen($padre["telefono"]) !== 9) ? 'text-danger fw-bold' : '' ?>">
                    <?= e($padre["telefono"]) ?>
                </span>
            </div>
        </div>

        <!-- Fecha nacimiento / Dirección -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Fecha Nac.:</span>
                <?= cambiarFormatoFecha($padre["fnacimiento"]) ?>
                (<?= calcularEdad($padre["fnacimiento"]) ?>)
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Dirección:</span> <?= e($padre["direccion"]) ?>
            </div>
        </div>

        <!-- Email / Cadastral -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Email:</span> <?= e($padre["email"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Cadastro:</span> <?= e($padre["cadastralNumber"]) ?>
            </div>
        </div>

        <!-- IBAN -->
        <div class="row mb-2">
            <div class="col-md-6">
                <?php 
                    $oficina_nombre = "IBAN inválido";
                    if (strlen($padre["iban"]) == 24) {
                        $of = substr($padre["iban"], 4, 4);
                        $oficina_nombre = oficinasBancos($of);
                    }
                ?>
                <span class="fw-semibold"><?= e($oficina_nombre) ?>:</span>
                <?= e($padre["iban"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Nota:</span> <?= e($padre["note"]) ?>
            </div>
        </div>

        <!-- Tarjeta sanitaria / Seguridad social -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">T. Sanitaria:</span> <?= e($padre["tsanitaria"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Nº SCC:</span> <?= e($padre["nseguridadsocial"]) ?>
            </div>
        </div>

        <!-- Familia numerosa / Pasaporte -->
        <div class="row mb-2">

            <!-- Familia numerosa -->
            <div class="col-md-6">
                <span class="fw-semibold">F. Numerosa:</span>
                <?= e($padre["fnumerosa"]) ?>

                <?php
                    $exp = cambiarFormatoFecha($padre["fnumerosaexpedicion"]);
                    $cad = cambiarFormatoFecha($padre["fnumerosacaducidad"]);

                    if ($exp && $cad) echo " ($exp, caduca $cad)";
                    elseif ($exp) echo " ($exp)";
                ?>
            </div>

            <!-- Pasaporte -->
            <div class="col-md-6">
                <span class="fw-semibold">Pasaporte:</span>
                <?= pintarDocumento($padre["pasaporte"], $padre["pasaportecaducidad"]) ?>
            </div>

        </div>

        <!-- Lugar nacimiento / Padres -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Lugar Nac.:</span> <?= e($padre["lugarnacimiento"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Padre/Madre:</span>
                <?= e($padre["nombepadre"]) ?> / <?= e($padre["nombremadre"]) ?>
            </div>
        </div>

    </div>

    <!-- ============= FAMILIARES =============== -->

    <div class="card-header text-white py-2">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 fw-semibold">👨‍👩‍👧 Familiares</h5>
            </div>

            <div class="col-md-6 d-flex justify-content-end gap-2">
                                <form method="post" action="add_familiar.php">
                    <input type="hidden" name="idsocio" value="<?= e($padre["idsocio"]) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($padre["idfamilar"]) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($padre["idfamilar"]) ?>">
                    
                    <input type="hidden" name="nombre_completo_padre" value="<?= e($nombre_completo_padre) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-dark">➕ Añadir Familiar</button>
                </form>


                <form method="post" action="add_conyuge.php">
                    <input type="hidden" name="idsocio" value="<?= e($padre["idsocio"]) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($padre["idfamilar"]) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($padre["idfamilar"]) ?>">
                    
                    <input type="hidden" name="nombre_completo_padre" value="<?= e($nombre_completo_padre) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-warning">➕ Añadir Cónyuge</button>
                </form>

                <form method="post" action="add_hijo.php">
                    <input type="hidden" name="idsocio" value="<?= e($padre["idsocio"]) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($padre["idfamilar"]) ?>">
                    <input type="hidden" name="nombre_completo_padre" value="<?= e($nombre_completo_padre) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-warning">➕ Añadir Hijo</button>
                </form>

            </div>
        </div>
    </div>

</div>

<!-- TABLA FAMILIARES -->
<table class="table table-hover table-sm mt-2">
    <thead class="table-light">
        <tr>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>F. Nacimiento</th>
            <th>Edad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

    <!-- Cónyuge -->
    <?php if ($madre): ?>
        <tr>
            <td><span class="badge bg-info">Cónyuge</span></td>

            <td><?= e($madre["nombre"] . " " . $madre["apellido1"] . " " . $madre["apellido2"]) ?></td>

            <td><?= pintarDocumento($madre["documento"], $madre["expirationDate"]) ?></td>

            <td><?= cambiarFormatoFecha($madre["fnacimiento"]) ?></td>

            <td><?= calcularEdad($madre["fnacimiento"]) ?></td>

            <td class="d-flex justify-content-end gap-2">
                <form method="post" action="02-socio_UPDATE_FORM.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= e($madre['idsocio']) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($madre["idfamilar"]) ?>">
                    <button class="btn btn-sm btn-outline-primary">✏️ Editar</button>
                </form>

                <form method="post" action="versocio.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= e($madre['idsocio']) ?>">
                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye-fill"></i> Ver</button>
                </form>
            </td>
        </tr>
    <?php endif; ?>


    <!-- Hijos -->
    <?php foreach ($hijos as $fila): ?>
        <tr>
            <td><span class="badge bg-success">Hijo</span></td>

            <td><?= e($fila["nombre"] . " " . $fila["apellido1"] . " " . $fila["apellido2"]) ?></td>

            <td><?= pintarDocumento($fila["documento"], $fila["expirationDate"]) ?></td>

            <td><?= cambiarFormatoFecha($fila["fnacimiento"]) ?></td>

            <td><?= calcularEdad($fila["fnacimiento"]) ?></td>

            <td class="d-flex justify-content-end gap-2">
                <form method="post" action="02-socio_UPDATE_FORM.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= e($fila['idsocio']) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($fila["idfamilar"]) ?>">
                    <button class="btn btn-sm btn-outline-primary">✏️ Editar</button>
                </form>

                <form method="post" action="versocio.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= e($fila['idsocio']) ?>">
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye-fill"></i> Ver
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>


    <!-- Familiares -->
    <?php foreach ($familiares as $fila): ?>
        <tr>
            <td><span class="badge bg-dark">Familia</span></td>

            <td><?= e($fila["nombre"] . " " . $fila["apellido1"] . " " . $fila["apellido2"]) ?></td>

            <td><?= pintarDocumento($fila["documento"], $fila["expirationDate"]) ?></td>

            <td><?= cambiarFormatoFecha($fila["fnacimiento"]) ?></td>

            <td><?= calcularEdad($fila["fnacimiento"]) ?></td>

            <td class="d-flex justify-content-end gap-2">
                <form method="post" action="02-socio_UPDATE_FORM.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= e($fila['idsocio']) ?>">
                    <input type="hidden" name="idfamilar" value="<?= e($fila["idfamilar"]) ?>">
                    <button class="btn btn-sm btn-outline-primary">✏️ Editar</button>
                </form>

                <form method="post" action="versocio.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= e($fila['idsocio']) ?>">
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye-fill"></i> Ver
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>

</div>
</div>
