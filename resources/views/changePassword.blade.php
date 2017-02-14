@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4>Change Password</h4>
					</div>
					<div class="panel-body">
						<form action="/changePassword" method="POST" class="form">
							{{ csrf_field() }}
							{{ method_field('PATCH') }}

							<div class="form-group">
								<label for="CurrentPassword">Old Password:</label>
								<input type="password" class="form-control" name="CurrentPassword" id="CurrentPassword" value="{{old('password') ?? Auth::user()->password}}" disabled>
							</div>

							<div class="form-group">
								<label for="NewPassword">New Password:</label>
								<input type="password" class="form-control" name="NewPassword" id="NewPassword">
							</div>

							<div class="form-group">
								<label for="ConfirmNewPassword">Confirm New Password:</label>
								<input type="password" class="form-control" name="NewPassword_confirmation" id="ConfirmNewPassword">
							</div>

							<div class="form-group">
								<button class="btn btn-primary">Update Account</button>
							</div>

							@if(count($errors) > 0)
							<div class="alert alert-danger">
								<ul>
									@foreach($errors->all() as $error)
										<li style="list-style: none;">{{ $error }}</li>
									@endforeach
								</ul>
							</div>
							@endif
						</form>
					</div>
				</div>
			</div>	
		</div>
	</div>
@endsection