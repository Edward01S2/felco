<div class="{{ $block->classes }}">
  <div class="max-w-5xl mx-auto px-6">

    <div class="py-6 sm:py-10">
      <div class="prose max-w-none lg:prose-xl">
        {!! $content !!}
      </div>

      @if($loc)
      <div class="location-container flex flex-col pt-8 space-y-6 sm:flex-row sm:space-y-0 lg:pt-12">

        <div class="flex sm:w-1/2">
          @svg('images.location', 'h-6 w-6 mr-2')
          <div class="text-lg lg:text-xl">
            <div class="font-bold">{!! $loc[0]['title'] !!}</div>
            <div>{!! $loc[0]['loc']['name'] !!}</div>
            <div>{!! $loc[0]['loc']['city'] !!}, {!! $loc[0]['loc']['state_short'] !!} {!! $loc[0]['loc']['post_code'] !!}</div>
          </div>
        </div>

        <div class="sm:w-1/2">
          <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyGbaOm6xcPYYf6B6LZiljhkPcen9akwU"></script>
          <div class="acf-map aspect-w-8 aspect-h-8" data-zoom="16">
            <div class="marker" data-lat="{!! $loc[0]['loc']['lat'] !!}" data-lng="{!! $loc[0]['loc']['lng'] !!}"></div>
        </div>
        </div>
      
      </div>

      @endif

    </div>

  </div>
</div>

{{-- @dump($loc) --}}