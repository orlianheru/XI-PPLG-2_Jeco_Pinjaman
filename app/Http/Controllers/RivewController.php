<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['book', 'user'])->get();
        return response()->json(['success' => true, 'data' => $reviews]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'book_id' => 'required|exists:books,id',
                'user_id' => 'required|exists:users,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:500',
            ]);

            $review = Review::create($validated);

            return response()->json(['success' => true, 'data' => $review], 201);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        try {
            $review = Review::with(['book', 'user'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $review]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Review not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:500',
            ]);

            $review->update($validated);

            return response()->json(['success' => true, 'data' => $review]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Review not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json(['success' => true, 'message' => 'Review deleted']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Review not found'], 404);
        }
    }
}
