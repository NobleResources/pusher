# Pusher

## Setup

1. run `composer require vanlonden/pusher`
2. add `VanLonden\Pusher\PusherServiceProvider::class,` to the `providers` in `config/app.php`
3. run `artisan vendor:publish`
4. edit `config/pusher.php` to your liking
5. add your `FIREBASE_SERVER_KEY` to your .env file
6. run `artisan migrate`


## Usage
```php
// Send a push message to all users
$this->pusher->send('Title', 'A message', User::all());

// Create a future push message
$futurePush = FuturePush::create([
    'time' => '2016-08-03 17:00',
    'title' => 'Title',
    'message' => 'A message',
]);
$futurePush->users()->saveMany(User::all());

// Send a future push message
$this->pusher->sendFuture(FuturePush::first());
```
