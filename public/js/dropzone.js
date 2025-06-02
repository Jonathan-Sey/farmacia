import Dropzone from "dropzone";

Dropzone.autoDiscover = false; // Deshabilitar la auto-descubrimiento de Dropzone

const dropzone = new Dropzone("#dropzone", {
    dictDefaultMessage: "Subir imagen",
    acceptedFiles: ".png,.jpg,.jpeg",
    addRemoveLinks: true,
    dictRemoveFile: "Borrar imagen",
    maxFiles: 1,
    uploadMultiple: false,
});

dropzone.on("success", function(file, response) {
    console.log("Archivo subido correctamente:", response);
});

dropzone.on("error", function(file, message) {
    console.error("Error al subir el archivo:", message);
});
