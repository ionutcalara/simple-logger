# simple-api-logger
Laravel based api that can be used to track information in json format

# Getting started

* Clone the repository to the folder set for the application
* Install composer
>`composer install`
* Update the env file with your application information
* Run the migrations and seed
>`php artisan migrate --seed`

# Sending data

* The api gets authenticated via a token you will be able to find in the users table under the api_token column. Just apend that to the query as follows
>`your.url/api/v1/log?api_token=API_TOKEN&tag=TAG&key=KEY&PARAM1=DATA1&PARAMX=DATAX`
* `TAG` is going to be used to entirely separate data one from the other, the same way like a table does, consider it as the table or collection anem
* `KEY` is going to be used as the primary id, in case you need to update something you can set the same key and the info will be overwritten.
* All other params are going to be added as an entry to the data as well as a created param which will automatically be populated with the created data

# Testing 


