<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\Post;                                 
use App\Models\UsersInterests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersInterestsController extends Controller
{

    public function create_interest(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'interest_name' => 'required'
        ]);

        $user = User::where('id', $request->user_id)->first();

        $userInterest = UsersInterests::create([
            'user_id' => request('user_id'),
            'username' => $user->username,
            'interest_name' => request('interest_name')
        ]);

        $post = Post::create([
            'title' => 'Post Interest',
            'body' => 'Post pour création et attribution d\'intèrêt au user connecté',
            'user_id' => request('user_id')
        ]);

        $user_created_interest = UsersInterests::where('id', $request->user_id)
                                                ->select('id as created_interest_id', 'username as created_interest_user', 
                                                'interest_name as created_interest_name')->get();


        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts créée par l\'utilisateur.',
            'user_created_interest' => $user_created_interest
        ]);

    }

    public function get_user_interests($user_id)
    {
        $total_user_interests = UsersInterests::where('user_id', $user_id)->count();

         $all_user_interests = DB::table('users_interests')
                                  ->join('users', 'users_interests.user_id', '=', 'users.id')
                                  ->where('users.id', $user_id)
                                  ->orderByRaw('username, interest_name')
                                  ->get();


        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de l\'utilisateur connecté.',
            'total_user_interests' => $total_user_interests,
            'all_user_interests' => $all_user_interests
        ]);
    }

    public function get_all_interests()
    {
        $total_interests = DB::table('users_interests')
                                    ->select('interest_name', DB::raw('count(*) as interest_name'))
                                    ->groupBy('name')->get();

         $all_interests = DB::table('users_interests')->select('interest_name')
                                  ->orderBy('interest_name')->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de l\'utilisateur connecté.',
            'total_interests' => $total_interests,
            'all_interests' => $all_interests
        ]);
    }


}
