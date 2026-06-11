function toggleAplauso(boton) {
    const idActividad = boton.dataset.idActividad;

    fetch("../controladores/toggle_aplauso.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id_actividad=" + encodeURIComponent(idActividad)
    })
    .then(res => res.json())
    .then(data => {
        if (!data.ok) {
            alert(data.mensaje || "Error al actualizar aplauso");
            return;
        }

        const contador = boton.querySelector("span");

        if (contador) {
            contador.textContent = data.total;
        }

        if (data.activo) {
            boton.classList.add("activo");
        } else {
            boton.classList.remove("activo");
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}