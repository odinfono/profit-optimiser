<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AISuggestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quote'                  => 'required|array',
            'quote.lines'            => 'required|array|min:1',
            'quote.lines.*.gross'    => 'required|numeric',
            'quote.lines.*.margin'   => 'required|numeric',
            'quote.total'            => 'required|numeric',
            'quote.labor_cost'       => 'required|numeric',
            'quote.net_profit'       => 'required|numeric',
            'quote.net_margin'       => 'required|numeric',
            'quote.overhead'         => 'required|numeric',
            'quote.target_margin'    => 'required|numeric|between:0,100',
            'quote.health'           => 'required|string|in:green,amber,red',
        ];
    }

    public function quote(): array
    {
        return $this->validated()['quote'];
    }
}
