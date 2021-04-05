# ChessBundle

## Installation

Run `composer require p-chess/chess-bundle`

## Configuration

Create a configuration file, and use a content like the following:

```yaml
# config/packages/chess.yaml
chess:
    routes:
        start: move_start
        cancel: move_cancel
        end: move_end
        promotion: move_promotion
```

This bundle expects you defined the routes mentioned in the above example.
This is an example of how such routes can be defined:

```yaml
# config/routes.yaml

move_start:
    path: /move/{from}
    methods: GET
    controller: ... # your controller action

move_cancel:
    path: /
    methods: GET
    controller: ... # your controller action

move_promotion:
    path: /promote/{from}/{to}
    methods: GET
    controller: ... # your controller action

move_end:
    path: /move/{from}/{to}/{promotion}
    methods: GET
    controller: ... # your controller action
    defaults:
        promotion: ~
```

Please note that parameter name cannot be changed, because this bundle
expects them to be named "from", "to", and "promotion".

## Usage

You can inject a service implementing `\PChess\ChessBundle\ChessProviderInterface` in your controller, then
implement different actions, using provided `\PChess\Chess\Chess` object.

In your template, you can use Twig function `chess_render(chess)` to render the board.

The main service you can use is `\PChess\ChessBundle\SessionChessProvider`.
This service allows you to keep a chess game in session, providing following methods:

* `getChess($fen)` to get main `\PChess\Chess\Chess` instance (as provided by interface)
* `restart()` to restart the game
* `save()` to save the game in session
* `reverse()` to switch sides
* `getAllowedMoves($chess, $from)` to get a list of currently allowed moves (optionally limited to `$from` square)

### Styling

You can use provided `_board.scss` file to style the board:

`@import '~@p-chess/chess-bundle/scss/board';`

Don't forget to update your frontend files, using npm or yarn.

The final result should be something like this:

<img src="https://user-images.githubusercontent.com/179866/113510143-9b982700-9559-11eb-9152-a8e7977326fa.png" alt="">
