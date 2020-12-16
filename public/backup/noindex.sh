#!/bin/bash

cd ../../

git pull

# Commit comment

# commit
ct="$(date +'%Y:%m:%d-%H:%M:%S')"

# Management
git add .
git commit -m $ct
git push origin master