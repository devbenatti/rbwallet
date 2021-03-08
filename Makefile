IMAGE_COMPOSER=composer
COMPOSER_RUN:=docker run --rm -it --user 1000:1000 -v ${PWD}:/usr/app -w /usr/app ${IMAGE_COMPOSER}
args = `arg="$(filter-out $@,$(MAKECMDGOALS))" && echo $${arg:-${1}}`

configure: 
	- ${COMPOSER_RUN} 'update'

composer-require:
	- ${COMPOSER_RUN} require ${call args}

composer-require-dev:
	- ${DOCKER_RUN} require --dev ${call args}
