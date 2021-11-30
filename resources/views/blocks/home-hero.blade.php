<div class="{{ $block->classes }} relative bg-gray-700">
  <img src="{!! $bg['url'] !!}" alt="" class="object-cover absolute w-full h-full object-center z-0">
  <div class="max-w-5xl mx-auto px-6 relative z-10 lg:px-8">
    <div class="pt-24 pb-12 sm:pt-28 sm:pb-16 lg:pt-32">
      <div class="text-center text-white mb-6">
        <h1 class="text-center text-3xl font-bold mb-2 sm:text-4xl lg:text-5xl lg:mb-4">{!! $title !!}</h1>
        <div class="text-lg lg:text-xl">
          {!! $content !!}
        </div>
      </div>
      <div class="grid grid-cols-1 gap-2 service-type-container sm:grid-cols-3 md:gap-3 max-w-sm mx-auto sm:max-w-none lg:gap-4">
        <button class="service-type active" data-type="Service">
          @svg('images.service', 'h-8 w-8 mr-2 lg:h-10 lg:w-10')
          <span>Service</span>
        </button>
        <button class="service-type" data-type="Repair">
          @svg('images.repair', 'h-8 w-8 mr-2 lg:h-10 lg:w-10')
          <span>Repair</span>
        </button>
        <button class="service-type" data-type="Warranty">
          @svg('images.warranty', 'h-8 w-8 mr-2 lg:h-10 lg:w-10')
          <span>Warranty</span>
        </button>
      </div>
    </div>
  </div>
</div>
