<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{
    public function addFavorite($favoriteUserId)
    {
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
        $favorites = $user->favorites()->get()->makeHidden(['verification_code', 'verified','email']);
        return response()->json(['favorite' => $favorites ]);

    }

}
