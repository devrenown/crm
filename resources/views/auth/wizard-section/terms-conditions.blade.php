<h3>Terms & Conditions</h3>
<section style="overflow-y: scroll; overflow-x: hidden; font-size: 14px;">

    <h4 class="text-center fw-bold fs-3 mb-3">Terms and Conditions</h4>

    {!! $companySettings->term_conditions !!}

    <div class="mt-3 d-flex align-items-center">
        <label for="acceptTerms">I agree with the Terms and Conditions.</label>
        <input id="acceptTerms" name="acceptTerms" type="checkbox" class="ms-1 form-check required necessary" {{ $user->is_term_accepted == 1 ? 'checked' : '' }}>
    </div>
</section>