# Description

Script creates file (json or csv) with crawling results.

## Instructions

- Download folder with project
- Run docker-compose up -d
- Access php container via "docker exec -it php bash" command
- Run composer install
- Run bin/console crawler -h to see help

## Examples
- bin/console crawler --url https://www.bbc.com/news
- bin/console crawler --url https://www.bbc.com/news --domain www.bbc.com
- bin/console crawler --url https://www.bbc.com/news --domain www.bbc.com --words_list NewsPage,BBC
- bin/console crawler --url https://www.bbc.com/news --header_level 2
- bin/console crawler --url https://www.bbc.com/news --format csv
- bin/console crawler --url https://www.bbc.com/news --domain www.bbc.com --search_depth 2
