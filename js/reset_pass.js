function togglePassword(inputId, iconSpan) {
  const input = document.getElementById(inputId);
  if (input.type === "password") {
    input.type = "text";
    iconSpan.textContent = "🙈";
  } else {
    input.type = "password";
    iconSpan.textContent = "👁️";
  }
}

const passwordInput = document.getElementById('password');
passwordInput.addEventListener('input', function () {
  const value = passwordInput.value;
  document.getElementById('rule-length').classList.toggle('valid', value.length >= 8);
  document.getElementById('rule-uppercase').classList.toggle('valid', /[A-Z]/.test(value));
  document.getElementById('rule-lowercase').classList.toggle('valid', /[a-z]/.test(value));
  document.getElementById('rule-number').classList.toggle('valid', /\d/.test(value));
  document.getElementById('rule-specialkey').classList.toggle('valid', /[^a-zA-Z0-9]/.test(value));

});