# Trigger all tags/branchs for this automated build.
$ curl -H "Content-Type: application/json" --data '{"build": true}' -X POST https://registry.hub.docker.com/u/cmptech/cmp_app_server/trigger/0fbff06e-73f7-4cac-80ac-6e1ca2cd941c/

# Trigger by docker tag name
#$ curl -H "Content-Type: application/json" --data '{"docker_tag": "master"}' -X POST https://registry.hub.docker.com/u/cmptech/cmp_app_server/trigger/0fbff06e-73f7-4cac-80ac-6e1ca2cd941c/

# Trigger by Source branch named staging
#$ curl -H "Content-Type: application/json" --data '{"source_type": "Branch", "source_name": "staging"}' -X POST https://registry.hub.docker.com/u/cmptech/cmp_app_server/trigger/0fbff06e-73f7-4cac-80ac-6e1ca2cd941c/

# Trigger by Source tag named v1.1
#$ curl -H "Content-Type: application/json" --data '{"source_type": "Tag", "source_name": "v1.1"}' -X POST https://registry.hub.docker.com/u/cmptech/cmp_app_server/trigger/0fbff06e-73f7-4cac-80ac-6e1ca2cd941c/

