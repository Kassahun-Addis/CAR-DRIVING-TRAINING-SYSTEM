<?php

// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
  {
      \Log::info('Form data', $request->all());

      $this->validator($request->all())->validate();

      $user = $this->create($request->all());

      // Optionally, log the user in after registration
      // Auth::login($user);

      return redirect()->route('admin.login')->with('success', 'Registration successful.');
  }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_no' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_no' => $data['phone_no'],
            'password' => Hash::make($data['password']),
        ]);
    }
}