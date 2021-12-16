# Clippings

## Requirements

- PHP 8.1
- PHP extensions - xml, mbstring

## Run

Run `composer start`. It will start a development server for development purpose. 
Then open `localhost:8000` in browser and test the app as described in the served page.

## CodeSniff

Run `composer codesniff` to check the code

Note: Only the enum file does not meet the PSR-2 coding standards because it's kinda new :)

## Code coverage

Run `composer test-coverage` and open file `tests/coverage/html/index.html` in browser

## Not covered & known mistakes

- No limit on the files' characteristics. Basically exploitable code but for dev purpose works as a charm :)

- App class is responsible for multiple things. Incorrect.
- Validation mechanism not the best.
- Validation could be better
- DTO could be used for the CSV data when parsed, but it's additional not needed effort 