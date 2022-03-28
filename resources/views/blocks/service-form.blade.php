<div class="{{ $block->classes }}">
  <div class="max-w-5xl mx-auto px-6">

    <div class="py-6 sm:py-10">
      <h2 class="text-center font-bold text mb-4 sm:text-xl md:mb-6">{!! $title !!}</h2>

      <div class="grid grid-cols-1 gap-2 service-type-container sm:grid-cols-2 md:gap-3 max-w-sm mx-auto sm:max-w-none lg:gap-4">
        @foreach($service as $item)
          <button class="service-type {{ $loop->first ? 'active' : '' }}" data-type="{!! $item['name'] !!}">
            @svg($item['icon'], 'h-8 w-8 mr-2 lg:h-10 lg:w-10')
            <span>{!! $item['name'] !!}</span>
          </button>
        @endforeach
      </div>

      <div class="service-content-container flex justify-center py-6">
        @foreach($service as $item)
          <div class="prose max-w-lg service-item {{ $loop->first ? 'active' : '' }}" data-type="{!! $item['name'] !!}">
              {!! $item['content'] !!}
          </div>
        @endforeach
      </div>

      <div class="service-form">
        @include('partials.form', ['form' => $form])
      </div>
    </div>

  </div>
</div>
