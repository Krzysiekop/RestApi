
# Setup  
*  Copy/clone the files to the server, e.g. local  
*  Add a connection to your own database in the file .env:  ```DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7```  
*  Run ``` composer install ```
*  Run Doctrine migrations: ```php bin/console d:m:m ``` 

# Endpoints:  

*  api/show-orders  
Example  
``` GET http://127.0.0.1:8000/api/show-orders ```
*  api/show-order/{id}  
Example  
``` GET http://127.0.0.1:8000/api/show-order/2 ```
*  api/add-order  
Example  
```POST http://127.0.0.1:8000/api/add-order ```  
 Json body  
 ```
{
    "product": "Pizza",
    "clientFullName": "Jan Kowalski",
    "createdAt": "2020-08-22 12:13:13"
}
 ```
*  api/delete-order/{id}   
Example  
``` DELETE http://127.0.0.1:8000/api/delete-order/1 ```

*  api/edit-order/{id}  
Example  
```PUT http://127.0.0.1:8000/api/edit-order/2 ```
 Json body  
 ```
{
    "product": "Coca-cola",
}
