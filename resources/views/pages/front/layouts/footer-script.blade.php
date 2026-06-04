<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
<!-- jQuery Validation plugin -->
<script defer src="{{ asset('assets/plugins/jquery-validator/dist/jquery.validate.min.js') }}" defer></script>

<script src="{{ asset('assets/plugins/jQuery-Infinite-Carousel-Slider/jquery.loopslider.min.js') }}" defer></script>
<!-- Front JS -->
<script src="{{ asset('js/front.js') }}" defer></script>

<!-- GOOGLE RECAPTCHA -->
<script>

let recaptchaLoaded = false;
function loadRecaptcha() {
    if (recaptchaLoaded) return;
    recaptchaLoaded = true;
    const script = document.createElement('script');
    script.src =
    'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit';
    script.async = true;
    script.defer = true;
    document.body.appendChild(script);
}

/*
|--------------------------------------------------------------------------
| LOAD CAPTCHA ONLY WHEN USER INTERACTS
|--------------------------------------------------------------------------
*/

document.querySelectorAll(
    '#contact-form input, #demo-form input, [data-bs-target="#staticBackdrop"]'
).forEach(el => {
    el.addEventListener('focus', loadRecaptcha);
    el.addEventListener('mouseenter', loadRecaptcha);
    el.addEventListener('click', loadRecaptcha);
});

</script>

</body>


