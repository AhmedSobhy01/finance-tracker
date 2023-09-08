<a name="readme-top"></a>

[![Forks][forks-shield]][forks-url]
[![Issues][issues-shield]][issues-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://www.github.com/AhmedSobhy01/finance-tracker">
    <img src="https://icon-library.com/images/money-png-icon/money-png-icon-5.jpg" alt="Logo" width="80" height="80">
  </a>

<h3 align="center">Finance Tracker</h3>

  <p align="center">
    Simply a finance tracker that tracks your income, expenses, debts, and cash money.
    <br />
    <a href="https://finance-tracker.ahmedsobhy.net">View Demo</a>
    ·
    <a href="https://www.github.com/AhmedSobhy01/finance-tracker/issues">Report Bug</a>
    ·
    <a href="https://www.github.com/AhmedSobhy01/finance-tracker/issues">Request Feature</a>
  </p>
</div>

<!-- ABOUT THE PROJECT -->

## About The Project

[![Product Name Screen Shot][product-screenshot]](https://finance-tracker.ahmedsobhy.net)

A Laravel project for a finance tracker is a web application that helps users manage their financial information by tracking their income, expenses, debts, and cash money. It allows users to add and categorize transactions, view their current financial status, generate reports and charts to help them understand their spending patterns and make informed decisions about their finances. The project makes use of the Laravel framework to provide a secure and user-friendly platform for managing financial information. It includes features such as user authentication, transaction management, categorization, reporting, and charts to help users keep track of their financial information and make informed decisions. The goal of the project is to provide users with a simple and effective tool for managing their finances and helping them make the most of their financial resources.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Built With

-   [![Laravel][laravel.com]][laravel-url]
-   [![Vue][vue.js]][vue-url]
-   [![Bootstrap][bootstrap.com]][bootstrap-url]

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- Installation -->

### Installation

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

1. Clone the repo
    ```sh
    git clone https://www.github.com/AhmedSobhy01/finance-tracker.git
    ```
2. Install Composer packages
    ```sh
    composer install
    ```
3. Install NPM packages
    ```sh
    npm install
    ```
4. Copy .env.example and then edit .env
    ```sh
    cp .env.example .env
    ```
5. Generate app encryption key
    ```sh
    php artisan key:generate
    ```
6. Migrate and seed the database
    ```sh
    php artisan migrate:fresh --seed
    ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- USAGE EXAMPLES -->

## Usage

After seeding the database, you can log in using the default user:
| **Username** | **Password** |
| --- | --- |
| Test | password |

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTACT -->

## Contact

Ahmed Sobhy - contact@ahmedsobhy.net

Project Link: [https://www.github.com/AhmedSobhy01/finance-tracker](https://www.github.com/AhmedSobhy01/finance-tracker)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->

[forks-shield]: https://img.shields.io/github/forks/AhmedSobhy01/finance-tracker.svg?style=for-the-badge
[forks-url]: https://github.com/AhmedSobhy01/finance-tracker/network/members
[stars-shield]: https://img.shields.io/github/stars/AhmedSobhy01/finance-tracker.svg?style=for-the-badge
[stars-url]: https://www.github.com/AhmedSobhy01/finance-tracker/stargazers
[issues-shield]: https://img.shields.io/github/issues/AhmedSobhy01/finance-tracker.svg?style=for-the-badge
[issues-url]: https://www.github.com/AhmedSobhy01/finance-tracker/issues
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/ahmed-sobhy-dev
[product-screenshot]: https://ahmedsobhy.net/storage/ca72cb478218073ac9b447908534f5c5/home-phone.jpg
[vue.js]: https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D
[vue-url]: https://vuejs.org/
[laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[laravel-url]: https://laravel.com
[bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[bootstrap-url]: https://getbootstrap.com
