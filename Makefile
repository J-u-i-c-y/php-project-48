lint:
	vendor/bin/phpcs --standard=PSR12 src/ tests/

test:
	vendor/bin/phpunit

coverage:
	vendor/bin/phpunit --coverage-clover=coverage.xml
