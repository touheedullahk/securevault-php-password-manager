function copyGeneratedPassword() {
    const password = document.getElementById('generated-password').textContent;
    const button = document.querySelector('.copy-button');

    navigator.clipboard.writeText(password).then(function () {
        button.textContent = 'Copied';
        setTimeout(function () {
            button.textContent = 'Copy';
        }, 1200);
    });
}
