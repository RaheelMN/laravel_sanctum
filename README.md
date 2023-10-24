Project Name: Laravel_sanctum
Project Type: Back end REST API with authentication using Laravel Framework
Front End: Postman
Youtube Tutorial: Laravel API Crash Course With Sanctum 
Youtuber: Code With Dary

Project Summary
This project is divided in to two parts. First deals with User authentication and second deals with User tasks. 
Laravel uses bearer token to authenticate the user. 
In project all routes are processed in api.php file. It has two types of routes one public and second private.
In public routes users can login and register whereas in private routes user can logout or perform tasks.
 
 Front End
1 Postman
First new workspace is created named Laravel_sanctum. Than two collections are added. One Authentication and second Tasks.
1.1 Authentication collection
In Authentication collection following requests are included. 
Register request. Type post. Token not required
Login request. Type post. Token not required.
Logout request. Type post. Token required.
1.2 Tasks collection
All requests require bearer token. Token is saved in Environment tab’s global variables section.
Following requests are included
List tasks
Type get.
List all tasks that are part of user with bearer token.
Task Detail
Type get. 
URL include id of task. It gives detail about task.
Add Task
 Type post
User add new task 
Update Task
 Type put
User updates all required fields
Update Some Task
 Type patch
User updates some fields
Delete Task
Type delete
User deletes task.

BackEnd Laravel Framework
Database: Laravel_sanctum
Tables: User,Tasks,password_access_tokens
2 Laravel Database
Laravel database includes migrations, seeders and factory folder.
2.1 Mirgration
In migration folder two tables are created using make:model ModelName –all command.
In Users table following columns are included: id,name,email,password,created_at,updated_at.
Where email is unqiue.
In tasks table following columns are included: id,user_id,name,description,priority.
Where user_id has act as foreign key to users table’s id column. Name is unique. 
2.2 Factory and Seeding
In User and tasks factory files we define fake data to populate table.
2.2.1 Task Factory
 Task name and descriptions are fake sentences and text. User_id is selected from User table randomly.
Priority is chosen randomly by using randomElement method.
2.2.2 Seeding Tasks and Users
Users and Tasks tables have relationship of one to many defined in their model file.
When we seed user table we use User::factory()->count()->hasTasks()->create() command
Where count refers to number of users created and hasTasks referes to number of tasks for each users.
In dataseeders.php file we use $this->call([UserSeeder::class]) command which is executed when in cli we use –seed command.
2.3 Models
Two models Task and User are created.
They have property $fillable which dictate which columns will be used when data is inserted or updated in table.
2.3.1 User Model
User model has property $hidden which includes columns that are excluded when we retrieve data from database.
To use token with User model we include following command
Use hasApiTokens within class. We can now use Auth::user() to retrieve user object related to token.
User model has function tasks which return $this->hasMany(Task::class). It provides one to many relation with task model.
2.3.2 Task Model
It has users function which return $this->belongsTo(User::class). It provides many to one relation with user model.
2.4 Routes
As this application acts as backend server therefore it only interacts as REST API. All communication is in json format. All uri include version of application api/v1. 
In api.php all routes are grouped by prefix v1.
Routes are further divided into middleware auth:sanctum for protected route and public route.
All protected routes can only be accessed by valid bearer token. 
All routes are passed to either AuthController or TaskController.
2.5 AuthController
It deals with user registration, login and logout.
When user login server return user information and token. Token determines access to operation e.g. create, update, delete on database.
2.5.1 Login function
When user login through Postman. Its body include email and password. The arguments are received by LoginUserRequest object. It is type of formRequest object. Authorisation is set to true in formrequest.
Validation is performed on input and return to login function.
Then using Auth class input is sent to database to verify user. Then new token is generated and sent  with user information. 
In response user data is passed to UserResponse class which determines which fields of user table and in which format to be sent back.
2.5.2 Register function
When user register through Postman following fields have to be filled. Name, email, password, password_confirmation. It is received by StoreUserRequest object which inherits formRequest class.
The authrisation is set to true then input is validated. 
Name is required, it should be string and less than 256 characters.
Email is required, it should be unique and checked against User table. It should be in email format.
Password is required, it is matched against password_confirmation variable. It should have minimum of 6 characters.
After validation data is returned to register function.
User information is stored in database using create method. Password is stored in hash format using Hash::make() method.
New token is created and returned to user.
2.5.3 Logout Function
In postman when user sends logout request it is received by logout function. 
First token is retrieved by Auth::user() object. Then it is destroyed and success message is sent to browser.

2.6 TaskController
All apiResource routes to tasks are forwarded to taskcontroller.
2.6.1 Index function
This function return list of tasks associated with user in task folder. It uses TaskCollection class which receives collection of tasks from Task table where Auth::use()->id equals to tasks user_id column.
Collection is passed through TaskResouce where additional fields from user table are added and data is formatted in array form. This data is returned to browser as list of tasks.
2.6.2 Store Function
When user adds new tasks this function is used. Tasks parameters such as name, description and priority are filled. Data is stored in StoreTaskRequest class’s object which inherits formRequest class. 
First user authority is checked. If user token has create permission or not. Then input is validated.
Name should be unique in database table. Description is required and priority is within Rule::in([]).
Then validated data is returned to store function.
New task is added to database using eloquent create method.
2.6.3 Show function
When user request to get detail of single task then is function is used. Task id is sent in url. Id is received by Task class object. Laravel retrieved data against id and stores in Task’s object. 
First task object is passed to checkAuthorisation method to check whether task id is associated with user. If not then 402 unauthorize error is returned. 
This object is passed to TaskResource class which return task detail in array form after formatting.
This data is returned by server.
2.6.4 Update function
When user updates tasks then this function is called. First the data is passed to UpdateTaskRequest object. Then user authority is checked, that is if user is authorized to update database. Then method type is checked whether request is put or patch. If it is put then all required fields validated else some fields are validated. 
Name should be unique therefore if updated name already exist for some other record then error is generated.
After validation data is returned to update function.
Then data is updated in database using update method of Task object.
2.6.5 Delete Function
When user wants to delete a task then this function is called. Task id is sent in url. 
First checkAuthorisation method is called to verify that task id is associated with user of bearer token.
Then task is deleted .
