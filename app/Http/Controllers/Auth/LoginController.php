<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\APIController;
use Socialite;
class LoginController extends APIController
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->responseUnauthorized();
        }

        // Get the user data.
        $user = auth()->user();

        return response()->json([
            'status' => 200,
            'message' => 'Authorized.',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => array(
                'id' => $user->hashid,
                'name' => $user->name
            )
        ], 200);
    }

    public function socialLogin($social)
    {
        
        if ($social == "facebook" || $social == "google") {
            
            return Socialite::driver($social)->stateless()->redirect();
        } else {
            dd("else condtion");
            return Socialite::driver($social)->redirect();           
        }
    }

    public function handleProviderCallback($social)
    {
        if ($social == "facebook" || $social == "google") {
            $userSocial = Socialite::driver($social)->stateless()->user();
        } else {
            $userSocial = Socialite::driver($social)->user();
        }
        $token = $userSocial->token;
        $user = User::firstOrNew(['email' => $userSocial->getEmail()]);

        if (!$user->id) {
            $user->fill(["name" => $userSocial->getName(),"password"=>bcrypt(str_random(6))]);
            $user->save();
        }

        return response()->json([
            'user'  => [$user],
            'userSocial'  => $userSocial,
            'token' => $token,
        ],200);
    }

}
