# About
Getting acquainted with Symfony 4 using LinkedIn Learning tutorial
Topics covered:
1. Build pages using Twig Templates
2. Create Services with Service Container
3. Work with Data using Doctrine ORM
4. Authentication Middleware (Implement token through subscriber)


The files are hosted inside the container under /App/myapp/
To pull this code, inside the container, run the following command  
```shell
    #git 
    git clone https://github.com/wwangsa/learnSymfony4.git myapp

```

To run the application, in browser
* https://localhost:8000
* https://localhost:8000/api/rooms
* https://localhost:8000/api/rooms?token=token1


Learning Symfony 4 without having to concern spending much time in the environment. 
# Setting up the environment

## Prerequisites
* Docker
* VS Code with Docker and Symfony extensions

## Sharing the existing folder with other containers using bind mount below
```shell
    #We're going to share the learnSymfony folder between the host and container
    mkdir /Users/user/Codes/docker-fs/learnSymfony
    docker pull bitnami/symfony:1.5.11
    docker run -it -d -p 8000:8000 --name learnSymfony -v /Users/user/Codes/docker-fs/learnSymfony:/app bitnami/symfony  
```

#### Notes
* /app/myapp is set as the default web location. In order to change my app, has to build the container usind Dockerfile
* Developer's note: 
    * To access bash inside the container's command prompt, run the following from the host. This will be referred throughout the 
    ```shell
        docker exec -it learnSymfony /bin/bash
    ```
    * If the files have been edited before with visual studo code, make sure to remove the workspace after removing the default myapp folder by going to cmd+shift+P "Workspaces: Remove Folder from Workspace", then don't save it. Open the new myapp folder immediately and it will overwrite the old one. If it doesn't work, go inside the container and wipe ~/.vscode-server
    ```shell
        rm -rf ~./vscode-server
    ```
    * By default, the bitnami containers created a symfony/skeleton project (for microframework). The course requires full framework
    ```shell
        rm -rf /app/myapp
        composer create-project symfony/website-skeleton myapp
    ```
    * If postgres is not needed, comment out the connection string in .env file under myapp to prevent exception occured in driver or remove doctrine completely if database connection is not needed by running the following command inside the container
    ```shell
        composer remove doctrine/doctrine-bundle doctrine/doctrine-migrations-bundle
    ```

## Spinning of MySQL container
Not sure how to resurrect mysql inside bitnami container. Easier way is just to spin off another one
``` shell

    docker pull mysql
    docker run -p 3306:3306 --name symfonyMySQL -e MYSQL_ROOT_PASSWORD={replace_me} -d mysql:latest
    docker exec -it symfonyMySQL /bin/bash  
    > mysql -u root -p
    SQL > CREATE USER 'symfonyUser'@'%' IDENTIFIED by '{replace_me}';
    SQL > GRANT ALL PRIVILEGES ON *.* TO 'symfonyUser'@'%';
    SQL > FLUSH PRIVILEGES;
	#How to find the ipaddress for this MySQL container
	docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' symfonyMySQL
```

# Reference
* [Learning Symfony 4 with Mike Oram](https://www.linkedin.com/learning/learning-symfony-4/)
