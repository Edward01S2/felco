<div class="min-h-screen flex flex-col">

  <a class="sr-only focus:not-sr-only" href="#main">
    {{ __('Skip to content') }}
  </a>

  @include('partials.header')

    <main id="main" class="flex-grow">
      @yield('content')
    </main>

  @include('partials.footer')
</div>
