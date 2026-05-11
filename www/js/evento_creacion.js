document.addEventListener("DOMContentLoaded", () => {
    const pais = document.getElementById("pais_evento");
    const campoProvincia = document.getElementById("campo_provincia_evento");
    const campoLocalidad = document.getElementById("campo_localidad_evento");

    if (!pais || !campoProvincia || !campoLocalidad) return;

    pais.addEventListener("change", function () {
        const idPais = this.value;
        const iso = this.options[this.selectedIndex].dataset.iso;

        if (iso === "ES") {
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

            cargarProvinciasEvento(idPais);
        } else {
            campoProvincia.innerHTML = `
                <label>Provincia</label>
                <input type="text" name="provincia" required>
            `;

            campoLocalidad.innerHTML = `
                <label>Localidad</label>
                <input type="text" name="localidad" required>
            `;
        }
    });
});

function cargarProvinciasEvento(idPais) {
    fetch("../controladores/load_provincias.php?id_pais=" + idPais)
        .then(res => res.json())
        .then(data => {
            const provincia = document.getElementById("provincia_evento");

            provincia.innerHTML = `<option value="">Selecciona provincia</option>`;

            data.forEach(p => {
                provincia.innerHTML += `<option value="${p.id}">${p.nombre}</option>`;
            });

            provincia.addEventListener("change", function () {
                cargarLocalidadesEvento(this.value);
            });
        });
}

function cargarLocalidadesEvento(idProvincia) {
    fetch("../controladores/load_localidades.php?id_provincia=" + idProvincia)
        .then(res => res.json())
        .then(data => {
            const localidad = document.getElementById("localidad_evento");

            localidad.innerHTML = `<option value="">Selecciona localidad</option>`;

            data.forEach(l => {
                localidad.innerHTML += `<option value="${l.id}">${l.nombre}</option>`;
            });
        });
}