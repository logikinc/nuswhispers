@extends('admin')

@section('content')
  <div class="page-header">
    <h1 class="page-title"><span class="typcn typcn-heart"></span>Add New User <small><a href="/admin/users/">(Back to Users Listing)</a></small></h1>
  </div>

  @include('message')

  <div class="admin-content-wrapper">

    <?php echo \Form::model($user, ['route' => 'admin.users.add', 'class' => 'user-form form']) ?>

    <div class="form-group {{$errors->first('email') ? 'has-error' : ''}}">
      <label for="email">E-mail Address <span class="text-danger">*</span></label>
      <?php echo \Form::text('email', null, ['class' => 'form-control']) ?>
      @if ($errors->first('email'))
      <p class="alert alert-danger">{{$errors->first('email')}}</p>
      @endif
    </div>

    <div class="form-group {{$errors->first('name') ? 'has-error' : ''}}">
      <label for="name">Display Name <span class="text-danger">*</span></label>
      <?php echo \Form::text('name', null, ['class' => 'form-control']) ?>
      @if ($errors->first('name'))
      <p class="alert alert-danger">{{$errors->first('name')}}</p>
      @endif
    </div>

    <div class="form-group {{$errors->first('role') ? 'has-error' : ''}}">
      <label for="role">Role <span class="text-danger">*</span></label>
      <?php echo \Form::select('role', ['Moderator' => 'Moderator', 'Administrator' => 'Administrator'], null, ['class' => 'form-control']) ?>
      @if ($errors->first('role'))
      <p class="alert alert-danger">{{$errors->first('role')}}</p>
      @endif
    </div>

    <div class="form-group {{$errors->first('password') ? 'has-error' : ''}}">
      <label for="password">Password <span class="text-danger">*</span></label>
      <?php echo \Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off']) ?>
      @if ($errors->first('password'))
      <p class="alert alert-danger">{{$errors->first('password')}}</p>
      @endif
    </div>

    <div class="form-group {{$errors->first('repeat_password') ? 'has-error' : ''}}">
      <label for="repeat_password">Repeat Password <span class="text-danger">*</span></label>
      <?php echo \Form::password('repeat_password', ['class' => 'form-control', 'autocomplete' => 'off']) ?>
      @if ($errors->first('repeat_password'))
      <p class="alert alert-danger">{{$errors->first('repeat_password')}}</p>
      @endif
    </div>

    <p class="form-actions">
    <?php echo \Form::submit('Add User', ['class' => 'btn btn-primary']) ?>
    </p>

    <?php echo \Form::close() ?>

  </div>

@endsection
