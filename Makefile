.SILENT:
.PHONY:

build:
	DOCKER_BUILDKIT=1 docker build -t feature-switches:latest .

validate:
	docker run -v $$(pwd):/FeatureSwitches feature-switches:latest
	docker run -v $$(pwd):/FeatureSwitches feature-switches:latest composer run analyse

fix-code-format:
	docker run -v $$(pwd):/FeatureSwitches feature-switches:latest composer run fix
