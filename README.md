## Dice-API Project (v.1)

Dice-API project is a game played with 2 dice, if the sum of the result of the two dice is 7, the game is won. To play, you must first be registered as a user (client role). In case of blank login, the application also include an anonymous client login.
The user can:
 · Play a game (store)
 · See their games with their win rate constantly updated (show)
 · Update their data (update)
 · Delete all their games and restore his win rate to null (destroy)

In the API, there is also an admin role that can see all the players with their games and a average win rate of all players in the application.

$\textcolor{lime}{\textsf{The API was developed respecting the MVC design pattern and also includes a Service layer that helps keep the UserController clean.}}$
 
## How to install
###  · Cloning the repository

 1. Create a new folder on your Desktop and do it "Git Bash Here" (you need to have installed [git](https://git-scm.com/)).
 2. Copy the next command on your GIT tab:
	```
	git clone https://github.com/AlbertLnz/dice-API.git
	```
	
 3. Edit the **example.env** file to **.env** in our files and configure the DB migration:
	```
	DB:DATABASE=db_name
	```
 4. Be sure that you are inside the app:
	```
	cd dice-API
	```
 5. Execute the next command to download the project dependencies (you must have installed [composer](https://getcomposer.org/))
	```
	composer install
	```

 6. Generate a key for the application:
	```
	php artisan key:generate
	```
	
 7. Create the tables of the Database:
	```
	php artisan migrate
	```
	
 8. ***OPTIONAL:*** Let's configurate the admin of the API, a client user and a number of example games:
    

	>8.0. *Default:* </br>
	 · admin -> albert@gmail.com</br>
	 · player1 -> maria@gmail.com</br>
	 · number of games -> 15

	>8.1. To change the admin and 1 user example, let's go to the ***\folder\dice-API\database\seeders\UserSeeder.php*** and change the 'name', 'email' and 'password' attributes.
*If we want, we can delete the user example called Maria per default*
    <img src="https://github.com/AlbertLnz/dice-API/assets/120119395/5f089b29-30fb-4c48-af45-0a1d7c15016e" width="400" alt="Edit UserSeeder">

	>8.2. And to change the games (that will be randomly distributed by the usernames we have defined in the previous step), go to: ***\folder\dice-API\database\seeders\DatabaseSeeder.php***
    <img src="https://github.com/AlbertLnz/dice-API/assets/120119395/5ced4ef3-e379-4e85-88be-cfb587b8d744" width="400" alt="Edit UserSeeder">

9. And now we are going to migrate the configuration made it.
	```
	php artisan migrate:fresh --seed
	```
    
10. Install Laravel Passport creating it's encryption keys:
	```
	php artisan passport:install
	```

11. And finally create Personal Access Token for the users we will create:
	```
	php artisan passport:client --personal
	```
    &#8594; Name of personal access token: **Personal Access Token**
    
    And we copy the **Client secret** generate on terminal and paste it in the **.env** file without quotation marks like the image below:
   	```
	PASSPORT_PERSONAL_ACCESS_CLIENT_ID=3
    PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="Client secret"
	```
    <img src="https://github.com/AlbertLnz/dice-API/assets/120119395/3f978363-95ac-4457-9dfc-24204ee41b24" width="800" alt="Personal Access Client">
    
    :arrow_right: *Every time we do a fresh migrate, we must generate the encryption keys & Personal Access Token (steps 10 & 11)*
        
## Try it in [Postman](https://www.postman.com/)!
Once configurate correctly the project, we can prove it in Postam running a serve:
```
php artisan serve
```
*Every time we do a migrate fresh of our DB, we can generate the encryption keys & Personal Access Token (Steps 10 & 11)*

## Testing
*The realization of testing will behave a creation of Users & Games (do a fresh migrate after test)*
```
php artisan test --filter UserTest
```
<img src="https://github.com/AlbertLnz/dice-API/assets/120119395/ab6c56cf-0f6c-4c6a-96fb-00808788a8db" width="250" alt="Edit UserSeeder">

## Technologies used
### Languages:
![Top Langs](https://github-readme-stats.vercel.app/api/top-langs/?username=AlbertLnz&theme=dice-API)
### Framework & Tools used:
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a></br>
<a href="https://www.postman.com" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/c/c2/Postman_%28software%29.png" width="200" alt="Postman Logo"></a>

## About me / License
· [Github Albert](https://github.com/AlbertLnz) </br>
· [Linkedin Albert](https://www.linkedin.com/in/albert-l-342138178/)

Albert Lanza Rio
