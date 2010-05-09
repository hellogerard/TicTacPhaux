<?php
require_once 'PHPUnit/Framework.php';
require_once '../Classes/Base/Object.php';
require_once '../Classes/Phaux-base/WHComponent.php';
require_once '../Classes/Phaux-test/TicTacPhaux.php';

class TicTacPhauxTest extends PHPUnit_Framework_TestCase
{

    public function testClaimAndPeek() {
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->peek(0), NULL);
		$this->assertEquals($newGame->claim(0, 'X'), true);
		$this->assertEquals($newGame->peek(0), 'X');
		$this->assertEquals($newGame->claim(0, 'O'), false);
		$this->assertEquals($newGame->peek(0), 'X');
    }

	public function testDiagonalWin() {
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->diagonalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(0, 'X');
		$newGame->claim(4, 'X');
		$newGame->claim(8, 'X');
		$this->assertEquals($newGame->diagonalWin(), true);
		$this->assertEquals($newGame->win(), true);
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->diagonalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(2, 'X');
		$newGame->claim(4, 'X');
		$newGame->claim(6, 'X');
		$this->assertEquals($newGame->diagonalWin(), true);
		$this->assertEquals($newGame->win(), true);
	}

	public function testHorizontalWin() {
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->horizontalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(0, 'X');
		$newGame->claim(1, 'X');
		$newGame->claim(2, 'X');
		$this->assertEquals($newGame->horizontalWin(), true);
		$this->assertEquals($newGame->win(), true);
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->horizontalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(3, 'X');
		$newGame->claim(4, 'X');
		$newGame->claim(5, 'X');
		$this->assertEquals($newGame->horizontalWin(), true);
		$this->assertEquals($newGame->win(), true);
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->horizontalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(6, 'X');
		$newGame->claim(7, 'X');
		$newGame->claim(8, 'X');
		$this->assertEquals($newGame->horizontalWin(), true);
		$this->assertEquals($newGame->win(), true);
	}

	public function testVerticalWin() {
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->verticalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(0, 'X');
		$newGame->claim(3, 'X');
		$newGame->claim(6, 'X');
		$this->assertEquals($newGame->verticalWin(), true);
		$this->assertEquals($newGame->win(), true);
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->verticalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(1, 'X');
		$newGame->claim(4, 'X');
		$newGame->claim(7, 'X');
		$this->assertEquals($newGame->verticalWin(), true);
		$this->assertEquals($newGame->win(), true);
		$newGame = new TicTacPhaux();
		$this->assertEquals($newGame->verticalWin(), false);
		$this->assertEquals($newGame->win(), false);
		$newGame->claim(2, 'X');
		$newGame->claim(5, 'X');
		$newGame->claim(8, 'X');
		$this->assertEquals($newGame->verticalWin(), true);
		$this->assertEquals($newGame->win(), true);
	}
}
?>
