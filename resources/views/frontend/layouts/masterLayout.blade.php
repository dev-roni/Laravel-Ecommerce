@include('frontend/partials/header')
@include('frontend/partials/navbar')
@include('frontend/partials/flashMessage')
@include('frontend/partials/breadcrumb')
<main class="anim-up">
  @yield('content')
</main>
@include('frontend/partials/feature')
@include('frontend/partials/footer')
