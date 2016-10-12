### AIML parser

#### Instalation

```
    composer require a2design/aiml
```


#### Usage

```php
$aimlFilePath = '/path/to/file.aiml';
$chat = new AIML();
$chat->addDict($aimlFilePath);

$answer = $chat->getAnswer('how are you?');
// i'm fine
```

#### Contributing
