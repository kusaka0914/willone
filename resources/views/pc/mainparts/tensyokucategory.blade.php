<h2 class="c-title-l-bar">カテゴリから探す</h2>
                <div class="c-category-sub">
                    <div class="c-category-sub-title"><span class="c-category-sub-link">年代</span></div>
                    <ul class="c-category-sub-list">
                        @foreach( $category as $c_category)
                            @if ( $c_category->value3 == 1)
                            <li class="c-category-sub-item"><a href="{{ route('TensyokuCategory' , ['category' => $c_category->value2 ])}}" class="c-category-sub-link">{{ $c_category->value1 }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="c-category-sub-title"><span class="c-category-sub-link">転職理由</span></div>
                    <ul class="c-category-sub-list">
                        @foreach( $category as $c_category)
                            @if ( $c_category->value3 == 2)
                            <li class="c-category-sub-item"><a href="{{ route('TensyokuCategory' , ['category' => $c_category->value2 ])}}" class="c-category-sub-link">{{ $c_category->value1 }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="c-category-sub-title"><span class="c-category-sub-link">職種</span></div>
                    <ul class="c-category-sub-list">
                        @foreach( $category as $c_category)
                            @if ( $c_category->value3 == 3)
                            <li class="c-category-sub-item"><a href="{{ route('TensyokuCategory' , ['category' => $c_category->value2 ])}}" class="c-category-sub-link">{{ $c_category->value1 }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>