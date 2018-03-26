#!/bin/bash


SCHEMA=$PWD/vendor/kronostechnologies/crm-api/crm.graphqls
DESTINATION=$PWD/build
NAMESPACE=Kronos\\CRM\\GraphQL\\Schema

rm -rf "$DESTINATION"
php $PWD/graphqlgen generate-classes -q --namespaced-target-namespace "$NAMESPACE" "$SCHEMA" "$DESTINATION"
$PWD/vendor/bin/phpcbf -q --standard=$PWD/kronos_ruleset.xml "$DESTINATION"
