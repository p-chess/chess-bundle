# ChessBundle

## Installation

Run `composer require p-chess/chess-bundle`

## Configuration

Create a service that extends `PChess\ChessBundle\HtmlOutput` and
implement the required methods.
You probably want to inject Symfony's router service here and use it
to return the required URLs.
Note that each route can be provided with an identifier for your game.

Create a configuration file, and use content like the following:

```yaml
# config/packages/chess.yaml
chess:
    output_service: App\YourOutputService
```

This is an example of how routes can be defined (using an "id" parameter as an identifier):

```yaml
# config/routes.yaml
move_start:
    path: /{id}/move/{from}
    methods: GET
    controller: ... # your controller action

move_cancel:
    path: /{id}
    methods: GET
    controller: ... # your controller action

move_promotion:
    path: /{id}/promote/{from}/{to}
    methods: GET
    controller: ... # your controller action

move_end:
    path: /{id}/move/{from}/{to}/{promotion}
    methods: GET
    controller: ... # your controller action
    defaults:
        promotion: ~
```

## Usage

You can inject a service implementing `\PChess\ChessBundle\ChessProviderInterface` in your controller, then
implement different actions, using provided `\PChess\Chess\Chess` object.

In your template, you can use the Twig function `chess_render(chess)` to render the board.
If you need to pass an identifier, use `chess_render(chess, identifier)` instead.

The main service you can use is `\PChess\ChessBundle\SessionChessProvider`.
This service allows you to keep chess games in session, providing the following methods:

* `getChess($identifier, $fen)` to get main `\PChess\Chess\Chess` instance (as provided by interface)
* `restart($identifier)` to restart the game
* `save($identifier)` to save the game in session
* `reverse($identifier)` to switch sides
* `getAllowedMoves($chess, $from)` to get a list of currently allowed moves (optionally limited to `$from` square)

Using `$identifier` is not mandatory.

### Styling

You can use the provided `_board.scss` file to style the board:

`@import '~@p-chess/chess-bundle/scss/board';`

Don't forget to update your frontend files, using npm or yarn.

The final result should be something like this:

<img src="https://user-images.githubusercontent.com/179866/114995898-92cf1b80-9e9e-11eb-8e99-75a60bbba6bd.png" alt="">

### Persisting a Chess object

You can easily save a `Chess` object into Doctrine (or other kind of mapping libraries), using two fields/properties:
`fen`, and `history`.
The first one is a simple string. The second one can be a `simple_array` (for Doctrine), where you should put
the result of `Mover::getHistoryStrings()` method.
When retrieving an object, you should use `fen` and the result of `Mover::getHistoryEntries()` to build back your
`Chess` object.
