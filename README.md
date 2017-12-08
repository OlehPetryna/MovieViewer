MovieViewer - web-application created for simplifying process of storing information about movies.

MovieViewer provides next features: 
 - showing list of stored movies in alphabetical order.
 - showing detailed information about movie. 
 - adding new movies, editing and deleting already existing movies.
 - mobile-friendly design.
 - finding movies by it's title or by actor's name.
 - importing movies from txt file.
 
Local deploy:
 - 1.Run start.sh from it's directory and provide it 4 arguments in next order - host for MySQL database, database name, database username, database user's password.
 You should provide already existing database and user.
 
     start.sh will:
      - drop old Movie table if it's exists.
      - create new Movie table in specified database and run PHP web-server locally on 8000 port.
 
   example run: 
               
                cd /path/to/project/
                ./start.sh dbHost dbName dbUser dbUserPassword
                
 - 2.Fill information about your database in /src/config/config.php.
 - 3.Visit localhost:8000 to see index page. 



Application architecture:
 - MovieViewer uses/implements MVC design pattern.
 
Base classes\scripts functionality: 
 - Entry script - index.php - accepts all requests, initializes instance of App class with provided config file and runs the application. 
 - App class is responsible for registering autoload function, initializing and saving in memory config file (+ creating instances of classes components)
 (you can access all of config properties presented as array via App::$app call), running needed Controller's action and returning response.
 - app\base\Router - responsible for routing.
 - app\base\Controller, app\base\View, app\base\Model - base classes for all Controllers, Models, Views. 
 - app\base\Connection - provides PDO instance with data from config.
 - app\base\ErrorHandler - shows error page with provided code\message.
 - app\components\DbQuery - wrapper to work with database.
 - app\controller\SiteController - handling all requests associated with Movies
 - app\model\MovieModel - represents Movie table, contains necessary logic.
 - js/main.js - contains all client-side logic (click handlers, ajax, popup function)

  
