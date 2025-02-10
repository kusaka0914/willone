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
      <!-- Content Header (Page header) -->
    <form method="post">
      {{ csrf_field() }}
    <section class="content-header">
      <h1>
        就職活動ノウハウブログ一覧
      </h1>
      <a href="{{ route('SyusyokuBlogNew') }}">新規作成</a>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        @if( isset($message))
        <div class="pad margin no-print">
          <div class="callout callout-info" style="margin-bottom: 0!important;">
            {{ $message }}
          </div>
        </div>
        @endif
        <div class="col-xs-12">
          <div class="box">

            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>公開/下書き</th>
                  <th>タイトル</th>
                  <th>投稿日付</th>
                  <th>アイキャッチ画像</th>
                  <th>カテゴリ</th>
                  <th>編集</th>
                </tr>
                </thead>
                <tbody>
@if ($blog)
                  @foreach( $blog as $value)

                  @if( $value->open_flg == 1)
                  <tr bgcolor="#FFFFFF">
                  <td width="100">公開</td>
                  @else
                  <tr bgcolor="#b0c4de">
                  <td>下書き</td>
                  @endif
                  <td width="600">{{ $value->title }}</td>
                  <td>{{ $value->post_date }}</td>

                  <td><img src="{{ getS3ImageUrl(config('const.blog_image_path') . $value->list_image) }}" width="100" height="100"></td>
                  <?php $flg = 0;?>
                  @foreach( $category as $cate_value)
                    @if( $cate_value->key_value == $value->category_id)
                      <td>{{ $cate_value->value1}}</td>
                      <?php $flg = 1;?>
                      @break
                    @endif
                  @endforeach
                  @if( $flg == 0)
                  <td></td>
                  @endif
                  <td><a href="{{ route('SyusyokuBlogDetail' , ['id' => $value->id]) }}">編集</a>
                    <a href="javascript:void(0);" onclick="var ok=confirm('削除してもよろしいですか？');
if (ok) location.href='{{ route('SyusyokuBlogDel' , ['id' => $value->id]) }}'; return false;">削除</a>
                </tr>
                  @endforeach
@endif

                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
      <!-- /.row -->
    </form>
　</div>


	@include('admin.script')
</body>
@endsection
