{{--
  Template Name: Contact Template
  Redesign după mockup `preferinte/Pagina Contact.html`.
  Chrome-ul paginii (CSS) e scoped sub `.contact-page` (vezi resources/css/contact.css,
  livrat prin contact-bundle.css din App\page_bundles()). Formularul în sine (markup +
  JS + AJAX + email + stocare) e furnizat de plugin-ul `mn-contact-form` prin
  shortcode-ul [natura_contact_form] (vezi partials/contact/form.blade.php).
--}}

@extends('layouts.app')

@section('content')
  <div class="contact-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Contact', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.contact.hero')
    @include('partials.contact.channels')
    @include('partials.contact.self-serve')
    @include('partials.contact.form')
    @include('partials.contact.sediu')
    @include('partials.contact.program')
    @include('partials.contact.social')
  </div>
@endsection
