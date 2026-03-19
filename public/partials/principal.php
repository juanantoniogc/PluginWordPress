<?php 
global $wpdb;

$tabla = "{$wpdb->prefix}encuestas";
$tabla2 = "{$wpdb->prefix}encuestas_detalle";

if (isset($_POST['btnguardar'])) {

    $nombre = $_POST['txtnombre'];

    $query = "SELECT encuestaId FROM $tabla ORDER BY encuestaId DESC LIMIT 1";
    $resultado = $wpdb->get_results($query, ARRAY_A);

    if (!empty($resultado)) {
        $proximoId = $resultado[0]['encuestaId'] + 1;
    } else {
        $proximoId = 1;
    }

    $shortcode = "[ENC id='$proximoId']";

    $datos = [
        'encuestaId' => null,
        'nombre' => $nombre,
        'shortCode' => $shortcode
    ];

    $respuesta = $wpdb->insert($tabla, $datos);

    if ($respuesta) {
        $listapreguntas = isset($_POST['name']) ? $_POST['name'] : [];
        $i = 0;

        foreach ($listapreguntas as $key => $value) {
            $tipo = isset($_POST['type'][$i]) ? $_POST['type'][$i] : '';

            $datos2 = [
                'detalleId' => null,
                'encuestaId' => $proximoId,
                'pregunta' => $value,
                'tipo' => $tipo
            ];

            $wpdb->insert($tabla2, $datos2);
            $i++;
        }
    }
}

$query = "SELECT * FROM $tabla";
$lista_encuestas = $wpdb->get_results($query, ARRAY_A);

if (empty($lista_encuestas)) {
    $lista_encuestas = array();
}
?>

<div class="wrap">
    <?php echo "<h1 class='wp-heading-inline'>" . get_admin_page_title() . "</h1>"; ?>
    
    <a id="btnnuevo" class="page-title-action">Añadir nueva</a>

    <br><br><br>

    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th>Nombre de la encuestas</th>
                <th>ShortCode</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php 
            foreach ($lista_encuestas as $key => $value) {
                $id = $value['encuestaId'];
                $nombre = $value['nombre'];
                $shortcode = $value['shortCode'];

                echo "
                    <tr>
                        <td>$nombre</td>
                        <td>$shortcode</td>
                        <td>
                            <a data-ver='$id' class='page-title-action'>Ver estadisticas</a>
                            <a data-id='$id' class='page-title-action'>Borrar</a>
                        </td>
                    </tr>
                ";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalnuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">Nueva encuesta</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="txtnombre" class="col-sm-4 col-form-label">Nombre de la encuesta</label>
                        <div class="col-sm-8">
                            <input type="text" id="txtnombre" name="txtnombre" style="width:100%">
                        </div>
                    </div>

                    <br>
                    <hr>
                    <h4>Preguntas</h4>
                    <hr>
                    <br>

                    <table id="camposdinamicos">
                        <tr id="row1">
                            <td>
                                <label class="col-form-label" style="margin-right:5px">Pregunta 1</label>
                            </td>
                            <td>
                                <input type="text" name="name[]" class="form-control name_list">
                            </td>
                            <td>
                                <select name="type[]" class="form-control type_list" style="margin-left:5px">
                                    <option value="1" selected>SI - NO</option>
                                    <option value="2">Rango 0 - 5</option>
                                    <option value="3">Respuesta breve</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" name="add" id="add" class="btn btn-success" style="margin-left:15px">
                                    Agregar mas
                                </button>
                            </td>
                        </tr>
                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="btnguardar" id="btnguardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>