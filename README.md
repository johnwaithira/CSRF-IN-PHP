# CSRF-IN-PHP ( Cross-Site Request Forgery )


# [Download Code](https://github.com/johnwaithira/CSRF-IN-PHP/archive/refs/heads/main.zip)

## Table of content

1. [What is CSRF?](#what-is-csrf)
2. [How to implement CSRF token in PHP](#how-to-implement-csrf-token-in-php)
3. [Create a CSRF Token](#create-a-csrf-token)
4. [Run the application int the browser and it should have the following output](#run-the-application-int-the-browser-and-it-should-have-the-following-output)
5. [Conclussion](#conclussion)
# What is CSRF
- ``CSRF`` stands for cross-site request forgery. It’s a kind of attack in which a hacker forces you to execute an action against a website where you’re currently logged in.

`CSRF` is a on



For example, you visit the ``malicious-site.com`` that has a hidden form. And that form submits on page load to ``yourbank.com/transfer-fund form``.

Because you’re currently logged in to the ``yourbank.com``, the request silently transfers a fund out of your bank account.

If ``yourbank.com/transfer-fund`` implements the ``CSRF`` correctly, it generates a one-time token and inserts the token into the fund transfer form like this:

``` html
<input type="hidden" 
       name="token"        
       value="b3f44c1eb885409c222fdb51678dd3a33d3a18dd3">


```
When the ```malicious-site.com``` submits the form, the ``yourbank.com/transfer-fund form`` compares the token with the one on the *yourbank.com‘s* server.

If the token doesn’t exist in the submitted data or it doesn’t match with the token on the server, the fund transfer form will reject the submission and return an error.

When the ``malicious-site.com ``tries to submit the form, the token is likely not available or won’t match.
#
# How to implement CSRF token in PHP


- First, open a new folder in you local server and open it in you favourite IDE. In my case I;m using VS Code.
  
 ![project dir]( /files/open-new-folder.png "Create a new Project in local server folder")

 - ## Create a file and name it `login.php` 
 - ## Then, create a folder and name it `app` 
 - Inside that folder `app`, create 2 files 
    - CSRF.php
    - handleform.php


*Demo*

![Files and folder](/files/files_and_folder.png "Demo of the file and folder created")


# Create a CSRF Token 
- This is gonna be generated by a fuction that we will create inside `app/CSRF.php` file. 

#### `app/CSRF.php`

``` php

<?php
session_start();
class CSRF
{
    public static function create_token()
    {
        $token = md5(time());
        $_SESSION['token'] = $token;

        echo "<input name='token' value='$token' type= 'hidden'>";
    }


}

```

* # Explanation 

``` php
    session_start();
```
`session_start() ` function start a new session use the 


```php
 public static function create_token(){

        #code to be executed
 }

```
`create_token()` function returns the html form input filed

* $token is generated using `md5()` and the `time()` function 
```php
     $token = md5(time());
```

# In the `login.php `, add the following lines of code



```php


<?php 

require_once "./app/CSRF.php";
?>
<h2>Login Form With CSRF token</h2>
<form action="./app/handleform.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <br>
    <?php CSRF::create_token();?>
    <br>
    <input type="password" name="password" placeholder="Password">
    <br>
    <input type="submit" name="login" value="Login">
</form>


```


* # Explanation 

`require_once "./app/CSRF.php";` statement takes all the code that exists in the `CSRF.php` file and copies it into `login.php`

Since we have `CSRF.php` code, we can easily invoke the function `create_token()` by using

```php
 <?php CSRF::create_token();?>
```
which returns 

```html
<input name='token' value='8bd739595053867e1225aa8323effe73' type= 'hidden'>    

```


# Run the application int the browser and it should have the following output

![login.php](/files/run_the_application.png "Results after running the application")

-  view page source

```html

<h2>Login Form With CSRF token</h2>
<form action="./app/handleform.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input name='token' value='d57f0a788ac9518487c254c7e1d2272c' type= 'hidden'>
    <input type="password" name="password" placeholder="Password">
    <input type="submit" name="login" value="Login">
</form>


```

- As you can see, the `<?php CSRF::create_token();?>` outputs the following input field `<input name='token' value='d57f0a788ac9518487c254c7e1d2272c' type= 'hidden'>`



Now that we have added the token, Lets create fuction that will handle the submitted data


- In `app/CSRF.php` lets add another function and call it `validate()`

```php
   public static function validate($token)
    {
        return isset($_SESSION['token']) && $_SESSION['token'] == $token;
    }

```

- You should have the following code `app/CSRF.php`

```php
<?php

session_start();

class CSRF
{
    public static function create_token()
    {
        $token = md5(time());
        $_SESSION['token'] = $token;

        echo "<input name='token' value='$token' type= 'hidden'>";
    }


    public static function validate($token)
    {
        return isset($_SESSION['token']) && $_SESSION['token'] == $token;
    }
}

```


# Explanation


The `validate()` function takes in one parameter ie `$token` and return true if `$_SESSION['token']` is set and `$_SESSION['token'] == $token`

    If one of the contidion if false, the function return `false`
    We can check whether this is valid by using  a fake token
    You can create a new file and name it `test.php`

    Inside test.php, write the following code 
```php
<?php

require_once "./app/CSRF.php";

$token = "hvhvfgcgcfgcdhdjhmwr"; #this is a random text
echo "<pre>";
var_dump(CSRF::validate($token))
echo "</pre>";

?>

```


Now lets run this and see the output


![Test.php](/files/check_if_valid.png "Check return value of validate fuction")

- This returns to false since the token is invalid



*Now lets validate the form*
# In the `app/handleform.php` add the following code


```php

<?php
require_once "./CSRF.php";

if(isset($_POST['login']))
{
    if(CSRF::validate($_POST['token']))
    {
        echo "Continue CSRF token is valid";
    }else 
    {
        exit("Failed to validate the token");
    }
}


```


Since we need the `validate()` function , we require the `app/CSRF.php` file.


`isset($_POST['login']` checks whether a post request is made. If false, the code is skipped.


We validate the token from the form input field with name `token`

```html
<input name='token' value='d57f0a788ac9518487c254c7e1d2272c' type= 'hidden'>

```

If the token doesn't match the already set token which is staored in the `$_SESSION['token']`, it means the request was forged nd should not be processed.

Else if the request is valid, the processing continues

# Conclusion


CSRF attack is a severe threat to web applications. The vulnerability depends on how the HTTP protocol manages web requests and processes. In a CSRF attack, the attacker tricks the authenticated user into performing malicious action on a web application without the user’s knowledge. This causes a significant impact on the victim or the entire web application.

