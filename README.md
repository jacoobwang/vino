# Vino PHP Framework 1.0.0

Vino is a PHP framework that helps you quick build web applications and APIs.

## Installation

1,Now you can download from this github respository,use git clone https://github.com/jacoobwang/vino.git or click button download zip.either is ok.

2,When you finished download,you should use composer install some php packages that you can find it in the file ---- composer.json.

Use this cmd:

```php
php composer.phar install 
```

3,When you installed packages,if you web container is nginx ,you sholud modify nginx.conf.

```json
location / {
	try_files $uri $uri/ /index.php?_url=$1&$args;
}
```

If you use apache ,just open rewrite module is ok.Vino user .htaccess file.

4,At last, you should open your browser visit http://localhost/

So ,congratulations，it's worked!

and i also support examples like login and reg function in the UserCrontoller.

## Documentation

You can visit this website to learn more

https://jacoobwang.github.io/vino/vino.html

## Ends

If you have some ideas,welcome contact me ! 

Thank you very much!

