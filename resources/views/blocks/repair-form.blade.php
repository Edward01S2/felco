<div class="{{ $block->classes }}">
  <div class="max-w-5xl mx-auto px-6">

    <div class="py-6 sm:py-10">
      <div class="flex flex-col space-y-6 md:flex-row md:space-y-0 md:space-x-6 lg:space-x-12">

        <div class="md:w-1/2">
          @if($image)
            {!! wp_get_attachment_image($image['id'], 'large') !!}
          @endif
        </div>

        <div class="md:w-1/2">
          <div class="repair-form">
            @include('partials.form', ['form' => $form])
          </div>
        </div>

      </div>  
    </div>  

  </div>
</div>
