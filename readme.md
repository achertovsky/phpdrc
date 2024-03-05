# User

## why?
A tool that called to help checking dependency rule and not allow its violations.

## usage

### Config
Requires config to work. Example of config:
```
- namespace\you\want\to\keep\clean:
    - namespace\you\allow\there1
    - namespace\you\allow\there2
```
Keep in mind that in namespaces you want to check also in a list of allowed. So if you check `App\Core` all uses with that namespace prefix would be automatically allowed.

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
