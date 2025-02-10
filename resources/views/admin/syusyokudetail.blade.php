@extends('admin.head')

@section('content')
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include('admin.mainhead')
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    @include('admin.sidebar')
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
      <div class="row">
        <!-- left column -->
        @if( isset($message))
        <div class="pad margin no-print">
          <div class="callout callout-info" style="margin-bottom: 0!important;">
            {{ $message }}
          </div>
        </div>
        @endif
        <div class="col-md-12">
          <!-- Horizontal Form -->

          <!-- general form elements disabled -->
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">就職活動ノウハウブログ詳細</h3>
              <a href="{{ route('SyusyokuBlogNew') }}">新規作成</a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
@if ($blog)
              <form role="form" method="post" action="{{ route('SyusyokuBlogUpdate') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $blog->id }}">
                <input type="hidden" name="blog_image_check" value="{{ $blog->list_image }}">
                <div class="form-group">
                <input type="submit" class="btn btn-primary" value="保存">
                </div>
                <!-- text input -->
                <div class="form-group">
                  @if( $blog->open_flg == 1)
                  <div class="radio">
                    <label>
                      <input type="radio" name="open_flg" id="optionsRadios1" value="1" checked="">
                      公開

                    </label><br>
                    <label><input type="radio" name="open_flg" id="optionsRadios2" value="0" >
                      下書き(非公開)
                    </label>
                  </div>
                  @else
                  <div class="radio">
                    <label>
                      <input type="radio" name="open_flg" id="optionsRadios1" value="1" >
                      公開

                    </label><br>
                    <label><input type="radio" name="open_flg" id="optionsRadios2" value="0" checked="">
                      下書き(非公開)
                    </label>
                  </div>
                  @endif
                </div>

                <div class="form-group">
                  <label>タイトル</label>
                  <input name="title" type="text" class="form-control" placeholder="タイトル" value="{{ $blog->title }}">
                </div>

                <div class="form-group">
                  <label for="exampleInputFile">アイキャッチ画像</label>
                  <input type="file" name="file" id="exampleInputFile">

                  <p class="help-block"></p>
                </div>
                <div class="form-group">
                  <label>カテゴリー</label>
                  <select name="category_id" class="form-control">
                    @foreach($category as $value)
                      @if( $value->key_value == $blog->category_id)
                      <option value="{{ $value->key_value }}" selected>
                      {{$value->value1}}</option>
                      @else
                      <option value="{{ $value->key_value }}" >
                      {{$value->value1}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">ブログ内容
                <small>就職活動ノウハウブログ　ブログ内容</small>
              </h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body pad">

                    <textarea id="editor1" name="editor1" rows="10" cols="80">
                                            {{ $blog->post_data_img_replace}}
                    </textarea>

            </div>
          </div>

              </form>
@endif
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
       </div>
　</div>


	@include('admin.script')
  <script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1' ,{height:500 ,image_previewText:'プレビュー' ,language:'ja'})

  })
</script>
</body>
@endsection
