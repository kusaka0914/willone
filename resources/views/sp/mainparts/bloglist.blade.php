<h2 class="c-title-l-bar">ブログ</h2>
                <ul class="c-column">
                    @foreach($blog_list as $blog_value)
                    <li class="c-column-item">
                        <a class="c-column-link" href="{{ route('BlogDetail' , ['id' => $blog_value->id])}}">
                            <div class="c-column-img-wrap">
                                <div class="c-column-img" style="background-image:url({{ getS3ImageUrl(config('const.blog_image_path') . $blog_value->list_image)}})"></div>
                            </div>
                            <div class="c-column-detail">
                                <div class="c-column-date">{{ $blog_value->post_date}}</div>
                                <div class="c-column-title">{{ mb_strimwidth($blog_value->title ,0,50,'...')}}</div>
                            </div>
                        </a>
                    </li>
                    @endforeach

                </ul>
                <p class="c-text-link-wrap"><a href="{{ route('BlogDetail' , ['id' => 1])}}" class="c-text-link">ブログ一覧へ</a></p>
