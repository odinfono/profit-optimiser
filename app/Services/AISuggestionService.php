<?php
namespace App\Services;

use OpenAI;

class AISuggestionService
{
    public function suggest(array $quote): string
    {
        $total        = $quote['total'];
        $netProfit    = $quote['net_profit'];
        $netMargin    = $quote['net_margin'];
        $laborCost    = $quote['labor_cost'];
        $overhead     = $quote['overhead'];
        $targetMargin = $quote['target_margin'];

        $client = OpenAI::client(config('openai.key'));

        $prompt = [
            ['role'=>'system','content'=>'You are a helpful senior financial accountant/analyst.'],
            ['role'=>'user','content'=>
                "Quote summary:\n".
                "- Total: {$total}\n".
                "- Net Profit: {$netProfit}\n".
                "- Net Margin: {$netMargin}%\n".
                "- Labor Cost: {$laborCost}\n".
                "- Overhead: {$overhead}\n".
                "- Target Margin: {$targetMargin}%\n\n".
                "Suggest concrete adjustments (pricing, quantities, labor allocation, product swaps) to reach the target margin, in client-friendly language."
            ]
        ];
        $res = $client->chat()->create([
          'model'    => config('openai.model'),
          'messages' => $prompt,
        ]);
        return trim($res->choices[0]->message->content);
    }
}
