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

        @if (isset($message))
        <div class="pad margin no-print">
          <div class="callout callout-{{ $class }}" style="margin-bottom: 0!important;">
            {!! $message !!}
            @if (!empty($errors))
                @foreach ($errors as $error)
                    <br>{{ $error }}
                @endforeach
            @endif
          </div>
        </div>
        @endif
        <section class="content-header">
          <h1>注目枠求人</h1>
        </section>
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">注目枠求人登録</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" method="post" action="{{ route('PrOpportunityListCsvUpload') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputFile">注目枠求人CSVアップロード</label>
                  <input type="file" name="csv_file" id="JobCsvFile">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="アップロード">
                </div>
              </form>
              <form role="form" method="post" action="{{ route('PrOpportunityUpdate') }}">
                {{ csrf_field() }}
                <input type="hidden" name="add_flag" value="1">
                コメディカルオーダーID:<input type="text" name="sf_order_id">
                注目枠表示位置:<select name="pr_display_position">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="登録">
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">

              <!-- /.box-header -->
              <div class="box-body">
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
                    <th>編集</th>
                  </tr>
                  </thead>
                  <tbody>
    @if ($pr_opportunity)
                    @foreach( $pr_opportunity as $value)
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->job_id }}</td>
                    <td>{{ $value->sf_order_id }}</td>
                    <td>{{ $value->sf_account_id }}</td>
                    <td>{{ $value->office_name }}</td>
                    <td>{{ $value->company_name }}</td>
                    <td>{{ $value->pr_display_position }}</td>
                    <td><a href="{{ route('PrOpportunityDetail' , ['id' => $value->id]) }}">編集</a>
                      <a href="javascript:void(0);" onclick="var ok=confirm('削除してもよろしいですか？');
    if (ok) location.href='{{ route('PrOpportunityDelete' , ['id' => $value->id]) }}'; return false;">削除</a>
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
 </div>
	@include('admin.script')
</body>
@endsection
