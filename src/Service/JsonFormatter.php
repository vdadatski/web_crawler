<?php

namespace Dadatski\WebCrawler\Service;

class JsonFormatter
{
    public function execute(array $content): string
    {
        $json_data = json_encode($content, JSON_UNESCAPED_SLASHES);
        $filename = time() . '.json';
        file_put_contents($filename, $json_data);

        return $filename;
    }
}
