# MultiThread Library
> MultiThread Is A PHP Library That Allows Developers To Make Multiple Requests To SQLite Database Which Means You Can Use SQLite for More Stuff Now
## How It Works
> Using Core PHP Magic
## Example ?
### Enable MultiThread On A Database:
```php
require 'vendor/autoload.php';
// Enabling MultiThread By Putting The Database File Path and the Query String And Disabling the Request by setting it to false and Enabling MultiThread on The Database By Setting It To true and choosing the limit which is by default is 3 (3 means only 3 request will made)
$start = new MultiThread("database path", "The Query String Here", false, true, 3);
```
### Using MultiThread:
```php
require 'vendor/autoload.php';
// Make New MultiThread Object and Setting The Request Boolean to true
$call = new MultiThread("database path", "The Query String Here", true);
$waitingnumber = $call->call("database path", "The Query String Here");
// sleep for 5 seconds or more depending on how busy is your website to wait for 3 (or the limit you made) requests
sleep(5);
// Get The Query Output (in a array)
$output = file_get_contents("path/to/src/ids/shared-" . $waitingnumber . ".json");
while ($row = $output){
// do stuff
}
```
# Made By SolDeveloper.
> I Hope This Library would help someone
