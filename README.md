# example
Display meta information of a PNG image.

## How to use
1. Clone repository.
```
$ git clone https://github.com/nishi-hi/example.git
```

2. Build Docker image.
```
$ cd example/build/
$ bash build.sh
```

3. Run Docker container.
```
$ cd ../
$ bash run.sh
```

4. Change the owner and group of application data uniformly.
```
$ docker exec example.com /bin/bash -c 'chown -Rh nginx:nginx /srv/www/example/'
```

5. Change hosts file to access the application as FQDN.
```
# vi /etc/hosts
[before] 127.0.0.1 localhost
[after]  127.0.0.1 localhost example.com
```

6. Use a web browser to access the application. User name and password are *demo*.
```
http://example.com
```
