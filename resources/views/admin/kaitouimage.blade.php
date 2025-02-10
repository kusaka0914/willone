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
    @if( isset($message))
      <div class="pad margin no-print">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
          {{ $message }}
        </div>
      </div>
    @endif

    <section class="content-header">
      <h1>
        解答速報画像一覧
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <form role="form" method="post" action="{{ route('KaitouImageUpload') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="exampleInputFile">画像追加</label>
            <input type="file" name="file" id="ImageFile">
        </div>
        <input type="submit" class="btn btn-primary" value="アップロード">
      </form>
      <div class="row">

        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>画像</th>
                  <th>URL</th>
                  <th>更新日時</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($file as $value)
                  <tr>
                    <td>
                      <a href="{{getS3ImageUrl($value['filename'], '/')}}" target="_blank">
                        <img src="{{getS3ImageUrl($value['filename'], '/')}}" width="100" height="100">
                      </a>
                    </td>
                    <td>
                      <a href="{{getS3ImageUrl($value['filename'], '/')}}" target="_blank">
                        {{ $value['filename'] }}
                      </a>
                    </td>
                    <td>{{ $value['updatedate'] }}</td>
                  </tr>
                  @endforeach
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
  </div>

  @include('admin.script')
</body>
@endsection
