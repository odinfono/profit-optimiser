<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lines'            => 'required|array|min:1',
            'lines.*.cost'     => 'required|numeric|min:0',
            'lines.*.price'    => 'required|numeric|min:0',
            'lines.*.qty'      => 'required|integer|min:1',
            'labor_hours'      => 'required|numeric|min:0',
            'labor_rate'       => 'required|numeric|min:0',
            'overhead'         => 'required|numeric|min:0',
            'target_margin'    => 'required|numeric|between:0,100',
        ];
    }
}
