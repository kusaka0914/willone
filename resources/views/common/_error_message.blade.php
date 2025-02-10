<div class="error_message">
            @if($errors->has('license')) <div>{{$errors->first('license')}}</div> @endif
            @if($errors->has('graduation_year')) <div>{{$errors->first('graduation_year')}}</div> @endif
            @if($errors->has('req_emp_type')) <div>{{$errors->first('req_emp_type')}}</div> @endif
            @if($errors->has('req_date')) <div>{{$errors->first('req_date')}}</div> @endif
            @if($errors->has('addr1')) <div>{{$errors->first('addr1')}}</div> @endif
            @if($errors->has('addr2')) <div>{{$errors->first('addr2')}}</div> @endif
            @if($errors->has('addr3')) <div>{{$errors->first('addr3')}}</div> @endif
            @if($errors->has('name_kan')) <div>{{$errors->first('name_kan')}}</div> @endif
            @if($errors->has('name_cana')) <div>{{$errors->first('name_cana')}}</div> @endif
            @if($errors->has('introduce_name')) <div>{{$errors->first('introduce_name')}}</div> @endif
            @if($errors->has('birth_year')) <div>{{$errors->first('birth_year')}}</div> @endif
            @if($errors->has('retirement_intention')) <div>{{$errors->first('retirement_intention')}}</div> @endif
            @if($errors->has('mob_mail')) <div>{{$errors->first('mob_mail')}}</div> @endif
            @if($errors->has('mob_phone')) <div>{{$errors->first('mob_phone')}}</div> @endif
            @if($errors->has('entry_category_manual')) <div>{{$errors->first('entry_category_manual')}}</div> @endif
</div>
