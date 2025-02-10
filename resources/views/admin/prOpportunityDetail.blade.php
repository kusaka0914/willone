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
          <div class="callout callout-{{ $class }}" style="margin-bottom: 0!important;">
            {{ $message }}
          </div>
        </div>
        @endif
        <div class="col-md-12">
          <!-- Horizontal Form -->

          <!-- general form elements disabled -->
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">注目枠求人詳細</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @if ($pr_opportunity)
              <form role="form" method="post" action="{{ route('PrOpportunityUpdate') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $pr_opportunity->id }}">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="保存">
                    </div>
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>id</th>
                      <th>オーダーID</th>
                      <th>コメディカルオーダーID</th>
                      <th>sf_account_id</th>
                      <th>事業所名</th>
                      <th>会社名</th>
                      <th>注目枠表示位置</th>
                    </tr>
                    </thead>
                    <tbody>
                      <td>{{ $pr_opportunity->id }}</td>
                      <td>{{ $pr_opportunity->job_id }}</td>
                      <td>{{ $pr_opportunity->sf_order_id }}</td>
                      <td>{{ $pr_opportunity->sf_account_id }}</td>
                      <td>{{ $pr_opportunity->office_name }}</td>
                      <td>{{ $pr_opportunity->company_name }}</td>
                      <td>
                        <select name="pr_display_position">
                          <option value="1" @if($pr_opportunity->pr_display_position == 1) selected @endif>1</option>
                          <option value="2" @if($pr_opportunity->pr_display_position == 2) selected @endif>2</option>
                        </select>
                    </tr>
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
