@extends('layouts.app')

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">
<div class="jumbotron col-md-12">
	<div class="conteiner">
		<div class="col-md-6 col-md-offset-3 login-form">
			<form action="/login" method="post">
				<div class="form-group">
    				<label for="exampleInputEmail1">Email address</label>
    				<input type="email" class="form-control" id="InputEmail1" placeholder="Enter email">
  				</div>
  				<div class="form-group">
    				<label for="exampleInputEmail1">Password</label>
    				<input type="password" class="form-control" id="InputPassword" placeholder="Enter Password">
  				</div>
  				<button type="submit" class="btn btn-primary">ログイン</button>
			</form>
		</div>
	</div>
</div>
@endsection