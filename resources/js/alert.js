window.onload = function () {
    // Obtén el botón de cerrar
    const closeButton = document.querySelector(".closealertbutton");

    // Verifica si el botón de cerrar existe antes de agregar el evento de clic
    if (closeButton) {
        closeButton.addEventListener("click", function () {
            // Oculta el mensaje de éxito cuando se hace clic en el botón de cerrar
            const element = document.getElementById("success-message");
            if (element) {
                element.style.display = "none";
            }
        });
    }

    // Oculta el mensaje de éxito automáticamente después de 5 segundos
    setTimeout(function () {
        const element = document.getElementById("success-message");
        if (element) {
            element.style.display = "none";
        }
    }, 5000);

    // Oculta el mensaje de éxito automáticamente después de 5 segundos
    setTimeout(function () {
        const element = document.getElementById("error-alert");
        if (element) {
            element.style.display = "none";
        }
    }, 5000);
};
