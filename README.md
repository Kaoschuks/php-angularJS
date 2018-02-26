# php-angularJS
Web framework using php and angularJS using api centric architecture and mvc. Contains api, admin, and website modules

# README #

This README would normally document whatever steps are necessary to get your application up and running.

### What is this repository for? ###

* Quick summary
This is the repository for the project version project containing all its part including -:
api - api server folder containing all its logics, engine - Contains libraries, configurations, and core 
entities of the project
website - base website with user accounts
cdn - contains the assets (css, js, images etc) for each core domain of the biddgh project
* Version
   1.0.0

### How do I get set up? ###

* Summary of set up
	"Engine" contains the framework core and genaral entities like libs, modules entities, configs, logs, tmp files, user data history log files etc
  "Admin" conatin a dummy admin for controlling the framework data written in php and angularJS
  "Website" is used for creating website or web application creation. It is written in php and angularJS
  "Mobile" is used for same funcationality like "website" but is written in php only
  "Api" this is the api gateway for the framework which is consumed by the admin or web application written in php.
  "CDN" this contain the assets files for web application of the framework's Admin, Website and Mobile
	 
* Configuration
   includes changing folder name and file data to neccessary 
   website/.htaccess - change /"foldername" to whatever folder name you changed to the folder to.
   config/modules/  - change website folder here to whatever name you change the website folder to. 
   						Also change every file data url from .../Website to new name
   
* Dependencies
	No depency required except php and mysql in your favourite server pack like xampp
	
* Database configuration
    In engine/libs/DB/db.conf
	 change the to neccessary config of your development server
	 
* How to run tests
     To run test use 
	 php unit test tools for unit testing
	 php benchmarking tools or apache benchmark tool for stress and load test
	 security test use various web application ttols like vega etc
	 
* Deployment instructions
   Contact kaosemeka@yahoo.com
   
### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Any question contact kaosemeka@yahoo.com
