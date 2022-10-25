<div class="modal-header">
    <h5 class="modal-title">Cambiar clasificación masiva</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <form class="row">
        <div class="col-12 col-md-6 col-lg-6 form-group">
            <label for="clasificacion_anterior" class="form-label">Anterior <b class="text-danger">*</b></label>
            <select id="clasificacion_anterior" class="form-select form-select-sm">
                <option value="">Seleccione</option>
                <?php foreach ($this->configuracion_model->obtener('clientes_bitacora_clasificaciones') as $clasificacion) { ?>
                    <option value="<?php echo $clasificacion->id; ?>"><?php echo $clasificacion->nombre; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-12 col-md-6 col-lg-6 form-group">
            <label for="clasificacion_nueva" class="form-label">Nueva <b class="text-danger">*</b></label>
            <select id="clasificacion_nueva" class="form-select form-select-sm">
                <option value="">Seleccione</option>
                <?php foreach ($this->configuracion_model->obtener('clientes_bitacora_clasificaciones') as $clasificacion) { ?>
                    <option value="<?php echo $clasificacion->id; ?>"><?php echo $clasificacion->nombre; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-12 col-md-12 col-lg-12 form-group">
            <label for="usuario_asignado" class="form-label">Asignar usuario <b class="text-danger">*</b></label>
            <select id="usuario_asignado" class="form-select form-select-sm">
                <option value="">Seleccione</option>
                <?php foreach ($this->usuarios_model->obtener('usuarios') as $usuario) { ?>
                    <option value="<?php echo $usuario->id; ?>"><?php echo "$usuario->nombres $usuario->apellidos"; ?></option>
                <?php } ?>
            </select>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success" onClick="javascript:cambiarClasificacion()">Cambiar</button>
</div>

<script type="text/javascript">
    cambiarClasificacion = async () => {
        let camposObligatorios = [
            $('#clasificacion_anterior'),
            $('#clasificacion_nueva'),
            $('#usuario_asignado')
        ]

        // Se validan los datos obligatorios
        if (!validarCamposObligatorios(camposObligatorios)) return false

        let datos = {
            clasificacion_anterior: $('#clasificacion_anterior').val(),
            clasificacion_nueva: $('#clasificacion_nueva').val(),
            usuario_asignado: $('#usuario_asignado').val()
        }

        // Se realiza el cambio de clasificación
        let respuesta = await consulta('cambiar_clasificacion_clientes', datos)

        // Al cambiar la clasificación de varios clientes
        if (parseInt(respuesta.clasificaciones) > 0 && parseInt(respuesta.clientes) > 0) {
            mostrarNotificacion('exito', 'Se actualizaron los datos correctamente', 5000)
            cerrarModal()
            listarClientes()
        } else {
            mostrarNotificacion('alerta', 'Debe seleccionar una clasificación que los clientes tengan asignada.', 10000)
        }
    }

    /**
     *  Para no seleccionar la misma clasificación en las dos listas desplegables
     *  se realiza la validación para desactivar la opción en la lista
     *  que aun no ha sido seleccionada y aplica en viceversa
     */
    validarSeleccionClasificacion = (activo, inactivo) => {
        const opcionActiva = $(`#${activo} option:selected`)

        for (let opcionInactiva of $(`#${inactivo}`)[0]) {
            if ($(opcionActiva).val() === $(opcionInactiva).val()) $(opcionInactiva).attr('disabled', true)
            if ($(opcionActiva).val() !== $(opcionInactiva).val()) $(opcionInactiva).removeAttr('disabled', true)
        }
    }

    $().ready(() => {
        // Al seleccionar la clasificación anterior
        $('#clasificacion_anterior').change(() => {
            validarSeleccionClasificacion('clasificacion_anterior', 'clasificacion_nueva')
        })

        // Al seleccionar la clasificación nueva
        $('#clasificacion_nueva').change(() => {
            validarSeleccionClasificacion('clasificacion_nueva', 'clasificacion_anterior')
        })
    })
</script>