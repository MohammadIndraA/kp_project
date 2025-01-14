function showAlert(type, message) {
    // Create the alert div
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type}`;
    alertDiv.role = "alert";
    alertDiv.innerHTML = ` <i class="dripicons-checkmark me-2"></i> This is a <strong>${type}</strong> alert - ${message} `; // Append the alert to the body or a specific container
    document.body.appendChild(alertDiv); // Remove the alert after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

function alertNotify(type, message) {
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        className: type,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
            background: type == "success" ? "#0acf97" : "#fa5c7c",
        },
        offset: {
            x: 50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
            y: 10, // vertical axis - can be a number or a string indicating unity. eg: '2em'
        },
        onClick: function () {}, // Callback after click
    }).showToast();
}

function loopErrors(errors) {
    $(".invalid-feedback").remove();
    if (errors == undefined) {
        return;
    }
    for (error in errors) {
        $("[name=" + error + "]").addClass("is-invalid");

        $(
            '<span class="error invalid-feedback">' +
                errors[error][0] +
                "</span>"
        ).insertAfter($("[name=" + error + "]"));
        if (errors[error] === "Invalid credentials") {
            alertNotify("error", "Username atau Password Salah");
        }
    }
}
