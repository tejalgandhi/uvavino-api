## About The Project 

This project will be a market place to buy and sale different types of wine and also arrange an auction of the wine at initial stage.

Eventually, we plan to have wine clubs and it's membership, which awail you to have different discounts on certain wine and other community based feature. we also plan to have some deals with NFT in future.

## Project prerequisits
- PHP 8 or higher should be installed
- Composer should be installed
- MySql setup should be there
- Make sure you have installed Git and gitflow
- Code editor (preferabally VS Code)
- Make sure you have installed insomenia for trying out api

## Project setup locally

- Create database for this project for example `uvanino`
- Clone the repo `git clone git@github.com:immera/uvavino-api.git`
- Go to the repo directory `cd uvanino-api`
- Install dependancies `composer install`
- Copy environment file `cp .env.example .env`
- Change all the variables as needed in `.env` - for example change database name to `uvanino` as we created at the start
- Run migration `php artisan migrate`
- Run server `php artisan serve` -> Now your API will be running on `http://127.0.0.1:8000`

**Few things that are out side the development flow**
- to try out the api, you can open insomenia and upload insomenia file (which is present in the project root) and try out all the different apis
- there is a `api-docs.yaml` file, which you can use for generate api documentation in the swagger editor `https://editor.swagger.io/`

## How to contribute
- Once you choose task from the Jira to be working on, make sure you assign that task to your self (in the Jira). also make sure it's right priority task (if there is high priority task present which you can pick then don't pick medium or low priority task)
- Read that task well, and understand what you need to do in order to complete that task
- If you have any question and query regarding the understanding feel free to ask the reporter of that task
- If you have any technical queries or need technical help you can ask any of the senior people in the project.
- Once you done with all the above things and ready to write code, then create right branch for that task using gitflow. for example: `git flow feature start [[feature-name]]`
- then you make your code contribution and once you setisfied with you solution, make sure you added enough testcases for the same.
- Once all set and ready to push, then you need to run the following command to verify your code will be accepted or not.
  - `php artisan test --coverage` - make sure this will reach to the expected coverage percentage.
  - `./vendor/bin/phpstan` - make sure you don't have any error here too.
- Now you are ready to push, do push to your branch.
- Raise PR to the `develop` branch, make sure you explain your work in detail in the PR description.
- If any correction or changes comes back then you need to work accordingly.
- Keep reminding the reviewer untill your PR is merged.
- Your PR is merged then, youueeee..... you done that task.