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

        @if( isset($message))
        <div class="pad margin no-print">
          <div class="callout callout-info" style="margin-bottom: 0!important;">
            {!! $message !!}
          </div>
        </div>
        @endif
        <section class="content-header">
          <h1>黒本リスト</h1>
        </section>
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">黒本リストCSV</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" method="post" action="{{ route('KurohonListCsvUpload') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputFile">黒本リストCSVアップロード</label>
                  <input type="file" name="csv_file" id="JobCsvFile">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="アップロード">
                </div>
              </form>
              <br><br><br>
              <a href="{{ route('KurohonListCsvDownload') }}">黒本リストCSVダウンロード</a><br><br>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
　</div>

	@include('admin.script')
</body>
@endsection