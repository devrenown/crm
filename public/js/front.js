// $(function () {

AOS.init({
    duration: 800,
    once: false
});

// Counter Animation
$(window).on('scroll', function () {
    $('.counter').each(function () {
        var $this = $(this),
            countTo = $this.attr('data-count');
        if (!$this.hasClass('counted') && $(window).scrollTop() + $(window).height() > $this.offset().top) {
            $this.addClass('counted');
            $({ countNum: $this.text() }).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function () {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function () {
                    $this.text(this.countNum + "+");
                }
            });
        }
    });
});

$(window).scroll(function () {
    const bannerScreenHeight = $('#home').outerHeight();

    if ($(window).scrollTop() > bannerScreenHeight) {
        $('nav').removeClass('glass').addClass('bg-light');
        $('.nav-link').removeClass('text-white').addClass('text-dark');
        $('#login-btn').removeClass('btn-primary').addClass('bg-blue-gradient text-white');
    } else {
        $('nav').addClass('glass').removeClass('bg-light');
        $('.nav-link').removeClass('text-dark').addClass('text-white');
        $('#login-btn').removeClass('bg-blue-gradient').addClass('btn-primary');
    }
});

// Scroll to top button
var scrollTopBtn = $('#scrollTopBtn');
$(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
        scrollTopBtn.fadeIn();
    } else {
        scrollTopBtn.fadeOut();
    }
});

scrollTopBtn.click(function () {
    $('html, body').animate({ scrollTop: 0 }, 600);
});

var captchaDemo, captchaContact;

function onloadCallback() {

  const demoDiv = document.getElementById('captcha-demo');
  const contactDiv = document.getElementById('captcha-contact');

  if (demoDiv) {
    captchaDemo = grecaptcha.render('captcha-demo', {
      'sitekey': '6LdkPu0rAAAAAH1n75G8L_XC09TkFJheYCNg84bu'
    });
  }

  if (contactDiv) {
    captchaContact = grecaptcha.render('captcha-contact', {
      'sitekey': '6LdkPu0rAAAAAH1n75G8L_XC09TkFJheYCNg84bu'
    });
  }

}

$('#demo-form').on('submit', function(e) {
    e.preventDefault();

    var response = grecaptcha.getResponse(captchaDemo);
    var form = $(this);

    if (response.length === 0) {
        $('#captcha-message').text('Please verify the captcha');
        return false;
    }

    $('#captcha-message').text('');

    $('#sendRequest')
        .attr('disabled', true)
        .attr('title', 'Sending...')
        .text('Sending...');

    $.ajax({
        url: form.attr('action'),
        type: "POST",
        dataType: "JSON",
        data: form.serialize(),
        success: function(res) {

            if (res.success) {

                form.trigger('reset');
                grecaptcha.reset();

                let html = `
                    <div class="card text-center p-4 border-0 shadow-sm">
                        <div class="text-success mb-3">
                            <i class="bi bi-send-check fs-1"></i>
                        </div>
                        <p class="fw-bold fs-6">Request Sent Successfully ! </p>
                        <p class="text-muted mb-0">Thank you for your interest — our team will contact you shortly.</p>
                    </div>
                `

                $('#form-container').html(html);
            } else {
                alert(res.msg);
            }

            $('#sendRequest')
                .attr('disabled', false)
                .attr('title', 'Send Request')
                .text('Send Request');
        },
        error: function(xhr) {
            console.log('Error:', xhr.responseText);
            alert('Something went wrong, please try again.');

            $('#sendRequest')
                .attr('disabled', false)
                .attr('title', 'Send Request')
                .text('Send Request');
        }
    });
});

$('#contact-form').on('submit', function(e) {
    e.preventDefault();
    
    var form    = $(this);
    let name    = form.find('#name').val()      ?? null;
    let email   = form.find('#email').val()     ?? null;
    let phone   = form.find('#phone').val()     ?? null;
    let message = form.find('#message').val()   ?? null;

    var response = grecaptcha.getResponse(captchaContact);
    var form = $(this);

    if (response.length === 0) {
        $('#error-message').text('Please verify the captcha');
        return false;
    }

    if (!name || !email || !phone) {
        $('#error-message').text('Please Fill Required Fields !');
        return;
    }

    $('#error-message').text('');

    $('#sendMessage')
        .attr('disabled', true)
        .text('Sending...');

    $.ajax({
        url: form.attr('action'),
        type: "POST",
        dataType: "JSON",
        data: form.serialize(),
        success: function(res) {

            if (res.success) {

                form.trigger('reset');
                // grecaptcha.reset();

                let html = `
                    <div class="text-center p-4 border-0">
                        <div class="text-success mb-3">
                            <i class="bi bi-send-check fs-1"></i>
                        </div>
                        <p class="fw-bold fs-6">Message Sent Successfully ! </p>
                        <p class="text-muted mb-0">Thank you for your interest — our team will contact you shortly.</p>
                    </div>
                `;

                $('#contact-container').html(html);
            } else {
                alert(res.msg);
            }

            $('#sendMessage')
                .attr('disabled', false)
                .text('Send Message');
        },
        error: function(xhr) {
            console.log('Error:', xhr.responseText);
            alert('Something went wrong, please try again.');

            $('#sendMessage')
                .attr('disabled', false)
                .text('Send Message');
        }
    });
});

