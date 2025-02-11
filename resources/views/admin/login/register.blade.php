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
        

        <div class="register-box-body">
          <p class="login-box-msg">ユーザ登録</p>

          <form action="{{ Route('RegisterInsert') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group has-feedback">
              <input type="text" class="form-control" name="name" placeholder="Full name">
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <input type="email" name="email" class="form-control" placeholder="Email">
              <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <input type="password" name="password" class="form-control" placeholder="Password">
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            
            <div class="row">
              
              <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">登録</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

          
        </div>
        <!-- /.form-box -->
      </div>
    </div>
      <!-- /.register-box -->
    </section>
    <!-- /.content -->
  

  
  
</div>
<!-- ./wrapper -->

</body>
@endsection


