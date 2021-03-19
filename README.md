## Recipe App

## Preface
The recipe app is an api that aims at attracting people together for the sole purpose of sharing recipes.
<br/>
## How it works
 A visitor can go through the website to see a list of shared recipes.
To add a recipe, the visitor is required to:
  - create a account by signing up with a username, email and password
  - click on the confirmation link sent to the email to activate the account

After which such user can post, edit and delete all posts associated with the account. However, a user can only edit a post that has not been 
Approved by the admin.

## Languages used:
 - php (laravel)
 - MySql

## Installation procedure:
 - run composer install
 - configure your .env file
 - run php artisan migrate
 - php artisan db:seed.      This will insert a default admin username and password

    - admin email:  admin1@admin.com
     - password: passwordD123
### Routes:
### BaseUrl: http://127.0.0.1/api/v1

- /register : POST  : registers a new user  <br/>
  {  <br/>
   &emsp; name : "enter your username", <br/>
  &emsp;     email : "enteryouremail@gmail.com", <br/>
  &emsp;  password : "passwordS123" <br/>
  }
<br/>
  
- /verify_account/{token} : GET  : uses to verify user email

- /login : GET : logins a user <br/>
  &ensp;{  
  &emsp;&emsp;email : "enteryouremail@gmail.com", <br/>
  &emsp;&emsp;password : "passwordS123" <br/>
   }

  

- /logout : POST : logs a user out <br/>


- /recipes : GET : displays a list of recipes


- /recipes/recipe/{id} : GET : Displays a particular recipe by id


- /recipes/search : GET : Search for recipes by title and recipe name <br/>
   {  <br/>
  &emsp;&emsp;search : "search term", <br/>
   }

### Profilee routes (user token must be set in header to access these routes)

   #### BaseUrl: http://127.0.0.1/api/v1/profile

  - '/' : GET : displays all profiles
    
  - /create : POST : create a new profile using form enctype of multipart/form-data
      #### fields:
      &emsp; first_name <br/>
      &emsp; last_name <br/>
      &emsp; phone <br/>
      &emsp; country <br/>
      &emsp; profile_picture <br/>
   
     
  - /update : POST : updates user profile ( used post as a result of laravel patch issues)
    #### fields:
    &emsp; first_name <br/>
    &emsp; last_name <br/>
    &emsp; phone <br/>
    &emsp; country <br/>
    &emsp; profile_picture <br/>

### Recipe routes (user token must be set in header to access these routes)

#### BaseUrl: http://127.0.0.1/api/v1/recipes

    - '/' : GET : displays all recipes

    - /postrecipe : POST : create a new recipe <br/>
        { 
            "recipe_name": " africa dish", 
             "title": "making africa dish",
            "description": "this is the description making africa dish",
            "recipe_picture": "/xyx/fng.png",
            "ingredients": "water, oil, rice",
            "nutritional_value": "water, oil, rice",
            "cost": "25000",
            "primary_ingredients": "water",
            "main_ingredients": "rice",
            "meal": "lunch"

        }<br/>
     
    
    - /update/{id} - updates a user recipe by id
     { 
            "recipe_name": " africa dish", 
             "title": "making africa dish",
            "description": "this is the description making africa dish",
            "recipe_picture": "/xyx/fng.png",
            "ingredients": "water, oil, rice",
            "nutritional_value": "water, oil, rice",
            "cost": "25000",
            "primary_ingredients": "water",
            "main_ingredients": "rice",
            "meal": "lunch"

        }

    - /delete/{id} - deletes an unapproved recipe by id

### Admin routes (user token must be set in header to access these routes)

#### BaseUrl: http://127.0.0.1/api/v1/admin

    - /users : GET : gets a list of all users

    - /users/{id} : GET : Gets a specific user by id

    - /recipes/status/approved : GET : Get all approved recipes

    - /recipes/status/unapproved : GET : Get all unapproved recipes

    - '/recipes/{id}/authorize : POST : approves or disapproves a post
       {
            "approved" : false,
            "comment" : "I have decided not to approve this because its fake"
        }
     - /recipes/{id}/delete : DELETE : deletes a post by id

     - '/recipes/{id} : GET : retrieves a particular recipe by id

     - '/recipes' : GET : retrieves all recipes

