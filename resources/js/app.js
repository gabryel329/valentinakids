import './bootstrap';

window.partialLoader = function (status = true) {

    if (!status) {
        swal.close();
        return;
    }

    Swal.fire({
        customClass: {
            container: "",
            popup: "bg-white",
            header: "",
            title: "",
            closeButton: "",
            icon: "",
            image: "",
            content: "",
            htmlContainer: "",
            input: "",
            inputLabel: "",
            validationMessage: "",
            actions: "",
            confirmButton: "",
            denyButton: "",
            cancelButton: "",
            loader: "swal2-custom-loader",
            footer: "",
            timerProgressBar: "",
        },
        width: 200,
        allowOutsideClick: false,
        showCancelButton: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
        text: "Carregando...",
    });
}

window.onunload = function () { partialLoader(false); };
