#!/bin/bash

###############################################################

              # PRE-SETUP YOUR LOCAL DEV ENVIRONMENT #

###############################################################

# Make sure the .env file exists
FILE=api/.env
if [ ! -f "$FILE" ]; then
    cp api/.env.example api/.env
fi
