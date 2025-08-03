function togglePassword(fieldId, icon) {
    const input = document.getElementById(fieldId);
    if (input.type === "password") {
        input.type = "text";
        icon.textContent = "🙈";
    } else {
        input.type = "password";
        icon.textContent = "👁";
    }
}

document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const errors = [];

    if (password.length < 8) {
        errors.push("Password must be at least 8 characters.");
    }
    if (!/[A-Z]/.test(password)) {
        errors.push("Password must contain at least one uppercase letter.");
    }
    if (!/[a-z]/.test(password)) {
        errors.push("Password must contain at least one lowercase letter.");
    }
    if (!/[!@#$%^&*(),.?\":{}|<>]/.test(password)) {
        errors.push("Password must contain at least one special character.");
    }

    const errorList = document.getElementById("password-errors");
    errorList.innerHTML = "";
    errors.forEach(error => {
        const li = document.createElement("li");
        li.textContent = error;
        errorList.appendChild(li);
    });


    this.classList.toggle("input-error", errors.length > 0);
});