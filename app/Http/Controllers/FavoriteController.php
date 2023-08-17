<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{


    public function addFavorite(Request $request)
    {
        $favoriteUserId = $request->query('id');
        $user = auth()->user();
        $favoriteUser = User::find($favoriteUserId);
        $user->addFavorite($favoriteUser);

        return response()->json(['message' => 'Person added to favorite.']);
    }

    public function removeFavorite($favoriteUserId)
    {
        $user = \auth()->user();
        $favoriteUser = User::find($favoriteUserId);
        $user->removeFavorite($favoriteUser);

        return response()->json(['message' => 'Person deleted from favorite.']);

    }

    public function getFavoriteUsers()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->get()->makeHidden(['email']);
        return response()->json(['favorite' => $favorites ]);

    }

    public function getIDs()
    {
        // Assuming your user_favorite_persons table has 'user_id' and 'favorite_person_id' columns
        $user = auth()->user();
        $favoritePersonIds = $user->favorites()->pluck('favorite_user_id');

        return response()->json([
            'favorite_person_ids' => $favoritePersonIds
        ]);
    }



}
