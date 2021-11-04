# PowerAll Test

Test for the PowerAll API with large data sets


# Usage
1. Run composer install
2. If not created, copy the .env file and add your credentials

# Get products
For our test we had a site with a very large data set of 130.000 products. Because the PowerAll API products routes lack pagination you have to download all 130.000 products. If you run /index.php?products for the first time the json will be downloaded and stored locally 'products.json', to not stress the PowerAll API and quick testing.

To get the products run the following url
{DOMAIN}/index.php?products
