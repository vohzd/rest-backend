# Rest Api 
##### For intheon.uk/home #####

The homepage is a dashboard a la iGoogle before that was discontinued. Every user has a set of widgets that they have associated with their profile. Each user needs to have a series of 'states' which describe what widget they have, and what metadata (user generated data) is associated with that particular widget.

An example of what widgets are are calendars, to do lists, news feeds, favourite sites, etc.

##### Application flow #####

- User logs in.
- App checks if user has any widgets.
  - If no, prompt to add.
  - If yes, grab and restore widgets and last known state.
- User continues to use app.

##### Concepts and data stores #####

###### User ######

Information about a particular user and its state. 

-	userId  - DatabaseId which is used to uniquely identify user.
-	name – name of the user.
-	states – States define what widgets the user has added, and the userdata they’ve attached to each. An Object which has multiple states inside it.

###### Widget ######

A useful tool such as a todo list, a calendar, news feed etc. All have some form of user data.

-	widgetId - DatabaseId which is used to uniquely identify.
-	name – Name to which this is identified by.
-	pathToCode – All widgets engines and code will be stored in a directory on the server.

###### State ######

The ‘link’ between a user and widget which effectively tells us what position in the app each widget should appear, and what metadata / user data to have the widget parse.

-	stateId – Unique Identified in DB.
-	widgetId – One widget Id.
-	widgetData – A block of JSON this widget should be responsible to parse.

##### Allowed Methods #####

| Method | URI          | Action                     |
|--------|--------------|----------------------------|
| GET    | /api/user    | Returns list of all users. |
| GET    | /api/user/:id | Returns specific user profile. |
| GET    | /api/widget | Returns list of all widgets. |
| GET    | /api/widget/:id | Returns specific widget. |
| GET    | /api/state/:id | Returns specific state. |
| POST  | /api/user | Create a brand new user with a blank profile. |
| POST  | /api/widget | Create a new widget. |
| POST  | /api/state | Create a new state. |
| PUT    | /api/user/:id | Updates specific user profile. |
| PUT    | /api/widget/:id | Updates specific widget. |
| PUT    | /api/state/:id | Updates specific state. |
| DELETE    | /api/user/:id | Delete's specific user profile. |
| DELETE    | /api/widget/:id | Delete's specific widget. |
| DELETE    | /api/state/:id | Delete's specific state. |


##### More to come! #####