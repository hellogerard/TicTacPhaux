<?php

class TicTacPhaux extends WHComponent {

    private $board = array();
    private $lastMove = 'X';

    function __construct() {
        $board = array('', '', '', '', '', '', '', '', '');
    }

    function claim($pos, $token) {
        $this->lastMove = ($this->lastMove == 'X') ? 'O' : 'X';
        $cell = $this->peek($pos);
        if (empty($cell)) {
            $this->board[$pos] = $token;
            return true;
        } else {
            return false;
        }
    }

    function peek($pos) {
	    return $this->board[$pos];
    }

    function diagonalWin() {
        if ((!empty($this->board[0]) && ($this->board[0] == $this->board[4]) && ($this->board[4] == $this->board[8])) ||
            (!empty($this->board[6]) && ($this->board[6] == $this->board[4]) && ($this->board[4] == $this->board[2]))) {
            return true;
        } else {
            return false;
        }
    }

    function horizontalWin() {
        if ((!empty($this->board[0]) && ($this->board[0] == $this->board[1]) && ($this->board[1] == $this->board[2])) ||
            (!empty($this->board[3]) && ($this->board[3] == $this->board[4]) && ($this->board[4] == $this->board[5])) ||
            (!empty($this->board[6]) && ($this->board[6] == $this->board[7]) && ($this->board[7] == $this->board[8]))) {
            return true;
        } else {
            return false;
        }
    }

    function verticalWin() {
        if ((!empty($this->board[0]) && ($this->board[0] == $this->board[3]) && ($this->board[3] == $this->board[6])) ||
            (!empty($this->board[1]) && ($this->board[1] == $this->board[4]) && ($this->board[4] == $this->board[7])) ||
            (!empty($this->board[2]) && ($this->board[2] == $this->board[5]) && ($this->board[5] == $this->board[8]))) {
            return true;
        } else {
            return false;
        }
    }

    function win() {
        return $this->diagonalWin() || $this->horizontalWin() || $this->verticalWin();
    }

	public function renderContentOn($html){

        if ($this->win())
        {
            $this->lastMove = ($this->lastMove == 'X') ? 'O' : 'X';
            $won = $html->headingLevel(1)->with($this->lastMove . " has won!<br/>");
        }

        $k = 0;
        for ($i = 0; $i < 3; $i++)
        {
            $row = '';

            for ($j = 0; $j < 3; $j++)
            {
                $cell = $this->peek($k);
                if (empty($cell) && ! isset($won))
                {
                    $row .= $html->tableData()->with(
                                $html->anchor()->callback($this, "claim", array($k, $this->lastMove))->with("click")
                            );
                }
                else
                {
                    $row .= $html->tableData()->with(
                                $html->text($cell)
                            );
                }
                $k++;
            }

            $rows .= $html->tableRow()->with($row);
        }

        return $won . $html->table()->border("1")->cellpadding("30")->with($rows);
	}
}

?>
