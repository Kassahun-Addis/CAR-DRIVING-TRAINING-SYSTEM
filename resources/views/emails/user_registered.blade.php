<p>A new user has registered:</p>
<p>Name: {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p>
<p>Phone No: {{ $user->phone_no }}</p>
<p>Please verify the user to activate their account. Please click the link below to verify the user.</p>
<a href="{{ route('admin.unverifiedUsers') }}">Verify User</a>