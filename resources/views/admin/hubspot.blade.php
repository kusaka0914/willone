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
          </div>
        </div>
        @endif

      </div>
      <!-- /.row -->
</div>


	@include('admin.script')
</body>
@endsection
