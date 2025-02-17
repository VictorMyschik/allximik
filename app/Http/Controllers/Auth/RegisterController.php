<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailInvite;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, User $user): RedirectResponse
    {
        $token = $request->get('token');

        $permissions = json_encode([
            'platform.index'              => true,
            'platform.systems.roles'      => true,
            'platform.systems.users'      => true,
            'platform.systems.attachment' => true,
        ]);

        DB::table('users')->where('id', $user->id)->update(['permissions' => $permissions]);

        if (!$token) {
            return redirect($this->redirectPath());
        }

        $email = $request->get('email');

        /** @var EmailInvite $emailInvite */
        $emailInvite = EmailInvite::where('token', $token)->first();

        if ($emailInvite) {
            // Если пользователь поменял mail при регистрации - обновляем его в приглашении
            if ($emailInvite->getEmail() !== $email) {
                $emailInvite->setEmail($email);
                $emailInvite->save_mr();
            }

            // Редиректим на страницу с приглашением
            return redirect()->route('travel.email.invite.link', ['token' => $emailInvite->getToken(), 'status' => 'true']);
        }

        // Если приглашение не найдено - редиректим на главную
        return redirect($this->redirectPath());
    }
}
