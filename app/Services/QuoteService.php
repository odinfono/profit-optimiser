<?php
namespace App\Services;

class QuoteService
{
    public function calculate(array $payload): array
{
    $lines = collect($payload['lines'])->map(function ($line) {
        $qty = max(1, $line['qty']);
        $unitCost  = $line['cost'] / $qty;
        $unitPrice = $line['price'] / $qty;

        $grossProfit = ($unitPrice - $unitCost) * $qty;
        $grossMargin = $unitPrice > 0
            ? (($unitPrice - $unitCost) / $unitPrice) * 100
            : 0;

        return [
            'gross'  => round($grossProfit, 2),
            'margin' => round($grossMargin, 2),
        ];
    })->values()->toArray();
    $laborThreshold = 40;
    $grossTotal = array_sum(array_column($lines, 'gross'));
    $totalRevenue = collect($payload['lines'])->sum(fn($l) => $l['price']);
    $totalCost    = collect($payload['lines'])->sum(fn($l) => $l['cost']);
    $laborCost = $payload['labor_hours'] * $payload['labor_rate'];
    $overhead  = $payload['overhead'];
    $netProfit = $totalRevenue - $totalCost - $laborCost - $overhead;
    $netMargin = round(($netProfit / max(1, $totalRevenue)) * 100, 2);
    $targetMargin = (float)$payload['target_margin'];
    $exceedsLaborThreshold = $payload['labor_hours'] > $laborThreshold;
    $status = $netMargin >= $targetMargin
        ? 'green'
        : ($netMargin >= $targetMargin * 0.8 ? 'amber' : 'red');

    return [
        'lines'         => $lines,
        'total'         => round((float)$grossTotal, 2),
        'totalRevenue'  => round((float)$totalRevenue, 2),
        'totalCost'     => round((float)$totalCost, 2),
        'laborCost'     => round((float)$laborCost, 2),
        'overhead'      => round((float)$overhead, 2),
        'netProfit'     => round((float)$netProfit, 2),
        'netMargin'     => $netMargin,
        'target_margin' => $targetMargin,
        'health'        => $status,
        'exceedsLaborThreshold' => $exceedsLaborThreshold,
    ];
}

}
