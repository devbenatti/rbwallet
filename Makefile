IMAGE=myphpcontainer
DOCKER_RUN:=docker run --rm -it --user 1000:1000 -v ${PWD}:/usr/app -w /usr/app ${IMAGE}
args = `arg="$(filter-out $@,$(MAKECMDGOALS))" && echo $${arg:-${1}}`

configure: 
	- ${DOCKER_RUN} composer update

composer-require:
	- ${DOCKER_RUN} composer require ${call args}

composer-require-dev:
	- ${DOCKER_RUN} composer require --dev ${call args}
