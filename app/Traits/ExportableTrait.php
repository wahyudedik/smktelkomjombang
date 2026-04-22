<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ExportableTrait
{
    /**
     * Generate PDF export response
     */
    protected function generatePdf($view, $data, $filename, $orientation = 'portrait')
    {
        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('a4', $orientation);

        return $pdf->download($filename . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate JSON export response
     */
    protected function generateJson($data, $modelName): JsonResponse
    {
        return response()->json([
            'success' => true,
            'model' => $modelName,
            'data' => $data,
            'total' => is_countable($data) ? count($data) : 0,
            'exported_at' => now()->toIso8601String(),
            'format' => 'json'
        ], 200);
    }

    /**
     * Generate XML export response
     */
    protected function generateXml($data, $rootElement, $itemElement, $filename, $callback = null): Response
    {
        $xml = new \SimpleXMLElement("<{$rootElement}/>");
        $xml->addAttribute('exported_at', now()->toIso8601String());
        $xml->addAttribute('total', is_countable($data) ? count($data) : 0);

        foreach ($data as $item) {
            $itemNode = $xml->addChild($itemElement);

            if ($callback && is_callable($callback)) {
                // Use custom callback for populating XML
                $callback($itemNode, $item);
            } else {
                // Default: add all attributes
                foreach ($item->toArray() as $key => $value) {
                    if (is_scalar($value)) {
                        $itemNode->addChild($key, htmlspecialchars((string)$value));
                    } elseif (is_array($value)) {
                        $arrayNode = $itemNode->addChild($key);
                        foreach ($value as $subKey => $subValue) {
                            if (is_scalar($subValue)) {
                                $arrayNode->addChild(is_numeric($subKey) ? 'item' : $subKey, htmlspecialchars((string)$subValue));
                            }
                        }
                    }
                }
            }
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '-' . date('Y-m-d') . '.xml"');
    }
}
