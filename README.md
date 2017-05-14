# Overflow

### Requirements:
* PHP
* MySQL
* Composer

### Steps to setup:

1. Clone the repo
2. Navigate to cloned repo directory root in terminal
3. Run "composer install --prefer-source" (important to use --prefer-source)
4. Edit bootstrap.php with your mysql configuration 
5. Run "php vendor\doctrine\orm\bin\doctrine orm:schema-tool:create" to setup tables 
