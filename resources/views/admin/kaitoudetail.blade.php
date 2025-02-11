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

            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
              </ul>
            </div>
            @endif

            <div class="box-header with-border">
              <h3 class="box-title">解答速報ページ詳細</h3>
              <a href="{{ route('AdminKaitouNew') }}">新規作成</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
@if($kaitou)
              <form role="form" method="post" action="{{ route('AdminKaitouUpdate') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $kaitou->id }}">
                <div class="form-group">
                  <label>タイトル</label>
                  <input name="title" type="text" class="form-control" placeholder="タイトル" value="@if($errors->any()){{ old('title') }}@else{{ $kaitou->title }}@endif" style="width:400px;"/>
                </div>
                <div class="form-group">
                  <label>試験日</label>
                  <input name="shiken_date" type="date" class="form-control" placeholder="試験日" value="@if($errors->any()){{ old('shiken_date') }}@else{{ $kaitou->shiken_date }}@endif" style="width:200px;"/>
                </div>
                <div class="form-group">
                  <label>解答イメージ1</label>
                  <input name="kaitou_image1" type="text" class="form-control" placeholder="解答イメージ1" value="@if($errors->any()){{ old('kaitou_image1') }}@else{{ $kaitou->kaitou_image1 }}@endif" style="width:200px;"/>
                </div>
                <div class="form-group">
                  <label>解答イメージ2</label>
                  <input name="kaitou_image2" type="text" class="form-control" placeholder="解答イメージ2" value="@if($errors->any()){{ old('kaitou_image2') }}@else{{ $kaitou->kaitou_image2 }}@endif" style="width:200px;"/>
                </div>
                <div class="form-group">
                  <label>解答イメージ3</label>
                  <input name="kaitou_image3" type="text" class="form-control" placeholder="解答イメージ3" value="@if($errors->any()){{ old('kaitou_image3') }}@else{{ $kaitou->kaitou_image3 }}@endif" style="width:200px;"/>
                </div>
                <div class="form-group">
                  <label>解答URL</label>
                  <input name="kaitouurl" type="text" class="form-control" placeholder="解答URL" value="@if($errors->any()){{ old('kaitouurl') }}@else{{ $kaitou->kaitouurl }}@endif" style="width:200px;"/>
                </div>
                <div class="box-footer">
                  <input type="submit" class="btn btn-primary" value="保存">
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
</body>
@endsection
