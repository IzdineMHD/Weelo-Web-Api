<?php

namespace App\Http\Controllers\Api\Auth;


use Image;
use App\Models\User;
use App\Models\Post;
use App\Notifications\UserActivation;
use App\Http\Controllers\Controller;
use Laravel\Passport\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use IssueTokenTrait;

	private $client;


	public function __construct()
    {
		$this->client = Client::find('918acd20-e51d-4477-b7b5-4821bd301e58');
	}


    /**
     * Create new user
     *
     * @param  [string] fullname
     * @param  [string] email
     * @param  [string] username
     * @param  [string] phone_number
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     * @return [string] access_token
     * @return [string] token_type
     */
    public function register(Request $request)
    {

    	$this->validate($request, [
    		'fullname' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|string|min:2|max:255|unique:users',
            'phone_number' => 'required|min:8',
            'password' => 'required|string|min:6|confirmed',
    	]);

    	$user = User::create([
    		'fullname' => request('fullname'),
            'email' => request('email'),
            'username' => request('username'),
    		'phone_number' => request('phone_number'),
    		'password' => bcrypt(request('password')),
            'activation_token' => Str::random(60)
    	]);

        $post = Post::create([
            'title' => 'Post Register',
            'body' => 'Post pour Inscription et Enregistrement',
            'user_id' => $user->id
        ]);

        $user->notify(new UserActivation($user));

    	return $this->issueToken($request, 'password');

    }


    /**
     * Add user others infos
     *
     * @param  [string] sexe
     * @param  [string] age
     * @param  [string] function
     * @param  [string] location_address
     * @return [string] message
     */
    public function insert(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'sexe' => 'required|string',
            'age' => 'required|string|min:2',
            'function' => 'required|string|min:3|max:255',
            'location_address' => 'required|string|min:3|max:255'
        ]);


        $inserted_data = DB::table('users')
                            ->where('id', request('user_id'))
                            ->update(['sexe' => request('sexe'),
                                'age' => request('age'),
                                'function' => request('function'),
                                'location_address' => request('location_address')
                            ]);

        $post = Post::create([
            'title' => 'Post Insert Others Infos',
            'body' => 'Post pour la mise à jour des informations supplémentaires du user',
            'user_id' => request('user_id')
        ]);
       
        return response()->json([
            'message' => 'Informations supplémentaires d\'utilisateur ajoutées avec succès!',
            'inserted_data' => $inserted_data
        ]);
    }


    public function activation($token)
    {
        $user = User::where('activation_token', $token)->first();    

        if (!$user) {
            return response()->json([
                'message' => 'Token d\'activation invalide.'
            ], 404);
        }

        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return $user;
    }


    public function user(Request $request)
    {
        return response()->json($request->user());
    }


    /*
     * Store user image avatar
     *
     * @param  [string] user_avatar
     * @return [string] message
    public function store_image(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $image_file = request('avatar');
        $image = Image::make($image_file);
        Response::make($image->encode('png'));

        $user = Auth::user();

        $stored_image = DB::table('users')
                           ->update(['avatar' => $image]);

       return response()->json([
            'message' => 'Image ou Avatar utilisateur mis à jour avec succès!',
            'stored_image' => $stored_image
        ]);
    }
    */


    /*
     * Fetch user image avatar
     *
     * @param  [string] user_id
     * @return [string] message
    public function fetch_image()
    {
        $image = User::findOrFail(1);
        $image_file = Image::make($image->avatar);

        $response = Response::make($image_file->encode('png'));
        $response->header('Content-Type', 'image/png');

        return $response;
    }
    */

}
