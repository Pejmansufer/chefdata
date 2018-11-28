#!/bin/bash
find ./media/weaveapps -type f -iname '*jpg' -exec sh -c 'jpegtran -outfile "{}.out" -optimize "{}"; mv "{}.out" "{}"' \;