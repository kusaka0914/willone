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
		<div class="callout callout-danger">
          <h4>Warning!</h4>

          <p>メールアドレスが登録済みか、名前、メールアドレス、パスワードが空白です。　再度ご確認の上登録ください。
        </div>
        <a href="{{ Route('Register')}}" class="text-center">戻る</a>
    	</div>
      </div>
  </section>
@include('admin.script')
</body>
@endsection