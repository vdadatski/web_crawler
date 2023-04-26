<?php

namespace Dadatski\WebCrawler\Service;

use Dadatski\WebCrawler\Commands\Crawler;
use Symfony\Component\Console\Input\InputInterface;

class InputValidator
{
    private const AVAILABLE_FORMATS = [
        Crawler::CSV_FORMAT,
        Crawler::JSON_FORMAT
    ];

    public function execute(InputInterface $input): array
    {
        $errors = [];
        $url = $input->getOption(Crawler::KEY_URL);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $errors[] = "The URL ($url) is not valid";
        }

        $domain = $input->getOption(Crawler::KEY_DOMAIN);
        if ($domain) {
            if (!checkdnsrr($domain, 'A') ) {
                $errors[] = "The domain ($domain) is not valid";
            }
        }
        $headerLevel = $input->getOption(Crawler::KEY_HEADER_LEVEL);
        if ($headerLevel) {
            if ($headerLevel < 1 || $headerLevel > 6) {
                $errors[] = "The header level ($headerLevel) is not valid";
            }
        }
        $format = $input->getOption(Crawler::KEY_FORMAT);
        if ($format) {
            if (!in_array($format, self::AVAILABLE_FORMATS)) {
                $errors[] = "The format ($format) is not valid";
            }
        }

        return $errors;
    }
}
