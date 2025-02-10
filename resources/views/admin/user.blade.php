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
        Adminユーザ編集
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>NAME</th>
                  <th>email</th>
                  <th>アカウント状態</th>
                  <th>作成日</th>
                  <th>編集</th>
                </tr>
                </thead>
                <tbody>
                  @foreach( $user as $value)
                <tr>
                  <td>{{ $value->name }}</td>
                  <td>{{ $value->email }}</td>
                  @if( $value->del_flg == 1)
                    <td>アカウント停止</td>
                  @else
                    <td>ログイン可能</td>
                  @endif
                  <td>{{ $value->created_at }} </td>
                  <td><a href="{{ route('AdminUserDelete' , ['id' => $value->id]) }}">削除</a>
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
      <!-- /.row -->
　</div>


	@include('admin.script')
</body>
@endsection