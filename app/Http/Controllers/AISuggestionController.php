<?php
namespace App\Http\Controllers;

use App\Http\Requests\AISuggestionRequest;
use App\Services\AISuggestionService;
use Illuminate\Http\JsonResponse;

class AISuggestionController extends Controller
{
    public function __construct(private AISuggestionService $ai) {}

    public function process(AISuggestionRequest $req): JsonResponse
    {
        return response()->json([
          'suggestions' => $this->ai->suggest($req->quote())
        ]);
    }
}
