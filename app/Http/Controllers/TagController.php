<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tag::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $tags = $query->orderBy('name')->get();

        return ApiResponse::success(
            'Tags retrieved successfully',
            ['tags' => TagResource::collection($tags)]
        );
    }

    /**
     * Store a newly created tag or return existing one.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $tag = Tag::firstOrCreate([
            'name' => $validated['name'],
        ]);

        return ApiResponse::success(
            'Tag created successfully',
            ['tag' => new TagResource($tag)],
            201
        );
    }
}

