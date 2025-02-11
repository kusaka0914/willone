@if(is_array($syokusyu_text) && !empty($syokusyu_text))
    <div class="l-contents-under">
        <div class="c-description">
            @foreach($syokusyu_text as $data)
                <div class="c-description-wrap">
                    <p class="c-description-title">{{ $data['title'] }}</p>
                    <p class="c-description-text">{!! nl2br($data['text']) !!}</p>
                </div>
            @endforeach
        </div>
    </div>
@endif
