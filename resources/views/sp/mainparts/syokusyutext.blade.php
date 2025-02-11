@if(is_array($syokusyu_text) && !empty($syokusyu_text))
    <div class="l-contents-under">
        @foreach($syokusyu_text as $data)
            <div class="c-description">
                <h2 class="c-description-title js-toggle-next">{{ $data['title'] }}</h2>
                <p class="c-description-text u-none">{!! nl2br($data['text']) !!}</p>
            </div>
        @endforeach
    </div>
@endif
