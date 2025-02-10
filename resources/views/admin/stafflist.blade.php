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
        スタッフ一覧
      </h1>
      <a href="{{ route('AdminStaffNew') }}">新規作成</a>

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
                  <th>名前</th>
                  <th>職種</th>
                  <th>画像</th>

                  <th>キャッチコピー</th>
                  <th>編集・削除</th>
                </tr>
                </thead>
                <tbody>
@if (!empty($staff))
                  @foreach( $staff as $value)
                <tr>

                  <td>{{ $value->name }}</td>
                  @if( $value->type == 1)
                  <td>エージェント</td>
                  @else
                  <td>エージェント<br>（求人担当）</td>
                  @endif
                  <td><img src="{{getS3ImageUrl($value->staff_image_path)}}" width="150"></td>
                  <td>{{ $value->catchcopy }}</td>
                  <td>
                    <a href="{{ route('AdminStaffUpdate' , ['id' => $value->id]) }}">編集</a>
                    <a href="javascript:void(0);" onclick="var ok=confirm('削除してもよろしいですか？');
if (ok) location.href='{{ route('AdminStaffDel' , ['id' => $value->id]) }}'; return false;">削除</a>
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
