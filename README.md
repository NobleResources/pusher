# Pusher

# Setup

1. run `composer require vanlonden/pusher`
2. add `VanLonden\Pusher\PusherServiceProvider::class,` to the `providers` in `config/app.php`
3. run `artisan vendor:publish`
4. edit `config/pusher.php` to your liking
5. add your `FIREBASE_SERVER_KEY` to your .env file
6. run `artisan migrate`
