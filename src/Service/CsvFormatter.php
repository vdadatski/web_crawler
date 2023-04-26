<?php

namespace Dadatski\WebCrawler\Service;

class CsvFormatter
{
    public function execute(array $content): string
    {
        $filename = time() .'.csv';
        $fp = fopen( $filename, 'w');
        foreach ($this->prepareData($content) as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        return $filename;
    }

    private function prepareData(array $content): array
    {
        return [
            'links' => $content['links'],
            'headers' => $this->prepareHeaders($content['headers']),
            'words' => $this->prepareWords($content['words']),
        ];
    }

    private function prepareHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $level => $levelHeaders) {
            $result[] = "$level : " . implode(';', $levelHeaders);
        }

        return $result;
    }

    private function prepareWords(array $words): array
    {
        $result = [];
        foreach ($words as $word => $number) {
            $result[] = "$word : $number";
        }

        return $result;
    }
}
