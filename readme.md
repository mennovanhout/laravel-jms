# Laravel JMS

More info coming soon

## Installation

```bash
composer require mennovanhout/laravel-jms
```

## Usage

```
$serializer = app(Serializer::class);
```

## Example

### JSON
```json
{
  "title": "Hello World!"
}
```

### DTO
```php
use JMS\Serializer\Annotation as Serializer;

class PaymentDTO
{
    /**
     * @Serializer\SerializedName("title")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $title;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
```

### Deserialize
```php
$serializer = app(Serializer::class);

/** @var PaymentDTO */
$paymentDTO = $serializer->deserialize($json, PaymentDTO::class, 'json');
```

### Serialize
```php
$serializer = app(Serializer::class);

$json = $serializer->serialize($paymentDTO, 'json');
```

## More info

Annotations: https://jmsyst.com/libs/serializer/master/reference/annotations