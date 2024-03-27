# README
This PHP script allows you to query and summarize services based on country codes from a CSV file.

## Requirements:
PHP 7 or above installed on your machine

Composer for installing necessary dependencies

The script expects the **services.csv** file to be present in the project directory.

## Installation:
Download the repository to your local machine

Navigate to the **working directory** in the terminal

Next, run the command `php composer.phar install` This will install any necessary vendor binaries needed

## Usage
To run the script, use the following command: `php queryService.php <command>`

## Available Commands:

service:summary - Provides a summary of the number of services being run from each country in the services.csv file

service:query <country_code> - Filters out all services being run in the specified country along with their centres.



