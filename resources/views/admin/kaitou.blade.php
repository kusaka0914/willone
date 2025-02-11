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
    <section class="content-header">
      <h1>
        解答速報ページ一覧
      </h1>
      <a href="{{ route('AdminKaitouNew') }}">新規作成</a>
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
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        <div class="col-xs-12">
          <div class="box">

            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>タイトル</th>
                  <th>試験日</th>
                  <th>解答イメージ1</th>
                  <th>解答イメージ2</th>
                  <th>解答イメージ3</th>
                  <th>解答URL</th>
                  <th>更新日時<th>
                </tr>
                </thead>
                <tbody>
@if($kaitoulist)
                  @foreach($kaitoulist as $value)
                <tr>
                  <td>{{ $value->title }}</td>
                  <td>{{ str_replace('-', '/', $value->shiken_date) }}</td>
                  <td>{{ $value->kaitou_image1 }}</td>
                  <td>{{ $value->kaitou_image2 }}</td>
                  <td>{{ $value->kaitou_image3 }}</td>
                  <td><a href="/woa/kaitousokuhou/{{ $value->kaitouurl }}" target="_blank">{{ $value->kaitouurl }}</a></td>
                  <td>{{ $value->updated_at }} </td>
                  <td>
                    <a href="{{ route('AdminKaitouDetail' , ['id' => $value->id]) }}">編集</a>
                    <a href="javascript:void(0);" onclick="if (confirm('削除してもよろしいですか？')) location.href='{{ route('AdminKaitouDel' , ['id' => $value->id]) }}'; return false;">削除</a>
                  </td>
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
  </div>

  @include('admin.script')
</body>
@endsection
