# User

## usage

# Development
## install env
```
docker build -t phpdrc .
docker run -m 200m --cpus 0.3 --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp phpdrc composer i
```
## testing
```
docker run -eXDEBUG_MODE=off -m 200m --cpus 0.3 --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp phpdrc vendor/bin/phpunit
```

## testing with xdebugging (xdebug on 9001 by default)
```
docker run -m 200m --cpus 0.3 --rm -it --add-host=host.docker.internal:host-gateway -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp phpdrc vendor/bin/phpunit
```
