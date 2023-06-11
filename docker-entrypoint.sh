#!/bin/sh

# Perform any application initialization here

# Define the path to the .env file
ENV_FILE=".env"

# Define the list of environment variables to search for and replace
ENV_VARS="DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD"

# Loop through each environment variable and replace its value in the .env file
for var in $ENV_VARS
do
    value=$(printenv $var)
    sed -i "s/\($var\s*=\s*\).*/\1$value/" $ENV_FILE
done

echo "Environment variables replaced in .env file."

# Run the default command (php artisan serve)
exec "$@"
