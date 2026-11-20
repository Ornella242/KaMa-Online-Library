<?php

namespace App\Services\Audiobook;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

class PdfTextExtractor
{
    public function extract(string $filePath): array
    {
        $fullPath = Storage::disk('local')->path($filePath);

        if (!file_exists($fullPath)) {
            throw new \RuntimeException(
                "Le fichier PDF n'existe pas."
            );
        }

        $parser = new Parser();

        $pdf = $parser->parseFile($fullPath);

        $pages = [];

        foreach ($pdf->getPages() as $index => $page) {

            $text = $page->getText();

            $pages[] = [
                'page' => $index + 1,
                'text' => $text,
                'characters' => mb_strlen($text),
                'words' => str_word_count($text),
            ];
        }

        return [
            'pages' => $pages,
            'page_count' => count($pages),
        ];
    }
}