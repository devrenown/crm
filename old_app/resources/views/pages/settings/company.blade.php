@extends('pages.settings.index')

@section('page-header-section')

    <!-- Page Header -->
    <x-breadcrumb>
        <x-slot name="title">{{ __('Company Settings') }}</x-slot>
    </x-breadcrumb>
    <!-- /Page Header -->
    
    <style>
    /* Stop CKEditor from resetting height on focus */
    .ck-editor__editable {
        min-height: 150px !important;
        height: auto !important;
        overflow-y: hidden !important;
    }

    /* Prevent wrapper from forcing scroll */
    .ck-editor__main {
        overflow: visible !important;
    }
</style>
@endsection

@section('page-section')
    <form action="{{ route('settings.company.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Company Name') }}</x-form.label>
                    <x-form.input value="{{ $settings->name }}" name="name" />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Contact Person') }}</x-form.label>
                    <x-form.input value="{{ $settings->contact_person }}" name="contact_person" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Address') }}</x-form.label>
                    <x-form.input value="{{ $settings->address }}" name="address" />
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Country') }}</x-form.label>
                    <x-form.input name="country" value="{{ $settings->country }}" />
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('City') }}</x-form.label>
                    <x-form.input value="{{ $settings->city }}" name="city" />
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('State/Province') }}</x-form.label>
                    <x-form.input value="{{ $settings->province }}" name="province" />
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Postal Code') }}</x-form.label>
                    <x-form.input value="{{ $settings->postal_code }}" name="postal_code" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Email') }}</x-form.label>
                    <x-form.input value="{{ $settings->email }}" type="email" name="email" />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Phone Number') }}</x-form.label>
                    <x-form.input value="{{ $settings->phone }}" name="phone" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Mobile Number') }}</x-form.label>
                    <x-form.input value="{{ $settings->mobile }}" name="mobile" />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Fax') }}</x-form.label>
                    <x-form.input value="{{ $settings->fax }}" name="fax" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Website Url') }}</x-form.label>
                    <x-form.input value="{{ $settings->website_url }}" name="website_url" />
                </div>
            </div>

            <div class="col-12">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Company Term & Condition and Privacy Policy') }}</label>
                    
                    <x-form.ckeditor name="term_conditions" id="editor1" placeholder="Describe your company’s employee Terms and Conditions and Privacy Policy. Include workplace rules, employee responsibilities, confidentiality, data protection, and other important policies that employees must agree to during onboarding.">{!! $settings->term_conditions !!}</x-form.ckeditor>
                </div>
            </div>
        </div>

        <div class="col-12">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('About Company') }}</label>
                    
                    <x-form.ckeditor name="about" id="editor2" placeholder="Write a brief introduction about your company. This information will be shown to employees after they complete onboarding on the welcome page. You may include your company’s mission, values, culture, and what employees can expect as part of the organization.">{!! $settings->about !!}</x-form.ckeditor>
                </div>
            </div>
        </div>
        <div class="submit-section">
            <button class="btn btn-primary submit-btn">{{ __('Save') }}</button>
        </div>
    </form>
@endsection

@push('page-scripts')
@vite([
   
    "resources/assets/css/ckeditor.css",
    "resources/js/ckeditor.js"
])



