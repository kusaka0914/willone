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
            <div class="box-header with-border">
              <h3 class="box-title">スタッフ詳細</h3>
              <a href="{{ route('AdminStaffNew') }}">新規作成</a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
@if (!empty($staff))
              <form role="form" method="post" action="{{ route('AdminStaffPost') }}" enctype="multipart/form-data" id="form">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $staff->id }}">
                <input type="hidden" name="h_image_path" value="{{ $staff->staff_image_path }}">
                <div class="form-group">
                <input type="submit" class="btn btn-primary" value="保存">
                </div>
                <!-- text input -->
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input type="radio" name="type" id="optionsRadios1" value="1" @if(empty($staff->type) || $staff->type == 1)checked="checked"@endif>
                            エージェント
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input type="radio" name="type" id="optionsRadios2" value="2" @if($staff->type == 2)checked="checked"@endif>
                            エージェント（求人担当）
                        </label>
                    </div>
                </div>
                <div class="form-group">
                  <label>名前</label>
                  <input name="name" type="text" class="form-control" placeholder="名前" value="{{ $staff->name }}">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">スタッフ</label>
                  <input type="file" name="file" >

                  <p class="help-block"></p>
                </div>
                <div class="form-group">
                  <label>キャッチコピー</label>
                  <input name="catchcopy" type="text" class="form-control" placeholder="キャッチコピー" value="{{ $staff->catchcopy }}">
                </div>
                <div class="form-group">
                  <label>出身地</label>
                  <input name="from_place" type="text" class="form-control" placeholder="出身地" value="{{ $staff->from_place }}">
                </div>
                <div class="form-group">
                  <label>座右の銘</label>
                  <input name="zayuu" type="text" class="form-control" placeholder="座右の銘" value="{{ $staff->zayuu }}">
                </div>
                <div class="form-group">
                  <label>尊敬する人</label>
                  <input name="sonkei" type="text" class="form-control" placeholder="尊敬する人" value="{{ $staff->sonkei }}">
                </div>

                <div class="form-group">
                  <label>説明</label>
                  <input name="caption" type="text" class="form-control" placeholder="説明" value="{{ $staff->caption }}">
                </div>
                <div class="form-group">
                  <label>キャリアカウンセリングで心掛けている事</label>
                  <textarea name="question1" class="form-control" rows="5" placeholder="キャリアカウンセリングで心掛けている事">{{ $staff->question1 }}</textarea>

                </div>
                <div class="form-group">
                  <label>仕事で一番嬉しかった事</label>
                  <textarea name="question2" class="form-control" rows="5" placeholder="仕事で一番嬉しかった事">{{ $staff->question2 }}</textarea>

                </div>
                <div class="form-group">
                  <label>就職・転職を考えている方へメッセージ</label>
                  <textarea name="question3" class="form-control" rows="5" placeholder="就職・転職を考えている方へメッセージ">{{ $staff->question3 }}</textarea>

                </div>
                @if(!empty($staff->id))
                <!--### 事例 ###-->
                <h2 style="font-weight: bold;">成功事例</h2>
                @if(($staffExamples ?? collect())->isNotEmpty())
                @foreach($staffExamples as $staffExample)
                <p style="display: flex;justify-content: space-between;">
                    <a href="{{ route('staff.example.select', ['exampleId' => $staffExample->id]) }}" style="margin-left: 0;">{{$staffExample->catchphrase}}</a><a href="{{ route('staff.example.delete', ['exampleId' => $staffExample->id]) }}" style="margin-right: 0;" onclick="return confirm('事例を削除しますか？');">削除</a>
                </p>
                @endforeach
                @endif
                @if(!empty($exampleUpdateData))
                <button type="button" onclick="window.location.reload();" style="margin-bottom: 20px;">新規登録モード</button>
                @endif
                <p>※並び順は regist_date asc になっているので並び順を変更したい場合は直接テーブルを更新してください。</p>
                <div class="form-group">
                  <label>分類</label>
                  <div>
                  @foreach(config('ini.EXAMPLE_TYPE') as $key => $label)
                  <label style="margin-left: 10px;cursor: pointer;"><input name="example_type" type="radio" value="{{ $key }}" {{$key === ($exampleUpdateData->example_type ?? '') ? 'checked' : ''}}>{{ $label }}</label>
                  @endforeach
                  </div>
                </div>
                <div class="form-group">
                  <label>年代</label>
                  <input name="age" type="text" class="form-control" value="{{ $exampleUpdateData->age ?? '' }}" maxlength="10">
                </div>
                <div class="form-group">
                  <label>性別</label>
                  <div>
                  @foreach(config('ini.GENDER') as $gender)
                  <label style="margin-left: 10px;cursor: pointer;"><input name="gender" type="radio" value="{{ $gender }}" {{$gender === ($exampleUpdateData->gender ?? '') ? 'checked' : ''}}>{{ $gender }}</label>
                  @endforeach
                  </div>
                </div>
                <div class="form-group">
                  <label>資格</label>
                  <input name="license" type="text" class="form-control" value="{{ $exampleUpdateData->license ?? '' }}" maxlength="255">
                </div>
                <div class="form-group">
                  <label>学年</label>
                  <input name="grade" type="text" class="form-control" value="{{ $exampleUpdateData->grade ?? '' }}" maxlength="255">
                </div>
                <div class="form-group">
                  <label>求職者ペルソナ（キャッチ）</label>
                  <input name="catchphrase" type="text" class="form-control" value="{{ $exampleUpdateData->catchphrase ?? '' }}" maxlength="255">
                </div>
                <div class="form-group">
                  <label>お悩み</label>
                  <textarea name="worry" class="form-control" rows="5" maxlength="500">{{ $exampleUpdateData->worry ?? '' }}</textarea>
                </div>
                <div class="form-group">
                  <label>就活の情報収集</label>
                  <textarea name="research" class="form-control" rows="5" maxlength="500">{{ $exampleUpdateData->research ?? '' }}</textarea>
                </div>
                <div class="form-group">
                  <label>求職者の感想（ありがたかった点）</label>
                  <textarea name="customer_comment" class="form-control" rows="5" maxlength="500">{{ $exampleUpdateData->customer_comment ?? '' }}</textarea>
                </div>
                <div class="form-group">
                  <label>CPからの一言</label>
                  <textarea name="cp_comment" class="form-control" rows="5" maxlength="500">{{ $exampleUpdateData->cp_comment ?? '' }}</textarea>
                </div>
                <button type="button" onclick="doExample(`{{ route('staff.example.upsert', ['id' => $staff->id, 'exampleId' => $exampleUpdateData->id ?? null]) }}`)">成功事例の{{empty($exampleUpdateData) ? '登録' : '更新'}}</button>
                <!--### 事例 ###-->
                @endif
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

       </div>
  </div>


  @include('admin.script')
  <script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1' ,{height:500 ,image_previewText:'プレビュー' ,language:'ja'})

  })
  function doExample(action){
    jQuery('#form').attr('action', action);
    jQuery('#form').submit();
  }
</script>
</body>
@endsection
