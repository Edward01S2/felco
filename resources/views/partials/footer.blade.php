<footer class="content-info">
  <div class="bg-black text-white">
    <div class="container mx-auto px-6">
      <div class="py-3 lg:flex lg:items-center lg:space-x-4 md:text-sm">
        <div class="text-center mb-2 lg:mb-0">
          <span>&copy; <?php echo esc_attr( date( 'Y' ) ); ?></span>
          {!! $copyright !!}
        </div>
        @if($footer)
        <div class="flex flex-wrap justify-center lg:divide-x lg:divide-white">
          @foreach($footer as $item)
            <a href="{!! $item->url !!}" class="px-2.5 hover:underline lg:px-2">{!! $item->label !!}</a>
          @endforeach
        </div>
        @endif
      </div>
    </div>
  </div>
</footer>