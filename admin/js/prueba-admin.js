jQuery(document).ready(function ($) {
console.log(SolicitudesAjax);
    $("#btnnuevo").on("click", function () {
        $("#modalnuevo").modal("show");
    });

    var i = $("#camposdinamicos tr").length;

    $(document).off("click", "#add").on("click", "#add", function (e) {
        e.preventDefault();

        i++;

        $("#camposdinamicos").append(
            '<tr id="row' + i + '">' +
                '<td>' +
                    '<label class="col-form-label" style="margin-right:5px">Pregunta ' + i + '</label>' +
                '</td>' +
                '<td>' +
                    '<input type="text" name="name[]" class="form-control name_list">' +
                '</td>' +
                '<td>' +
                    '<select name="type[]" class="form-control type_list" style="margin-left:5px">' +
                        '<option value="1" selected>SI - NO</option>' +
                        '<option value="2">Rango 0 - 5</option>' +
                        '<option value="3">Respuesta breve</option>' +
                    '</select>' +
                '</td>' +
                '<td>' +
                    '<button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove" style="margin-left:15px">X</button>' +
                '</td>' +
            '</tr>'
        );
    });

    $(document).off("click", ".btn_remove").on("click", ".btn_remove", function (e) {
        e.preventDefault();
        var button_id = $(this).attr("id");
        $("#row" + button_id).remove();
    });

    $(document).on('click',"a[data-id]",function(){
            var id = this.dataset.id;
            var url = SolicitudesAjax.url;
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    action : "peticioneliminar",
                    nonce : SolicitudesAjax.seguridad,
                    id: id,
                },
                success:function(){
                    location.reload();
                }
            });
    });


    $(document).on('click',"a[data-ver]",function(){
        $("#modalestadisticas").modal("show");
    })




});