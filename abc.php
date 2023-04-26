<section class="mt-xl">
<div class="container">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <h6>{{ $error }}</h6>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="col-lg-4" data-sal="slide-up" data-sal-delay="100">

            <div class="appImage inViewJs is-4-5"><img data-src="{{ asset ('/assets/img/bitstreams/'.$img.'.jpg')}}">
            </div>
            <div class="mb-lg d-block d-lg-none"></div>
        </div>
        <div class="col-xl-6 col-lg-5" data-sal="slide-up" data-sal-delay="200">
            @foreach ($items as $item)
            <h1 class="mb-lg">
                {{ $item->title }}
            </h1>
            <?php $sub='';?>
            @foreach ($item->items as $link)
            @if ($link->item_key=='dc.subject')
            <?php $sub.=$link->item_value.' - '  ?>
            @continue
            @endif
            @if ($link->item_key=='dc.description.url')
            @php
            $link_value=str_replace('http://:81/','',$link->item_value);
            $url[]=str_replace('page','',$link_value); @endphp
            @continue
            @endif
            @if ($link->item_key=='dc.description.video' || $link->item_key=='dc.description.audio')

            @php $video_url[]=$link->item_value;

            @endphp
            @continue
            @endif
            @if ($link->item_key=='dc.identifier.uri')
            @php $handle_url=$link->item_value;
            $handle_url= strstr($handle_url, 'handle');
            $handle= "https:///bitstream/". $handle_url; @endphp
            @endif
            @if ($link->item_key=='dc.identifier.uri' || $link->item_key=='dc.date.available' ||
            $link->item_key=='dc.sdr.email' || $link->item_key=='dc.sdr.note' || $link->item_key=='dc.date.available' ||
            $link->item_key=='dc.date.accessioned')

            @continue
            @endif
            @if ( $link->item_key=='dc.description.urlname')
            @php $urlname[]=$link->item_value; @endphp
            @continue
            @endif
            <p class="mb-sm">@lang('site.'.$link->item_key):
                @if ($link->item_key === 'dc.type' && preg_match('/[a-zA-Z]/', $link->item_value))
                @lang('site.'.$link->item_value)
                @else
                {{ $link->item_value }}
                @endif
            </p>
            @if ($link->item_key=='dc.description.url')
            <?php $url[]=$link->item_value;  ?>

            @endif



            @endforeach
            @if ($sub!='') @lang('site.subjects'): {{ rtrim($sub,' - ') }}@endif
            @endforeach
            @foreach($audio_files as $audio)
            @unless(in_array($audio->bundleName, ['THUMBNAIL', 'LICENSE']))
            @php
            $name[] = $audio->name;
            $link = $audio->retrieveLink;
            $type[] = $audio->mimeType;
            $video_link = 'https://' . $link;
            $audio_url[] = $video_link;
            @endphp
            @endunless
            @endforeach
            <div class="mt-lg">
                <div class="btn btn-light rounded-pill ml-2 mb-2">@if(Auth::user()->name=='admin')<a href="{{config('app.url') }}{{
                    '/borrowscount/'.$book_id }}">@lang('site.borrowings') {{ $count }}</a> @else
                    @lang('site.borrowings'){{ $count }} @endif </div>
                <div class="btn btn-light rounded-pill ml-2 mb-2"> @lang('site.views') {{ $view_count }}</div>
                <div class="btn btn-gray rounded-pill icon-share mb-2"></div>
            </div>
            <div class="mb-lg d-block d-lg-none"></div>
        </div>

        <div class="col-xl-2 col-lg-3" data-sal="slide-up" data-sal-delay="300">
            @if ( $reserve===0 )
            @lang('site.your-borrow-end')
            {{ $end_date }}
            <form class="d-none" method="POST" id="destroy_{{ $book_id }}" action="{{config('app.url') }}{{
                    '/books/'.$book_id }}">
                @method('DELETE')@csrf
            </form>
            <button type="submit" class="btn btn-danger rounded-pill w-100 d-block mb-md" form="destroy_{{ $book_id }}"
                onclick="return confirm('Are You Sure ?')" data-toggle="tooltip" title="@lang('site.destroy')">
                <span class="fa fa-trash-o">@lang('site.delete-borrow')</span>
            </button>
            @elseif($reserve==='add_reserve' )
            <form id='reserve' action="{{config('app.url') }}{{ '/reserve/'.$book_id }}" method="POST">
                @csrf
                <button class="btn btn-secondary rounded-pill w-100 d-block mb-md" href="#"
                    title="@lang('site.reserve')">@lang('site.reserve')</button>
            </form>

            @elseif($reserve===1)
            <a class="btn btn-secondary rounded-pill w-100 d-block mb-md" href="#"
                title="@lang('site.reserved')">@lang('site.reserved')</a>
            </td>

            @elseif($reserve==='add_borrow' )

            <form action="{{config('app.url')}}{{ '/borrow/'.$book_id }}" method="POST">
                @csrf
                <button class="btn btn-secondary rounded-pill w-100 d-block mb-md" href="#"
                    title="@lang('site.borrow')">@lang('site.borrow')</button>
            </form>

            @endif
            @if($fav===0)
            <form action="{{config('app.url') }}{{ '/favourite/'.$book_id }}" method="POST">
                @csrf
                <button class="btn btn-danger rounded-pill w-100 d-block mb-md" title="@lang('site.add-to-favourite')"
                    class="btn btn-primary">@lang('site.add-to-favourite')</button>
            </form>
            @else
            <form class="d-none" method="POST" id="destroy_{{ $book_id }}" action="{{config('app.url') }}{{
                   '/favourite/'.$book_id }}">
                @method('DELETE')@csrf
            </form>
            <button type="submit" class="btn btn-danger rounded-pill w-100 d-block mb-md" form="destroy_{{ $book_id }}"
                onclick="return confirm('Are You Sure ?')" data-toggle="tooltip" title="@lang('site.destroy')">
                <span class="fa fa-trash-o">@lang('site.delete-favourite')</span>
            </button>
            @endif
        </div>
    </div>
    </div>
</section>
@if ( $reserve===0 )
<section class="mt-xl" data-sal="slide-up" data-sal-delay="400">
    <div class="container">
        <div class="appBox">
            <script type="text/javascript" src="{{ asset('/assets/js/jquery-3.5.1.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('/assets/js/jquery-ui.js') }}"></script>


            <script src="https://vjs.zencdn.net/7.20.1/video.min.js"></script>

            <link href="{{ asset('/assets/js/jquery.fancybox.min.css') }}" rel="stylesheet">
            <script src="{{ asset('/assets/js/jquery.fancybox.min.js') }}"></script>

            <h3 class="mb-md">@lang('site.Files in this item')</h3>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>@lang('site.file')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($url))
                    @for ($i = 0; $i < count($url); $i++) <tr>
                        <td data-title="@lang('site.file'): "><span class="mb-xs">@if(!empty($urlname))</span>@endif
                            <div class="badge badge-pill badge-success rounded-pill mr-1 ml-1">@if(!empty($urlname)){{
                                $urlname[$i] }}@else @lang('site.file')@endif</div>
                        </td>
                        @php
                        $url[$i]=rtrim($url[$i], '/');
                        $url[$i]=strstr($url[$i], 'preview');
                        $url[$i] = str_replace('preview','//sdr.shjlib.gov.ae//elending/preview',$url[$i]);

                        @endphp
                        <td><a class="btn btn-primary w-100 d-block mb-sm rounded-pill" href="{{ $url[$i] }}"
                                title="@lang('site.browse-file')">@lang('site.browse-file')</a></td>
                        </tr>
                        @endfor
                        @endif
                        @if(!empty($audio_url))
                        @for ($i = 0; $i < count($audio_url); $i++) <tr>
                            <td data-title="@lang('site.file'): "><span class="mb-xs">
                                </span>
                                <div class="badge badge-pill badge-success rounded-pill mr-1 ml-1">
                                    @if(!empty($name)){{
                                    $name[$i] }}@else @lang('site.file')@endif</div>
                            </td>
                            <td>

                                @if( str_contains($type[$i], 'image'))
                                <a class="btn btn-primary w-100 d-block mb-sm rounded-pill" data-type="iframe"
                                    href="javascript:;" data-fancybox="data-fancybox"
                                    data-src="{{ $handle}}/{{ $name[$i] }}" src='{{ $handle}}/{{ $name[$i] }}'
                                    title="@lang('site.browse-file')">@lang('site.play-file')</a>
                                @elseif( $type[$i]=='application/octet-stream')

                                <a class="btn btn-primary w-100 d-block mb-sm rounded-pill" href="#myAudio{{ $i }}"
                                    data-fancybox="" title="@lang('site.browse-file')">@lang('site.play-file')</a>
                                <video controls id="myAudio{{ $i }}" style="display:none;">
                                    <source src="{{ $handle}}/{{ $name[$i] }}">
                                </video>
                                @elseif( str_contains($type[$i], 'application/pdf'))

                                @php
                                $handle_url = explode('/', $handle_url);
                                $handle = $handle_url[0].'='.$handle_url[2];
                                $handle="https:///viewer/?".$handle
                                @endphp

                                <a class="btn btn-primary w-100 d-block mb-sm rounded-pill" data-type="iframe"
                                    href="{{ $handle}}" data-fancybox="data-fancybox"
                                    title="@lang('site.browse-file')">@lang('site.play-file')</a>
                                @endif
                            </td>
                            </tr>
                            @endfor
                            @endif

                            @if(!empty($video_url))

                            @for ($i = 0; $i < count($video_url); $i++) <tr>
                                @php $video_url[$i] = preg_replace('/"/i','',$video_url[$i]);
                                @endphp
                                <script>
                                    $.ajax({
                                url: "{{ $video_url[$i]}}?tit=1" ,
                                complete: function(data) {
//console.log(data.responseText);

  $('#pgtitlea{{$i}}' ).html(data.responseText);
}
});
                                </script>
                                <td data-title="@lang('site.file'): "><span class="mb-xs">
                                        @php
                                        if(!empty($urlname)){
                                        $urlname[$i];
                                        }

                                        @endphp</span>
                                    <div id="pgtitlea{{ $i }}"
                                        class="badge badge-pill badge-success rounded-pill mr-1 ml-1">
                                        "@lang('site.limited
                                        access')"</div>
                                </td>
                                <td><a class="btn btn-primary w-100 d-block mb-sm rounded-pill" data-type="iframe"
                                        href="javascript:;" data-fancybox="data-fancybox"
                                        data-src="{{ $video_url[$i] }}" src='{{ $video_url[$i] }}'
                                        title="@lang('site.browse-file')">@lang('site.play-file')</a></td>
                                </tr>
                                @endfor
                                @endif
                </tbody>
            </table>
        </div>
    </div>

</section>
@endif
