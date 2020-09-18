<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\Post;                                 
use App\Models\UsersInterests;
use App\Models\CentersOfInterests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersInterestsController extends Controller
{

    public function create_interest(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'interest_id' => 'required'
        ]);

        $user = User::where('id', $request->user_id)->first();

        $user_created_interest_type = CentersOfInterests::where('id', $request->interest_id)
                                                         ->select('type as created_interest_type')
                                                         ->get();
        $user_created_interest_name = CentersOfInterests::where('id', $request->interest_id)
                                                         ->select('name as created_interest_name')
                                                         ->get();

        $userInterest = UsersInterests::create([
            'user_id' => request('user_id'),
            'username' => $user->username,
            'interest_id' => request('interest_id'),
            'interest_type' => $user_created_interest_type,
            'interest_name' => $user_created_interest_name
        ]);

        $post = Post::create([
            'title' => 'Post Interest',
            'body' => 'Post pour création et attribution d\'intèrêt au user connecté',
            'user_id' => request('user_id')
        ]);

        $user_created_interest = CentersOfInterests::where('id', $request->interest_id)->get();

        $total_user_interests = UsersInterests::where('user_id', $request->user_id)->count();

        $all_user_interests_type = DB::table('users_interests')
                                      ->join('users', 'users_interests.user_id', '=', 'users.id')
                                      ->join('centers_of_interests', 'users_interests.interest_id', '=', 'centers_of_interests.id')
                                      ->where('users.id', $request->user_id)
                                      ->select('centers_of_interests.type as user_interest_type', DB::raw(
            'count(*) as user_total_interest_type'))
                                      ->groupBy('centers_of_interests.type')->get();

        $all_user_interests_list = DB::table('users_interests')
                                      ->join('users', 'users_interests.user_id', '=', 'users.id')
                                      ->join('centers_of_interests', 'users_interests.interest_id', '=', 'centers_of_interests.id')
                                      ->where('users.id', $request->user_id)
                                      ->select('centers_of_interests.type', 'centers_of_interests.name')
                                      ->orderByRaw('centers_of_interests.type, centers_of_interests.name')
                                      ->get();

         $all_user_interests = DB::table('users_interests')
                                  ->join('users', 'users_interests.user_id', '=', 'users.id')
                                  ->join('centers_of_interests', 'users_interests.interest_id', '=', 'centers_of_interests.id')
                                  ->where('users.id', $request->user_id)
                                  ->orderByRaw('centers_of_interests.type, centers_of_interests.name')
                                  ->get();


        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de l\'utilisateur connecté.',
            'user_created_interest' => $user_created_interest,
            /*'total_user_interests' => $total_user_interests,
            'all_user_interests_type' => $all_user_interests_type,
            'all_user_interests_list' => $all_user_interests_list,
            'all_user_interests' => $all_user_interests*/
        ]);
    }

    public function get_interests_types()
    {
      $interests_types_total = DB::table('centers_of_interests')
                                    ->select('type as interest_type', DB::raw(
            'count(*) as total_interest_type'))
                                    ->groupBy('type')->get();

      return response()->json([
            'Message' => 'Informations liées aux types de centres d\'intèrêts enregistrées.',
            'interests_types_total' => $interests_types_total
        ]);
    }
    

    public function get_interests()
    {

        $interests_total_cont = CentersOfInterests::count();

        $interests_liste = CentersOfInterests::orderByRaw('type, name')->distinct()->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts enregistrées.',
            // 'interests_total_cont' => $interests_total_cont,
            'interests_liste' => $interests_liste
        ]);
    }

    public function get_musics_interests()
    {

        $musics_interests_total = CentersOfInterests::where('type', 'Musiques et Sons')
                                                     ->count();

        $musics_interests_liste = CentersOfInterests::where('type', 'Musiques et Sons')
                                                     ->orderBy('name')->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de musics enregistrées.',
            //'musics_interests_total' => $musics_interests_total, 
            'musics_interests_liste' => $musics_interests_liste
          ]);
    }

    public function get_movies_interests()
    {

        $movies_interests_total = CentersOfInterests::where('type', 'Films, Séries et Cinémas')
                                                     ->count();

        $movies_interests_liste = CentersOfInterests::where('type', 'Films, Séries et Cinémas')
                                                     ->orderBy('name')->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de movies enregistrées.',
            //'movies_interests_total' => $movies_interests_total, 
            'movies_interests_liste' => $movies_interests_liste
          ]);
    }

    public function get_sports_interests()
    {

        $sports_interests_total = CentersOfInterests::where('type', 'Sports')
                                                     ->count();

        $sports_interests_liste = CentersOfInterests::where('type', 'Sports')
                                                     ->orderBy('name')->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts enregistrées.',
            //'sports_interests_total' => $sports_interests_total, 
            'sports_interests_liste' => $sports_interests_liste
          ]);
    }

    public function get_docs_interests()
    {

        $docs_interests_total = CentersOfInterests::where('type', 'Documents et Livres')
                                                     ->count();

        $docs_interests_liste = CentersOfInterests::where('type', 'Documents et Livres')
                                                     ->orderBy('name')->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de documents enregistrées.',
            // 'docs_interests_total' => $docs_interests_total, 
            'docs_interests_liste' => $docs_interests_liste
          ]);
    }

    public function get_entertainments_interests()
    {

        $entertainments_interests_total = CentersOfInterests::where('type', 'Divertissements et Loisirs')
                                                     ->count();

        $entertainments_interests_liste = CentersOfInterests::where('type', 'Divertissements et Loisirs')
                                                     ->orderBy('name')->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de divertissements enregistrées.',
            //'entertainments_interests_total' => $entertainments_interests_total, 
            'entertainments_interests_liste' => $entertainments_interests_liste
          ]);
    }

    public function get_cuisines_interests()
    {

        $cuisines_interests_total = CentersOfInterests::where('type', 'Cuisines et Gastronomies')
                                                     ->count();

        $cuisines_interests_liste = CentersOfInterests::where('type', 'Cuisines et Gastronomies')
                                                     ->orderBy('name')->get();

        return response()->json([
            'Message' => 'Informations liées aux centres d\'intèrêts de cuisines enregistrées.',
            //'cuisines_interests_total' => $cuisines_interests_total, 
            'cuisines_interests_liste' => $cuisines_interests_liste
          ]);
    }


}
