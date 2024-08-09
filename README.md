# Survey Full Stack Application


<table>
    <tr>
        <td>
            <a href="https://laravel.com"><img src="https://i.imgur.com/pBNT1yy.png" /></a>
        </td>
        <td>
            <a href="https://vuejs.org/"><img src="https://i.imgur.com/BxQe48y.png" /></a>
        </td>
        <td>
            <a href="https://tailwindcss.com/"><img src="https://i.imgur.com/wdYXsgR.png" /></a>
        </td>
        <td>
            <img src="https://i.imgur.com/Kp5kTUp.png" />
        </td>
    </tr>
</table> 

# SMS Manager

This is an SMS management web application built with Laravel and Vue. It provides features such as sending and receiving SMS messages, managing contacts, and saving message drafts.

## Features

- **User Authentication**: Secure login and registration for users.
- **SMS Provider Configuration**: The web application handles two differents sms providers TWILIO && VONAGE.
- **Send SMS Options**: Users can send SMS messages directly from the application to one contact or a group of contacts.
- **Contact Management**: Manage contacts with details such as name, phone number, and group associations.
- **Draft Functionality**: Save unfinished SMS messages as drafts to complete and send later.
- **Dashboard with SMS Overview**: View an overview of SMS activities, including sent, received, and draft messages.

- 
### Login Page

![Login Page](https://github.com/anas-en-naqadi/SmsManager/blob/stage/screenshots/loginPage.PNG)


### Dashboard

![Dashboard](https://github.com/anas-en-naqadi/SmsManager/blob/stage/screenshots/dashboardPage.PNG)


### SMS Provider Configuration

![Employees List](https://github.com/anas-en-naqadi/SmsManager/blob/stage/screenshots/smsConfiguration.PNG)


### Contact Form

![Profile Page](https://github.com/anas-en-naqadi/SmsManager/blob/stage/screenshots/contactForm.PNG)


### SMS Form

![Profile Page](https://github.com/anas-en-naqadi/SmsManager/blob/stage/screenshots/smsForm.PNG)


### SMS Listing Tables Example

![Profile Page](https://github.com/anas-en-naqadi/SmsManager/blob/stage/screenshots/smsMessagesExample.PNG)


### Profile

![Profile Page](https://github.com/anas-en-naqadi/SmsManager/blob/stage/screenshots/profile.PNG)


## Requirements
You need to have PHP version **8.0** or above. Node.js version **12.0** or above.



## Installation

#### Backend
1. Clone the project
2. Go to the project root directory
3. Run `composer install`
4. Create database
5. Copy `.env.example` into `.env` file and adjust parameters
6. Run `php artisan serve` to start the project at http://localhost:8000

#### Frontend
1. Navigate to `vue` folder using terminal
2. Run `npm install` to install vue.js project dependencies
3. Copy `vue/.env.example` into `vue/.env` and specify API URL
4. Start frontend by running `npm run dev`
5. Open http://localhost:3000


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

