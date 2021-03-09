window.addEventListener('load', function () {
    const form = document.getElementById('form');
    form.addEventListener('recaptcha', function (response) {
        console.log(response);
        this.submit();
    });
});
