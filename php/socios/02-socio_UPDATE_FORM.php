<?php
include_once '../resource/headerPage.php';
include_once '../resource/Database.php';

/* Sanitización segura */
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/* Validación del parámetro */
$idsocio = $_POST['idsocio'] ?? null;
$idfamilar = $_POST['idfamilar'] ?? null;
if (!$idsocio) {
    die("ID inválido.");
}

/* Consulta segura */
$sql = "SELECT * FROM socios WHERE idsocio = :idsocio LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute([':idsocio' => $idsocio]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Registro no encontrado.");
}

/* Nombre completo */
$nombre_completo_socio = trim($row["nombre"] . " " . $row["apellido1"] . " " . $row["apellido2"]);
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card shadow-sm rounded-4 border-0" >

                <!-- Encabezado -->
                <div class="card-header bg-warning text-dark rounded-top-4">
                    <h5 class="mb-0">Editar : <?= e($nombre_completo_socio) ?></h5>
                </div>

                <!-- Formulario -->
                <div class="card-body text-bg-secondary p-3" >

                    <form action="02-socio_UPDATE_FORM_R.php" method="post">

                        <!-- Campo oculto -->
                        <input type="hidden" name="idsocio" value="<?= e($row['idsocio']) ?>">
                        <input type="hidden" name="idfamilar" value="<?= e($row['idfamilar']) ?>">

                        <!-- Datos personales -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-12 d-flex align-items-center">
                                <label class="form-label small fw-bold me-2 mb-0">Tipo socio</label>
                                <input type="text" class="form-control form-control-sm " 
                                    name="tiposocio" value="<?= e($row['tiposocio']) ?>" style="max-width: 150px;">
                            </div>
                        </div>

                        
                        <div class="row g-2 mb-3">

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Documento</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="documento" value="<?= e($row['documento']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">NIF soporte</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="nifSupport" value="<?= e($row['nifSupport']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Fecha caducidad doc.</label>
                                <input type="date" class="form-control form-control-sm" 
                                       name="expirationDate" value="<?= e($row['expirationDate']) ?>">
                            </div>


                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Nombre</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="nombre" value="<?= e($row['nombre']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Apellido 1</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="apellido1" value="<?= e($row['apellido1']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Apellido 2</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="apellido2" value="<?= e($row['apellido2']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Fecha nacimiento</label>
                                <input type="date" class="form-control form-control-sm" 
                                       name="fnacimiento" value="<?= e($row['fnacimiento']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Teléfono</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="telefono" value="<?= e($row['telefono']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">IBAN</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="iban" value="<?= e($row['iban']) ?>">
                            </div>

                        </div>
<hr>
                        <!-- Dirección -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Dirección</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="direccion" value="<?= e($row['direccion']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Email</label>
                                <input type="email" class="form-control form-control-sm" 
                                       name="email" value="<?= e($row['email']) ?>">
                            </div>


                        </div>
                                                <div class="col-md-4">
                                <label class="form-label small fw-bold">cadastralNumber</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="cadastralNumber" value="<?= e($row['cadastralNumber']) ?>">
                            </div>
<hr>
                        <!-- Documentación -->


                       <div class="row g-2 mb-3  ">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Tarjeta sanitaria</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="tsanitaria" value="<?= e($row['tsanitaria']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Seguridad social</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="nseguridadsocial" value="<?= e($row['nseguridadsocial']) ?>">
                            </div>
                        </div> 

                        

                        <!-- Familia numerosa -->
                       <div class="row g-2 mb-3 ">
                         <div class="col-md-4">
                                <label class="form-label small fw-bold">Familia numerosa</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="fnumerosa" value="<?= e($row['fnumerosa']) ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Expedición familia numerosa</label>
                                <input type="date" class="form-control form-control-sm" 
                                       name="fnumerosaexpedicion" value="<?= e($row['fnumerosaexpedicion']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Cad. familia numerosa</label>
                                <input type="date" class="form-control form-control-sm" 
                                       name="fnumerosacaducidad" value="<?= e($row['fnumerosacaducidad']) ?>">
                            </div>
                        </div> 

                         <!-- Pasaporte-->
                       <div class="row g-2 mb-3  ">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Pasaporte</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="pasaporte" value="<?= e($row['pasaporte']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Caducidad pasaporte</label>
                                <input type="date" class="form-control form-control-sm" 
                                       name="pasaportecaducidad" value="<?= e($row['pasaportecaducidad']) ?>">
                            </div>

                        </div> 

<hr>
                        <!-- Información adicional -->
                       
                        <div class="row g-2 mb-3">

                            <?php 
                            if ($row['tiposocio'] == "P") {
                                ?>
                                <div class="col-md-4">
                                <label class="form-label small fw-bold">Drive Link</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="driveLink" value="<?= e($row['driveLink']) ?>">
                            </div>


                            
                            <?php
                            }
                            ?>
    

                        </div>


<hr>
                        <!-- Familia -->                
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Lugar nacimiento</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="lugarnacimiento" value="<?= e($row['lugarnacimiento']) ?>">
                            </div>
 
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Nombre padre</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="nombepadre" value="<?= e($row['nombepadre']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Nombre madre</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="nombremadre" value="<?= e($row['nombremadre']) ?>">
                            </div>
                           <div class="col-md-4">
                                <label class="form-label small fw-bold">Lugar matrimonio</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="lugarmatrimonio" value="<?= e($row['lugarmatrimonio']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Casado con</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="casadocon" value="<?= e($row['casadocon']) ?>">
                            </div>



                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Fecha matrimonio</label>
                                <input type="date" class="form-control form-control-sm" 
                                       name="fmatrimonio" value="<?= e($row['fmatrimonio']) ?>">
                            </div>

       
                        </div>
<hr>
                     <!-- Notas -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nota</label>
                            <textarea class="form-control form-control-sm" name="note" rows="2"><?= e($row['note']) ?></textarea>
                        </div>

                        <!-- Botón submit -->
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm px-4 rounded-3" type="submit">
                                Guardar cambios
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
