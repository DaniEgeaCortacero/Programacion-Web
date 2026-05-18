const inputBusqueda = document.getElementById("busquedaAmigos");
const resultados = document.getElementById("resultados");

inputBusqueda.addEventListener("keyup", () => {
    const texto = inputBusqueda.value.trim();

    if (texto.length < 2) {
        resultados.innerHTML = "";
        return;
    }

    fetch("../controladores/buscar_usuarios.php?busqueda=" + encodeURIComponent(texto))
        .then(res => res.text())
        .then(data => {
            resultados.innerHTML = data;
        });
});

function agregarAmigo(idAmigo) {
    fetch("../controladores/agregar_amistad.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id_amigo=" + encodeURIComponent(idAmigo)
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
    });
}