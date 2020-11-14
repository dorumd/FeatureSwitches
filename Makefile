.SILENT:
.PHONY:

build:
	DOCKER_BUILDKIT=1 docker build -t feature-switches:latest .
	docker run feature-switches:latest
	docker run feature-switches:latest composer run analyse

fix-code-format:
	docker run -v $$(pwd)/src:/FeatureSwitches/src -v $$(pwd)/tests:/FeatureSwitches/tests feature-switches:latest composer run fix
