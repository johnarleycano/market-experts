<div class="card">
    <div class="card-body">
        <div id="datos">
            <?php $this->load->view('clientes/bitacora/datos', $this->data); ?>
        </div>

        <button class="btn btn-primary btn-block" onClick="javascript:cargarMasDatos('clientes/bitacora')" id="btn_mostrar_mas">Mostrar más</button>
    </div>
</div>