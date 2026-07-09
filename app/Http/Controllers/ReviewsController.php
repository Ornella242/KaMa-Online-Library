<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    $reviews = Review::with(['book', 'user'])
        ->whereHas('book', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->latest()
        ->paginate(10);

    /* Statistiques */

    $totalReviews = Review::whereHas('book', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();

    $averageRating = Review::whereHas('book', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->avg('rating') ?? 0;

    $fiveStars = Review::whereHas('book', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('rating', 5)
        ->count();

    /* Satisfaction (%) */

    $satisfiedReviews = Review::whereHas('book', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->whereIn('rating', [4, 5])
        ->count();

    $satisfaction = $totalReviews > 0
        ? round(($satisfiedReviews / $totalReviews) * 100)
        : 0;

    /* Répartition des notes  */

    $ratingCounts = [];

    $ratingPercentages = [];

    for ($i = 1; $i <= 5; $i++) {

        $count = Review::whereHas('book', function ($query) use ($userId) {

                $query->where('user_id', $userId);

            })
            ->where('rating', $i)
            ->count();

        $ratingCounts[$i] = $count;

        $ratingPercentages[$i] = $totalReviews > 0
            ? round(($count / $totalReviews) * 100)
            : 0;
    }

    return view('writer.reviews', compact(
        'reviews',
        'totalReviews',
        'averageRating',
        'fiveStars',
        'satisfaction',
        'ratingCounts',
        'ratingPercentages'
    ));
}
}
