
                <ul class="c-teacher-list">
                    @foreach( $list as $value)

                    <li class="c-teacher-list-item">
                        <a class="c-teacher" href="{{ route('KnowhowDetail' , ['id' => $value->id , 'knowhow' => $knowhow_cate ])}}">
                            <div class="c-teacher-img">
                                <img class="bloglist_img" src="{{ getS3ImageUrl(config('const.blog_image_path') . $value->list_image)}}" >
                            </div>
                            <div class="c-teacher-detail">
                                <div class="c-teacher-position bloglist_text">{{ $value->title }}</div>

                            </div>
                        </a>
                    </li>
                    @endforeach

                </ul>
                <p class="c-text-link-wrap"><a href="{{ route('Knowhow')}}" class="c-text-link">ノウハウTOPへ</a></p>
