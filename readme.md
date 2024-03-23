# User

## why?
A tool that called to help checking dependency rule and not allow its violations.

## usage

### Config
Requires config to work. Example of config:
```
namespaces:
    namespace\you\want\to\keep\clean:
        - namespace\you\allow\there1
        - namespace\you\allow\there2
exclude:
    - path/to/dir
    - path/to/file.php
```
Keep in mind that in namespaces you want to check also in a list of allowed. So if you check `App\Core` all uses with that namespace prefix would be automatically allowed. If multiple entries match, the first matching entry will be the only one to be used.

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

## build
```
docker run -m 200m --cpus 0.3 --rm -it --add-host=host.docker.internal:host-gateway -u $(id -u):$(id -g) -w /tmp -e XDEBUG_MODE=off -v ${PWD}:/tmp phpdrc sh build.sh
```

### code analysis
```
docker run --rm -it -u ${UID} -v ${PWD}:/app -w /app achertovsky/phptools all
// if want to analyze only staged files
docker run --rm -it -u ${UID} -v ${PWD}:/app -w /app achertovsky/phptools all -m
```
