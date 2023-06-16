# PROJECT SETUP
This guide will help you set up your project using MySQL 5.6 for data storage, Google Cloud Storage for storing picture data, Laravel as the backend framework, and Google App Engine for hosting your application.

## Prerequisites
Before getting started, make sure you have the following prerequisites installed:

- PHP (>= 7.4)
- Composer
- MySQL 5.6
- cloud storage

## Installation

Follow these steps to set up your project:

1. Clone the repository:
```
git clone <repository_url>
```
2. Navigate to the project directory:
```
cd <project_directory>
```
3.Install project dependencies using Composer:
```
composer install
```
4. Set up the MySQL database:

- Create a new database in MySQL:

  ```
  CREATE DATABASE your_database_name;
  ```

- Configure the database connection in Laravel. Open the `.env` file and modify the following lines:

  ```
  DB_CONNECTION=mysql
  DB_HOST=your_database_host
  DB_PORT=your_database_port
  DB_DATABASE=your_database_name
  DB_USERNAME=your_database_username
  DB_PASSWORD=your_database_password
  ```

5. Set up Google Cloud Storage:

- Create a new Google Cloud Storage bucket for storing picture data.
- Generate a service account key in the Google Cloud Console with access to the storage bucket.
- Save the service account key JSON file in a secure location.

6. Configure Laravel to use Google Cloud Storage:

- Open the `.env` file and modify the following lines:

  ```
  FILESYSTEM_DRIVER=gcs
  GOOGLE_CLOUD_PROJECT=your_google_cloud_project_id
  GOOGLE_CLOUD_KEY_FILE=absolute_path_to_your_service_account_key_json_file
  GOOGLE_CLOUD_STORAGE_BUCKET=your_google_cloud_storage_bucket_name
  ```
- Open the `app/filesystem.php` file and modify the following lines under 'S3':
```
'gcs' => [
            'driver' => 'gcs',
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'key_file' =>env('GOOGLE_CLOUD_KEY_FILE', base_path()."\yourfilekeyname.json"),
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'yourstoragebucket'),
            'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', null),
        ],
 ```
 - Open the `app/service.php` file and modify the following lines under 'ses':
```
 'google' => [
        'storage' => [
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'projectid'),
            'key_file' => env('GOOGLE_CLOUD_KEY_FILE', base_path()."\yourfilekeyname.json"),
        ],
    ],
 ```
7. Run database migrations:
```
php artisan migrate
 ```
 
8. deploy your app with app engine and choose your location
```
gcloud app deploy
```


# BACKEND API DOCUMENTATION
## REGISTER
    URL             : /register
    Method          : POST
    Params          :   name as string
                        email as string, must be unique
                        password as string
                        age as integer
                        province as string
                        city as string
    Response        : 
                        {
                            "message" => "User has been regist"
                        }
## LOGIN
    URL             : /login
    Method          : POST
    Params          :   email as string
                        password as string
    Response        :
                        {
                            "message": "Login success",
                            "access_token": "0|svvMUShfKM5i2aWa1JmlZMVjgh6GrL35P3MBp7vj",
                            "token_type": "Bearer"
                        }

## GET USER
    URL             : /user
    Method          : GET
    Authorization   : access_token
    Response        :  {
                            "id": "862b59b5-626e-4138-9ea2-5ba2cc6c747f",
                            "name": "fari",
                            "email": "andra@gmail.com",
                            "age": 21,
                            "province": "jateng",
                            "city": "semarang"
                        }
## UPDATE USER
    URL             : /update/{id}
    Method          : POST
    body            :   name as string
                        email as string
                        password as string
                        age as integer
                        province as string
                        city as string
    Authorization   : access_token
    Headers         :   key     =   accept
                        value   =   application/json
    Response        :   {
                            "message": "Record has been updated"
                        }

## ADD SKIN 
    URL             : /addskin/{id}
    Method          : POST
    Params          :   date as date
                        user_id as string
                        bodypart as string
                        since as date
                        symptom as string
                        cancertype as string
                        accu as float
    Authorization   : access_token
    Haders 	    : key = content-type, value = multipart/form-data
		      key     =   accept, value   =   application/json
    Body	    : file| key = image, value = select files 
    Response        :   {
                            "message": "skin added"
                        }

## DELETE USER
    URL             : /deleteuser
    Method          : POST
    Authorization   : access_token
    Response        :   {
                            "message": "User has been deleted"
                        }

## LOGOUT USER
    URL             : /logout
    Method          : POST
    Authorization   : access_token
    Response        :   {
                        	'message' => 'logout success'
                        }


## GET ALL SKIN per user already authenticated
    URL             : /skin
    Method          : GET
    Authorization   : access_token
    Response        :   
		 	{
        			"id": "858932fe-ab7c-4095-aeda-e3343bcb03c3",
        			"date": "2022-10-10",
        			"user_id": "5c3c8b9a-6624-4ea6-99dd-f83314a4824b",
        			"bodypart": "head",
        			"since": "2022-10-10",
        			"symptom": "itch",
        			"cancertype": "melanoma",
        			"accu": 90,
        			"link": "YOUR_PHOTOS_LINK"
    			}

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


