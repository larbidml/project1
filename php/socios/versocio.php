<?php
include_once '../resource/headerPage.php';
include_once '../resource/Database.php';

/* ============================================================
   Funciones auxiliares
   ============================================================ */

function e(?string $valor): string {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

function calcularEdad(?string $fechaNacimiento): ?string {
    if (empty($fechaNacimiento)) return null;

    $fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
    if (!$fecha) return null;

    $edad = (new DateTime())->diff($fecha)->y;
    return $edad !== null ? "{$edad} años" : null;
}

function cambiarFormatoFecha(?string $fecha): ?string {
    if (empty($fecha)) return null;

    $f = DateTime::createFromFormat('Y-m-d', $fecha);
    return $f ? $f->format('d-m-Y') : null;
}

function oficinasBancos(string $oficina): string {
    static $oficinas = [
        '2080'=>'Abanca Corporación Bancaria','0061'=>'Banca March','0188'=>'Banco Alcalá',
        '0182'=>'BBVA','0130'=>'Banco Caixa Geral','0234'=>'Banco Caminos','2105'=>'Banco Castilla-La Mancha',
        '0240'=>'Banco Crédito Social Cooperativo','0081'=>'Banco Sabadell','0487'=>'Banco Mare Nostrum',
        '0186'=>'Banco Mediolanum','0238'=>'Banco Pastor','0075'=>'Banco Popular','0049'=>'Banco Santander',
        '3873'=>'Banco Santander Totta','2038'=>'Bankia','0128'=>'Bankinter','0138'=>'Bankoa',
        '0152'=>'Barclays Bank PLC','3842'=>'BNP Paribas','3025'=>'Caixa Enginyers','2100'=>'Caixabank',
        '2045'=>'Caja Ontinyent','3035'=>'Caja Laboral','3081'=>'Caja Rural CLM','2000'=>'Cecabank',
        '1474'=>'Citibank','3821'=>'Commerzbank','3877'=>'Danske Bank','0019'=>'Deutsche Bank',
        '0239'=>'EVO Banco','2085'=>'Ibercaja','1465'=>'ING','2095'=>'Kutxabank','2048'=>'Liberbank',
        '0131'=>'Novo Banco','0073'=>'Openbank','0108'=>'Société Générale','2103'=>'Unicaja'
    ];

    return $oficinas[$oficina] ?? '';
}

function estaCaducado(?string $fecha): bool {
    if (empty($fecha)) return true;

    $exp = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$exp) return true;

    return $exp < new DateTime('today');
}

/* ============================================================
   Carga de datos del socio
   ============================================================ */

$idsocio = trim($_POST['idsocio'] ?? '');

if (!$idsocio) {
    die("<div class='alert alert-danger'>ID de socio no válido.</div>");
}

$sqlQuery = "SELECT * FROM socios WHERE idsocio = :idsocio";
$stmt = $db->prepare($sqlQuery);
$stmt->execute([':idsocio' => $idsocio]);
$resultados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultados) {
    die("<div class='alert alert-danger'>Socio no encontrado.</div>");
}

?>
<style>
.uniform-header {
    background-color: #295773;
}
</style>
<br>
<div class="container">
<div class="card shadow-sm border-0 rounded-3 overflow-hidden">

    <!-- HEADER -->
    <div class="card-header text-white py-2 uniform-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 fw-semibold">
                    <?= e($resultados['nombre'] . " " . $resultados['apellido1'] . " " . $resultados['apellido2']) ?>
                </h5>
            </div>

            <div class="col-md-6 d-flex justify-content-end gap-2">



                <form method="post" action="02-socio_update_form.php" class="m-0">
                    <input type="hidden" name="idsocio" value="<?= $resultados["idsocio"] ?>">
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

                <?php
                    $doc = e($resultados["documento"]);
                    $fechaExp = $resultados["expirationDate"] ?? null;
                    $caducado = estaCaducado($fechaExp);
                    $caducidadFormateada = cambiarFormatoFecha($fechaExp);
                ?>

                <span style="<?= $caducado ? 'color:red;font-weight:bold;' : '' ?>">
                    <?= $doc ?>
                    <?= $caducidadFormateada ? "(caduca $caducidadFormateada)" : "(sin fecha)" ?>
                </span>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Soporte:</span> <?= e($resultados["nifSupport"]) ?>
            </div>
        </div>

        <!-- Nombre / Teléfono -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Nombre:</span>
                <?= e($resultados["nombre"] . " " . $resultados["apellido1"] . " " . $resultados["apellido2"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Tel:</span>
                <span class="<?= strlen($resultados["telefono"]) !== 9 ? 'text-danger fw-bold' : '' ?>">
                    <?= e($resultados["telefono"]) ?>
                </span>
            </div>
        </div>

        <!-- Fecha nacimiento / Dirección -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Fecha Nac.:</span>
                <?= cambiarFormatoFecha($resultados["fnacimiento"]) ?>
                ( <?= calcularEdad($resultados["fnacimiento"]) ?> )
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Dirección:</span> <?= e($resultados["direccion"]) ?>
            </div>
        </div>

        <!-- Email / Cadastral -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Email:</span> <?= e($resultados["email"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Cadastro:</span> <?= e($resultados["cadastralNumber"]) ?>
            </div>
        </div>

        <!-- IBAN / Nota -->
        <div class="row mb-2">
            <div class="col-md-6">
                <?php 
                    if (strlen($resultados["iban"]) == 24) {
                        $oficina = substr($resultados["iban"], 4, 4);
                        $oficina_nombre = oficinasBancos($oficina);
                    } else {
                        $oficina_nombre = "IBAN";
                    }
                ?>
                <span class="fw-semibold"><?= e($oficina_nombre) ?>:</span>
                <?= e($resultados["iban"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Nota:</span> <?= e($resultados["note"]) ?>
            </div>
        </div>

        <!-- Tarjeta Sanitaria / Seguridad Social -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">T. Sanitaria:</span> <?= e($resultados["tsanitaria"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Nº SCC:</span> <?= e($resultados["nseguridadsocial"]) ?>
            </div>
        </div>

        <!-- Familia numerosa / Pasaporte -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">F. Numerosa:</span> 
                <?= e($resultados["fnumerosa"]) ?>
                <?php
                    $exp = cambiarFormatoFecha($resultados["fnumerosaexpedicion"]);
                    $cad = cambiarFormatoFecha($resultados["fnumerosacaducidad"]);

                    if ($exp && $cad) echo " ($exp, caduca $cad)";
                    elseif ($exp) echo " ($exp)";
                ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Pasaporte:</span> 
                <?= e($resultados["pasaporte"]) ?>

                <?php
                    $cadPas = cambiarFormatoFecha($resultados["pasaportecaducidad"]);
                    echo $cadPas ? " (caduca $cadPas)" : "(sin fecha)";
                ?>
            </div>
        </div>

        <!-- Lugar nacimiento / Padres -->
        <div class="row mb-2">
            <div class="col-md-6">
                <span class="fw-semibold">Lugar Nac.:</span> <?= e($resultados["lugarnacimiento"]) ?>
            </div>

            <div class="col-md-6">
                <span class="fw-semibold">Padres:</span>
                <?= e($resultados["nombepadre"]) ?> / <?= e($resultados["nombremadre"]) ?>
            </div>
        </div>

    </div>


        <!-- Lugar nacimiento / Padres -->
        <div class="row mb-2">
            <div class="col-md-6">
                
                   <form action="ver.php" method="post">
                        <div class="text-end">
                            <button class="btn btn-primary btn-sm px-4 rounded-3" type="submit">
                                <input type="hidden" name="idfamilar" value="<?= $resultados["idfamilar"] ?>">
                              <i class="bi bi-arrow-return-left"></i> volver
                            </button>
                        </div>

                    </form>
            </div>

     

    </div>

                           

</div>
</div>
