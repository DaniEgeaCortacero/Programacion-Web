document.addEventListener("DOMContentLoaded", () => {
    const pais = document.getElementById("pais_evento");
    const campoProvincia = document.getElementById("campo_provincia_evento");
    const campoLocalidad = document.getElementById("campo_localidad_evento");

    if (!pais || !campoProvincia || !campoLocalidad) return;

    function crearSelectsUbicacion() {
        campoProvincia.innerHTML = `
            <label>Provincia</label>
            <select name="provincia" id="provincia_evento" required>
                <option value="">Selecciona provincia</option>
            </select>
        `;

        campoLocalidad.innerHTML = `
            <label>Localidad</label>
            <select name="localidad" id="localidad_evento" required>
                <option value="">Selecciona localidad</option>
            </select>
        `;
    }

    function crearInputsTextoUbicacion(provinciaValor = "", localidadValor = "") {
        campoProvincia.innerHTML = `
            <label>Provincia</label>
            <input 
                type="text" 
                name="provincia" 
                value="${provinciaValor}" 
                required
            >
        `;

        campoLocalidad.innerHTML = `
            <label>Localidad</label>
            <input 
                type="text" 
                name="localidad" 
                value="${localidadValor}" 
                required
            >
        `;
    }

    pais.addEventListener("change", function () {
        const idPais = this.value;
        const iso = this.options[this.selectedIndex].dataset.iso;

        if (iso === "ES") {
            crearSelectsUbicacion();
            cargarProvinciasEvento(idPais);
        } else {
            crearInputsTextoUbicacion();
        }
    });


    if (window.EVENTO_EDICION && window.EVENTO_EDICION.modo && window.EVENTO_EDICION.idPais > 0) {
        const optionPais = pais.querySelector(`option[value="${window.EVENTO_EDICION.idPais}"]`);
        const iso = optionPais ? (optionPais.dataset.iso || "").trim().toUpperCase() : "";

        if (iso === "ES" || window.EVENTO_EDICION.idProvincia > 0) {
            crearSelectsUbicacion();

            cargarProvinciasEvento(
                window.EVENTO_EDICION.idPais,
                window.EVENTO_EDICION.idProvincia,
                window.EVENTO_EDICION.idLocalidad
            );
        }
    }
});

let companerosSeleccionados = [];

function agregarCompanero(idUsuario, nombreUsuario) {
    if (companerosSeleccionados.includes(idUsuario)) {
        return;
    }

    companerosSeleccionados.push(idUsuario);

    const inputCompaneros = document.getElementById("companeros_ids");
    const listaCompaneros = document.getElementById("lista_companeros_seleccionados");

    if (inputCompaneros) {
        inputCompaneros.value = JSON.stringify(companerosSeleccionados);
    }

    if (listaCompaneros) {
        listaCompaneros.innerHTML += `
            <span class="companero_chip">@${nombreUsuario}</span>
        `;
    }
}

function cargarProvinciasEvento(
    idPais,
    idProvinciaSeleccionada = 0,
    idLocalidadSeleccionada = 0
) {
    fetch("../controladores/load_provincias.php?id_pais=" + encodeURIComponent(idPais), {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(data => {
            const provincia = document.getElementById("provincia_evento");

            if (!provincia) return;

            provincia.innerHTML = `<option value="">Selecciona provincia</option>`;

            data.forEach(p => {
                const selected = parseInt(p.id) === parseInt(idProvinciaSeleccionada)
                    ? "selected"
                    : "";

                provincia.innerHTML += `
                    <option value="${p.id}" ${selected}>
                        ${p.nombre}
                    </option>
                `;
            });

            provincia.addEventListener("change", function () {
                cargarLocalidadesEvento(this.value);
            });

            if (idProvinciaSeleccionada > 0) {
                cargarLocalidadesEvento(
                    idProvinciaSeleccionada,
                    idLocalidadSeleccionada
                );
            }
        })
        .catch(error => {
            console.error("Error cargando provincias:", error);
        });
}

function cargarLocalidadesEvento(
    idProvincia,
    idLocalidadSeleccionada = 0
) {
    fetch("../controladores/load_localidades.php?id_provincia=" + encodeURIComponent(idProvincia), {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(data => {
            const localidad = document.getElementById("localidad_evento");

            if (!localidad) return;

            localidad.innerHTML = `<option value="">Selecciona localidad</option>`;

            data.forEach(l => {
                const selected = parseInt(l.id) === parseInt(idLocalidadSeleccionada)
                    ? "selected"
                    : "";

                localidad.innerHTML += `
                    <option value="${l.id}" ${selected}>
                        ${l.nombre}
                    </option>
                `;
            });
        })
        .catch(error => {
            console.error("Error cargando localidades:", error);
        });
}