<p align="center">
    <a href="https://teknasyon.com" target="_blank">
        <img src="https://teknasyon.com/content/assets/img/logo/teknasyon-logo@2x.png" width="250">
    </a>
</p>

## PHP Challenge

In this PHP challenge, we have an API application which is responsible to **register** devices on this system. managing their **subscriptions** on the applications and **checking** subscription status on demand. 

Also, this application contains a **worker** which is responsible to extend subscriptions of applications when their expiration time has been reached.

The other feature of this applications is the **events** part which are responsible to inform a third party whenever each one of devices **subscribe**(new) or **extend**(renew) their subscription or even **cancel** it.

Finally, there is an API in order to represent a report over to present final status of all subscription in following status of "**New**", "**Expired**" and "**Renewed**".

#### Data Models
You can see this application data model by checking schemas.drawio file which is located in `public` directory:
- [http://localhost:8000/schemas.drawio](http://localhost:8000/schemas.drawio)

#### Running Application
You will be able to run this application with 2 different method of:
- using **Docker**
- using **artisan** (serve)

Before starting each of these two method, you should run ``composer install`` and then:

##### Using Docker
In order to run development environment, go to root directory and run following command in terminal:

``bash build.sh``

The bash code has been written in the way to run migrate command immediately after running environment by itself. By finishing work of this environment, you can run following command to destroy built images:

``bash destroy.sh`` 

##### Using Artisan
###### Configuring .env
Since current `.env` file is configured in order to use by dockers, it is needed to doing some changes in following configuration as will be described in next lines:

STORE_APPLE_HOST="http://localhost:8000/"<br />
STORE_GOOGLE_HOST="http://localhost:8000/"<br />
THIRD_PARTY_HOST="http://localhost:8000/"<br />

Also, there is need to change database configuration which will be described in the next paragraph.

###### Create Database
Firstly, it is needed to create a database exactly based on .env file configuration file.
- Database name: teknasyon
- Database username: root
- Database password: docker

(you can change these configuration files and use the values you prefer in `.env` file).

###### Migrate Database
Secondly, you should run following command in order to have the database ready to work before running your web engine.

``php artisan migrate``

###### Serve Web Engine
Finally, you can serve the web engine by running following command in the terminal:

``php artisan serve``

#### API 
##### Register
[http://localhost:8000/api/v1/register](http://localhost:8000/api/v1/register)

This is a POST method and require following parameter in its body when you are sending request to it.
- **u_id** (UID) which is an integer number.
- **app_id** (AppID) which is an integer number.
- **lang** which is a  string value, represented as the language of application.
- **os** which can be one of two values of "ios" or "android"

Sample:
{
    "u_id": 1,
    "app_id": 3,
    "lang": "tr",
    "os": "ios"
}

The method will return a json body in its response which include of following parameter:
- **result** which will be one of "OK" or "NOK" values, when  it  is "OK", it means the API works successfully and when the result is "NOK", means something went wrong.
- **message** which always include a text message to explain what happened during of executing API call.
- **client_token** which is the unique Client Token related to this UID and AppID, please note that this *TOKEN* will be used in the next steps.

Sample: 
{
    "result": "OK",
    "message": "the device successfully has been added to the DB",
    "client_token": "6623bbd9-49ed-4127-a23b-a50c84b69a9d"
}

##### Purchase
[http://localhost:8000/api/v1/purchase](http://localhost:8000/api/v1/purchase)

This is a POST method and require following parameter in its body when you are sending request to it.
- **client_token** which is an UUID parameter retrieved from [Register] API previously.
- **receipt** which is a hashed string parameter representing all the receipt data in the hashed code.

Sample:
{
    "client_token": "6623bbd9-49ed-4127-a23b-a50c84b69a9d",
    "receipt": "aab206e2dd33aecd3bdfd3308e7ed1c1"
}


The method will return a json body in its response which include of following parameter:
- **result** which will be one of "OK" or "NOK" values, when  it  is "OK", it means the API works successfully and when the result is "NOK", means something went wrong.
- **message** which always include a text message to explain what happened during of executing API call.

Sample: 
{
    "result": "OK",
    "message": "subscription for 6623bbd9-49ed-4127-a23b-a50c84b69a9d successfully registered till 2021-05-16 22:01:07"
}

##### Check
[http://localhost:8000/api/v1/check](http://localhost:8000/api/v1/check)
This is a GET method and require only client token in its query string when you are sending request to it.
- **client_token** which is an UUID parameter retrieved from [Register] API previously.

Sample:
[http://localhost:8000/api/v1/check?client_token=6623bbd9-49ed-4127-a23b-a50c84b69a9d](http://localhost:8000/api/v1/check?client_token=6623bbd9-49ed-4127-a23b-a50c84b69a9d)

The method will return a json body in its response which include of following parameter:
- **result** which will be one of "OK" or "NOK" values, when  it  is "OK", it means the API works successfully and when the result is "NOK", means something went wrong.
- **status** which represent current status of that client token.

#### WORKER
The worker is inherited from Laravel command class, located in Console\Commands\WorkerCommand.php, this command is running every night at **00:30** by a cron job which is calling Laravel console kernel every minutes.

For testing purpose, you can run following command in terminal:

If you are running application on a docker run:
``bash worker.sh``

else do this:
``php artisan worker:run``

#### CALL BACK
On each change of subscriptions status, one of the following events will be raised automatically. the listener will be pushed in the queue then a consumer will pick them one by one and execute them after.

For testing purpose, If you are running application on a docker, please run following command on your terminal:
``bash queue.sh``

else do this:
``php artisan queue:work``

***Please Note*** that in production a cron job will run laravel scheduler each minutes and a supervisor service will consume all the queued items automatically, so running previous command is only for the test purposes.

Also, Since this is a test, I did not change the queue names, because of difficulty to run consumer and setting queue names on that. so I just used `default` queue name but for sure in a real application setting different name in order to create the priority over running them is a must.

#### REPORTING
To have simple report, you use following link to see the report result in a json format, please note that the results are cached for 1 minutes, so it means you will have 1 minute dirty data on each request of report end-point.

[http://localhost:8000/api/v1/report](http://localhost:8000/api/v1/report)
