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


### Notes

#### Not covered

- Adding multiple currencies with multiplier 1 (default ones)
- No limit on files

#### Known mistakes

- App class is responsible for multiple things. Incorrect.
- Validation mechanism not the best.