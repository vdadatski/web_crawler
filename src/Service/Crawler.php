<?php

namespace Dadatski\WebCrawler\Service;

use DOMDocument;

class Crawler
{
    private const PATTERN_FOR_LINKS = '/<a\s+.*?href=["\']([^"\']+)["\'].*?>(.*?)<\/a>/si';
    private const PATTERN_FOR_HEADERS = '/<h([LEVEL]).*?>(.*?)<\/h[LEVEL]>/si';
    private const PATTERN_FOR_WORDS = '/<p[^>]*>(.*?)<\/p>/s';

    public  function execute(
        string $url,
        ?string $wordsList,
        ?string $domain,
        ?int $headerLevel
    ): array {
        $html = file_get_contents($url);
        $wordCount = array_count_values($this->getWords($html, $wordsList));
        arsort($wordCount);

        return [
            'links' => $this->getLinks($html, $domain),
            'headers' => $this->getHeaders($html, $headerLevel),
            'words' => $wordCount,
        ];
    }

    private function getLinks(string $content, ?string $domain): array
    {
        preg_match_all(self::PATTERN_FOR_LINKS, $content, $matches, PREG_SET_ORDER);

        $links = array_map(function($match) {
            return $match[1];
        }, $matches);

        if($domain !== null) {
            $filteredLinks = array_filter($links, function($link) use ($domain) {
                $urlParts = parse_url($link);
                return isset($urlParts['host']) && $urlParts['host'] === $domain;
            });
            $links = $filteredLinks;
        }

        return $links;
    }

    private function getHeaders(string $content, int $headerLevel): array
    {
        $result = [];
        $headers = '1-6';
        if ($headerLevel !== 0) {
            $headers = (string) $headerLevel;
        }
        $pattern = str_replace('LEVEL', $headers, self::PATTERN_FOR_HEADERS);
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $result[$match[1]][] = trim($match[2]);
        }
        ksort($result);

        return $result;
    }

    private function getWords(string $content, ?string $wordsList): array
    {
        $words = [];

        preg_match_all(self::PATTERN_FOR_WORDS, $content, $matches);
        foreach ($matches[1] as $p) {
            $cleanP = strip_tags($p);
            preg_match_all('/\b\w+\b/', $cleanP, $nextMatches);
            $words = array_merge($words, $nextMatches[0]);
        }


        if ($wordsList !== null) {
            $filterList = explode(',', $wordsList);
            $words = array_intersect(
                $words,
                array_map(function($word) {
                    return trim($word);
                }, $filterList)
            );
        }

        return $words;
    }
}
