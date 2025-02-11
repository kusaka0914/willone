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
          <h1></h1>
        </section>
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">解答速報CSV変換</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" method="post" action="{{ route('answerNo') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputFile">CSVファイルを選択 (回答番号JSON)</label>
                  <input type="file" name="csv_file" id="csv_file">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="回答番号JSONをダウンロード">
                </div>
              </form>
              <form role="form" method="post" action="{{ route('answerAll') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputFile">CSVファイルを選択 (完全JSON)</label>
                  <input type="file" name="csv_file" id="csv_file">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="完全JSONをダウンロード">
                </div>
              </form>
              <br>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div>

	@include('admin.script')
</body>
@endsection
