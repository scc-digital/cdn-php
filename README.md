# CDN-PHP

A SCCD library useful to build CDN urls based on a very simple generator and without any dependency other than PHPUnit
in DEV environment.

## Unit Tests

The PHPUnit tests can be launched with this command line (replace $PHPUNIT_PATH by your own PHPUnit path):

```bash
$PHPUNIT_PATH -c vendor/scc/cdn-php/phpunit.xml.dist
```

## How to use it?

```php
use Scc\Cdn\Client;

$client = new Client('my_api_secret', 'https://cdn.my-domain.com/');

$options = [
    'resource_type' => 'image',
    'crop'          => 'pad',
    'width'         => 50,
    // any other supported resource transformation defined in Scc\Cdn\Transformation\Type
];

$picsUrl = $client->getUrl('my/picture/path.png', $options);
// or
$picsUrl = $client->getUrl('https://www.example.org/my-picture-url.png', $options);
```
