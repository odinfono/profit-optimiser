<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function __construct(private QuoteService $svc) {}

    public function process(StoreQuoteRequest $req): QuoteResource
    {
        $result = $this->svc->calculate($req->validated());
        return new QuoteResource($result);

    }
}
