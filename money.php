<?php

/**
 * @author Helton Alves <helton687@gmail.com>
 *  
 * To be run through cli (php ./money.php);
 * Just do not type anything, and the value <b>1069<b> will be used.
 * Other values can also be inserted
 */
class SimpleATM
{

    private $availableBanknotes;

    public function __construct()
    {
        $this->availableBanknotes = [100, 50, 5, 2 ];
    }

    private function calculate(int $amount): array
    {
        $banknotes = array();

        $tempAmount = $amount;

        for ($i = 0; $i < count($this->availableBanknotes); $i ++) {
            $banknoteSize = $this->availableBanknotes[$i];
            $banknotes[$banknoteSize] = $this->calculateNotesQtd($tempAmount, $banknoteSize);
            $tempAmount = $this->calculateRest($tempAmount, $banknoteSize);
        }

        return $banknotes;
    }

    public function withdrawMoney(): void
    {
        // just in case we want to use another value
        $amount = readline("How much do you want to withdraw (default 1069)? ");

        if (! $amount || ! is_numeric($amount)) {
            $amount = 1069; // well, that was what the test was really about
        }

        $banknotes = $this->calculate($amount);

        // just in case...
        if ($this->assertTotal($amount, $banknotes)) {
            $this->ejectMoney($banknotes);
        } else {
            $this->leaveErrorMessage();
        }
    }
    
    private function leaveErrorMessage()
    {
        echo "It is not possible to spew out that exact amount\n";
    }

    private function ejectMoney(array $banknotes)
    {
        echo "\n";
        echo "Tanks for using our service. You just got:\n";
        foreach ($banknotes as $size => $qtd) {
            echo $qtd . " notes of $" . $size . ";\n";
        }

        echo "\n";
    }

    private function calculateNotesQtd(int $amount, int $divisor): int 
    {
        return intval($amount / $divisor);
    }

    private function calculateRest(int $amount, int $divisor): int
    {
        return $amount % $divisor;
    }

    private function assertTotal(int $initialAmount, array $banknotes): bool
    {
        $total = 0;

        foreach ($banknotes as $banknoteSize => $banknoteQtd) {
            $total += $banknoteSize * $banknoteQtd;
        }

        return $total == $initialAmount;
    }
}

$atm = new SimpleATM();
$atm->withdrawMoney();
