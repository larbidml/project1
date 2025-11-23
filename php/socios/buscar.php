<?PHP include_once '../resource/headerPage.php';
 ?>



<div class="container"><br>
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card ">
                <div class="card-header">
               
                    <form method="post" action="01-nif_add.php">
                        <label for=""><i class="bi bi-search"></i>
                         Buscar Socio</label>
                    </form>
                   
                </div>
                <div class="card-body  ">
                    <form method="post" action="buscar_R.php">
                       
                        <div class="form-group" >
                            <label for="" class="form-label">Busqueda:</label>
                            <input type="text" class="form-control" name="terminobusqueda"  placeholder="Nombre o Nif o Telefono o dni">
                        </div>

                        <br>
                        <button class="btn btn-primary " type="submit">BUSCAR</button>
                        <a href="../ini/index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

