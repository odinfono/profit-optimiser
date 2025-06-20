<?php
// app/Http/Resources/QuoteResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'lines'         => $this['lines'],
            'total'         => $this['total'],
            'labor_cost'    => $this['laborCost'],
            'net_profit'    => $this['netProfit'],
            'net_margin'    => $this['netMargin'],
            'health'        => $this['health'],
            'overhead'      => $this['overhead'],
            'target_margin' => $this['target_margin'],
            'exceedsLaborThreshold' => $this["exceedsLaborThreshold"]
        ];
    }
}
