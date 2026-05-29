function copyGeneratedPassword() {
    copyText('generated-password', document.querySelector('.copy-button'));
}

function copyText(elementId, button) {
    const value = document.getElementById(elementId).textContent;

    navigator.clipboard.writeText(value).then(function () {
        const originalText = button.textContent;
        button.textContent = 'Copied';

        setTimeout(function () {
            button.textContent = originalText;
        }, 1200);
    });
}
