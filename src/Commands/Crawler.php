<?php

namespace Dadatski\WebCrawler\Commands;

use Dadatski\WebCrawler\Service\Crawler as Service;
use Dadatski\WebCrawler\Service\CsvFormatter;
use Dadatski\WebCrawler\Service\InputValidator;
use Dadatski\WebCrawler\Service\JsonFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Crawler extends Command
{
    public const JSON_FORMAT = 'json';
    public const CSV_FORMAT = 'csv';
    public const KEY_URL = 'url';
    public const KEY_WORDS_LIST = 'words_list';
    public const KEY_DOMAIN = 'domain';
    public const KEY_HEADER_LEVEL = 'header_level';
    public const KEY_FORMAT = 'format';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errors = $this->validate($input);
        if ($errors) {
            $output->writeln('<error>' . implode('</error>' . PHP_EOL . '<error>', $errors)) . '</error>';

            return self::FAILURE;
        }

        $url = $input->getOption(self::KEY_URL);
        $wordsList = $input->getOption(self::KEY_WORDS_LIST);
        $domain = $input->getOption(self::KEY_DOMAIN);
        $headerLevel = (int)$input->getOption(self::KEY_HEADER_LEVEL);
        $format = $input->getOption(self::KEY_FORMAT);

        $crawler = new Service();
        $result = $crawler->execute($url, $wordsList, $domain, $headerLevel);

        $formatter = $format === self::CSV_FORMAT ? new CsvFormatter() : new JsonFormatter();
        $filename = $formatter->execute($result);

        $output->writeln('<info>Your result is here - ' . $filename . '</info>');


        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setName('crawler')
            ->setDescription('Crawl web page')
            ->setDefinition($this->getOptionsList());
    }

    private function getOptionsList(): array
    {
        return [
            new InputOption(
                self::KEY_URL,
                null,
                InputOption::VALUE_REQUIRED,
                '(Required) Web page url'
            ),
            new InputOption(
                self::KEY_WORDS_LIST,
                null,
                InputOption::VALUE_OPTIONAL,
                '(Optional) List of words to look for (comma separated)'
            ),
            new InputOption(
                self::KEY_DOMAIN,
                null,
                InputOption::VALUE_OPTIONAL,
                '(Optional) Domain to filter the links'
            ),
            new InputOption(
                self::KEY_HEADER_LEVEL,
                null,
                InputOption::VALUE_OPTIONAL,
                '(Optional) Header level (1-6)'
            ),
            new InputOption(
                self::KEY_FORMAT,
                null,
                InputOption::VALUE_OPTIONAL,
                '(Optional) Format parameter (csv, json)',
                self::JSON_FORMAT
            ),
        ];
    }

    private function validate(InputInterface $input): array
    {
        $validator = new InputValidator();

        return $validator->execute($input);
    }
}
