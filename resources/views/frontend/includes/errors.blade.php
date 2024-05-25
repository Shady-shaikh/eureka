@if(count($errors))
  <div class="form-group">
      <div class="alert alert-danger">
          <ul>
              @foreach($errors->all() as $error)
                  <li>{{$error}}</li>
              @endforeach
          </ul>
      </div>
  </div>
@endif
@if (session()->has('success'))
  <div class="form-group">
    <div class="alert alert-success">
        <h4>{{ session('success') }}</h4>
    </div>
  </div>
@endif
