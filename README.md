# Customer io notification channel for Laravel

This package makes it easy to send notifications using the Customer io API with Laravel.

## About

The Customer io channel makes it possible to send out Laravel notifications as a Customer io event. The notifiable model data will also be synced with Customer io and kept up to date.

## Installation

Setup locally in packages

## Setting up the Customer io service
You will need to create a Customer io account to use this channel. Within your account, you will find the API key and the site ID. Place them inside your .env file:

```
CUSTOMER_IO_ENABLED=true
CUSTOMER_IO_SITE_ID=[SITE_ID]
CUSTOMER_IO_API_KEY=[API_KEY]
CUSTOMER_IO_MODEL=App\User
```

## Usage

Add the trait to your notifiable model:

``` php
use Tether\LaravelCustomerIo\Traits\SyncsToCustomerIo;

class User extends Authenticatable
{
    use Notifiable, SyncsToCustomerIo;
    
    // ...
}

```

Adding customer io support to the notification class:

``` php
use Tether\LaravelCustomerIo\Channels\CustomerIoChannel;
```
``` php
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            CustomerIoChannel::class,
        ];
    }

    /**
     * Get the customer io representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toCustomerIo($notifiable)
    {
        return [
            // ...
        ];
    }
}
```

Sync all customers with customer.io with a single command:

``` bash
php artisan customer-io:sync-customers
```

### Testing

``` php
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dev@steadfastcollective.com instead of using the issue tracker.

## Credits

- [André Breia](https://github.com/andrebreia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
