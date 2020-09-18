<?php

namespace App\Http\Controllers\Api\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{

    use IssueTokenTrait;

	private $client;


	public function __construct()
    {
        $this->client = Client::find('918acd20-e51d-4477-b7b5-4821bd301e58');
	}


    public function login(Request $request)
    {

    	$this->validate($request, [
    		'username' => 'required|string|max:255',
            'password' => 'required|string|min:6'
    	]);

        $user = User::where('email', $request->username)->first();
        $user['active'] = 1;
        $user['delated_at'] = null;

        return $this->issueToken($request, 'password');

    }


    public function refresh(Request $request)
    {
    	$this->validate($request, [
    		'refresh_token' => 'required'
    	]);

    	return $this->issueToken($request, 'refresh_token');

    }


    public function logout(Request $request)
    {

    	$accessToken = Auth::user()->token;

    	DB::table('oauth_refresh_tokens')
    		->where('access_token_id', $accessToken->id)
    		->update(['revoked' => true]);

    	$accessToken->revoke();

    	return response()->json([], 204);

    }

}
