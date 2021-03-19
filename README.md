## Recipe App

### Preface
The recipe app is an api that aims at attracting people together for the sole purpose of sharing recipes.
<br/> 

# How it works
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


### Routes:
### BaseUrl: http://127.0.0.1/api/v1
<br/>

- /Register : POST  : registers a new user
   -{  
      -- name : "enter your username",
      -- email : "enteryouremail@gmail.com",
      -- password : "passwordS123"
   -}


- /verify_account/{token} : GET  : uses to verify user email

- /login : GET : logins a user
   -{  
      
      -- email : "enteryouremail@gmail.com",
      -- password : "passwordS123"
   -}

- /logout : POST : logs a user out

- /recipes : GET : displays a list of recipes

- /recipes/recipe/{id} : GET : Displays a particular recipe by id

- /recipes/search : GET : Search for recipes by title and recipe name 
   -{  
      -- search : "search term",
   -}

### Recipe routes (user token must be set in header to access these routes)

   #### BaseUrl: http://127.0.0.1/api/v1/profile

  - '/' : GET : displays all profiles
  - /create : POST : create a new profile using form enctype of multipart/form-data
     -- #### fields:
      -- name
      -- email
     
  - /update : POST : updates user profile ( used post as a result of laravel patch issues)


### Recipe routes (user token must be set in header to access these routes)

#### BaseUrl: http://127.0.0.1/api/v1/recipes

    - '/' : GET : displays all recipes
    - /postrecipe : POST : create a new recipe 
     -{  
      -- name : "enter your username",
      -- email : "enteryouremail@gmail.com",
      -- password : "passwordS123"
    -}
    
    - /update/{id} - updates a user recipe by id
    - /delete/{id} - deletes an unapproved recipe by id

### Admin routes (user token must be set in header to access these routes)

#### BaseUrl: http://127.0.0.1/api/v1/admin

    - /users : GET : gets a list of all users
    - /users/{id} : GET : Gets a specific user by id
    - /recipes/status/approved : GET : Get all approved recipes
    - /recipes/status/unapproved : GET : Get all unapproved recipes
    - '/recipes/{id}/authorize : POST : approves or disapproves a post
       - {
       -}
     
     - /recipes/{id}/delete : DELETE : deletes a post by id
     - '/recipes/{id} : GET : retrieves a particular recipe by id
     - '/recipes' : GET : retrieves all recipes

