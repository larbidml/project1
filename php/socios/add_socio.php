<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
include_once '../resource/headerPage.php';
include_once '../resource/Database.php';

/* ===============================
   Sanitización y valores seguros
   =============================== */
function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}


$tiposocio = "P";

$defaults   = $defaults   ?? [];
$alert      = $alert      ?? null;
$alertType  = $alertType  ?? '';
$csrf       = $csrf       ?? '';

$formData = $_SESSION['form_data'] ?? [];
$formError = $_SESSION['form_error'] ?? null;
unset($_SESSION['form_data'], $_SESSION['form_error']);

?>
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-person-fill-add me-2"></i><strong>Añadir Socio</strong></div>
                </div>

                <div class="card-body " style="background-color: gray;">

                    <?php if ($alert !== null): ?>
                        <div class="alert alert-<?php echo $alertType === 'success' ? 'success' : 'danger'; ?>">
                            <?php echo e($alert); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($formError)): ?>
                        <div class="alert alert-danger">
                            <?php echo e($formError); ?>
                        </div>
                    <?php endif; ?>

                    <!-- FORMULARIO -->
                    <form method="post" action="add_socio_R.php" class="row g-3">

                        <input type="hidden" name="action" value="add_hijo">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrf); ?>">

                        
                        <input type="hidden" name="tiposocio" value="<?php echo "P"; ?>"> 

                        <!-- =======================
                             DOCUMENTACIÓN
                        ======================== -->
                        <!-- DOCUMENTO / CADUCA / SOPORTE -->
                        <div class="row g-3 mt-1">

                            <!-- DOCUMENTO -->
                            <div class="col-12 col-md-4">
                                <label class="form-label mb-1">Documento</label>
                                <input id="documento" type="text" name="documento" class="form-control bg-warning" value="<?php echo e($formData['documento'] ?? ''); ?>" required>
                                <div id="documento-feedback" class="form-text text-danger" style="display:none;"></div>
                            </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control bg-warning" required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Apellido 1 *</label>
                            <input type="text" name="apellido1" class="form-control bg-warning" required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Apellido 2</label>
                            <input type="text" name="apellido2" class="form-control">
                        </div>

                        <div class="col-6 col-md-3">
                            <label class="form-label">F. nacimiento</label>
                            <input type="date" name="fnacimiento" class="form-control">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control">
                        </div>

                        <div class="col-12 col-md-8">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                   
                            <!-- CADUCA -->
                            <div class="col-12 col-md-4">
                                <label class="form-label mb-1">Dni Caduca</label>
                                <input type="date" name="expirationDate" class="form-control" value="<?php echo e($formData['expirationDate'] ?? ''); ?>">
                            </div>

                            <!-- SOPORTE -->
                            <div class="col-12 col-md-4">
                                <label class="form-label mb-1">Soporte</label>
                                <input type="text" name="nifSupport" class="form-control">
                            </div>

                        </div>

                        

                        <!-- =======================
                             SALUD Y SEGURIDAD
                        ======================== -->


                        <div class="col-6 col-md-3">
                            <label class="form-label">IBAN</label>
                            <input type="text" name="iban" class="form-control"pattern="[A-Z0-9]+" title="Introduce IBAN sin espacios">
                        </div>


                        <div class="col-6 col-md-3">
                            <label class="form-label">T. Sanitaria</label>
                            <input type="text" name="tsanitaria" class="form-control">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Nº Seguridad Social</label>
                            <input type="text" name="nseguridadsocial" class="form-control bg-info">
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">T. Numerosa</label>
                                <input type="text" name="fnumerosa" class="form-control">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Expedición</label>
                                <input type="date" name="fnumerosaexpedicion" class="form-control">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Caducidad</label>
                                <input type="date" name="fnumerosacaducidad" class="form-control">
                            </div>
                        </div>

                        

                        <!-- =======================
                             PASAPORTE
                        ======================== -->

                        <div class="col-12 col-md-4">
                            <label class="form-label">Pasaporte</label>
                            <input type="text" name="pasaporte" class="form-control">
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label">Caducidad</label>
                            <input type="date" name="pasaportecaducidad" class="form-control">
                        </div>

                        

                        <!-- =======================
                             NACIMIENTO
                        ======================== -->
                        <div class="col-12 col-md-4">
                            <label class="form-label">Lugar nacimiento</label>
                            <input type="text" name="lugarnacimiento" class="form-control">
                        </div>

                        

                        <!-- =======================
                             ENLACES Y PADRES
                        ======================== -->

                        <div class="col-12 col-md-6">
                            <label class="form-label">Drive link</label>
                            <input type="url" name="driveLink" class="form-control">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Nombre padre</label>
                            <input type="text" name="nombepadre" class="form-control">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Nombre madre</label>
                            <input type="text" name="nombremadre" class="form-control">
                        </div>

                        

                        <!-- =======================
                             MATRIMONIO
                        ======================== -->

                        <div class="col-6 col-md-4">
                            <label class="form-label">Casado con</label>
                            <input type="text" name="casadocon" class="form-control">
                        </div>

                        <div class="col-6 col-md-4">
                            <label class="form-label">Lugar matrimonio</label>
                            <input type="text" name="lugarmatrimonio" class="form-control">
                        </div>

                        <div class="col-6 col-md-4">
                            <label class="form-label">Fecha matrimonio</label>
                            <input type="date" name="fmatrimonio" class="form-control">
                        </div>

                        

                        <!-- =======================
                             NOTAS
                        ======================== -->
                        <div class="col-12">
                            <label class="form-label">Notas</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- =======================
                             BOTONES
                        ======================== -->
                        <div class="col-12 d-flex justify-content-end gap-2">
                           
                            <button class="btn btn-primary" type="submit" id="submit-btn">Guardar Socio</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
                <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const docField = document.getElementById('documento');
                    const feedback = document.getElementById('documento-feedback');
                    const submitBtn = document.getElementById('submit-btn');
                    let timer = null;

                    function checkDocumento() {
                        const val = docField.value.trim();
                        if (!val) {
                            feedback.style.display = 'none';
                            submitBtn.disabled = false;
                            return;
                        }

                        fetch('check_documento.php?documento=' + encodeURIComponent(val))
                            .then(res => res.json())
                            .then(data => {
                                if (data.exists) {
                                    feedback.style.display = 'block';
                                    feedback.textContent = 'El documento ya existe.';
                                    submitBtn.disabled = true;
                                } else {
                                    feedback.style.display = 'none';
                                    submitBtn.disabled = false;
                                }
                            }).catch(err => {
                                console.error(err);
                            });
                    }

                    docField.addEventListener('input', function(){
                        clearTimeout(timer);
                        timer = setTimeout(checkDocumento, 500);
                    });
                    docField.addEventListener('blur', checkDocumento);
                    if (docField.value.trim()) { checkDocumento(); }
                });
                </script>

