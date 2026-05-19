<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $accessCode = config('app.registration_access_code');

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'website' => ['nullable', 'size:0'],
            'form_started_at' => ['required', 'integer'],
            'access_code' => [$accessCode ? 'required' : 'nullable', 'string', 'max:80'],
        ]);

        $validator->after(function ($validator) use ($request, $accessCode) {
            if ($request->integer('form_started_at') > now()->subSeconds(3)->timestamp) {
                $validator->errors()->add('email', 'Merci de reprendre le formulaire calmement.');
            }

            if ($accessCode && ! hash_equals((string) $accessCode, (string) $request->input('access_code'))) {
                $validator->errors()->add('access_code', 'Le code d’accès est incorrect.');
            }
        });

        $validator->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
