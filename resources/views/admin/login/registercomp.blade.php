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

    <section class="content-wrapper">

      <div class="row">
      	<div class="register-box">
          <p>ユーザ情報が登録されました。</p>
          <a href="{{ route('AdminUser')}}">ユーザ編集へ</a>
        </div>
      </div>
    </section>
@include('admin.script')
</body>
@endsection
