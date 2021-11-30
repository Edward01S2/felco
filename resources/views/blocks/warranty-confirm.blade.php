<div class="{{ $block->classes }}">
  <div class="max-w-5xl mx-auto px-6">
    <div class="py-6 sm:py-10">

      <div>
        <div class="text-center text-lg mb-4 md:mb-6 md:text-xl lg:mb-10">You have selected: <span class="font-bold">{!! $part !!}</span></div>

        <div class="flex flex-col space-y-6 md:flex-row md:space-y-0 md:space-x-6 lg:space-x-12">

          @if($items)
            @foreach($items as $item)

              @if($item['name'] == $part)
                <div class="md:w-1/2">
                  @if($item['image'])
                    {!! wp_get_attachment_image($item['image']['id'], 'large') !!}
                  @endif
                </div>
        
                <div class="md:w-1/2">
                  <div>
                    @svg('images.feedback', 'h-10 w-10 mb-2')
                  </div>
                  @if($warranty == 'Yes')
                    <div class="prose max-w-none lg:prose-lg">
                      {!! $item['content'] !!}
                    </div>
                    @if($label)
                      <div class="pt-6">
                        <a href="{!! $label !!}" class="flex items-center justify-center bg-c-red text-white py-3 uppercase font-bold md:py-4" target="_blank">
                          @svg('images.printer', 'h-5 w-5 mr-2')
                          <span>Generate Shipping Label</span>
                        </a>
                      </div>
                    @endif

                  @else
                    <div class="prose max-w-none lg:prose-lg">
                      {!! $nowarranty!!}
                    </div>
                    <div class="nowarranty-form pt-6">
                      @include('partials.form', ['form' => $form])
                    </div>
                    <div class="prose max-w-none lg:prose-lg pt-6">
                      @php
                        $token = $_GET['ep_token'];    
                      @endphp
                      <p>If your tool is no longer under warranty, you may submit your tool for <a href="/repair/?ep_token={!! $token !!}">repair</a></p>
                    </div>
                  @endif
                </div>
              @endif

            @endforeach
          @endif
  
        </div>
        
        <div class="text-center mt-10 md:mt-12">
          <a href="/warranty" class="inline-flex items-center py-2 px-6 font-bold text-c-red border border-c-red hover:bg-c-red hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            <span>GO BACK</span>
          </a>
        </div>

      </div>

    </div>
  </div>
</div>