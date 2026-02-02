<?php
    $error = $errors->has($name) ? 'is-invalid' : '';
    $inputId = random_str(5);
    if (!empty($id) && isset($id)) {
        $inputId = $id;
    }
?>
<input type="hidden" name="country_code">
<input type="hidden" name="country_name">
<input type="hidden" name="dial_code">
<input id="<?php echo e($inputId); ?>" <?php echo $attributes->merge(['class' => 'form-control ' . $error]); ?>>
<?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="invalid-feedback">
        <?php echo e($message); ?>

    </div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<script>
    $(document).ready(function() {
        let input = document.querySelector("#<?php echo e($inputId); ?>")
        let iti = intlTelInput(input, {
            initialCountry: "auto",
            separateDialCode: true,
            geoIpLookup: callback => {
                fetch("https://ipapi.co/json")
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("us"));
            },
        });
        input.addEventListener("countrychange", function() {
            let data = iti.getSelectedCountryData()
            $('input[name="dial_code"]').val(data.dialCode)
            $('input[name="country_code"]').val(data.iso2)
            $('input[name="country_name"]').val(data.name)
        });
    })
</script>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/components/form/phone.blade.php ENDPATH**/ ?>