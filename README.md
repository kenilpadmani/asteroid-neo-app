Asteroid - Neo Stats
Summary
Neo stands for Near Earth Objects. Nasa provides an open API and in this problem we will be using the Asteroid NeoWs API.

We want to plot a line chart showing number of asteroids passing near earth each day for the given date range as well as find the nearest asteroid and the fastest asteroid.
Data Source
NASA’s Open API -> https://api.nasa.gov
Neo Feed
Retrieve a list of Asteroids based on their closest approach date to Earth
https://api.nasa.gov/api.html#neows-feed

A) Show the following stats (deduced from the data you will receive from Neo Feed)
    1. Fastest Asteroid in km/h (Respective Asteroid ID & its speed)
    2. Closest Asteroid (Respective Asteroid ID & its distance)
    3. Average Size of the Asteroids in kilometers

B) Plot a graph showing total number of asteroids for each day of the given date range. Use a bar or line chart for the same.

project setup
php artisan install
php artisan key:generate
php artisan migrate
php artisan config:cache

url:http://localhost/asteroid-neo-app/public/