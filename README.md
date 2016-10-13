# AIML parser

[![Build Status](https://travis-ci.org/a2design-inc/AIML.svg?branch=master)](https://travis-ci.org/a2design-inc/AIML)

## Requirements

* PHP: ≥ 5.4

## Instalation

```
    composer require a2design/aiml
```


## Usage

```php
$aimlFilePath = '/path/to/file.aiml';
$chat = new AIML();
$chat->addDict($aimlFilePath);

$answer = $chat->getAnswer('how are you?');
// i'm fine
```

## Contributing

1. Fork the Project

2. Install Development Dependencies

3. Create a Feature Branch

4. (Recommended) Run the Test Suite

    ``` sh
    vendor/bin/phpunit
    ```

5. Send us a Pull Request
