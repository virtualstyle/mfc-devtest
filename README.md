# Marketing for Change DevTest

This repo is built for a developer test to the requirements in [REQUIREMENTS.md](REQUIREMENTS.md) at the root of this repo.

### Prerequisites

- [PHP](https://www.php.net/manual/en/install.php)
- [Composer](https://getcomposer.org/doc/00-intro.md)
- [Docker](https://docs.docker.com/install/)
- [Vue.cli](https://cli.vuejs.org/)
- [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
- [node/npm](https://nodejs.org/en/) or [yarn](https://yarnpkg.com/lang/en/)

### Dev Environment

**WARNING:** *I have committed the .env file for Laravel and Vue, and removed the .env entry from the gitignore files to ease spinning up the dev containers.*

**NOTE:** *All of this was done on a Mac. Docker and various CLI tools have some weirdness on Windows machines. If you're on Windows, hopefully you have resolved many of the issues with the dev tools used here.*

**NOTE:** *The seeded companies will cause errors when edited because the images are urls and not uploaded files. Testin g company edits should be done with companies created by users rather than the seeded values.*

**NOTE:** *This code is kinda sloppy. Starting a project to me, is where most of the heavy lifting happens, trying out packages and discarding them, testing strategies, and getting the most solid and adaptable foundation possible. Really, I expect project starts to take a few weeks or more, since getting the foundation right is the mosty important part of development. Unless you're just grinding out boilerplate... then just create-react-app and accept the limitations, I suppose...*

#### Clone the repo, start containers, migrate/seed data:
These commands execute artisan commands in the php container, which has php-cli and access to both our database and our Laravel code directory. These could also be executed in the local Laravel root directory, but it felt like there was some weirdness sometimes, so I just handled it through the container.
```bash
git clone https://github.com/virtualstyle/mfc-devtest

cd mfc-devtest/services/mfc-devtest-ui/www
yarn #Or npm install
yarn build #Or npm rum build

cd ../../../services/mfc-devtest-api/www/default
#Note that composer is an alias to php composer.phar. Check their install docs if needed.
composer install

cd bootstrap
mkdir cache
cd ..

docker-compose up

#In a new terminal:
cd mfc-devtest/services/mfc-devtest-api/www/default
#Migrate and seed DB
docker-compose exec vs-php php /usr/share/nginx/html/default/artisan migrate
docker-compose exec vs-php php /usr/share/nginx/html/default/artisan db:seed

```

### Navigate to the server(s)

The docker-compose command will output messages from our containers. If no errors pop up, you should be able to navigate to mfc-devtest-ui.localhost and see the ui. The API should be running at mfc-devtest-api.localhost. The DB will be seeded with plenty of mock data.

### Primary Source Code

The most relevant source code areas are the Laravel API root folder at [/services/mfc-devtest-api/www/default](/services/mfc-devtest-api/www/default), and the Vue.js UI root folder at [/services/mfc-devtest-ui/www](/services/mfc-devtest-ui/www).

### Running for Development

For development, I found it easier to enter the UI directory and just run `yarn serve`, after running `docker-compose up` because running yarn build and rerunning docker-compose takes longer than respinning the dev server from yarn.

Postman is invaluable for interacting with the API. You can simply hit the login endpoint (http://mfc-devtest-api.localhost/api/auth/login) and enter the data to get an auth token that can be pasted into Postman's Authorization Bearer Token field, and then run requests against any other endpoints.

### Useful Commands
```bash
#Wipe and recreate/reseed db
docker-compose exec vs-php php /usr/share/nginx/html/default/artisan migrate:fresh --seed

#Clear Laravel cache
docker-compose exec vs-php php /usr/share/nginx/html/default/artisan config:cache

#Wipe all project containers
docker-compose rm -v

##Rebuild container
docker-compose up --build



```

#### TODO

##### Production and other environment config

The current container build process is dev only. An environment variable should be checked, and then an override file should be passed with -f to the docker-compose command. These files will need to be created. The UI should also have prod/test/staging config setup. The DB seeding will also need to be configured differently for any prod environment, since there is a significant amount of seeding done for dev. Additionally, CORS config would need to be setup correctly for production, as it's all wildcards for dev right now.

##### Environment Indicator

I find it generally best to add a badge or other indicator to inform users (particularly devs) which environment they are on in the UI. Just a small hint that can prevent mistakes and time wastes.

##### Token Expiration Detection UI Side/Token Refresh

The UI should use a timeout to poll the token to detect when it expires, and put an overlay on the UI with a link to refresh the token and/or log in again.

##### Mobile/Responsive Design

The UI uses Bootstrap, and is ostensibly already responsive, but needs to be looked through and breakpoints added to improve display on smaller screens. Currently only large screens are sure to work well. The navbar collapse function of Bootstrap (and modals) is broken in Vue, and there wasn't time to search for packages that work.

##### Paginator size limit

The paginator right now will just spew out a button for every page, even if there's 100k of them. It should be limited to a spread, like any other paginator.

##### Automatic linting and formatting

I tried to keep up with formatting and linting, but it would be far better to install some packages that will handle that automatically on save or commit, to ensure standards are consistently kept.

##### Garbage Collection on Temp Uploads Folder

Since the Dropzone uploader uploads the file before the DB record is created, it's possible that the temp folder will have orphaned files. This is why I made the temp folder, moving the files to logos when the record is created/saved in the DB. But I didn't get around to adding a garbage collection cronjob. Could probably just delete all files more than a day old in there on a daily basis.

##### Improved Error Handling

The error handling is a little blunt, terminating the session on pretty much any API or other error. This needs to be improved to maintain the session except when there may be stability or security issues arising from the error.

##### Security

ReCaptcha on the login, xsrf tokens, and more could/must be done. This is just a dev app, but I would have liked to have had the time to properly go through all the security config.

##### Better User Feedback

Currently, after a successful action, the app is just returning to the list pages. This could be improved either by better messaging, going to the correct page to show the item, or implementing show pages to redirect to rather than the lists.

##### Refactoring & Commenting

There is some repetitive code that should be abstracted and refactored, and some of the trickier areas should be commented.
