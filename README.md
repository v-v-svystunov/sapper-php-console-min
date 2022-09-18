# sapper-php-console-min
Game "Sapper" is realized by PHP usage as console application with minimal dependencies.

Application was developed and tested by usage php version PHP 7.4.16 (cli) (built: Mar  5 2021 07:54:20) ( NTS )
based on standard SPL
without any additional requirements

To observe list of available console options should be used:

$ php saper -h

To test the application without saving created matrix should be used:

$ php saper -t {grid_size} {quantity_of_black_halls}

*** Note second and third parameters should be placed as integers to configurate field.

To initiate new game should be used:

$ php saper -i {grid_size} {quantity_of_black_halls}

To make a move should be used:

$ php saper -m a1

Where second parameter is coordinate of the cell willed to be opened.

For an ability to visualize initiated field should be used:

$ php saper -v

## As example.

$ php saper -h

![help](https://user-images.githubusercontent.com/65670141/190916278-8e3985aa-774f-4635-b262-83f38d814947.png)

$ php saper -t 10 40

![test](https://user-images.githubusercontent.com/65670141/190916367-545a49a3-5119-487b-804a-5970e716f6bf.png)

