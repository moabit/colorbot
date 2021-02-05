# ColorBot
Telegram Bot [@spb_color](https://t.me/spb_color)  

This bot takes screenshot of youtube live stream and posts info about current sky color in telegram channel. Source of inspiration - [@colorofberlin](https://twitter.com/colorofberlin)

As source image I take this [stream](https://www.youtube.com/watch?v=X-dyUamzjJE).

## Requirements
1. PHP 7.4+ 
2. Linux
3. youtube-dl and ffmpeg
4. composer

## Installation
1. Create [Telegram bot](https://core.telegram.org/bots/api) and add him to admins of your channel
1. Clone the project with clone `https://github.com/moabit/colorbot.git`
3. Run `composer install`
3. Edit `config.json ` and set youtube live stream url, your channel and Telegram bot api token
4. Set crontab to run `bot.php` Example: `0 */3 * * * /usr/bin/php /home/user/colorbot/bot.php`

