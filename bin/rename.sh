#!/usr/bin/env bash

SLUG=''
NAMESPACE=''

for arg; do
	shift
	case $arg in
	--slug*)
		SLUG=${arg//"--slug="/}
		;;
	--namespace*)
		NAMESPACE=${arg//"--namespace="/}
		;;
	*)
		set -- "$@" "$arg"
		;;
	esac
done

if [ -n "$SLUG" ]; then
	echo "Updating plugin slug with $SLUG"
	grep -rl blocks-plugin-template . --exclude-dir={node_modules,vendor,.history,.git,bin} | xargs sed -i '' "s/blocks-plugin-template/$SLUG/g"

	echo "Updating plugin files with $SLUG"
	find . -name 'blocks-plugin-template*' -type f | sed -e "p;s/blocks-plugin-template/$SLUG/"  | xargs -n2 mv
fi

if [ -n "$NAMESPACE" ]; then
	echo "Updating plugin namespace with $NAMESPACE"
	grep -rl BlocksPluginTemplate . --exclude-dir={node_modules,vendor,.history,.git,bin} | xargs sed -i '' "s/BlocksPluginTemplate/$NAMESPACE/g"
fi
