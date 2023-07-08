# Documentation

## Registration and login

### Register

`POST /api/register`

Headers:

`Accept:application/json`

Params:

* _name_ - user's name (required)
* _email_ - user's email (required)
* _password_ - user's password (required)

Responses:

**Code 200:** registration successful

Response example:
```json
{
    "status": "ok",
    "message": "Registration successful",
    "data": {
        "token": "2|zzuHZEO0FNyzMX9Z6FfVGldpr8ZxPl8lFRNy22CU",
        "user": {
            "id": 6,
            "name": "user",
            "email": "test@test.com",
            "latitude": null,
            "longitude": null,
            "created_at": "2023-07-08 19:40"
        }
    }
}
```
**Code 500:** error when creating user

Response example:
```json
{
    "status": "error",
    "message": "Error when creating new user",
    "data": []
}
```
**Code 422:** incoming params are not valid

Response example:
```json
{
    "message": "The name field is required. (and 2 more errors)",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

### Login

`POST /api/login`

Headers:

`Accept:application/json`

Params:

* _email_ - user's email (required)
* _password_ - user's password (required)

Responses:

**Code 200:** login successful

Response example:
```json
{
    "status": "ok",
    "message": "Login successful",
    "data": {
        "token": "1|ZCqPKG06sKnM4yjKPQ974phwRsBBNBgFdpMWQu02",
        "user": {
            "id": 3,
            "name": "Glennie Friesen",
            "email": "lbraun@example.org",
            "latitude": "81.319286",
            "longitude": "61.941144",
            "created_at": "2023-07-08 19:19"
        }
    }
}
```
**Code 401:** wrong credentials

Response example:
```json
{
    "status": "error",
    "message": "This credentials are not valid!",
    "data": []
}
```
**Code 422:** incoming params are not valid

Response example:
```json
{
    "message": "The email field is required. (and 1 more error)",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

### Logout

`POST /api/logout`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Responses:

**Code 200:** logout successful

Response example:
```json
{
    "status": "ok",
    "message": "Logout successful",
    "data": []
}
```
**Code 401:** when token is absent, wrong, or is already rejected

Response example:
```json
{
    "message": "Unauthenticated."
}
```

## Routes for weather records

### Get all records

`GET /api/weather-records/all`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Responses:

**Code 200:** get all weather records for current user

Response example:
```json
{
    "status": "ok",
    "message": "",
    "data": [
        {
            "id": 3,
            "created_at": "2023-07-08 15:34:55"
        }
    ]
}
```

### Get single record

`GET /api/weather-records/get`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Params:

* _weather_record_id_ - weather record's id (required)

Responses:

**Code 200:** get details for selected weather record

Response example:
```json
{
    "status": "ok",
    "message": "",
    "data": {
        "id": 3,
        "temp": 22.2,
        "temp_min": 16.7,
        "temp_max": 29.2,
        "feels_like": 22.2,
        "pressure": 1029.63,
        "humidity": 45.7,
        "created_at": "2023-07-08 15:34:55"
    }
}
```
**Code 401:** when token is absent, wrong, or is already rejected

Response example:
```json
{
    "message": "Unauthenticated."
}
```
**Code 403:** user not allowed to get details for selected weather record

Response example:
```json
{
    "status": "error",
    "message": "The user is not allowed to make this action",
    "data": []
}
```
**Code 422:** incoming params are not valid

Response example:
```json
{
    "message": "The weather request id field is required.",
    "errors": {
        "weather_record_id": [
            "The weather request id field is required."
        ]
    }
}
```

### Update single record

`POST /api/weather-records/update`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Params:

* _weather_record_id_ - weather record's id (required)
* _temp_ - temperature (required)
* _temp_min_ - min temperature (required)
* _temp_max_ - max temperature (required)
* _feels_like_ - feels-like temperature (required)
* _pressure_ - pressure (required)
* _humidity_ - humidity (required)

Responses:

**Code 200:** weather record has been successfully updated

Response example:
```json
{
    "status": "ok",
    "message": "Weather record has been updated successfully",
    "data": {
        "id": 3,
        "temp": 10,
        "temp_min": 10,
        "temp_max": 10,
        "feels_like": 10,
        "pressure": 10,
        "humidity": 10,
        "created_at": "2023-07-08 15:34:55"
    }
}
```
**Code 401:** when token is absent, wrong, or is already rejected

Response example:
```json
{
    "message": "Unauthenticated."
}
```
**Code 403:** user not allowed to update selected weather record

Response example:
```json
{
    "status": "error",
    "message": "The user is not allowed to make this action",
    "data": []
}
```
**Code 422:** incoming params are not valid

Response example:
```json
{
    "message": "The weather record id field is required. (and 6 more errors)",
    "errors": {
        "weather_record_id": [
            "The weather record id field is required."
        ],
        "temp": [
            "The temp field is required."
        ],
        "temp_min": [
            "The temp min field is required."
        ],
        "temp_max": [
            "The temp max field is required."
        ],
        "feels_like": [
            "The feels like field is required."
        ],
        "pressure": [
            "The pressure field is required."
        ],
        "humidity": [
            "The humidity field is required."
        ]
    }
}
```
**Code 500:** error when updating weather record

Response example:
```json
{
    "status": "error",
    "message": "Cannot update weather record due to internal error",
    "data": []
}
```

### Delete single record

`DELETE /api/weather-records/delete`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Params:

* _weather_record_id_ - weather record's id (required)

Responses:

**Code 200:** record has been deleted successfully

Response example:
```json
{
    "status": "ok",
    "message": "Weather record has been deleted successfully",
    "data": []
}
```
**Code 401:** when token is absent, wrong, or is already rejected

Response example:
```json
{
    "message": "Unauthenticated."
}
```
**Code 403:** user not allowed to delete selected weather record

Response example:
```json
{
    "status": "error",
    "message": "The user is not allowed to make this action",
    "data": []
}
```
**Code 422:** incoming params are not valid

Response example:
```json
{
    "message": "The weather record id field is required.",
    "errors": {
        "weather_record_id": [
            "The weather record id field is required."
        ]
    }
}
```
**Code 500:** error when deleting weather record

Response example:
```json
{
    "status": "error",
    "message": "Cannot delete weather record due to internal error",
    "data": []
}
```

## Routes for manipulating user profile

### Get user profile data

`GET /api/user/get`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Responses:

**Code 200:** get profile data for current user

Response example:
```json
{
    "status": "ok",
    "message": "",
    "data": {
        "id": 3,
        "name": "Glennie Friesen",
        "email": "lbraun@example.org",
        "latitude": "81.319286",
        "longitude": "61.941144",
        "created_at": "2023-07-08 19:19"
    }
}
```
**Code 401:** when token is absent, wrong, or is already rejected

Response example:
```json
{
    "message": "Unauthenticated."
}
```

### Update user profile

`POST /api/user/update`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Params:

* _name_ - user's name (required)
* _latitude_ - latitude
* _longitude_ - longitude

Responses:

**Code 200:** user profile has been updated successfully

Response example:
```json
{
    "status": "ok",
    "message": "User profile successfully updated",
    "data": {
        "id": 3,
        "name": "new user",
        "email": "lbraun@example.org",
        "latitude": "0",
        "longitude": "0",
        "created_at": "2023-07-08 19:19"
    }
}
```
**Code 401:** when token is absent, wrong, or is already rejected

Response example:
```json
{
    "message": "Unauthenticated."
}
```
**Code 422:** incoming params are not valid

Response example:
```json
{
    "message": "The name field is required.",
    "errors": {
        "name": [
            "The name field is required."
        ]
    }
}
```
**Code 500:** error when updating user profile

Response example:
```json
{
    "status": "error",
    "message": "Cannot update user profile due to internal error",
    "data": []
}
```

### Delete user profile

`DELETE /api/user/delete`

Headers:

`Accept:application/json`

`Authorization: Bearer {{token}}`

Responses:

**Code 200:** user profile has been deleted successfully

Response example:
```json
{
    "status": "ok",
    "message": "User has been deleted successfully",
    "data": []
}
```
**Code 401:** when token is absent, wrong, or is already rejected

Response example:
```json
{
    "message": "Unauthenticated."
}
```
**Code 500:** error when deleting user profile

Response example:
```json
{
    "status": "error",
    "message": "Cannot delete user due to internal error!",
    "data": []
}
```
