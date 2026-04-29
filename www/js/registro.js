document.addEventListener("DOMContentLoaded", () => {
    const pais = document.getElementById("pais");
    const campoProvincia = document.getElementById("campo_provincia");
    const campoLocalidad = document.getElementById("campo_localidad");

    if (!pais) return;

    pais.addEventListener("change", function () {
        const idPais = this.value;
        const iso = this.options[this.selectedIndex].dataset.iso;

        if (iso === "ES") {
            campoProvincia.innerHTML = `
                <label>Provincia:</label>
                <select name="provincia" id="provincia" required>
                    <option value="">Selecciona provincia</option>
                </select>
            `;

            campoLocalidad.innerHTML = `
                <label>Localidad:</label>
                <select name="localidad" id="localidad" required>
                    <option value="">Selecciona localidad</option>
                </select>
            `;

            cargarProvincias(idPais);
        } else {
            campoProvincia.innerHTML = `
                <label>Provincia:</label>
                <input type="text" name="provincia" required>
            `;

            campoLocalidad.innerHTML = `
                <label>Localidad:</label>
                <input type="text" name="localidad" required>
            `;
        }
    });
    
    pais.dispatchEvent(new Event("change"));
});

function cargarProvincias(idPais) {
    fetch("../controladores/load_provincias.php?id_pais=" + idPais)
        .then(res => res.json())
        .then(data => {
            const provincia = document.getElementById("provincia");

            provincia.innerHTML = `<option value="">Selecciona provincia</option>`;

            data.forEach(p => {
                provincia.innerHTML += `<option value="${p.id}">${p.nombre}</option>`;
            });

            provincia.addEventListener("change", function () {
                cargarLocalidades(this.value);
            });
        });
}

function cargarLocalidades(idProvincia) {
    fetch("../controladores/load_localidades.php?id_provincia=" + idProvincia)
        .then(res => res.json())
        .then(data => {
            const localidad = document.getElementById("localidad");

            localidad.innerHTML = `<option value="">Selecciona localidad</option>`;

            data.forEach(l => {
                localidad.innerHTML += `<option value="${l.id}">${l.nombre}</option>`;
            });
        });
}