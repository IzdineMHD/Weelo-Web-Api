<?php

namespace App\Http\Controllers\Api\Auth;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\ResetPassword;
use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordRequest;
use App\Notifications\ResetPasswordSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    /**
     * Get all passwords and tokens reset
     *
     */
    public function tokens() 
    {
        $all_tokens = ResetPassword::count();
        $tokens = ResetPassword::all();

        return response()->json([
            'Message' => 'All tokens informations.',
            //'all_tokens' => $all_tokens,
            'tokens' => $tokens
        ]);
    }

    /**
     * Create token password reset
     *
     */
    public function send_reset(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json( [
                'message' => 'Aucun utilisateur n\'existe avec le mail saisi.'
            ], 404);

        $passwordReset = ResetPassword::updateOrCreate( 
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
             ]
        );

        $post = Post::create([
            'title' => 'Post Send Reset Request',
            'body' => 'Post pour envoyer le mail et reçevoir un lien de récupération de password',
            'user_id' => $user->id
        ]);

        if ($user && $passwordReset)
            $user->notify(new ResetPasswordRequest($passwordReset->token));

        return response()->json([
            'user' => $user
        ]);
    }


    /**
     * Find token password reset
     *
     */
    public function find_token($token)
    {

        $passwordReset = ResetPassword::where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json( [
                'message' => 'Token invalide pour le mot de passe.'
            ], 404);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'Token invalide pour le mot de passe.'
            ], 404);
        }

        return response()->json($passwordReset);
    
    }


     /**
     * Reset password
     *
     */
    public function reset_password(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|string|email|min:6|max:255',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $passwordReset = ResetPassword::where('email', $request->email)->first();

        if (!$passwordReset) {
            return response()->json( [
                'message' => 'Lien de récupération invalide.'
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();
        
        if (!$user) {
            return response()->json( [
                'message' => 'Utilisateur avec cet adresse e-mail introuvable.'
            ], 404);
        }

        $post = Post::create([
            'title' => 'Post Reset Request',
            'body' => 'Post pour modifier ou changer le mot de passe de connexion',
            'user_id' => $user->id
        ]);

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new ResetPasswordSuccess($passwordReset));
        return response()->json($user);

    }

}